<?php

  class IndexController {
    public function index() {
      $response = array(
        'app' => 'monolytics',
        'version' => '0.1',
        'status' => 'success'
      );
      echo json_encode($response);
    }
  }
