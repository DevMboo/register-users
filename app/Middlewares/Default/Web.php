<?php

namespace App\Middlewares\Default;

use App\Http\Request;

class Web {

    protected $request;

    public function __construct() {
        $this->request = new Request();
    }

    public function handle()
    {
        
    }
}