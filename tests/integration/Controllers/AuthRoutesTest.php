<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class AuthRoutesTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testLoginPageLoads(): void
    {
        $result = $this->get('/login');

        $result->assertStatus(200);
        $result->assertSee('Login');
    }

    public function testSignupPageLoads(): void
    {
        $result = $this->get('/signup');

        $result->assertStatus(200);
        $result->assertSee('Signup');
    }

    public function testDashboardRequiresAuthentication(): void
    {
        $result = $this->get('/dashboard');

        $result->assertRedirectTo('/login');
    }
}
