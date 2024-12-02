<?php

namespace App\Middlewares\Default;

use App\Http\Request;

class Maintenance {

    protected $request;

    public function __construct() {
        $this->request = new Request();
    }

    public function handle()
    {
        
    }
}