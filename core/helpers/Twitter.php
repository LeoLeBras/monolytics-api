<?php

  require_once(VENDOR_DIR.'/autoload.php');

  class Twitter {

    public static $url = 'https://api.twitter.com/1.1/';
    public static $tokens = array(
      'oauth_access_token' => "1269600193-xMWhn1kM0m45fqP8VuS7FByZXf0XR2e2Kz4i6Va",
      'oauth_access_token_secret' => "NhEDOGYu6CWy11YPjIpRxxjq73rly0qv45q2ZxOKMWtxr",
      'consumer_key' => "0pAPqNUuns3MjyMFfw95rwUIG",
      'consumer_secret' => "QQqfK7atn0V7UwHENtsfIg5X6kfdRPXjK8GWCB6Uh5enlMaMmU"
    );



    /**
     * Return data related to a movie
     *
     * @param {string} $movie
     * @return {array}
     */
    static public function getMovie($query) {

      // Initialyze request
      $api = new TwitterAPIExchange(self::$tokens);
      $query = '?q='.$query.'%23nowwatching&count=100&since_id=0&result_type=recent';

      // Get tweets
      $data = $api
        ->setGetfield($query)
        ->buildOauth(self::$url.'search/tweets.json', 'GET')
        ->performRequest();
      $data = json_decode($data);

      // Format tweets and count tweets from last 2 days
      $count_tweets_from_last_3_days = 0;
      $now = new DateTime();
      $tweets = array();
      foreach($data->statuses as $key => $status) {
        $datetime = new DateTime($status->created_at);
        $interval = $now->diff($datetime);
        if($interval->days <= 3) {
          $count_tweets_from_last_3_days += 1;
        }
        $tweet = array(
          'text' => $status->text,
          'created_at' => $status->created_at,
          'user' => '@'.$status->user->screen_name
        );
        $tweets[$key] = $tweet;
      }

      // Build response
      $response = array(
        'twitter_count_popular_tweets' => count($tweets),
        'twitter_count_popular_tweets_from_last_3_years' => $count_tweets_from_last_3_days,
        'twitter_tweets' => $tweets
      );

      // Return data
      return $response;

    }

  }
