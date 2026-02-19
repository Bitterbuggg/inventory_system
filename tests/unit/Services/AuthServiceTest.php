<?php

use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\AuthService;
use CodeIgniter\Test\CIUnitTestCase;

final class AuthServiceTest extends CIUnitTestCase
{
    public function testRegisterCreatesUser(): void
    {
        $service = new AuthService(new FakeUserRepository(), new FakeRoleRepository());

        $userId = $service->register([
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'StrongPass123',
            'role_name' => 'Employee',
        ]);

        $this->assertGreaterThan(0, $userId);
    }

    public function testLoginReturnsNullForWrongPassword(): void
    {
        $service = new AuthService(new FakeUserRepository(), new FakeRoleRepository());

        $result = $service->login('existing@example.com', 'wrong-password');

        $this->assertNull($result);
    }

    public function testLoginReturnsUserForValidCredentials(): void
    {
        $service = new AuthService(new FakeUserRepository(), new FakeRoleRepository());

        $result = $service->login('existing@example.com', 'Password@123');

        $this->assertNotNull($result);
        $this->assertSame('Employee', $result['role_name']);
    }
}

class FakeUserRepository implements UserRepositoryInterface
{
    private array $users = [
        [
            'id' => 1,
            'role_id' => 2,
            'full_name' => 'Existing User',
            'email' => 'existing@example.com',
            'password_hash' => '',
            'is_active' => 1,
        ],
    ];

    public function __construct()
    {
        $this->users[0]['password_hash'] = password_hash('Password@123', PASSWORD_DEFAULT);
    }

    public function create(array $data): int
    {
        $newId = count($this->users) + 1;
        $data['id'] = $newId;
        $this->users[] = $data;

        return $newId;
    }

    public function findByEmail(string $email): ?array
    {
        foreach ($this->users as $user) {
            if ($user['email'] === $email && $user['is_active'] === 1) {
                return $user;
            }
        }

        return null;
    }

    public function findById(int $id): ?array
    {
        foreach ($this->users as $user) {
            if ($user['id'] === $id) {
                $user['role_name'] = 'Employee';

                return $user;
            }
        }

        return null;
    }

    public function updateLastLogin(int $id, string $lastLoginAt): bool
    {
        return true;
    }
}

class FakeRoleRepository implements RoleRepositoryInterface
{
    public function findByName(string $name): ?array
    {
        $roles = [
            'Admin' => ['id' => 1, 'name' => 'Admin'],
            'Employee' => ['id' => 2, 'name' => 'Employee'],
            'IT Dev/Staff' => ['id' => 3, 'name' => 'IT Dev/Staff'],
        ];

        return $roles[$name] ?? null;
    }

    public function all(): array
    {
        return [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Employee'],
            ['id' => 3, 'name' => 'IT Dev/Staff'],
        ];
    }
}
