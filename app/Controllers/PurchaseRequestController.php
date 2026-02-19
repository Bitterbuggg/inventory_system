<?php

namespace App\Controllers;

use App\Services\PurchaseWorkflowService;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class PurchaseRequestController extends BaseController
{
    private PurchaseWorkflowService $workflowService;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->workflowService = service('purchaseWorkflowService');
    }

    public function index(): string
    {
        $page = (int) ($this->request->getGet('page_purchase_requests') ?? 1);
        $result = $this->workflowService->paginatePurchaseRequests(max(1, $page), 10);

        return view('purchase_requests/index', [
            'rows' => $result['rows'],
            'pager' => $result['pager'],
        ]);
    }

    public function show(int $id): string
    {
        $record = $this->workflowService->getPurchaseRequest($id);

        if ($record === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Purchase Request not found.');
        }

        return view('purchase_requests/show', ['record' => $record]);
    }

    public function create(): string
    {
        return view('purchase_requests/create');
    }

    public function store(): RedirectResponse
    {
        try {
            $this->workflowService->createPurchaseRequest([
                'requested_by' => (int) $this->request->getPost('requested_by'),
                'remarks' => (string) ($this->request->getPost('remarks') ?? ''),
            ], $this->decodeItems((string) $this->request->getPost('items_json')));

            return redirect()->to('/purchase-requests')->with('success', 'Purchase Request created.');
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function edit(int $id): string
    {
        $record = $this->workflowService->getPurchaseRequest($id);

        if ($record === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Purchase Request not found.');
        }

        return view('purchase_requests/edit', [
            'record' => $record,
            'itemsJson' => json_encode(array_map(static fn (array $item): array => [
                'product_id' => (int) $item['product_id'],
                'requested_qty' => (int) $item['requested_qty'],
                'approved_qty' => $item['approved_qty'] !== null ? (int) $item['approved_qty'] : null,
                'unit_cost_estimate' => (float) $item['unit_cost_estimate'],
                'remarks' => $item['remarks'],
            ], $record['items'] ?? []), JSON_PRETTY_PRINT),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        try {
            $this->workflowService->updatePurchaseRequest($id, [
                'requested_by' => (int) $this->request->getPost('requested_by'),
                'status' => (string) ($this->request->getPost('status') ?? 'draft'),
                'remarks' => (string) ($this->request->getPost('remarks') ?? ''),
            ], $this->decodeItems((string) $this->request->getPost('items_json')));

            return redirect()->to('/purchase-requests/' . $id)->with('success', 'Purchase Request updated.');
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function delete(int $id): RedirectResponse
    {
        try {
            $this->workflowService->deletePurchaseRequest($id);

            return redirect()->to('/purchase-requests')->with('success', 'Purchase Request deleted.');
        } catch (\Throwable $exception) {
            return redirect()->to('/purchase-requests')->with('error', $exception->getMessage());
        }
    }
}
