<?php
namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    public function register()
    {
        return view('register');
    }

    public function registerSubmit()
    {
        $session = session();
        $model = new UserModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ];
        $model->save($data);
        $session->setFlashdata('msg', 'Registered successfully!');
        return redirect()->to('/login');
    }

    public function login()
    {
        return view('login');
    }

    public function loginSubmit()
    {
        $session = session();
        $model = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = $model->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session->set('user', $user);
            return redirect()->to('/');
        } else {
            $session->setFlashdata('msg', 'Invalid login credentials.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
