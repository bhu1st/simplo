<?php

// 1. Register Composer's Autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// 2. Create the Application
$app = new Simplo\Application();

// 3. Run the Application
$app->run();