<?php

  class Route {

    /**
     * Redirect the user to an
     * another route
     *
     * @param {string} route
     */
    static public function redirect($route) {
      global $routes;
      if($routes[$route]) { // Be sure the route exist
        header('Location: '.URL.substr($route, 1));
        exit();
      }
    }

    /**
     * Return last route
     *
     * @return {string}
     */
    static public function last() {
      return $_SERVER['HTTP_REFERER'];
    }

  }
