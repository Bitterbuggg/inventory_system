<?php

namespace App\Services;

use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use InvalidArgumentException;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleRepositoryInterface $roleRepository,
    ) {
    }

    public function register(array $input): int
    {
        $role = $this->roleRepository->findByName($input['role_name']);

        if ($role === null) {
            throw new InvalidArgumentException('Invalid role selected.');
        }

        $existing = $this->userRepository->findByEmail($input['email']);

        if ($existing !== null) {
            throw new InvalidArgumentException('Email is already registered.');
        }

        return $this->userRepository->create([
            'role_id'       => $role['id'],
            'full_name'     => $input['full_name'],
            'email'         => strtolower(trim($input['email'])),
            'password_hash' => password_hash($input['password'], PASSWORD_DEFAULT),
            'is_active'     => 1,
        ]);
    }

    public function login(string $email, string $password): ?array
    {
        $user = $this->userRepository->findByEmail(strtolower(trim($email)));

        if ($user === null || ! password_verify($password, $user['password_hash'])) {
            return null;
        }

        $this->userRepository->updateLastLogin((int) $user['id'], date('Y-m-d H:i:s'));

        return $this->userRepository->findById((int) $user['id']);
    }
}
