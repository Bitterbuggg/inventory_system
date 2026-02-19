<?php

namespace App\Repositories\Contracts;

interface PurchaseWorkflowRepositoryInterface
{
    public function createPurchaseRequest(array $requestData, array $items): int;

    public function updatePurchaseRequestStatus(int $purchaseRequestId, string $status): bool;

    public function recordApproval(array $approvalData): int;

    public function createPurchaseOrder(array $orderData, array $items): int;

    public function createPoRequest(array $poRequestData): int;

    public function createReceiving(array $receivingData, array $items): int;

    public function createIssuance(array $issuanceData, array $items): int;
}
