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

    public function paginatePurchaseRequests(int $page = 1, int $perPage = 10): array
    {
        return $this->workflowRepository->paginatePurchaseRequests($page, $perPage);
    }

    public function getPurchaseRequest(int $purchaseRequestId): ?array
    {
        return $this->workflowRepository->findPurchaseRequestById($purchaseRequestId);
    }

    public function createPurchaseRequest(array $requestData, array $items): int
    {
        $requestData['request_no'] = $requestData['request_no'] ?? $this->generateReference('PR');
        $requestData['status'] = $requestData['status'] ?? 'draft';
        $requestData['requested_at'] = $requestData['requested_at'] ?? date('Y-m-d H:i:s');

        return $this->workflowRepository->createPurchaseRequest($requestData, $items);
    }

    public function updatePurchaseRequest(int $purchaseRequestId, array $requestData, array $items): bool
    {
        $requestData['requested_at'] = $requestData['requested_at'] ?? date('Y-m-d H:i:s');

        return $this->workflowRepository->updatePurchaseRequest($purchaseRequestId, $requestData, $items);
    }

    public function deletePurchaseRequest(int $purchaseRequestId): bool
    {
        return $this->workflowRepository->deletePurchaseRequest($purchaseRequestId);
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
        $orderData['po_no'] = $orderData['po_no'] ?? $this->generateReference('PO');
        $orderData['status'] = $orderData['status'] ?? 'draft';

        return $this->workflowRepository->createPurchaseOrder($orderData, $items);
    }

    public function paginatePurchaseOrders(int $page = 1, int $perPage = 10): array
    {
        return $this->workflowRepository->paginatePurchaseOrders($page, $perPage);
    }

    public function getPurchaseOrder(int $purchaseOrderId): ?array
    {
        return $this->workflowRepository->findPurchaseOrderById($purchaseOrderId);
    }

    public function updatePurchaseOrder(int $purchaseOrderId, array $orderData, array $items): bool
    {
        $orderData['status'] = $orderData['status'] ?? 'draft';

        return $this->workflowRepository->updatePurchaseOrder($purchaseOrderId, $orderData, $items);
    }

    public function deletePurchaseOrder(int $purchaseOrderId): bool
    {
        return $this->workflowRepository->deletePurchaseOrder($purchaseOrderId);
    }

    public function createPoRequest(array $poRequestData): int
    {
        $poRequestData['request_no'] = $poRequestData['request_no'] ?? $this->generateReference('POR');
        $poRequestData['status'] = $poRequestData['status'] ?? 'pending';
        $poRequestData['requested_at'] = $poRequestData['requested_at'] ?? date('Y-m-d H:i:s');

        return $this->workflowRepository->createPoRequest($poRequestData);
    }

    public function convertReceiving(array $receivingData, array $receivingItems): int
    {
        $receivingData['receiving_no'] = $receivingData['receiving_no'] ?? $this->generateReference('RCV');
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
        $issuanceData['issuance_no'] = $issuanceData['issuance_no'] ?? $this->generateReference('ISS');
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

    private function generateReference(string $prefix): string
    {
        return sprintf('%s-%s-%04d', $prefix, date('YmdHis'), random_int(1, 9999));
    }
}
