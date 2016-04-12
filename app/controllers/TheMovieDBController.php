<?php

  require_once(CORE_DIR.'/helpers/Fetch.php');

  class TheMovieDBController {

    public function get() {

      Fetch::get();

    }

  }
