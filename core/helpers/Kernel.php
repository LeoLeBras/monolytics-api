<?php

  require_once(APP_DIR.'/routes.php');

  class Kernel {

    /**
     * Run the app
     *
     * $_GET as params of this
     * method with :
     * @param {string} name
     */
    static public function run() {

      // Run session
      session_start();

      // Extract data
      $q = isset($_GET['q']) ? $_GET['q'] : '/';
      $path = '/'.explode('/', $q)[0];

      // Get controller
      global $routes;
      foreach($routes as $_route => $_controller) {
        if(explode('/:', $_route)[0] == $path ) {
          $routeName = $_route;
          $route = explode('\\', $_controller);
          $controller = $route[0];
          $controllerMethod = $route[1];
        }
      }

      // Get route params
      $params = explode('/', substr($q, 1));
      array_shift($params);

      // Show error page
      while(
        !isset($controller) ||
        !isset($controllerMethod) ||
        !@require_once APP_DIR.'/controllers/'.$controller.'.php'
      ) {
        $controller = 'ErrorController';
        $controllerMethod = 'notfound';
      }

      // Run controller
      $app = new $controller();
      call_user_func_array(
        array($app, $controllerMethod),
        $params
      );

    }

  }
