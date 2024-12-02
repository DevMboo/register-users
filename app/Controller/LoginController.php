<?php 

namespace App\Controller;

use App\View\View;
use App\Http\Request;

class LoginController extends Controller {
    
    public function render()
    {
        View::title('Login - Phaesy App')->render('login/index.component.html', ['title' => 'Meu novo app render', 'description' => 'teste']);
    }
}