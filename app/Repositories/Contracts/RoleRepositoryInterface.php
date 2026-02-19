<?php

namespace App\Repositories\Contracts;

interface RoleRepositoryInterface
{
    public function findByName(string $name): ?array;

    public function all(): array;
}
