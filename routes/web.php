<?php

use App\Http\Router;

Router::get('/', 'IndexController', 'render');
Router::get('/login', 'LoginController');
Router::post('/auth', 'LoginController', 'auth');
Router::get('/home', 'HomeController', 'render')->middlewares(['web', 'auth']);