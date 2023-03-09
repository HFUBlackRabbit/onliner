<?php

namespace App;

class Router
{
    private array $routes;
    public function __construct()
    {
        $this->routes = require(ROOT . '/config/routes.php');
    }

    public function resolve() {

        $path = $_SERVER['REQUEST_URI'];
        $path = preg_replace('/\/?\?.*$/', '', $path);

        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        $route = $this->routes[$method][$path] ?? null;
        if ($route == null || !method_exists($route[0], $route[1])) {
            App()->abort('Not Fount', 404);
        }

        return $route;
    }
}