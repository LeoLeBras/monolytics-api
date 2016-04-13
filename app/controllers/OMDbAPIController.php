<?php

  require_once(CORE_DIR.'/helpers/Fetch.php');
  require_once(APP_DIR.'/models/Movie.php');

  class OMDbAPIController {

    private $url = 'http://www.omdbapi.com/';
    private $headers = array(
      'Content-Type' => 'application/json'
    );



    /**
     * Fetch IMDB metadatas
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
     * Crawl IMDB metadatas
     *
     * @param {string} $query
     * @return {array}
     */
    public function runCrawler() {

      // Get movies from databse
      $query = new Movie();
      $movies = $query
        ->limit(20)
        ->orderBY('imdb_last_update', 'ASC')
        ->fetchAll();

      // Fetch tweets
      $response = [];
      foreach($movies as $key => $movie) {
        echo $key;
        $response[$key] = $this->get(htmlentities(strtolower($movie->title)), false);
      }

      // Show response
      echo json_encode($response);

    }



    /**
     * Get some informations about a movie
     *
     * > http://docs.themoviedb.apiary.io/#reference/movies
     *
     * @param {string} $query
     * @param {boolean} $return_json
     * @return {array}
     */
    public function get($query, $return_json) {

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
        'metascore' =>  $response->Metascore,
        'imdb_last_update' => date("Y-m-d H:i:s")
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
      if($return_json) {
        echo json_encode($response);
      }

      // Return data
      return $movie;

    }

  }
