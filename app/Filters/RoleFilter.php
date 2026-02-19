<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = session('auth_user');

        if ($user === null) {
            return redirect()->to('/login')->with('error', 'Please login to continue.');
        }

        if ($arguments !== null && ! in_array($user['role_name'], $arguments, true)) {
            return redirect()->to('/dashboard')->with('error', 'You are not authorized for this section.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
