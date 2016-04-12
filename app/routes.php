<?php

  $routes = array(

    // Home
    '/' => 'LandingController\index',

    // Errors
    '/notfound' => 'errorController\notfound',

    // Get movies
    '/movies' => 'MoviesController\all',

    // Youtube
    '/youtube/:movie' => 'YoutubeController\get',

    // Rotten Tomatoes
    '/rottentomatoes/:movie' => 'RottenTomatoesController\get',

    // List of movies
    '/trakt/:type' => 'TraktController\get',
    '/list' => 'TraktController\list',

    // MovieDB
    '/omdbapi/:movie' => 'OMDbAPIController\get',

    // PirateBay
    '/piratebay/:movie' => 'PirateBayController\get',

    // Twitter
    '/twitter/:movie' => 'TwitterController\get'

  );
