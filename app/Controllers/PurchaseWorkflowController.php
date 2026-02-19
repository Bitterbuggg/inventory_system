<?php

namespace App\Controllers;

use App\Services\PurchaseWorkflowService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class PurchaseWorkflowController extends BaseController
{
    private PurchaseWorkflowService $workflowService;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->workflowService = service('purchaseWorkflowService');
    }

    public function createPurchaseRequest(): ResponseInterface
    {
        return $this->execute(function (array $payload): array {
            $rules = [
                'requested_by' => 'required|integer',
                'items' => 'required',
            ];

            if (! $this->validateData($payload, $rules)) {
                return [
                    'status' => 422,
                    'data' => ['errors' => $this->validator->getErrors()],
                ];
            }

            $id = $this->workflowService->createPurchaseRequest([
                'request_no' => $payload['request_no'] ?? null,
                'requested_by' => (int) $payload['requested_by'],
                'status' => $payload['status'] ?? null,
                'remarks' => $payload['remarks'] ?? null,
            ], $this->normalizeItems($payload['items']));

            return ['status' => 201, 'data' => ['purchase_request_id' => $id]];
        });
    }

    public function approvePurchaseRequest(int $purchaseRequestId): ResponseInterface
    {
        return $this->execute(function (array $payload) use ($purchaseRequestId): array {
            $rules = [
                'approver_id' => 'required|integer',
            ];

            if (! $this->validateData($payload, $rules)) {
                return [
                    'status' => 422,
                    'data' => ['errors' => $this->validator->getErrors()],
                ];
            }

            $id = $this->workflowService->approvePurchaseRequest(
                $purchaseRequestId,
                (int) $payload['approver_id'],
                (string) ($payload['remarks'] ?? ''),
            );

            return ['status' => 200, 'data' => ['approval_id' => $id]];
        });
    }

    public function createPurchaseOrder(): ResponseInterface
    {
        return $this->execute(function (array $payload): array {
            $rules = [
                'supplier_id' => 'required|integer',
                'created_by' => 'required|integer',
                'order_date' => 'required|valid_date[Y-m-d]',
                'items' => 'required',
            ];

            if (! $this->validateData($payload, $rules)) {
                return [
                    'status' => 422,
                    'data' => ['errors' => $this->validator->getErrors()],
                ];
            }

            $id = $this->workflowService->createPurchaseOrder([
                'po_no' => $payload['po_no'] ?? null,
                'supplier_id' => (int) $payload['supplier_id'],
                'created_by' => (int) $payload['created_by'],
                'approved_by' => $payload['approved_by'] ?? null,
                'status' => $payload['status'] ?? null,
                'order_date' => (string) $payload['order_date'],
                'expected_delivery_date' => $payload['expected_delivery_date'] ?? null,
                'total_amount' => $payload['total_amount'] ?? 0,
                'terms' => $payload['terms'] ?? null,
                'notes' => $payload['notes'] ?? null,
            ], $this->normalizeItems($payload['items']));

            return ['status' => 201, 'data' => ['purchase_order_id' => $id]];
        });
    }

    public function createPoRequest(): ResponseInterface
    {
        return $this->execute(function (array $payload): array {
            $rules = [
                'requested_by' => 'required|integer',
            ];

            if (! $this->validateData($payload, $rules)) {
                return [
                    'status' => 422,
                    'data' => ['errors' => $this->validator->getErrors()],
                ];
            }

            $id = $this->workflowService->createPoRequest([
                'request_no' => $payload['request_no'] ?? null,
                'purchase_order_id' => $payload['purchase_order_id'] ?? null,
                'requested_by' => (int) $payload['requested_by'],
                'status' => $payload['status'] ?? null,
                'remarks' => $payload['remarks'] ?? null,
            ]);

            return ['status' => 201, 'data' => ['po_request_id' => $id]];
        });
    }

    public function convertReceiving(): ResponseInterface
    {
        return $this->execute(function (array $payload): array {
            $rules = [
                'purchase_order_id' => 'required|integer',
                'received_by' => 'required|integer',
                'items' => 'required',
            ];

            if (! $this->validateData($payload, $rules)) {
                return [
                    'status' => 422,
                    'data' => ['errors' => $this->validator->getErrors()],
                ];
            }

            $id = $this->workflowService->convertReceiving([
                'receiving_no' => $payload['receiving_no'] ?? null,
                'purchase_order_id' => (int) $payload['purchase_order_id'],
                'received_by' => (int) $payload['received_by'],
                'status' => $payload['status'] ?? null,
                'notes' => $payload['notes'] ?? null,
            ], $this->normalizeItems($payload['items']));

            return ['status' => 201, 'data' => ['receiving_id' => $id]];
        });
    }

    public function issueInventory(): ResponseInterface
    {
        return $this->execute(function (array $payload): array {
            $rules = [
                'issued_by' => 'required|integer',
                'issued_to' => 'required|max_length[120]',
                'items' => 'required',
            ];

            if (! $this->validateData($payload, $rules)) {
                return [
                    'status' => 422,
                    'data' => ['errors' => $this->validator->getErrors()],
                ];
            }

            $id = $this->workflowService->issueInventory([
                'issuance_no' => $payload['issuance_no'] ?? null,
                'issued_by' => (int) $payload['issued_by'],
                'issued_to' => (string) $payload['issued_to'],
                'status' => $payload['status'] ?? null,
                'notes' => $payload['notes'] ?? null,
            ], $this->normalizeItems($payload['items']));

            return ['status' => 201, 'data' => ['issuance_id' => $id]];
        });
    }

    private function execute(callable $handler): ResponseInterface
    {
        try {
            $payload = $this->request->getJSON(true);
            if (! is_array($payload)) {
                $payload = $this->request->getPost();
            }

            $result = $handler($payload);

            return $this->response->setStatusCode($result['status'])->setJSON($result['data']);
        } catch (\Throwable $exception) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['error' => $exception->getMessage()]);
        }
    }

    private function normalizeItems(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return array_values(array_filter($items, static fn ($item): bool => is_array($item)));
    }
}
