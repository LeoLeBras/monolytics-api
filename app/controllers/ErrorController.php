<?php

  class ErrorController {
    public function notfound() {
      $response = array(
        'app' => 'monolytics',
        'version' => '0.1',
        'status' => 'error'
      );
      echo json_encode($response);
    }
  }
