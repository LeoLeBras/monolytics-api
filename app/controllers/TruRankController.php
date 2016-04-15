<?php

  require_once(CORE_DIR.'/helpers/Fetch.php');
  require_once(APP_DIR.'/models/Movie.php');

  class TruRankController {

    /**
     * Calc TRURANK
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
     * Crawl movies and calc TRURANK
     *
     * @param {string} $query
     * @return {array}
     */
    public function runCrawler() {

      // Get movies from databse
      $query = new Movie();
      $movies = $query
        ->limit(40)
        ->orderBY('trurank_last_update', 'ASC')
        ->fetchAll();

      // Fetch tweets
      $response = [];
      foreach($movies as $key => $movie) {
        $response[$key] = $this->get(htmlentities(strtolower($movie->title)), false);
      }

      // Show response
      echo json_encode($response);

    }



    /**
     * Calc TRURANK of a movie
     *
     * @param {string} $query
     * @param {boolean} $return_json
     * @return {array}
     */
    public function get($query, $return_json = true) {

      // Set $title
      $title = ucwords(strtolower($query));

      // Get movie
      $query = new Movie();
      $movie = $query
        ->where(array(
          'title' => $title
        ))
        ->fetch();

      // Calc "reality" score
      $reality = (
        $movie->trakt_watchers * 2 +
        $movie->trakt_plays * 4 +
        $movie->pirate_bay_leechers +
        $movie->pirate_bay_seeders +
        $movie->moviedb_revenue / 7
      ) / 4664251;

      // Calc "trailer" score
      $trailer = log(((
        $movie->youtube_view_count +
        $movie->youtube_comment_count * 5)*(
        $movie->youtube_like_count /
        $movie->youtube_dislike_count)*
        100
      ));

      // Calc "hypermeter" score
      $hypermeter = (
        $trailer +
        $movie->imdb_votes / 100 +
        $movie->twitter_count_popular_tweets_from_last_3_days
      );

      // Calc "review" score
      $review = ((
        $movie->rotten_tomatoes_score +
        $movie->rotten_tomatoes_meter
      ) / 20 + (
        $movie->imdb_rating +
        $movie->metascore / 10
      )) / 3;

      // Calc "noise" score
      $noise = ($hypermeter * $review) / 9;

      // Calc "TRURANK" score
      $trurank = ($noise * .8 + $reality * 1.2) * .8625;

      $movie = array(
        'trurank_last_update' => date("Y-m-d H:i:s"),
        'trurank_score' => (float) $trurank,
        'trurank_reality' => (float) $reality,
        'trurank_trailer' => (float) $trailer,
        'trurank_hypermeter' => (float) $hypermeter,
        'trurank_review' => (float) $review,
        'trurank_noise' => (float) $noise
      );

      // Save $movie in databse
      $query = new Movie();
      $query
        ->where(array(
          'title' => $title
        ))
        ->set($movie)
        ->save();

      // Show json
      if($return_json) {
        echo json_encode($response);
      }

      // Return data
      return $movie;

    }



    /**
     * Get best trurank movies
     */
    public function tops() {

      // Set key
      $key = 'topTrurank';

      // Get movies
      if(Storage::check($key)) { // from storage
        $movies = Storage::get($key);
      }
      else { // from databse
        $query = new Movie();
        $movies = $query
          ->orderBy('trurank_score', 'DESC')
          ->limit(10)
          ->fetchAll();
        Storage::set($key, $movies);
      }

      // Return json
      echo json_encode($movies);

    }

  }
