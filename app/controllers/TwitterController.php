<?php

  require_once(CORE_DIR.'/helpers/Fetch.php');
  require_once(CORE_DIR.'/helpers/Twitter.php');

  class TwitterController {

    /**
     * Get all tweets from a movie
     *
     * @param {string} $query
     * @return {array}
     */
    public function get($query) {

      // Get data
      $response = Twitter::getMovie($query);

      // Show json
      echo json_encode($response);

      // Return data
      return $response;

    }

  }
