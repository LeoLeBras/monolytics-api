<?php

  require_once(CORE_DIR.'/helpers/Fetch.php');
  require_once(CORE_DIR.'/helpers/Route.php');
  require_once(APP_DIR.'/models/Movie.php');

  class TraktController {

    private $url = 'https://api-v2launch.trakt.tv/movies/';
    private $headers = array(
      'Content-Type' => 'application/json',
      'trakt-api-version' => '2',
      'trakt-api-key' => '386bfb747aa8fa68d1689c6babb1bc58308e0f12f17462cb00db79b195fef8ee'
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
    public function list($type) {
      if(in_array($type, $this->types)) {
        return Fetch::get($this->url.$type, $this->headers);
      }
      else if($type == 'all') {
      }
      else {
        return null;
      }
    }



    /**
     * Get all top movies
     */
    public function all() {

      // Get all movies
      $movies = array();
      foreach($this->types as $type) {
        foreach($this->get($type) as $entry) {
          if(isset($entry->movie)) {
            $entry = $entry->movie;
          }
          $movie = array();
          $movie['title'] = $entry->title;
          $movie['type'] = $type;
          $movie['year'] = $entry->year;
          $movie['imdb_id'] = $entry->ids->imdb;
          $movies[$entry->ids->trakt] = $movie;
        }
      }

      // Show json
      echo json_encode($movies);

    }



    /**
     * Get stats informations
     * from a movie
     * > http://docs.trakt.apiary.io/#reference/movies/stats/get-movie-stats
     *
     * @param {string} $query
     * @param {array}
     */
    public function get($query) {

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
        'trakt_votes' => $response->votes
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
      echo json_encode($movie);

      // Return data
      return $query;

    }

  }
