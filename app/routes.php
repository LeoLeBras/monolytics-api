<?php

  $routes = array(

    // Status
    '/' => 'IndexController\index',
    '/notfound' => 'ErrorController\notfound',

    // Monolytics API
    '/movies' => 'MoviesController\getAll', // Get all movies
    '/tops' => 'MoviesController\getTops',  // Get all tops movies
    '/tweets' => 'TwitterController\tops',  // Get tweets who referenced #trurank

    // Calc TRURANK
    '/trurank/:movie' => 'TruRankController\index',

    // APIs
    '/trakt' => 'TraktController\getTops',
    '/youtube/:movie' => 'YoutubeController\index',
    '/rottentomatoes/:movie' => 'RottenTomatoesController\index',
    '/streaming/:movie' => 'TraktController\index',
    '/omdb/:movie' => 'OMDbAPIController\index',
    '/moviedb/:movie' => 'MovieDBController\index',
    '/piratebay/:movie' => 'PirateBayController\index',
    '/twitter/:query' => 'TwitterController\index'

  );
