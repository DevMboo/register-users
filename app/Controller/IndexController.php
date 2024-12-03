<?php

namespace App\Controller;

use App\View\View;
use App\Http\Request;

class IndexController extends Controller {
    public function render()
    {
        View::title('Home - Phaesy App')->render('index/index.component.html', ['title' => 'Meu novo app render', 'description' => 'teste']);
    }
}