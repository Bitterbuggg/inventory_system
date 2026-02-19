<?php

namespace App\Controllers;

use App\Services\PurchaseWorkflowService;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class WorkflowController extends BaseController
{
    private PurchaseWorkflowService $workflowService;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->workflowService = service('purchaseWorkflowService');
    }

    public function index(): string
    {
        return view('workflow/index', [
            'user' => session('auth_user'),
        ]);
    }

    public function purchaseRequestForm(): string
    {
        return view('workflow/purchase_request');
    }

    public function createPurchaseRequest(): RedirectResponse
    {
        try {
            $items = $this->decodeItems((string) $this->request->getPost('items_json'));

            $this->workflowService->createPurchaseRequest([
                'requested_by' => (int) $this->request->getPost('requested_by'),
                'remarks' => $this->request->getPost('remarks'),
            ], $items);

            return redirect()->to('/workflow')->with('success', 'Purchase request created successfully.');
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function purchaseOrderForm(): string
    {
        return view('workflow/purchase_order');
    }

    public function createPurchaseOrder(): RedirectResponse
    {
        try {
            $items = $this->decodeItems((string) $this->request->getPost('items_json'));

            $this->workflowService->createPurchaseOrder([
                'supplier_id' => (int) $this->request->getPost('supplier_id'),
                'created_by' => (int) $this->request->getPost('created_by'),
                'order_date' => (string) $this->request->getPost('order_date'),
                'expected_delivery_date' => $this->request->getPost('expected_delivery_date') ?: null,
                'terms' => $this->request->getPost('terms'),
                'notes' => $this->request->getPost('notes'),
                'total_amount' => (float) ($this->request->getPost('total_amount') ?: 0),
            ], $items);

            return redirect()->to('/workflow')->with('success', 'Purchase order created successfully.');
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function receivingForm(): string
    {
        return view('workflow/receiving_convert');
    }

    public function convertReceiving(): RedirectResponse
    {
        try {
            $items = $this->decodeItems((string) $this->request->getPost('items_json'));

            $this->workflowService->convertReceiving([
                'purchase_order_id' => (int) $this->request->getPost('purchase_order_id'),
                'received_by' => (int) $this->request->getPost('received_by'),
                'notes' => $this->request->getPost('notes'),
            ], $items);

            return redirect()->to('/workflow')->with('success', 'Receiving converted and inventory updated.');
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function issuanceForm(): string
    {
        return view('workflow/issuance');
    }

    public function createIssuance(): RedirectResponse
    {
        try {
            $items = $this->decodeItems((string) $this->request->getPost('items_json'));

            $this->workflowService->issueInventory([
                'issued_by' => (int) $this->request->getPost('issued_by'),
                'issued_to' => (string) $this->request->getPost('issued_to'),
                'notes' => $this->request->getPost('notes'),
            ], $items);

            return redirect()->to('/workflow')->with('success', 'Issuance posted successfully.');
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }

    private function decodeItems(string $itemsJson): array
    {
        $decoded = json_decode($itemsJson, true);

        if (! is_array($decoded) || $decoded === []) {
            throw new \InvalidArgumentException('Items JSON must be a non-empty array.');
        }

        $items = array_values(array_filter($decoded, static fn ($row): bool => is_array($row)));

        if ($items === []) {
            throw new \InvalidArgumentException('Items JSON contains no valid rows.');
        }

        return $items;
    }
}
