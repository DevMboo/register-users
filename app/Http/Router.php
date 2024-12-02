<?php

namespace App\Http;

use App\View\View;
use App\Http\Request;
use App\Http\Response;
use App\Middlewares\MiddlewareManager;

class Router {

    protected $routes = [];
    protected $response;
    protected $fileMap;
    protected $middlewareManager;

    public function __construct(Request $request) 
    {
        $this->response = new Response($request);
        $this->fileMap = include 'app/Config/troubles.php';
        $this->middlewareManager = new MiddlewareManager();
        $this->getDefault();
        $this->getRoutes();
    }

    public function getDefault()
    {
        $routeFile = './routes/default.php';
        
        if (!file_exists($routeFile)) {
            throw new \Exception("Arquivo de rotas default não encontrado em: $routeFile");
        }

        return include $routeFile;
    }

    public function getRoutes() 
    {
        $routeFile = './routes/web.php';
        
        if (!file_exists($routeFile)) {
            throw new \Exception("Arquivo de rotas não encontrado em: $routeFile");
        }

        return include $routeFile;
    }

    public function get($url, $controller = null, $exec = null) 
    {
        $this->routes[] = [
            'method' => 'GET',
            'url' => $url,
            'controller' => $controller,
            'exec' => $exec,
            'middlewares' => []
        ];

        return $this;
    }

    public function post($url, $controller = null, $exec = null) 
    {
        $this->routes[] = [
            'method' => 'POST',
            'url' => $url,
            'controller' => $controller,
            'exec' => $exec,
            'middlewares' => []
        ];

        return $this;
    }

    public function put($url, $controller = null, $exec = null) 
    {
        $this->routes[] = [
            'method' => 'PUT',
            'url' => $url,
            'controller' => $controller,
            'exec' => $exec,
            'middlewares' => []
        ];

        return $this;
    }

    public function delete($url, $controller = null, $exec = null) 
    {
        $this->routes[] = [
            'method' => 'DELETE',
            'url' => $url,
            'controller' => $controller,
            'exec' => $exec,
            'middlewares' => []
        ];

        return $this;
    }

    public function middlewares(array $middlewares) 
    {
        $index = count($this->routes) - 1;
        $this->routes[$index]['middlewares'] = $middlewares;
        
        return $this;
    }

    public function resolve(Request $request) 
    {
        $currentMethod = $request->getMethod();
        $currentUrl = $request->getUri();
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $currentMethod && $this->matchUrl($currentUrl, $route['url'])) {

                $this->middlewareManager->handleMiddlewares($route['middlewares'] ?? []);

                if (is_null($route['controller'])) {
                    $this->serveStaticHtml($route['url']);
                    return;
                }

                $this->executeController($route['controller'], $request->getParams(), $route['exec']);
                return;
            }
        }

        $this->serveStaticHtml('/not-found');
    }

    protected function matchUrl($currentUrl, $routeUrl) 
    {
        return preg_match('#^' . preg_replace('#\{[^\}]+\}#', '([a-zA-Z0-9]+)', $routeUrl) . '$#', $currentUrl);
    }

    protected function executeMiddlewares($middlewares)
    {
        foreach ($middlewares as $middleware) {
            $middlewareClass = config('middlewares')[$middleware] ?? null;
            
            if (!$middlewareClass || !class_exists($middlewareClass)) {
                throw new \Exception("Middleware not found: $middlewareClass");
            }

            $instance = new $middlewareClass();
            $instance->handle();
        }
    }

    protected function executeController($controller, $params, $exec = null) 
    {
        $controllerClass = "App\\Controller\\$controller";

        if (class_exists($controllerClass)) {
            $instance = new $controllerClass;
            if(!$exec) {
                $instance->render($params);
            } else {
                $instance->$exec($params);
            }
        } else {
            throw new \Exception("Controller not found: $controllerClass");
        }
    }
    
    protected function serveStaticHtml($url) {
        if (isset($this->fileMap[$url])) {
            http_response_code($this->fileMap[$url]['code']);
    
            View::render($this->fileMap[$url]['file'], 
                    [
                        'url' => $this->fileMap[$url]['url'],
                        'title' => $this->fileMap[$url]['title'],
                        'code' => $this->fileMap[$url]['code'], 
                        'message' => $this->response->defineMessageByUrl($url)['message'],
                        'moment' => $this->fileMap[$url]['moment'], 
                    ], 
                    'pages/views/event.html');
    
            exit; 
        } 
    }
}
