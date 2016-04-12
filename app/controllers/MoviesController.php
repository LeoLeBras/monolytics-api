<?php

  require_once(APP_DIR.'/models/Movie.php');

  class MoviesController {

    public function all() {
      $query = new Movie();
      $movies = $query
        ->fetchAll();
      echo json_encode($movies);
    }

  }
