<?php

  require_once(CORE_DIR.'/helpers/Fetch.php');
  require_once(APP_DIR.'/models/Movie.php');

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
      $title = ucwords(strtolower($query));
      $movie = array(
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
        $video_id = $video->id->videoId;
        $video = Fetch::get(
          $this->url.'videos?id='.$video_id.'&key='.$this->key.'&part=snippet,contentDetails,statistics,status',
          $this->headers
        );
        foreach ($movie as $key => $value) {
          $video_statistics = $video->items[0]->statistics;
          $movie[$key] = $movie[$key] + $video_statistics->$key;
        }
      }

      // Clear structure
      $structure = array(
        'viewCount' => 'youtube_view_count',
        'likeCount' => 'youtube_like_count',
        'dislikeCount' => 'youtube_dislike_count',
        'commentCount' => 'youtube_comment_count'
      );
      foreach ($structure as $key => $value) {
        $movie[$structure[$key]] = $movie[$key];
        unset($movie[$key]);
      }

      // Save $movie in databse
      $query = new Movie();
      $query
        ->where(array(
          'title' => $title
        ))
        ->set($movie)
        ->save();

      // Return json
      echo json_encode($movie);

    }

  }
