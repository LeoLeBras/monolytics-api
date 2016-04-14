<?php

  require_once(CORE_DIR.'/helpers/Fetch.php');
  require_once(APP_DIR.'/models/Movie.php');

  class YoutubeController {

    private $url = 'https://www.googleapis.com/youtube/v3/';
    private $headers = array(
      'Content-Type' => 'application/json'
    );
    private $key = YOUTUBE_API_KEY;



    /**
     * Fetch youtube metadatas
     *
     * @param {string} $query
     */
    public function index($query) {
      if($query == 'crawl') {
        $this->runCrawler();
      }
      else {
        $this->get($query);
      }
    }



    /**
     * Crawl youtube metadatas
     *
     * @param {string} $query
     * @return {array}
     */
    public function runCrawler() {

      // Get movies from databse
      $query = new Movie();
      $movies = $query
        ->limit(30)
        ->orderBY('youtube_last_update', 'ASC')
        ->fetchAll();

      // Fetch tweets
      $response = [];
      foreach($movies as $key => $movie) {
        $response[$key] = $this->get(htmlentities(strtolower($movie->title)), $movie->year, false);
      }

      // Show response
      echo json_encode($response);

    }



    /**
     * Get Youtube data
     * > https://developers.google.com/youtube/v3/docs/search/list#parameters
     *
     * @param {string} $name
     * @param {string} $year
     * @param {boolean} $return_json
     * @return {array}
     */
    public function get($query, $return_json = true) {

      // Get video list results
      $response = Fetch::get(
        $this->url.'search?part=snippe&part=snippet&order=relevance&q='.$query.'+'.$year.'+trailer&maxResults=3&type=video&key='.$this->key,
        $this->headers
      );

      // Initialize movie data
      $title = ucwords(strtolower($query));
      $movie = array(
        'viewCount' => 0,
        'likeCount' => 0,
        'dislikeCount' => 0,
        'commentCount' => 0
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
        'commentCount' => 'youtube_comment_count',
      );
      foreach ($structure as $key => $value) {
        $movie[$structure[$key]] = (int)$movie[$key];
        unset($movie[$key]);
      }

      // Add youtube video id
      $movie['youtube_id'] = $response->items[0]->id->videoId;
      $movie['youtube_last_update'] = date("Y-m-d H:i:s");

      // Save $movie in databse
      $query = new Movie();
      $query
        ->where(array(
          'title' => $title
        ))
        ->set($movie)
        ->save();

      // Return json
      if($return_json) {
        echo json_encode($movie);
      }

      // Return data
      return $movie;

    }

  }
