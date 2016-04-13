<?php

  define('DB_HOST', 'xx');
  define('DB_NAME', 'xx');
  define('DB_USER', 'xx');
  define('DB_PASS', 'xx');

  try {
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USER,DB_PASS);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
  } catch (Exception $e) {
    die('Cound not connect');
  }
