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
    '/youtube/:movie' => 'YoutubeController\get',
    '/rottentomatoes/:movie' => 'RottenTomatoesController\get',
    '/streaming/:movie' => 'TraktController\getMovie',
    '/omdbapi/:movie' => 'OMDbAPIController\get',
    '/piratebay/:movie' => 'PirateBayController\get',
    '/twitter/:movie' => 'TwitterController\get'


  );
