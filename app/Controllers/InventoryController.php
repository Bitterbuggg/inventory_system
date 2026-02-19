<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\InventoryStockModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class InventoryController extends Controller
{
    protected $productModel;
    protected $stockModel;
    protected $perPage = 20;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->stockModel = new InventoryStockModel();
    }

    /**
     * Display inventory table with search and filters
     */
    public function index(): string
    {
        // Validate and sanitize input parameters
        $page = max(1, (int)($this->request->getVar('page') ?? 1));
        $search = substr((string)($this->request->getVar('search') ?? ''), 0, 100);
        $filterClass = (string)($this->request->getVar('class') ?? '');
        $filterSkuPrefix = substr((string)($this->request->getVar('sku') ?? ''), 0, 10);

        // Build query
        $query = $this->productModel
            ->select('products.*, inventory_stocks.on_hand_qty, inventory_stocks.reserved_qty')
            ->join('inventory_stocks', 'products.id = inventory_stocks.product_id', 'left');

        // Apply filters
        if (!empty($search)) {
            $query->groupStart()
                  ->like('products.sku', $search)
                  ->orLike('products.brand_name', $search)
                  ->orLike('products.generic_name', $search)
                  ->orLike('products.description', $search)
                  ->groupEnd();
        }
                  ->orLike('products.generic_name', $search)
                  ->orLike('products.description', $search);
        }

        if (!empty($filterSkuPrefix)) {
            $query->like('products.sku', $filterSkuPrefix, 'after');
        }

        if (!empty($filterClass)) {
            $query->where('products.dosage_form', $filterClass);
        }

        // Get total count
        $totalProducts = $query->countAllResults(false);

        // Validate pagination
        $totalPages = ceil($totalProducts / $this->perPage);
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }

        // Get paginated results
        $offset = ($page - 1) * $this->perPage;
        $products = $query->orderBy('products.sku', 'ASC')
                         ->limit($this->perPage, $offset)
                         ->get()
                         ->getResultArray();

        // Enrich products with availability status
        foreach ($products as &$product) {
            $onHand = $product['on_hand_qty'] ?? 0;
            $reserved = $product['reserved_qty'] ?? 0;
            $available = max(0, $onHand - $reserved);
            $reorderLevel = $product['reorder_level'] ?? 0;

            $product['available_qty'] = $available;
            $product['status'] = $this->getStockStatus($available, $reorderLevel);
            $product['status_color'] = $this->getStatusColor($available, $reorderLevel);
        }

        // Get unique dosage forms for filter
        $dosageForms = $this->productModel
            ->distinct()
            ->select('dosage_form')
            ->where('dosage_form !=', null)
            ->where('dosage_form !=', '')
            ->orderBy('dosage_form')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Inventory Management',
            'products' => $products,
            'totalProducts' => $totalProducts,
            'currentPage' => (int)$page,
            'perPage' => $this->perPage,
            'totalPages' => ceil($totalProducts / $this->perPage),
            'search' => $search,
            'filterClass' => $filterClass,
            'filterSkuPrefix' => $filterSkuPrefix,
            'dosageForms' => $dosageForms,
        ];

        return view('inventory/index', $data);
    }

    /**
     * Determine stock status based on available quantity and reorder level
     */
    private function getStockStatus(int $available, int $reorderLevel): string
    {
        if ($available == 0) {
            return 'Out of Stock';
        } elseif ($available <= $reorderLevel) {
            return 'Low Stock';
        } elseif ($available <= $reorderLevel * 2) {
            return 'Adequate';
        } else {
            return 'Good Stock';
        }
    }

    /**
     * Get color class for stock status
     */
    private function getStatusColor(int $available, int $reorderLevel): string
    {
        if ($available == 0) {
            return 'bg-red-100 text-red-900';
        } elseif ($available <= $reorderLevel) {
            return 'bg-orange-100 text-orange-900';
        } elseif ($available <= $reorderLevel * 2) {
            return 'bg-yellow-100 text-yellow-900';
        } else {
            return 'bg-green-100 text-green-900';
        }
    }
}
