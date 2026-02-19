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
}
