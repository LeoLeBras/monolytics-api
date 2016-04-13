<?php

  class ErrorController {

    /**
     * Page not found
     */
    public function notfound() {
      $response = array(
        'app' => 'monolytics',
        'version' => '0.1',
        'status' => 'error'
      );
      echo json_encode($response);
    }

  }
