<?php

  require_once(APP_DIR.'/models/Movie.php');
  require_once(APP_DIR.'/models/MovieTop.php');

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

      // Set key
      $key = 'topMovies';

      // Get movies
      if(Storage::check($key)) { // from storage
        $movies = Storage::get($key);
      }
      else { // from databse
        $query = new MovieTop();
        $movies = $query
          ->join('movies', 'movies_tops.movie_id = movies.id')
          ->fetchAll();
        Storage::set($key, $movies);
      }

      // Return json
      echo json_encode($movies);
      return $movies;

    }

  }
