<?php

namespace App\Repositories\Database;

use App\Models\RoleModel;
use App\Repositories\Contracts\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function __construct(private readonly RoleModel $roleModel)
    {
    }

    public function findByName(string $name): ?array
    {
        $role = $this->roleModel->where('name', $name)->first();

        return $role ?: null;
    }

    public function all(): array
    {
        return $this->roleModel->orderBy('name', 'ASC')->findAll();
    }
}
