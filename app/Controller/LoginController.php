<?php 

namespace App\Controller;

use App\View\View;
use App\Http\Request;
use App\Http\Router;

use App\Model\Database\Users;

class LoginController extends Controller {
    
    protected $users;

    public function __construct() {
        $this->users = new Users();
    }

    public function generatedSession($user)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'age' => $user['age']
        ];

        return Router::redirect('/home');   
    }

    public function auth(Request $request)
    {
        $user = $this->users->findUserByEmail($request->get('email'));
        
        if($user) {
            return $this->generatedSession($user);
        }

        return Router::redirect('/login');        
    }

    public function render()
    {
        View::title('Login - Phaesy App')->render('login/index.component.html');
    }
}