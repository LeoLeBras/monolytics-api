<?php

  require_once(CORE_DIR.'/helpers/Fetch.php');
  require_once(CORE_DIR.'/helpers/Route.php');
  require_once(APP_DIR.'/models/Movie.php');

  class OMDbAPIController {

    private $url = 'https://api.themoviedb.org/3/';
    private $headers = array(
      'Content-Type' => 'application/json'
    );
    private $key = '39e198fbcb8ce7150a372180e92df284';



    /**
     * Get some informations about a movie
     *
     * > http://docs.themoviedb.apiary.io/#reference/movies
     *
     * @param {string} $query
     * @return {array}
     */
    public function get($query) {

      // Search movie
      $movie = Fetch::get(
        $this->url.'search/movie?api_key='.$this->key.'&query='.$query,
        $this->headers
      )->results[0];

      // Get data
      $movie = array(
        'title' => $movie->title,
        'imdb_popularity' => $movie->popularity,
        'imdb_vote_average' => $movie->vote_average,
        'imdb_vote_count' => $movie->vote_count,
        'release_date' => $movie->release_date,
      );

      // Show json
      echo json_encode($movie);

      // Return data
      return $movie;

    }

  }
