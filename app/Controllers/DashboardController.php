<?php

namespace App\Controllers;

use App\Services\InventorySearchService;

class DashboardController extends BaseController
{
    private InventorySearchService $searchService;

    public function __construct()
    {
        $this->searchService = new InventorySearchService();
    }

    public function index(): string
    {
        // Get inventory search results (same as InventoryController)

        $result = $this->searchService->search(
            request()->getVar(),
            20
        );

        return view('dashboard/index', array_merge(['title' => 'Inventory Management'], $result));
    }
}
