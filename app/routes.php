<?php

  $routes = array(

    // Status
    '/' => 'IndexController\index',
    '/notfound' => 'ErrorController\notfound',

    // Monolytics API
    '/movies' => 'MoviesController\getAll', // Get all movies
    '/tops' => 'MoviesController\getTops',  // Get all tops movies

    // Calc TRURANK
    '/trurank/:movie' => 'TruRankController\index',

    // APIs
    '/creator' => 'MovieDBController\creator',
    '/trakt' => 'TraktController\getTops',
    '/youtube/:movie' => 'YoutubeController\index',
    '/rottentomatoes/:movie' => 'RottenTomatoesController\index',
    '/streaming/:movie' => 'TraktController\index',
    '/omdb/:movie' => 'OMDbAPIController\index',
    '/moviedb/:movie' => 'MovieDBController\index',
    '/piratebay/:movie' => 'PirateBayController\index',
    '/twitter/:query' => 'TwitterController\index'

  );
