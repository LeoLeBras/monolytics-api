<?php

  $routes = array(

    // Home
    '/' => 'LandingController\index',

    // Auth
    '/subscribe' => 'AuthController\subscribe',
    '/login' => 'AuthController\login',
    '/logout' => 'AuthController\logout',

    // Errors
    '/notfound' => 'errorController\notfound',

    // Youtube
    '/youtube' => 'YoutubeController\all',

    // List of movies
    '/trakt/:type' => 'TraktController\get',

    // MovieDB
    '/moviedb' => 'TheMovieDB\get'

  );
