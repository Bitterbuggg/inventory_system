<?php

namespace App\Repositories\Contracts;

interface PurchaseWorkflowRepositoryInterface
{
    public function paginatePurchaseRequests(int $page, int $perPage): array;

    public function findPurchaseRequestById(int $purchaseRequestId): ?array;

    public function createPurchaseRequest(array $requestData, array $items): int;

    public function updatePurchaseRequest(int $purchaseRequestId, array $requestData, array $items): bool;

    public function deletePurchaseRequest(int $purchaseRequestId): bool;

    public function updatePurchaseRequestStatus(int $purchaseRequestId, string $status): bool;

    public function recordApproval(array $approvalData): int;

    public function paginatePurchaseOrders(int $page, int $perPage): array;

    public function findPurchaseOrderById(int $purchaseOrderId): ?array;

    public function createPurchaseOrder(array $orderData, array $items): int;

    public function updatePurchaseOrder(int $purchaseOrderId, array $orderData, array $items): bool;

    public function deletePurchaseOrder(int $purchaseOrderId): bool;

    public function createPoRequest(array $poRequestData): int;

    public function createReceiving(array $receivingData, array $items): int;

    public function createIssuance(array $issuanceData, array $items): int;
}
