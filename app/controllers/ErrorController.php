<?php

  require_once(CORE_DIR.'/helpers/View.php');

  class ErrorController {

    public function notfound() {
      View::make('notfound');
    }

  }
