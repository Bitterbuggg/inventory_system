<?php

namespace App\Repositories\Database;

use App\Models\ApprovalModel;
use App\Models\IssuanceItemModel;
use App\Models\IssuanceModel;
use App\Models\PoRequestModel;
use App\Models\PurchaseOrderItemModel;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseRequestItemModel;
use App\Models\PurchaseRequestModel;
use App\Models\ReceivingItemModel;
use App\Models\ReceivingModel;
use App\Repositories\Contracts\PurchaseWorkflowRepositoryInterface;
use CodeIgniter\Database\BaseConnection;

class PurchaseWorkflowRepository implements PurchaseWorkflowRepositoryInterface
{
    public function __construct(
        private readonly BaseConnection $db,
        private readonly PurchaseRequestModel $purchaseRequestModel,
        private readonly PurchaseRequestItemModel $purchaseRequestItemModel,
        private readonly ApprovalModel $approvalModel,
        private readonly PurchaseOrderModel $purchaseOrderModel,
        private readonly PurchaseOrderItemModel $purchaseOrderItemModel,
        private readonly PoRequestModel $poRequestModel,
        private readonly ReceivingModel $receivingModel,
        private readonly ReceivingItemModel $receivingItemModel,
        private readonly IssuanceModel $issuanceModel,
        private readonly IssuanceItemModel $issuanceItemModel,
    ) {
    }

    public function createPurchaseRequest(array $requestData, array $items): int
    {
        $this->db->transBegin();
        $this->purchaseRequestModel->insert($requestData);
        $purchaseRequestId = (int) $this->purchaseRequestModel->getInsertID();

        $rowItems = array_map(static fn (array $item): array => [
            'purchase_request_id' => $purchaseRequestId,
            'product_id'          => $item['product_id'],
            'requested_qty'       => $item['requested_qty'],
            'approved_qty'        => $item['approved_qty'] ?? null,
            'unit_cost_estimate'  => $item['unit_cost_estimate'] ?? 0,
            'remarks'             => $item['remarks'] ?? null,
        ], $items);

        if ($rowItems !== []) {
            $this->purchaseRequestItemModel->insertBatch($rowItems);
        }

        $this->db->transCommit();

        return $purchaseRequestId;
    }

    public function updatePurchaseRequestStatus(int $purchaseRequestId, string $status): bool
    {
        return (bool) $this->purchaseRequestModel->update($purchaseRequestId, ['status' => $status]);
    }

    public function recordApproval(array $approvalData): int
    {
        $this->approvalModel->insert($approvalData);

        return (int) $this->approvalModel->getInsertID();
    }

    public function createPurchaseOrder(array $orderData, array $items): int
    {
        $this->db->transBegin();
        $this->purchaseOrderModel->insert($orderData);
        $purchaseOrderId = (int) $this->purchaseOrderModel->getInsertID();

        $rowItems = array_map(static fn (array $item): array => [
            'purchase_order_id' => $purchaseOrderId,
            'product_id'        => $item['product_id'],
            'quantity'          => $item['quantity'],
            'received_qty'      => $item['received_qty'] ?? 0,
            'unit_cost'         => $item['unit_cost'],
            'line_total'        => $item['line_total'],
        ], $items);

        if ($rowItems !== []) {
            $this->purchaseOrderItemModel->insertBatch($rowItems);
        }

        $this->db->transCommit();

        return $purchaseOrderId;
    }

    public function createPoRequest(array $poRequestData): int
    {
        $this->poRequestModel->insert($poRequestData);

        return (int) $this->poRequestModel->getInsertID();
    }

    public function createReceiving(array $receivingData, array $items): int
    {
        $this->db->transBegin();
        $this->receivingModel->insert($receivingData);
        $receivingId = (int) $this->receivingModel->getInsertID();

        $rowItems = array_map(static fn (array $item): array => [
            'receiving_id'  => $receivingId,
            'product_id'    => $item['product_id'],
            'received_qty'  => $item['received_qty'],
            'batch_no'      => $item['batch_no'] ?? null,
            'expiry_date'   => $item['expiry_date'] ?? null,
            'unit_cost'     => $item['unit_cost'] ?? 0,
            'created_at'    => date('Y-m-d H:i:s'),
        ], $items);

        if ($rowItems !== []) {
            $this->receivingItemModel->insertBatch($rowItems);
        }

        $this->db->transCommit();

        return $receivingId;
    }

    public function createIssuance(array $issuanceData, array $items): int
    {
        $this->db->transBegin();
        $this->issuanceModel->insert($issuanceData);
        $issuanceId = (int) $this->issuanceModel->getInsertID();

        $rowItems = array_map(static fn (array $item): array => [
            'issuance_id' => $issuanceId,
            'product_id'  => $item['product_id'],
            'quantity'    => $item['quantity'],
            'created_at'  => date('Y-m-d H:i:s'),
        ], $items);

        if ($rowItems !== []) {
            $this->issuanceItemModel->insertBatch($rowItems);
        }

        $this->db->transCommit();

        return $issuanceId;
    }
}
