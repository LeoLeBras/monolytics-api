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
    '/youtube/:movie' => 'YoutubeController\get',

    // Rotten Tomatoes
    '/rottentomatoes/:movie' => 'RottenTomatoesController\get',

    // List of movies
    '/trakt/:type' => 'TraktController\get',
    '/list' => 'TraktController\list',

    // MovieDB
    '/omdbapi/:movie' => 'OMDbAPIController\get'

  );
