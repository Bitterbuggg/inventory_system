<?php

namespace App\Models;

use CodeIgniter\Model;

class ReceivingItemModel extends Model
{
    protected $table = 'receiving_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'receiving_id',
        'product_id',
        'received_qty',
        'batch_no',
        'expiry_date',
        'unit_cost',
        'created_at',
    ];
}
