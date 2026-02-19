<?php

namespace App\Repositories\Contracts;

interface InventoryRepositoryInterface
{
    public function increaseStock(
        int $productId,
        int $quantity,
        string $referenceType,
        int $referenceId,
        int $actorId,
        ?string $remarks = null
    ): bool;

    public function decreaseStock(
        int $productId,
        int $quantity,
        string $referenceType,
        int $referenceId,
        int $actorId,
        ?string $remarks = null
    ): bool;

    public function getStock(int $productId): int;
}
