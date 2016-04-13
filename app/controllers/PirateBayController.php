<?php

  require_once(VENDOR_DIR.'/autoload.php');
  require_once(APP_DIR.'/models/Movie.php');
  use Sunra\PhpSimple\HtmlDomParser;

  class PirateBayController {

    public $url = 'https://ukpirate.org';



    /**
     * Fetch piratebay metadatas
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
     * Crawl piratebay metadatas
     *
     * @param {string} $query
     * @return {array}
     */
    public function runCrawler() {

      // Get movies from databse
      $query = new Movie();
      $movies = $query
        ->limit(10)
        ->orderBY('pirate_bay_last_update', 'ASC')
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
     * Get some seeders and letchers
     * from PirateBay
     *
     * @param {string} $query
     * @param {string} $year
     * @param {boolean} $return_json
     * @return {array}
     */
    public function get($query, $year, $return_json = true) {

      // Get data
      $title = join('+', explode('-', join('+', explode(' ', $query))));
      $dom = @HtmlDomParser::file_get_html($this->url.'/s/?q='.$title.'+'.$year.'&page=0&orderby=99');
      if($dom) {
        $tbody = $dom->find('#searchResult', 0);
        $text_selector = '_';
        $nodeIndex = 0;
        $seeders = 0;
        $leechers = 0;
        if($tbody !== null) {
          foreach($tbody->find('tr') as $tr) {
            if($nodeIndex !== 0 && $nodeIndex < 6) {
              $torrent_seeders = $tr->find('td', 2)->nodes[0]->$text_selector;
              $torrent_leechers = $tr->find('td', 3)->nodes[0]->$text_selector;
              $seeders += $torrent_seeders[4];
              $leechers += $torrent_leechers[4];
            }
            $nodeIndex += 1;
          }
        }
      }

      // Build response
      $movie = array(
        'pirate_bay_seeders' => (int)$seeders,
        'pirate_bay_leechers' => (int)$leechers,
        'pirate_bay_last_update' => date("Y-m-d H:i:s")
      );

      // Save $movie in databse
      $query = new Movie();
      $query
        ->where(array(
          'title' => ucwords(strtolower(join(' ', explode('+', $title))))
        ))
        ->set($movie)
        ->save();

      // Show response
      if($return_json) {
        echo json_encode($movie);
      }

      // Return data
      return $movie;

    }

  }
