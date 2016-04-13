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
    '/trakt/:type' => 'TraktController\top',
    '/streaming/:movie' => 'TraktController\get',
    '/tops' => 'TraktController\tops',

    // MovieDB
    '/omdbapi/:movie' => 'OMDbAPIController\get',

    // PirateBay
    '/piratebay/:movie' => 'PirateBayController\get',

    // Twitter
    '/twitter/:movie' => 'TwitterController\get'

  );
