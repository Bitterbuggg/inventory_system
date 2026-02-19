<?php

namespace App\Services;

use App\Models\ProductModel;
use App\Models\InventoryStockModel;

class InventorySearchService
{
    private ProductModel $productModel;
    private InventoryStockModel $stockModel;
    private const DEFAULT_PER_PAGE = 20;
    private const MAX_SEARCH_LENGTH = 100;
    private const MAX_SKU_LENGTH = 10;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->stockModel = new InventoryStockModel();
    }

    /**
     * Search inventory with filters and pagination
     *
     * @param array $filters Search filters: search, class (dosage_form), sku, page
     * @param int $perPage Items per page
     * @return array Search results with pagination metadata
     */
    public function search(array $filters = [], int $perPage = self::DEFAULT_PER_PAGE): array
    {
        // Validate and sanitize input
        $page = max(1, (int) ($filters['page'] ?? 1));
        $search = substr((string) ($filters['search'] ?? ''), 0, self::MAX_SEARCH_LENGTH);
        $filterClass = (string) ($filters['class'] ?? '');
        $filterSkuPrefix = substr((string) ($filters['sku'] ?? ''), 0, self::MAX_SKU_LENGTH);

        // Build and execute query
        $query = $this->buildQuery($search, $filterSkuPrefix, $filterClass);
        $totalProducts = $query->countAllResults(false);

        // Validate page bounds
        $totalPages = ceil($totalProducts / $perPage);
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }

        // Fetch paginated results
        $offset = ($page - 1) * $perPage;
        $products = $query->orderBy('products.sku', 'ASC')
                         ->limit($perPage, $offset)
                         ->get()
                         ->getResultArray();

        // Enrich with status information
        $products = $this->enrichProductsWithStatus($products);

        // Fetch dosage forms for filter dropdown
        $dosageForms = $this->getDosageForms();

        return [
            'products' => $products,
            'totalProducts' => $totalProducts,
            'currentPage' => (int) $page,
            'perPage' => $perPage,
            'totalPages' => ceil($totalProducts / $perPage),
            'search' => $search,
            'filterClass' => $filterClass,
            'filterSkuPrefix' => $filterSkuPrefix,
            'dosageForms' => $dosageForms,
        ];
    }

    /**
     * Build the inventory query with filters
     *
     * @param string $search Search term
     * @param string $skuPrefix SKU prefix filter
     * @param string $dosageForm Dosage form filter
     * @return object Query builder instance
     */
    private function buildQuery(string $search, string $skuPrefix, string $dosageForm)
    {
        $query = $this->productModel
            ->select('products.*, inventory_stocks.on_hand_qty, inventory_stocks.reserved_qty')
            ->join('inventory_stocks', 'products.id = inventory_stocks.product_id', 'left');

        // Apply search filter
        if (!empty($search)) {
            $query->groupStart()
                  ->like('products.sku', $search)
                  ->orLike('products.brand_name', $search)
                  ->orLike('products.generic_name', $search)
                  ->orLike('products.description', $search)
                  ->groupEnd();
        }

        // Apply SKU prefix filter
        if (!empty($skuPrefix)) {
            $query->like('products.sku', $skuPrefix, 'after');
        }

        // Apply dosage form filter
        if (!empty($dosageForm)) {
            $query->where('products.dosage_form', $dosageForm);
        }

        return $query;
    }

    /**
     * Enrich products with status information
     *
     * @param array $products Raw product data
     * @return array Products with status and color fields
     */
    private function enrichProductsWithStatus(array $products): array
    {
        return array_map(function ($product) {
            $onHand = (int) ($product['on_hand_qty'] ?? 0);
            $reserved = (int) ($product['reserved_qty'] ?? 0);
            $available = max(0, $onHand - $reserved);
            $reorderLevel = (int) ($product['reorder_level'] ?? 0);

            $product['available_qty'] = $available;
            $product['status'] = $this->stockModel->getStockStatus($available, $reorderLevel);
            $product['status_color'] = $this->stockModel->getStatusColor($available, $reorderLevel);

            return $product;
        }, $products);
    }

    /**
     * Get unique dosage forms for filter dropdown
     *
     * @return array Dosage forms
     */
    private function getDosageForms(): array
    {
        return $this->productModel
            ->distinct()
            ->select('dosage_form')
            ->where('dosage_form !=', null)
            ->where('dosage_form !=', '')
            ->orderBy('dosage_form')
            ->get()
            ->getResultArray();
    }
}
