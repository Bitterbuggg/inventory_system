<?php

namespace App\Controllers;

use App\Services\AuthService;
use CodeIgniter\HTTP\RedirectResponse;
use Psr\Log\LoggerInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    private AuthService $authService;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->authService = service('authService');
    }

    public function loginForm(): string
    {
        return view('auth/login');
    }

    public function signupForm(): string
    {
        $roles = [];

        try {
            $roles = service('roleRepository')->all();
        } catch (\Throwable) {
            $roles = [
                ['name' => 'Admin'],
                ['name' => 'Employee'],
                ['name' => 'IT Dev/Staff'],
            ];
        }

        return view('auth/signup', [
            'roles' => $roles,
        ]);
    }

    public function login(): RedirectResponse
    {
        $credentials = $this->request->getPost(['email', 'password']);

        $user = $this->authService->login($credentials['email'] ?? '', $credentials['password'] ?? '');

        if ($user === null) {
            return redirect()->back()->withInput()->with('error', 'Invalid credentials.');
        }

        session()->set('auth_user', [
            'id'        => $user['id'],
            'full_name' => $user['full_name'],
            'email'     => $user['email'],
            'role_name' => $user['role_name'],
        ]);

        return redirect()->to('/dashboard');
    }

    public function signup(): RedirectResponse
    {
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[120]',
            'email' => 'required|valid_email|max_length[120]',
            'password' => 'required|min_length[8]|max_length[255]',
            'password_confirm' => 'required|matches[password]',
            'role_name' => 'required|max_length[40]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->authService->register([
                'full_name' => (string) $this->request->getPost('full_name'),
                'email' => (string) $this->request->getPost('email'),
                'password' => (string) $this->request->getPost('password'),
                'role_name' => (string) $this->request->getPost('role_name'),
            ]);
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()->to('/login')->with('success', 'Account created successfully.');
    }

    public function logout(): RedirectResponse
    {
        session()->remove('auth_user');
        session()->destroy();

        return redirect()->to('/login');
    }
}
