<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class WorkflowRoutesTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testWorkflowRequiresAuthentication(): void
    {
        $result = $this->get('/workflow');

        $result->assertRedirectTo('/login');
    }

    public function testWorkflowBlocksUnauthorizedRole(): void
    {
        $result = $this->withSession([
            'auth_user' => [
                'id' => 11,
                'role_name' => 'IT Dev/Staff',
            ],
        ])->get('/workflow');

        $result->assertRedirectTo('/dashboard');
    }

    public function testWorkflowLoadsForEmployee(): void
    {
        $result = $this->withSession([
            'auth_user' => [
                'id' => 12,
                'full_name' => 'Employee Test',
                'role_name' => 'Employee',
            ],
        ])->get('/workflow');

        $result->assertStatus(200);
        $result->assertSee('Workflow Operations');
    }
}
