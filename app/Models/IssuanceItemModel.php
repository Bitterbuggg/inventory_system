<?php

namespace App\Models;

use CodeIgniter\Model;

class IssuanceItemModel extends Model
{
    protected $table = 'issuance_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'issuance_id',
        'product_id',
        'quantity',
        'created_at',
    ];
}
