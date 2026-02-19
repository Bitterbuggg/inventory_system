<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryStockModel extends Model
{
    protected $table = 'inventory_stocks';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'product_id',
        'on_hand_qty',
        'reserved_qty',
        'updated_at',
    ];

    /**
     * Determine stock status based on available quantity and reorder level
     *
     * @param int $available Available quantity
     * @param int $reorderLevel Reorder point threshold
     * @return string Stock status label
     */
    public function getStockStatus(int $available, int $reorderLevel): string
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
     * Get Tailwind CSS color class for stock status
     *
     * @param int $available Available quantity
     * @param int $reorderLevel Reorder point threshold
     * @return string Tailwind color class for badge styling
     */
    public function getStatusColor(int $available, int $reorderLevel): string
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

    /**
     * Check if stock is low
     *
     * @param int $productId Product ID
     * @return bool True if stock is at or below reorder level
     */
    public function isLowStock(int $productId): bool
    {
        $stock = $this->where('product_id', $productId)->first();
        if (!$stock) {
            return true;
        }

        $product = model('ProductModel')->find($stock['product_id']);
        $available = max(0, ($stock['on_hand_qty'] ?? 0) - ($stock['reserved_qty'] ?? 0));
        $reorderLevel = (int) ($product['reorder_level'] ?? 0);

        return $available <= $reorderLevel;
    }

    /**
     * Get available quantity (on-hand minus reserved)
     *
     * @param int $productId Product ID
     * @return int Available quantity
     */
    public function getAvailableQuantity(int $productId): int
    {
        $stock = $this->where('product_id', $productId)->first();
        if (!$stock) {
            return 0;
        }

        return max(0, ($stock['on_hand_qty'] ?? 0) - ($stock['reserved_qty'] ?? 0));
    }
}
