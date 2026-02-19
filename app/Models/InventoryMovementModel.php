<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryMovementModel extends Model
{
    protected $table = 'inventory_movements';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'product_id',
        'reference_type',
        'reference_id',
        'movement_type',
        'quantity',
        'balance_after',
        'movement_at',
        'created_by',
        'remarks',
        'created_at',
    ];
}
