<?php

  require_once(CORE_DIR.'/helpers/Fetch.php');
  require_once(APP_DIR.'/models/Movie.php');

  class OMDbAPIController {

    private $url = 'http://www.omdbapi.com/';
    private $headers = array(
      'Content-Type' => 'application/json'
    );



    /**
     * Get some informations about a movie
     *
     * > http://docs.themoviedb.apiary.io/#reference/movies
     *
     * @param {string} $query
     * @return {array}
     */
    public function get($query) {

      // Get movie
      $response = Fetch::get(
        $this->url.'?t='.join('+', explode(' ', $query)),
        $this->headers
      );

      // Get data
      $movie = array(
        'title' => $response->Title,
        'imdb_rating' => $response->imdbRating,
        'imdb_votes' => $response->imdbVotes,
        'imdb_id' => $response->imdbID,
        'director' =>  $response->Director,
        'metascore' =>  $response->Metascore
      );

      // Save $movie in databse
      $query = new Movie();
      $query
        ->where(array(
          'title' => $response->Title
        ))
        ->set($movie)
        ->save();

      // Show json
      echo json_encode($response);

      // Return data
      return $movie;

    }

  }
