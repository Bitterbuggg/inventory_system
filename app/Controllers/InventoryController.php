<?php

namespace App\Controllers;

use App\Services\InventorySearchService;
use CodeIgniter\Controller;

class InventoryController extends BaseController
{
    private InventorySearchService $searchService;

    public function __construct()
    {
        $this->searchService = new InventorySearchService();
    }

    /**
     * Display inventory table with search and filters
     */
    public function index(): string
    {
        // Get search results through service
        $result = $this->searchService->search(
            $this->request->getVar(),
            20
        );

        return view('inventory/index', array_merge(['title' => 'Inventory Management'], $result));
    }
}
