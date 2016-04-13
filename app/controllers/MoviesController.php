<?php

  require_once(APP_DIR.'/models/Movie.php');

  class MoviesController {

    /**
     * Get all movies saved
     * in databse
     *
     * @return {array}
     */
    public function getAll() {

      // Set key
      $key = 'allMovies';

      // Get movies
      if(Storage::check($key)) { // from storage
        $movies = Storage::get($key);
      }
      else { // from databse
        $query = new Movie();
        $movies = $query
          ->fetchAll();
        Storage::set($key, $movies);
      }

      // Return json
      echo json_encode($movies);
      return $movies;

    }



    /**
     * Get tops movies
     *
     * @return {array}
     */
    public function getTops() {

      // Build response
      $movies = array(
        'app' => 'monolytics'
      );

      // Return json
      echo json_encode($movies);
      return $movies;

    }

  }
