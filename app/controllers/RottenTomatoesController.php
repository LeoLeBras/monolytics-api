<?php

  require_once(VENDOR_DIR.'/autoload.php');
  require_once(APP_DIR.'/models/Movie.php');
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
      $title = ucwords(strtolower($query));
      $query = join('+', explode(' ', $query));

      // Get link
      $dom = HtmlDomParser::file_get_html('http://www.rottentomatoes.com/search/?search='.$query);
      $link = $dom
        ->find('#movie_results_ul > .articleLink', 0)
        ->attr['href'];
      $movie['rotten_tomatoes_link'] = $this->url.$link;

      // Get stats
      $dom = HtmlDomParser::file_get_html($movie['rotten_tomatoes_link']);
      $scores_dom = $dom->find('#scorePanel', 0);
      $text_selector = '_';

      // Get tomato meter
      $tomato_meter = $scores_dom
        ->find('.tomato-left', 0)
        ->find('.meter-value', 0)
        ->find('span', 0)
        ->nodes[0]
        ->$text_selector;
      $movie['rotten_tomatoes_meter'] = $tomato_meter['4'];

      // Get audience score
      $audience_score = $scores_dom
        ->find('.audiencepanel', 0)
        ->find('.meter-value > .superPageFontColor', 0)
        ->nodes[0]
        ->$text_selector;
      $movie['rotten_tomatoes_score'] = explode('%', $audience_score['4'])[0];

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
