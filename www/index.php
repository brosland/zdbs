<?php
// absolute filesystem path to this web root
define('WWW_DIR', __DIR__);

// absolute filesystem path to the data root
define('DATA_DIR', __DIR__ . '/..');

// absolute filesystem path to the application root
define('APP_DIR', DATA_DIR . '/app');

// absolute filesystem path to the libraries
define('LIBS_DIR', DATA_DIR . '/libs');

// files dir
define('FILES_DIR', WWW_DIR . '/files');

// uncomment this line if you must temporarily take down your site for maintenance
// require APP_DIR . '/templates/maintenance.phtml';

// load bootstrap file
require APP_DIR . '/bootstrap.php';
