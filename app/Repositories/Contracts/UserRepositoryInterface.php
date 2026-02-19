<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function create(array $data): int;

    public function findByEmail(string $email): ?array;

    public function findById(int $id): ?array;

    public function updateLastLogin(int $id, string $lastLoginAt): bool;
}
