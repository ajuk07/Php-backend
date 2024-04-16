<?php

namespace App\Lib;

class Router
{
    private $routes = [];

    public function get($path, $callback, $middleware = null)
    {
        $this->add('GET', $path, $callback, $middleware);
    }

    public function post($path, $callback, $middleware = null)
    {
        $this->add('POST', $path, $callback, $middleware);
    }

    public function put($path, $callback, $middleware = null)
    {
        $this->add('PUT', $path, $callback, $middleware);
    }

    public function delete($path, $callback, $middleware = null)
    {
        $this->add('DELETE', $path, $callback, $middleware);
    }

    private function add($method, $path, $callback, $middleware)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    public function dispatch($method, $uri)
    {
        $uri = strtok($uri, '?');

        $pattern = '#^' . preg_replace('~(:id)~', '(\d+)', preg_quote($uri, '~')) . '$#';

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                $params = array_slice($matches, 1);
                // var_dump($params);

                if ($route['middleware']) {
                    $middleware = new $route['middleware']();
                    if ($middleware->handle($matches) === false) {
                        return;
                    }
                }
                call_user_func_array($route['callback'], $params);
                return;
            }
        }

        echo "404 Not Found";
    }
}
