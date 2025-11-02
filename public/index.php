<?php

// Define a constant for the project root for convenience
define('ROOT_PATH', dirname(__DIR__));

/*
 * ------------------------------------------------------
 *  MANUAL AUTOLOADING
 * ------------------------------------------------------
 */

// 1. Load the Autoloader Class
require_once ROOT_PATH . '/autoloader.php';

// 2. Instantiate the Autoloader
$loader = new Psr4AutoloaderClass();

// 3. Register the namespace prefixes.
// This tells the autoloader where to find the classes.
$loader->addNamespace('Simplo', 'src/Simplo');
$loader->addNamespace('App', 'src/App');

// 4. Register the autoloader with PHP.
$loader->register();


/*
 * ------------------------------------------------------
 *  MANUAL HELPER FILE LOADING
 * ------------------------------------------------------
 */
require_once ROOT_PATH . '/helpers/common.php';
require_once ROOT_PATH . '/helpers/form.php';
require_once ROOT_PATH . '/helpers/url.php';


/*
 * ------------------------------------------------------
 *  RUN THE APPLICATION
 * ------------------------------------------------------
 */
$app = new Simplo\Application();
$app->run();