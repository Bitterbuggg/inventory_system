<?php

namespace App\Repositories\Database;

use App\Models\InventoryMovementModel;
use App\Models\InventoryStockModel;
use App\Repositories\Contracts\InventoryRepositoryInterface;
use RuntimeException;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function __construct(
        private readonly InventoryStockModel $stockModel,
        private readonly InventoryMovementModel $movementModel,
    ) {
    }

    public function increaseStock(
        int $productId,
        int $quantity,
        string $referenceType,
        int $referenceId,
        int $actorId,
        ?string $remarks = null
    ): bool {
        $stock = $this->stockModel->where('product_id', $productId)->first();

        if ($stock === null) {
            $this->stockModel->insert([
                'product_id'  => $productId,
                'on_hand_qty' => $quantity,
                'reserved_qty'=> 0,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $balance = $quantity;
        } else {
            $balance = (int) $stock['on_hand_qty'] + $quantity;
            $this->stockModel->update((int) $stock['id'], [
                'on_hand_qty' => $balance,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        $this->movementModel->insert([
            'product_id'    => $productId,
            'reference_type'=> $referenceType,
            'reference_id'  => $referenceId,
            'movement_type' => 'in',
            'quantity'      => $quantity,
            'balance_after' => $balance,
            'movement_at'   => date('Y-m-d H:i:s'),
            'created_by'    => $actorId,
            'remarks'       => $remarks,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        return true;
    }

    public function decreaseStock(
        int $productId,
        int $quantity,
        string $referenceType,
        int $referenceId,
        int $actorId,
        ?string $remarks = null
    ): bool {
        $stock = $this->stockModel->where('product_id', $productId)->first();

        if ($stock === null || (int) $stock['on_hand_qty'] < $quantity) {
            throw new RuntimeException('Insufficient inventory stock.');
        }

        $balance = (int) $stock['on_hand_qty'] - $quantity;

        $this->stockModel->update((int) $stock['id'], [
            'on_hand_qty' => $balance,
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        $this->movementModel->insert([
            'product_id'    => $productId,
            'reference_type'=> $referenceType,
            'reference_id'  => $referenceId,
            'movement_type' => 'out',
            'quantity'      => $quantity,
            'balance_after' => $balance,
            'movement_at'   => date('Y-m-d H:i:s'),
            'created_by'    => $actorId,
            'remarks'       => $remarks,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        return true;
    }

    public function getStock(int $productId): int
    {
        $stock = $this->stockModel->where('product_id', $productId)->first();

        return $stock ? (int) $stock['on_hand_qty'] : 0;
    }
}
