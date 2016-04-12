<?php

  require_once(VENDOR_DIR.'/autoload.php');
  use Sunra\PhpSimple\HtmlDomParser;

  class PirateBayController {

    public $url = 'https://ukpirate.org';


    /**
     * Get some seeders and letchers
     * from PirateBay
     *
     * @param {string} $query
     * @return {array}
     */
    public function get($query) {

      // Get data
      $query = join('+', explode(' ', $query));
      $dom = HtmlDomParser::file_get_html($this->url.'/s/?q='.$query.'&page=0&orderby=99');
      $tbody = $dom->find('#searchResult', 0);
      $text_selector = '_';
      $nodeIndex = 0;
      $seeders = 0;
      $leechers = 0;
      foreach($tbody->find('tr') as $tr) {
        if($nodeIndex !== 0) {
          $torrent_seeders = $tr->find('td', 2)->nodes[0]->$text_selector;
          $torrent_leechers = $tr->find('td', 3)->nodes[0]->$text_selector;
          $seeders += $torrent_seeders[4];
          $leechers += $torrent_leechers[4];
        }
        $nodeIndex += 1;
      }

      // Build response
      $movie = array(
        'pirate_bay_seeders' => $seeders,
        'pirate_bay_leechers' => $leechers
      );

      // Show response
      echo json_encode($movie);

      // Return data
      return $movie;

    }

  }
