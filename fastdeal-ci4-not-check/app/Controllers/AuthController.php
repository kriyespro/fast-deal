<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        // Redirect if already logged in
        if (session()->get('isLoggedIn')) {
            return redirect()->to(session()->get('role') === 'admin' ? '/admin' : '/customer');
        }

        return view('auth/login');
    }

    public function loginAttempt()
    {
        $session = session();
        $throttler = \Config\Services::throttler();
        $ip = $this->request->getIPAddress();
        $throttler_key = "login_" . md5($ip);

        // Allow 5 attempts per 10 minutes
        if ($throttler->check($throttler_key, 5, 600) === false) {
            $session->setFlashdata('error', 'Too many login attempts. Please wait 10 minutes.');
            return redirect()->to('/login');
        }

        $model = new UserModel();

        $email_input = $this->request->getVar('email');
        $password_input = $this->request->getVar('password');

        $user = $model->where('email', $email_input)->first();

        if ($user) {
            $pass = $user['password'];
            $authenticatePassword = password_verify($password_input, $pass);

            if ($authenticatePassword) {
                // Set session data
                $ses_data = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'isLoggedIn' => true
                ];

                $session->set($ses_data);

                return redirect()->to($user['role'] === 'admin' ? '/admin' : '/customer');
            } else {
                $session->setFlashdata('error', 'Wrong Password');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Email not Found');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }
}
