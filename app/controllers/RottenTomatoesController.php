<?php

  require_once(VENDOR_DIR.'/autoload.php');
  require_once(APP_DIR.'/models/Movie.php');
  use Sunra\PhpSimple\HtmlDomParser;

  class RottenTomatoesController {

    private $url = 'http://www.rottentomatoes.com';



    /**
     * Fetch Rotten Tomatoes metadatas
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
     * Crawl Rotten Tomatoes metadatas
     *
     * @param {string} $query
     * @return {array}
     */
    public function runCrawler() {

      // Get movies from databse
      $query = new Movie();
      $movies = $query
        ->limit(10)
        ->orderBY('rotten_tomatoes_last_update', 'ASC')
        ->fetchAll();

      // Fetch tweets
      $response = [];
      foreach($movies as $key => $movie) {
        $response[$key] = $this->get(htmlentities(strtolower($movie->title)), $movie->rotten_tomatoes_link, false);
      }

      // Show response
      echo json_encode($response);

    }



    /**
     * Get scores from Rotten Tomatoes
     * website
     *
     * @param {string} $query
     * @param {boolean} $return_json
     * @return {array}
     */
    public function get($query, $link = null, $return_json = true) {

      $title = ucwords(strtolower($query));
      $query = join('+', explode('-', join('+', explode(' ', $query))));

      // Get link from search resultats
      if(empty($link)) {
        $dom = @HtmlDomParser::file_get_html('http://www.rottentomatoes.com/search/?search='.$query);
        if($dom) {
          $link = $dom->find('#movie_results_ul > .articleLink', 0);

          // Set if results exist
          if(!isset($link)) {
            $link = $this->url.'/'.'m/'.join('_', explode('+', $query)).'/';
          }
          else {
            $link = $this->url.$link->attr['href'];
          }
        }
      }

      // Initialyze $movie
      $movie = array(
        'rotten_tomatoes_meter' => 0,
        'rotten_tomatoes_score' => 0,
        'rotten_tomatoes_link' => $link,
        'rotten_tomatoes_last_update' => date("Y-m-d H:i:s")
      );

      // Get stats
      $dom = @HtmlDomParser::file_get_html($movie['rotten_tomatoes_link']);
      if($dom) {
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
      if($return_json) {
        echo json_encode($movie);
      }

      return $movie;

    }


  }
