<?php

namespace App\Bootstrap;

use App\Http\Router;
use App\Http\Request;
use App\View\View;

class Bootstrap {

    public function env($filePath)
    {
        if (!file_exists($filePath)) {
            http_response_code(500);
            View::render('errors/index.component.html', 
                    [
                        'url' => '',
                        'title' => '.env file not found.',
                        'code' => '500', 
                        'message' => 'The .env file is not defined. Please create the file in the project directory and restart the project.',
                        'moment' => '', 
                    ], 
                    'pages/views/event.html');
            exit;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // Ignora comentários no .env
            }

            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if (!empty($key)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }
    }

    public function handle() {
        $request = new Request();
        $router = new Router($request);
        
        $router->resolve($request);
    }
    
}