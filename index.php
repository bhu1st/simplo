<?php

// Setup Application Environment

if (file_exists("localhost")) 
{
    define('ENVIRONMENT', "development");
    define('HOST', 'http://localhost/');
    define('HOSTDIR', 'simplo');
    define('URLROOT', HOST . HOSTDIR);
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} 
else 
{
    define('ENVIRONMENT', "production");
    define('HOST', 'https://www.example.com/');
    define('HOSTDIR', '');
    define('URLROOT', HOST . HOSTDIR);
    ini_set('display_errors', '0');
    error_reporting(0);
    //error_reporting (E_ALL);
}

// Define a constant for the project root for convenience
define('ROOT_PATH', __DIR__);

/*
 * ------------------------------------------------------
 *  Make the Active Theme Globally Available
 * ------------------------------------------------------
 */

$themeConfig = require_once ROOT_PATH . '/config/theme.php';
define('ACTIVE_THEME', $themeConfig['active_theme'] ?? null);

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
$loader->addNamespace('Simplo', 'simplo');
$loader->addNamespace('App', 'app');

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
$app = new Simplo\Simplo();
$app->run();