<?php

  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  require_once('core/config/index.php');
  require_once(VENDOR_DIR.'/autoload.php');
  require_once('core/config/database.php'); // PDO connect
  require_once('core/config/tokens.php');   // Get tokens
  require_once('core/config/paths.php');    // Paths
  require_once('core/autoload.php');

  Kernel::register();
  Kernel::run();
