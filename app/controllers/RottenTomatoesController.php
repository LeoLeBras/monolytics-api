<?php

  require_once(CORE_DIR.'/helpers/Fetch.php');
  use Sunra\PhpSimple\HtmlDomParser;

  class RottenTomatoesController {

    private $url = 'http://www.rottentomatoes.com';

    /**
     * Get scores from Rotten Tomatoes
     * website
     *
     * @param {string} $query
     * @return {json}
     */
    public function get($query) {

      // Initialyze $movie
      $movie = array();
      $movie['title'] = ucwords(strtolower($query));
      $query = join('+', explode(' ', $query));

      // Get link
      $dom = HtmlDomParser::file_get_html('http://www.rottentomatoes.com/search/?search='.$query);
      $link = $dom
        ->find('#movie_results_ul > .articleLink', 0)
        ->attr['href'];
      $movie['rottentomatoes_link'] = $this->url.$link;

      // Get stats
      $dom = HtmlDomParser::file_get_html($movie['rottentomatoes_link']);
      $scoresDom = $dom->find('#scorePanel', 0);
      $textSelector = '_';

      // Get tomato meter
      $tomatoMeter = $scoresDom
        ->find('.tomato-left', 0)
        ->find('.meter-value', 0)
        ->find('span', 0)
        ->nodes[0]
        ->$textSelector;
      $movie['tomato_meter'] = $tomatoMeter['4'];

      // Get audience score
      $audienceScore = $scoresDom
        ->find('.audiencepanel', 0)
        ->find('.meter-value > .superPageFontColor', 0)
        ->nodes[0]
        ->$textSelector;
      $movie['audience_score'] = explode('%', $audienceScore['4'])[0];

      // Return json
      echo json_encode($movie);

    }


  }
