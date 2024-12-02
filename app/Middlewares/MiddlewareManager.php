<?php

namespace App\Middlewares;

class MiddlewareManager
{
    protected $middlewares;

    public function __construct() {
        $this->middlewares = require 'app\Config\middlewares.php';
    }

    public function handleMiddlewares(array $middlewareNames = []) {
        foreach ($middlewareNames as $name) {
            if (isset($this->middlewares[$name])) {
                $middlewareClass = $this->middlewares[$name];
                
                if (class_exists($middlewareClass)) {
                    $middleware = new $middlewareClass();
                    
                    if (method_exists($middleware, 'handle')) {
                        $middleware->handle();
                    }
                    
                } else {
                    throw new \Exception("Middleware class not found: $middlewareClass");
                }
            } else {
                throw new \Exception("Middleware alias not found: $name");
            }
        }
    }
}
