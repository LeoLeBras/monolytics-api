<?php

  require_once(APP_DIR.'/models/Movie.php');
  require_once(CORE_DIR.'/helpers/Fetch.php');
  require_once(CORE_DIR.'/helpers/Twitter.php');

  class TwitterController {

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
