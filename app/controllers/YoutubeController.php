<?php

  require_once(APP_DIR.'/models/Movie.php');
  require_once(CORE_DIR.'/helpers/Fetch.php');

  class YoutubeController {

    public function all() {
      echo Fetch::get('https://www.googleapis.com/youtube/v3/videos?id=7lCDEYXw3mM&key='.YOUTUBE_KEY.'&part=snippet,contentDetails,statistics,status');
    }

  }
