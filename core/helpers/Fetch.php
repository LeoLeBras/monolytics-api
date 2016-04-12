<?php

  require_once(VENDOR_DIR.'/autoload.php');

  class Fetch {

    /**
     * Get content from a WebAPI
     * endpoint
     *
     * @param {string} route
     */
    static public function get($route, $headers) {
      $response = Unirest\Request::get($route, $headers);
      return $response->body;
    }

  }
