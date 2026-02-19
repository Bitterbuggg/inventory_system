<?php

namespace App\Controllers;

use App\Services\PurchaseWorkflowService;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class PurchaseOrderController extends BaseController
{
    private PurchaseWorkflowService $workflowService;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->workflowService = service('purchaseWorkflowService');
    }

    public function index(): string
    {
        $page = (int) ($this->request->getGet('page_purchase_orders') ?? 1);
        $result = $this->workflowService->paginatePurchaseOrders(max(1, $page), 10);

        return view('purchase_orders/index', [
            'rows' => $result['rows'],
            'pager' => $result['pager'],
        ]);
    }

    public function show(int $id): string
    {
        $record = $this->workflowService->getPurchaseOrder($id);

        if ($record === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Purchase Order not found.');
        }

        return view('purchase_orders/show', ['record' => $record]);
    }

    public function create(): string
    {
        return view('purchase_orders/create');
    }

    public function store(): RedirectResponse
    {
        try {
            $this->workflowService->createPurchaseOrder([
                'supplier_id' => (int) $this->request->getPost('supplier_id'),
                'created_by' => (int) $this->request->getPost('created_by'),
                'order_date' => (string) $this->request->getPost('order_date'),
                'expected_delivery_date' => $this->request->getPost('expected_delivery_date') ?: null,
                'total_amount' => (float) ($this->request->getPost('total_amount') ?: 0),
                'terms' => (string) ($this->request->getPost('terms') ?? ''),
                'notes' => (string) ($this->request->getPost('notes') ?? ''),
            ], $this->decodeItems((string) $this->request->getPost('items_json')));

            return redirect()->to('/purchase-orders')->with('success', 'Purchase Order created.');
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function edit(int $id): string
    {
        $record = $this->workflowService->getPurchaseOrder($id);

        if ($record === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Purchase Order not found.');
        }

        return view('purchase_orders/edit', [
            'record' => $record,
            'itemsJson' => json_encode(array_map(static fn (array $item): array => [
                'product_id' => (int) $item['product_id'],
                'quantity' => (int) $item['quantity'],
                'received_qty' => (int) ($item['received_qty'] ?? 0),
                'unit_cost' => (float) $item['unit_cost'],
                'line_total' => (float) $item['line_total'],
            ], $record['items'] ?? []), JSON_PRETTY_PRINT),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        try {
            $this->workflowService->updatePurchaseOrder($id, [
                'supplier_id' => (int) $this->request->getPost('supplier_id'),
                'created_by' => (int) $this->request->getPost('created_by'),
                'status' => (string) ($this->request->getPost('status') ?? 'draft'),
                'order_date' => (string) $this->request->getPost('order_date'),
                'expected_delivery_date' => $this->request->getPost('expected_delivery_date') ?: null,
                'total_amount' => (float) ($this->request->getPost('total_amount') ?: 0),
                'terms' => (string) ($this->request->getPost('terms') ?? ''),
                'notes' => (string) ($this->request->getPost('notes') ?? ''),
            ], $this->decodeItems((string) $this->request->getPost('items_json')));

            return redirect()->to('/purchase-orders/' . $id)->with('success', 'Purchase Order updated.');
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function delete(int $id): RedirectResponse
    {
        try {
            $this->workflowService->deletePurchaseOrder($id);

            return redirect()->to('/purchase-orders')->with('success', 'Purchase Order deleted.');
        } catch (\Throwable $exception) {
            return redirect()->to('/purchase-orders')->with('error', $exception->getMessage());
        }
    }
}
