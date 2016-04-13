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
     * Crawl tweets for some websites
     *
     * @param {string} $query
     * @return {array}
     */
    public function runCrawler() {
      $query = new Movie();
      $movies = $query
        ->limit(10)
        ->orderBY('twitter_last_updated', 'ASC')
        ->fetchAll();

      foreach($movies as $movie) {
        $this->get(htmlentities(strtolower($movie->title)));
      }
    }



    /**
     * Get all tweets from a movie
     *
     * @param {string} $query
     * @return {array}
     */
    public function get($query) {

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
      echo json_encode($response);

      // Return data
      return $response;

    }

  }
