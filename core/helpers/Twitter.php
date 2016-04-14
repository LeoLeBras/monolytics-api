<?php

  class Twitter {

    public static $url = 'https://api.twitter.com/1.1/';
    public static $tokens = array(
      'oauth_access_token' => TWITTER_OAUTH_TOKENS,
      'oauth_access_token_secret' => TWITTER_OAUTH_TOKENS_SECRET,
      'consumer_key' => TWITTER_KEY,
      'consumer_secret' => TWITTER_SECRET
    );



    /**
     * Return data related to a movie
     *
     * @param {string} $movie
     * @return {array}
     */
    static public function get($query) {

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
        'twitter_count_popular_tweets_from_last_3_days' => $count_tweets_from_last_3_days,
        'twitter_tweets' => $tweets
      );

      // Return data
      return $response;

    }

  }
