<?php

namespace App\Services;

use App\Repositories\Contracts\InventoryRepositoryInterface;
use App\Repositories\Contracts\PurchaseWorkflowRepositoryInterface;

class PurchaseWorkflowService
{
    public function __construct(
        private readonly PurchaseWorkflowRepositoryInterface $workflowRepository,
        private readonly InventoryRepositoryInterface $inventoryRepository,
    ) {
    }

    public function createPurchaseRequest(array $requestData, array $items): int
    {
        $requestData['status'] = $requestData['status'] ?? 'draft';
        $requestData['requested_at'] = $requestData['requested_at'] ?? date('Y-m-d H:i:s');

        return $this->workflowRepository->createPurchaseRequest($requestData, $items);
    }

    public function approvePurchaseRequest(int $purchaseRequestId, int $approverId, string $remarks = ''): int
    {
        $this->workflowRepository->updatePurchaseRequestStatus($purchaseRequestId, 'approved');

        return $this->workflowRepository->recordApproval([
            'workflow_type' => 'purchase_request',
            'reference_id'  => $purchaseRequestId,
            'approver_id'   => $approverId,
            'status'        => 'approved',
            'remarks'       => $remarks,
            'acted_at'      => date('Y-m-d H:i:s'),
            'created_at'    => date('Y-m-d H:i:s'),
        ]);
    }

    public function createPurchaseOrder(array $orderData, array $items): int
    {
        $orderData['status'] = $orderData['status'] ?? 'draft';

        return $this->workflowRepository->createPurchaseOrder($orderData, $items);
    }

    public function createPoRequest(array $poRequestData): int
    {
        $poRequestData['status'] = $poRequestData['status'] ?? 'pending';
        $poRequestData['requested_at'] = $poRequestData['requested_at'] ?? date('Y-m-d H:i:s');

        return $this->workflowRepository->createPoRequest($poRequestData);
    }

    public function convertReceiving(array $receivingData, array $receivingItems): int
    {
        $receivingData['status'] = $receivingData['status'] ?? 'posted';
        $receivingData['received_at'] = $receivingData['received_at'] ?? date('Y-m-d H:i:s');

        $receivingId = $this->workflowRepository->createReceiving($receivingData, $receivingItems);

        foreach ($receivingItems as $item) {
            $this->inventoryRepository->increaseStock(
                (int) $item['product_id'],
                (int) $item['received_qty'],
                'receiving',
                $receivingId,
                (int) $receivingData['received_by'],
                'Receiving conversion posted',
            );
        }

        return $receivingId;
    }

    public function issueInventory(array $issuanceData, array $issuanceItems): int
    {
        $issuanceData['status'] = $issuanceData['status'] ?? 'posted';
        $issuanceData['issued_at'] = $issuanceData['issued_at'] ?? date('Y-m-d H:i:s');

        $issuanceId = $this->workflowRepository->createIssuance($issuanceData, $issuanceItems);

        foreach ($issuanceItems as $item) {
            $this->inventoryRepository->decreaseStock(
                (int) $item['product_id'],
                (int) $item['quantity'],
                'issuance',
                $issuanceId,
                (int) $issuanceData['issued_by'],
                'Inventory issuance posted',
            );
        }

        return $issuanceId;
    }
}
