<?php

  $routes = array(

    // Status
    '/' => 'IndexController\index',
    '/notfound' => 'ErrorController\notfound',

    // Monolytics API
    '/movies' => 'MoviesController\getAll', // Get all movies
    '/tops' => 'MoviesController\getTops',  // Get all tops movies
    '/tweets' => 'TwitterController\tops',  // Get tweets who referenced #trurank

    // APIs
    '/trakt' => 'TraktController\getTops',
    '/youtube/:movie' => 'YoutubeController\index',
    '/rottentomatoes/:movie' => 'RottenTomatoesController\get',
    '/streaming/:movie' => 'TraktController\index',
    '/omdb/:movie' => 'OMDbAPIController\get',
    '/piratebay/:movie' => 'PirateBayController\get',
    '/twitter/:query' => 'TwitterController\index'

  );
