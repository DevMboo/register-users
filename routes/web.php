<?php

use App\Http\Router;

Router::get('/login', 'LoginController');
Router::get('/home', 'HomeController', 'render')->middlewares(['web', 'auth']);