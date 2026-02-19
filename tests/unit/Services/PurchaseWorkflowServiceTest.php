<?php

use App\Repositories\Contracts\InventoryRepositoryInterface;
use App\Repositories\Contracts\PurchaseWorkflowRepositoryInterface;
use App\Services\PurchaseWorkflowService;
use CodeIgniter\Test\CIUnitTestCase;

final class PurchaseWorkflowServiceTest extends CIUnitTestCase
{
    public function testCreatePurchaseRequestAddsDefaultReferenceAndStatus(): void
    {
        $workflowRepo = new FakeWorkflowRepository();
        $inventoryRepo = new FakeInventoryRepository();
        $service = new PurchaseWorkflowService($workflowRepo, $inventoryRepo);

        $id = $service->createPurchaseRequest([
            'requested_by' => 2,
        ], [
            ['product_id' => 10, 'requested_qty' => 5],
        ]);

        $this->assertGreaterThan(0, $id);
        $this->assertNotEmpty($workflowRepo->lastPurchaseRequestData['request_no']);
        $this->assertSame('draft', $workflowRepo->lastPurchaseRequestData['status']);
    }

    public function testConvertReceivingIncreasesInventoryPerItem(): void
    {
        $workflowRepo = new FakeWorkflowRepository();
        $inventoryRepo = new FakeInventoryRepository();
        $service = new PurchaseWorkflowService($workflowRepo, $inventoryRepo);

        $service->convertReceiving([
            'purchase_order_id' => 11,
            'received_by' => 3,
        ], [
            ['product_id' => 1, 'received_qty' => 4],
            ['product_id' => 2, 'received_qty' => 7],
        ]);

        $this->assertCount(2, $inventoryRepo->increaseCalls);
        $this->assertSame(1, $inventoryRepo->increaseCalls[0]['product_id']);
        $this->assertSame(7, $inventoryRepo->increaseCalls[1]['quantity']);
    }

    public function testIssueInventoryDecreasesInventoryPerItem(): void
    {
        $workflowRepo = new FakeWorkflowRepository();
        $inventoryRepo = new FakeInventoryRepository();
        $service = new PurchaseWorkflowService($workflowRepo, $inventoryRepo);

        $service->issueInventory([
            'issued_by' => 5,
            'issued_to' => 'Main Pharmacy Counter',
        ], [
            ['product_id' => 8, 'quantity' => 2],
            ['product_id' => 9, 'quantity' => 3],
        ]);

        $this->assertCount(2, $inventoryRepo->decreaseCalls);
        $this->assertSame(8, $inventoryRepo->decreaseCalls[0]['product_id']);
        $this->assertSame(3, $inventoryRepo->decreaseCalls[1]['quantity']);
    }
}

class FakeWorkflowRepository implements PurchaseWorkflowRepositoryInterface
{
    public array $lastPurchaseRequestData = [];

    public function createPurchaseRequest(array $requestData, array $items): int
    {
        $this->lastPurchaseRequestData = $requestData;

        return 101;
    }

    public function updatePurchaseRequestStatus(int $purchaseRequestId, string $status): bool
    {
        return true;
    }

    public function recordApproval(array $approvalData): int
    {
        return 201;
    }

    public function createPurchaseOrder(array $orderData, array $items): int
    {
        return 301;
    }

    public function createPoRequest(array $poRequestData): int
    {
        return 401;
    }

    public function createReceiving(array $receivingData, array $items): int
    {
        return 501;
    }

    public function createIssuance(array $issuanceData, array $items): int
    {
        return 601;
    }
}

class FakeInventoryRepository implements InventoryRepositoryInterface
{
    public array $increaseCalls = [];
    public array $decreaseCalls = [];

    public function increaseStock(
        int $productId,
        int $quantity,
        string $referenceType,
        int $referenceId,
        int $actorId,
        ?string $remarks = null
    ): bool {
        $this->increaseCalls[] = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'actor_id' => $actorId,
            'remarks' => $remarks,
        ];

        return true;
    }

    public function decreaseStock(
        int $productId,
        int $quantity,
        string $referenceType,
        int $referenceId,
        int $actorId,
        ?string $remarks = null
    ): bool {
        $this->decreaseCalls[] = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'actor_id' => $actorId,
            'remarks' => $remarks,
        ];

        return true;
    }

    public function getStock(int $productId): int
    {
        return 0;
    }
}
