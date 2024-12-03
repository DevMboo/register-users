<?php

namespace App\Middlewares\Default;

use App\Http\Request;
use App\Http\Response;

class Auth {

    protected $request;
    protected $response;

    public function __construct() {
        $this->request = new Request();
        $this->response = new Response($this->request);
    }

    public function auth()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user']['id']);
    }

    public function handle()
    {
        if (!$this->auth()) {
            $this->response->redirect('/login');
            exit;
        }
    }
}
