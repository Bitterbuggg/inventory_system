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
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_DURATION = 900; // 15 minutes in seconds

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
        // Rate limiting: Check if IP is locked out
        $clientIp = $this->request->getIPAddress();
        $lockoutKey = 'login_lockout_' . $clientIp;
        $attemptsKey = 'login_attempts_' . $clientIp;

        if (cache($lockoutKey)) {
            return redirect()->back()
                ->with('error', '❌ Too many login attempts. Please try again in 15 minutes.');
        }

        $credentials = $this->request->getPost(['email', 'password']);

        $user = $this->authService->login($credentials['email'] ?? '', $credentials['password'] ?? '');

        if ($user === null) {
            // Increment failed attempts
            $attempts = (int)(cache($attemptsKey) ?? 0) + 1;

            if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
                // Lock out this IP
                cache()->save($lockoutKey, true, self::LOCKOUT_DURATION);
                cache()->delete($attemptsKey);
                log_message('warning', "⚠️ Login lockout triggered for IP: {$clientIp} after {$attempts} failed attempts");
                return redirect()->back()
                    ->with('error', '❌ Too many failed login attempts. Account locked for 15 minutes.');
            }

            cache()->save($attemptsKey, $attempts, self::LOCKOUT_DURATION);
            log_message('notice', "📍 Login attempt {$attempts} failed for IP: {$clientIp}");
            return redirect()->back()->withInput()->with('error', "❌ Invalid credentials. ({$attempts}/" . self::MAX_LOGIN_ATTEMPTS . " attempts)");
        }

        // Clear attempts on successful login
        cache()->delete($attemptsKey);
        cache()->delete($lockoutKey);

        session()->set('auth_user', [
            'id'        => $user['id'],
            'full_name' => $user['full_name'],
            'email'     => $user['email'],
            'role_name' => $user['role_name'],
        ]);

        log_message('info', "✅ User logged in: {$user['email']} from IP: {$clientIp}");
        return redirect()->to('/dashboard');
    }

    public function signup(): RedirectResponse
    {
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[120]',
            'email' => 'required|valid_email|max_length[120]',
            'password' => 'required|min_length[12]|max_length[255]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/] ',
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
