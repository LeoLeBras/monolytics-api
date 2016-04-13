<?php

  require_once(APP_DIR.'/models/Movie.php');
  require_once(CORE_DIR.'/helpers/Fetch.php');

  class TwitterController {

    /**
     * Fetch tweets
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
     * Crawl tweets
     *
     * @param {string} $query
     * @return {array}
     */
    public function runCrawler() {

      // Get movies from databse
      $query = new Movie();
      $movies = $query
        ->limit(5)
        ->orderBY('twitter_last_updated', 'ASC')
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
     * Get all tweets from a movie
     *
     * @param {string} $query
     * @param {boolean} $return_json
     * @return {array}
     */
    public function get($query, $return_json) {

      // Get data
      $response = Twitter::get($query);
      $title = ucwords(strtolower($query));

      // Save $movie in databse
      $query = new Movie();
      $query
        ->where(array(
          'title' => $title
        ))
        ->set(array(
          'twitter_last_updated' => date("Y-m-d H:i:s"),
          'twitter_count_popular_tweets' => $response['twitter_count_popular_tweets'],
          'twitter_count_popular_tweets_from_last_3_days' => $response['twitter_count_popular_tweets_from_last_3_days']
        ))
        ->save();

      // Show json
      if($return_json) {
        echo json_encode($response);
      }

      // Return data
      return $response;

    }

  }
