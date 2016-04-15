<?php

  require_once(CORE_DIR.'/helpers/Fetch.php');
  require_once(APP_DIR.'/models/Movie.php');

  class MovieDBController {

    private $url = 'http://api.themoviedb.org/3/';
    private $headers = array(
      'Content-Type' => 'application/json'
    );
    private $key = '39e198fbcb8ce7150a372180e92df284';

    /**
     * Fetch TheMovieDB metadatas
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
     * Crawl TheMovieDB metadatas
     *
     * @param {string} $query
     * @return {array}
     */
    public function runCrawler() {

      // Get movies from databse
      $query = new Movie();
      $movies = $query
        ->limit(30)
        ->orderBY('moviedb_last_update', 'ASC')
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
     * Get some informations about a movie
     *
     * > http://docs.themoviedb.apiary.io/#reference/movies
     *
     * @param {string} $query
     * @param {boolean} $return_json
     * @return {array}
     */
    public function get($query, $return_json = true) {

      // Get movie id
      $title = ucwords(strtolower($query));
      $search = Fetch::get(
        $this->url.'search/movie?api_key='.$this->key.'&query='.$query,
        $this->headers
      );

      // Get metada
      $response = Fetch::get(
        $this->url.'movie/'.$search->results[0]->id.'?api_key='.$this->key,
        $this->headers
      );


      // Build response
      $movie = array(
        'moviedb_last_update' => date("Y-m-d H:i:s"),
        'moviedb_revenue' => $response->revenue
      );

      // Save $movie in databse
      $query = new Movie();
      $query
        ->where(array(
          'title' => $title
        ))
        ->set($movie)
        ->save();


      // // Show json
      if($return_json) {
        echo json_encode($movie);
      }

      // // Return data
      return $movie;

    }




    /**
     * Add new movies to the databse
     */
    public function creator() {

      // Random page
      $page = rand(0, 10);

      // Add randomly 20 new movies
      $tops = Fetch::get(
        $this->url.'movie/top_rated?api_key='.$this->key.'&page='.$page,
        $this->headers
      )->results;

      // Save $movies
      $response = array();
      foreach ($tops as $key => $movie) {

        // Get movies metadatas
        $movie = Fetch::get(
          $this->url.'movie/'.$movie->id.'?api_key='.$this->key,
          $this->headers
        );

        // Check if it has not been created before
        $query = new Movie();
        $check_movie = $query
          ->where(array(
            'imdb_id' => $movie->imdb_id
          ))
          ->fetch();

        if(!$check_movie) {
          $movie = array(
            'title' => $movie->title,
            'imdb_id' => $movie->imdb_id,
            'year' => (int)substr($movie->release_date, 0, 4)
          );
          $query = new Movie();
          $query
            ->set($movie)
            ->save();
          $response[$key] = $movie;
        }


      }

      echo json_encode($response);

    }

  }
