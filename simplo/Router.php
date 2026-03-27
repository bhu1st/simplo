<?php

namespace Simplo;

class Router
{
    protected $routes = [];
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function addRoute(string $method, string $route, array $handler)
    {
        $this->routes[$method][$route] = ['controller' => $handler[0], 'action' => $handler[1]];
    }

    public function get(string $route, array $handler)
    {
        $this->addRoute('GET', $route, $handler);
    }

    public function post(string $route, array $handler)
    {
        $this->addRoute('POST', $route, $handler);
    }

    public function dispatch(string $uri, string $method)
    {
        if (!isset($this->routes[$method])) {
            throw new \Exception("No routes defined for method: $method");
        }

        foreach ($this->routes[$method] as $route => $handler) {

            $pattern = preg_replace(
                '/\{([a-zA-Z0-9_]+)\}/',
                '(?P<$1>[a-zA-Z0-9_-]+)',
                $route
            );

            if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {

                $controllerClass = $handler['controller'];
                $action = $handler['action'];

                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (!class_exists($controllerClass)) {
                    throw new \Exception("Controller not found: $controllerClass");
                }

                $controller = new $controllerClass($this->container);

                if (!method_exists($controller, $action)) {
                    throw new \Exception("Method $action not found in $controllerClass");
                }

                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        http_response_code(404);
        throw new \Exception("No route found for URI: $uri");
    }
}
