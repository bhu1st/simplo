<?php

use App\Controllers\Admin\AdminController;
use App\Controllers\HomeController;
use Simplo\Router;

return function(Router $router) {
    // Public Routes
    $router->get('/', [HomeController::class, 'index']);
    $router->get('/hello', [HomeController::class, 'hello']);
    $router->get('/greet', [HomeController::class, 'greet']);
    $router->get('/greet/{name}', [HomeController::class, 'greet']); // New, more flexible syntax
    $router->get('/form', [HomeController::class, 'showForm']);
    $router->post('/handle-form', [HomeController::class, 'handleForm']);

    // Admin Routes
    $router->get('/admin', [AdminController::class, 'index']);
};