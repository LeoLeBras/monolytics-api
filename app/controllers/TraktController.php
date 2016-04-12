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
    public function get($type) {
      if(in_array($type, $this->types)) {
        return Fetch::get($this->url.$type, $this->headers);
      }
      else {
        return null;
      }
    }



    /**
     * Get all top movies
     */
    public function list() {

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

      echo json_encode($movies);

    }

  }
