<?php

  require_once(CORE_DIR.'/helpers/Fetch.php');
  require_once(CORE_DIR.'/helpers/Route.php');

  class TraktController {

    private $url = 'https://api-v2launch.trakt.tv/movies/';
    private $headers = array(
      'Content-Type' => 'application/json',
      'trakt-api-version' => '2',
      'trakt-api-key' => TRAKT_KEY
    );


    /**
     * Get top movies by filter
     *
     * > http://docs.trakt.apiary.io/#reference/movies/trending/get-trending-movies
     */
    public function get($type) {
      $types = array('popular', 'trending', 'anticipated', 'boxoffice');
      if(in_array($type, $types)) {
        $response = Fetch::get($this->url.$type, $this->headers);
        echo '<pre>';
        print_r($response);
        echo '</pre>';
      }
      else {
        echo 'null :(';
      }
    }

  }
