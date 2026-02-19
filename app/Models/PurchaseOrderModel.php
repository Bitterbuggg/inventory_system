<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table = 'purchase_orders';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'po_no',
        'supplier_id',
        'created_by',
        'approved_by',
        'status',
        'order_date',
        'expected_delivery_date',
        'total_amount',
        'terms',
        'notes',
    ];
}
