<?php

namespace App\Repositories\Database;

use App\Models\UserModel;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly UserModel $userModel)
    {
    }

    public function create(array $data): int
    {
        $this->userModel->insert($data);

        return (int) $this->userModel->getInsertID();
    }

    public function findByEmail(string $email): ?array
    {
        $user = $this->userModel
            ->where('email', $email)
            ->where('is_active', 1)
            ->first();

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $user = $this->userModel
            ->select('users.*, roles.name AS role_name')
            ->join('roles', 'roles.id = users.role_id')
            ->find($id);

        return $user ?: null;
    }

    public function updateLastLogin(int $id, string $lastLoginAt): bool
    {
        return (bool) $this->userModel->update($id, ['last_login_at' => $lastLoginAt]);
    }
}
