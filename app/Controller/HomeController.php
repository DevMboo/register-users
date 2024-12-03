<?php

namespace App\Controller;

use App\View\View;
use App\Http\Request;
use App\Http\Router;

use App\Model\Database\Users;
class HomeController extends Controller {

    protected $users;

    protected $errors = [];

    public function __construct() {
        $this->users = new Users();
    }

    public function errors()
    {
        return $this->errors;
    }

    private function validate($data)
    {
        $this->errors = [];

        if (empty($data['name'])) {
            $this->errors[] = 'O campo nome é obrigatório.';
        }

        if (empty($data['email'])) {
            $this->errors[] = 'O campo e-mail é obrigatório.';
        }

        if (empty($data['age'])) {
            $this->errors[] = 'O campo idade é obrigatório.';
        }

        if ($this->isEmailDuplicated($data['email'])) {
            $this->errors[] = 'Este e-mail já está em uso.';
        }

        return empty($this->errors);
    }

    private function isEmailDuplicated($email)
    {
        return $this->users->findUserByEmail($email);
    }

    public function save(Request $request)
    {   
        $data = $request->all();

        if ($this->validate($data)) {
            $name = $data['name'];
            $email = $data['email'];
            $age = $data['age'];

            $insertSuccess = $this->users->insertUser($name, $email, $age);

            if ($insertSuccess) {
                Router::redirect('/home');
            } else {
                Router::redirect('/home');
            }
        } else {
            Router::redirect('/home');
        }
    }

    public function getAllUsers()
    {
        $users = $this->users->getAllUsers();

        $rows = '';
        foreach ($users as $user) {
            $rows .= "<tr>
                <td class='p-2 whitespace-nowrap'>
                    <div class='flex items-center'>
                        <div class='w-10 h-10 flex-shrink-0 mr-2 sm:mr-3'>
                            <img class='rounded-full' src='https://raw.githubusercontent.com/cruip/vuejs-admin-dashboard-template/main/src/images/user-36-05.jpg' width='40' height='40' alt='Teste'>
                        </div>
                        <div class='font-medium text-gray-800'>{$user['name']}</div>
                    </div>
                </td>
                <td class='p-2 whitespace-nowrap'>
                    <div class='text-left'>{$user['email']}</div>
                </td>
                <td class='p-2 whitespace-nowrap'>
                    <div class='text-lg text-center'>{$user['age']}</div>
                </td>
            </tr>";
        }

        return $rows;
    }

    public function render()
    {
        $users = $this->getAllUsers();
        $errors = $this->errors();

        View::title('Home - Phaesy App')->render('home/index.component.html', ['users' => $users, 'errors' => implode($errors)]);
    }
}