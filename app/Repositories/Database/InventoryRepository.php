<?php

namespace App\Repositories\Database;

use App\Models\InventoryMovementModel;
use App\Models\InventoryStockModel;
use App\Repositories\Contracts\InventoryRepositoryInterface;
use CodeIgniter\Database\BaseConnection;
use RuntimeException;

class InventoryRepository implements InventoryRepositoryInterface
{
    private BaseConnection $db;

    public function __construct(
        private readonly InventoryStockModel $stockModel,
        private readonly InventoryMovementModel $movementModel,
    ) {
        $this->db = \Config\Database::connect();
    }

    public function increaseStock(
        int $productId,
        int $quantity,
        string $referenceType,
        int $referenceId,
        int $actorId,
        ?string $remarks = null
    ): bool {
        try {
            $this->db->transBegin();

            // Lock the row for update to prevent race conditions
            $stock = $this->stockModel
                ->where('product_id', $productId)
                ->forUpdate()
                ->first();

            if ($stock === null) {
                // Create new stock record
                $this->stockModel->insert([
                    'product_id'   => $productId,
                    'on_hand_qty'  => $quantity,
                    'reserved_qty' => 0,
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);
                $balance = $quantity;
                $stockId = (int) $this->stockModel->getInsertID();
            } else {
                // Update existing stock record
                $balance = (int) $stock['on_hand_qty'] + $quantity;
                $stockId = (int) $stock['id'];
                $this->stockModel->update($stockId, [
                    'on_hand_qty' => $balance,
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
            }

            // Record the movement
            $this->movementModel->insert([
                'product_id'     => $productId,
                'reference_type' => $referenceType,
                'reference_id'   => $referenceId,
                'movement_type'  => 'in',
                'quantity'       => $quantity,
                'balance_after'  => $balance,
                'movement_at'    => date('Y-m-d H:i:s'),
                'created_by'     => $actorId,
                'remarks'        => $remarks,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);

            $this->db->transCommit();
            return true;
        } catch (RuntimeException $e) {
            $this->db->transRollback();
            log_message('error', "📛 Stock increase failed for product {$productId}: {$e->getMessage()}");
            throw $e;
        }
    }

    public function decreaseStock(
        int $productId,
        int $quantity,
        string $referenceType,
        int $referenceId,
        int $actorId,
        ?string $remarks = null
    ): bool {
        try {
            $this->db->transBegin();

            // Lock the row for update to prevent race conditions
            $stock = $this->stockModel
                ->where('product_id', $productId)
                ->forUpdate()
                ->first();

            if ($stock === null || (int) $stock['on_hand_qty'] < $quantity) {
                throw new RuntimeException('Insufficient inventory stock for product #' . $productId);
            }

            // Update stock
            $balance = (int) $stock['on_hand_qty'] - $quantity;
            $this->stockModel->update((int) $stock['id'], [
                'on_hand_qty' => $balance,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);

            // Record the movement
            $this->movementModel->insert([
                'product_id'     => $productId,
                'reference_type' => $referenceType,
                'reference_id'   => $referenceId,
                'movement_type'  => 'out',
                'quantity'       => $quantity,
                'balance_after'  => $balance,
                'movement_at'    => date('Y-m-d H:i:s'),
                'created_by'     => $actorId,
                'remarks'        => $remarks,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);

            $this->db->transCommit();
            log_message('info', "✅ Stock decreased: product {$productId}, qty -{$quantity}");
            return true;
        } catch (RuntimeException $e) {
            $this->db->transRollback();
            log_message('error', "⚠️ Stock decrease failed for product {$productId}: {$e->getMessage()}");
            throw $e;
        }
    }

    public function getStock(int $productId): int
    {
        $stock = $this->stockModel->where('product_id', $productId)->first();

        return $stock ? (int) $stock['on_hand_qty'] : 0;
    }
}
