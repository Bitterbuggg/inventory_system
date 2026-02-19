<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class PurchaseCrudRoutesTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testPurchaseRequestListRequiresAuthentication(): void
    {
        $result = $this->get('/purchase-requests');

        $result->assertRedirectTo('/login');
    }

    public function testPurchaseOrderListRequiresAuthentication(): void
    {
        $result = $this->get('/purchase-orders');

        $result->assertRedirectTo('/login');
    }

    public function testPurchaseListsBlockUnauthorizedRole(): void
    {
        $session = [
            'auth_user' => [
                'id' => 21,
                'role_name' => 'IT Dev/Staff',
            ],
        ];

        $requestResult = $this->withSession($session)->get('/purchase-requests');
        $orderResult = $this->withSession($session)->get('/purchase-orders');

        $requestResult->assertRedirectTo('/dashboard');
        $orderResult->assertRedirectTo('/dashboard');
    }

    public function testPurchaseCreatePagesLoadForEmployee(): void
    {
        $session = [
            'auth_user' => [
                'id' => 22,
                'full_name' => 'Employee Test',
                'role_name' => 'Employee',
            ],
        ];

        $requestResult = $this->withSession($session)->get('/purchase-requests/create');
        $orderResult = $this->withSession($session)->get('/purchase-orders/create');

        $requestResult->assertStatus(200);
        $requestResult->assertSee('Create Purchase Request');

        $orderResult->assertStatus(200);
        $orderResult->assertSee('Create Purchase Order');
    }
}
