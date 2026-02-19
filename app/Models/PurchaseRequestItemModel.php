<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseRequestItemModel extends Model
{
    protected $table = 'purchase_request_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'purchase_request_id',
        'product_id',
        'requested_qty',
        'approved_qty',
        'unit_cost_estimate',
        'remarks',
    ];
}
