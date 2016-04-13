<?php

  require_once(CORE_DIR.'/helpers/Fetch.php');
  require_once(APP_DIR.'/models/Movie.php');
  require_once(APP_DIR.'/models/MovieTop.php');

  class TraktController {

    private $url = 'https://api-v2launch.trakt.tv/movies/';
    private $headers = array(
      'Content-Type' => 'application/json',
      'trakt-api-version' => '2',
      'trakt-api-key' => TRAKT_API_KEY
    );
    private $types = array('popular', 'trending', 'anticipated', 'boxoffice');



    /**
     * Get top movies by filters (popular, trending,
     * anticipated or boxoffice.
     *
     * > http://docs.trakt.apiary.io/#reference/movies/trending/get-trending-movies
     *
     * @param {string} $type
     * @return {array}
     */
    public function getTop($type) {
      if(in_array($type, $this->types)) {
        return Fetch::get($this->url.$type, $this->headers);
      }
      else {
        return null;
      }
    }



    /**
     * Get all tops
     *
     * > http://docs.trakt.apiary.io/#reference/movies/trending/get-trending-movies
     *
     * @return {aray}
     */
    public function getTops() {

      // Get all movies
      $movies = array();
      $i = 0;
      foreach($this->types as $type) {
        $rank = 1;
        foreach($this->getTop($type) as $entry) {
          if(isset($entry->movie)) {
            $entry = $entry->movie;
          }
          $movie = array();
          $movie['title'] = $entry->title;
          $movie['type'] = $type;
          $movie['year'] = $entry->year;
          $movie['imdb_id'] = $entry->ids->imdb;
          $movie['rank'] = $rank;
          $movies[$i] = $movie;
          $i += 1;
          $rank += 1;
        }
      }

      // Get all saved movies
      $query = new Movie();
      $saved_movies = $query->fetchAll();

      // Truncate movies_tops databse
      $query = new MovieTop();
      $query->truncate();

      foreach($movies as $key => $movie) {

        // Find movie in your own databse
        $title = join(' ', explode("'", $movie['title']));
        $query = new Movie();
        $saved_movie = $query
          ->where(array('title' => $title))
          ->fetch();

        // Create the movie if it has not be done before
        if(empty($saved_movie)) {
          $query = new Movie();
          $query
            ->set(array(
              'title' => $title,
              'imdb_id' => $movie['imdb_id'],
              'year' => $movie['year']
            ))
            ->save();
          $movie['movie_id'] = $query->lastInsertId();
        }
        else {
          $movie['movie_id'] = $saved_movie->id;
        }

        // Add the movie the movies_tops database
        $query = new MovieTop();
        $query
          ->set(array(
            'movie_id' => $movie['movie_id'],
            'type' => $movie['type'],
            'rank' => $movie['rank']
          ))
          ->save();

      }

      // Return json
      echo json_encode($movies);
      return $movies;

    }



    /**
     * Fetch trakt metadatas
     *
     * @param {string} $query
     */
    public function index($query) {
      if($query == 'crawl') {
        $this->runCrawler();
      }
      else {
        $this->get($query);
      }
    }



    /**
     * Crawl trakt metadatas
     *
     * @param {string} $query
     * @return {array}
     */
    public function runCrawler() {

      // Get movies from databse
      $query = new Movie();
      $movies = $query
        ->limit(5)
        ->orderBY('trakt_last_update', 'ASC')
        ->fetchAll();

      // Fetch tweets
      $response = [];
      foreach($movies as $key => $movie) {
        $response[$key] = $this->get(htmlentities(strtolower($movie->title)).' '.$movie->year, false);
      }

      // Show response
      echo json_encode($response);

    }



    /**
     * Get stats informations
     * from a movie
     * > http://docs.trakt.apiary.io/#reference/movies/stats/get-movie-stats
     *
     * @param {string} $query
     * @param {boolean} $return_json
     * @param {array}
     */
    public function getMovie($query, $return_json) {

      // Get imdb id
      $title = ucwords(strtolower($query));
      $query = new Movie();
      $imdb_id = $query
        ->where(array(
          'title' => $title
        ))
        ->fetch()
        ->imdb_id;

      // Get streaming stats from movie
      $response = Fetch::get(
        $this->url.$imdb_id.'/stats',
        $this->headers
      );

      // Build data
      $movie = array(
        'trakt_watchers' => $response->watchers,
        'trakt_plays' => $response->plays,
        'trakt_collectors' => $response->collectors,
        'trakt_comments' => $response->comments,
        'trakt_lists' => $response->lists,
        'trakt_votes' => $response->votes,
        'trakt_last_update' => date("Y-m-d H:i:s")
      );

      // Save data in databse
      $query = new Movie();
      $query
        ->where(array(
          'title' => $title
        ))
        ->set($movie)
        ->save();


      // Show json response
      if($return_json) {
        echo json_encode($movie);
      }

      // Return data
      return $query;

    }

  }
