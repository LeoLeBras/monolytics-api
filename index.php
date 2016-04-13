<?php

  require_once('core/config/index.php');
  require_once('core/config/database.php'); // PDO connect
  require_once('core/config/tokens.php');   // Get tokens
  require_once('core/config/paths.php');    // Paths
  require_once('core/helpers/Kernel.php');

  Kernel::run();
