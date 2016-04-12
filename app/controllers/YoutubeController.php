<?php

  require_once(APP_DIR.'/models/Movie.php');
  require_once(CORE_DIR.'/helpers/Fetch.php');

  class YoutubeController {

    private $url = 'https://www.googleapis.com/youtube/v3/';
    private $headers = array(
      'Content-Type' => 'application/json'
    );
    private $key = 'AIzaSyCKiKcBC8tC1Py4kDc-mZ7z25lAOfa9GbE';



    /**
     * Get Youtube data
     * > https://developers.google.com/youtube/v3/docs/search/list#parameters
     *
     * @param {string} $name
     */
    public function get($query) {

      // Initialize movie data
      $movie = array(
        'title' => ucwords(strtolower($query)),
        'viewCount' => 0,
        'likeCount' => 0,
        'dislikeCount' => 0,
        'commentCount' => 0
      );

      // Get video list results
      $response = Fetch::get(
        $this->url.'search?part=snippe&part=snippet&order=relevance&q='.$query.'+trailer&maxResults=3&type=video&key='.$this->key,
        $this->headers
      );

      // Fetch data
      foreach($response->items as $video) {
        $videoId = $video->id->videoId;
        $video = Fetch::get(
          $this->url.'videos?id='.$videoId.'&key='.$this->key.'&part=snippet,contentDetails,statistics,status',
          $this->headers
        );
        foreach ($movie as $key => $value) {
          if($key !== 'title') {
            $videoStatistics = $video->items[0]->statistics;
            $movie[$key] = $movie[$key] + $videoStatistics->$key;
          }
        }
      }

      // Return json
      echo json_encode($movie);

    }

  }
