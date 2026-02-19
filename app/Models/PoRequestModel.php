<?php

namespace App\Models;

use CodeIgniter\Model;

class PoRequestModel extends Model
{
    protected $table = 'po_requests';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'request_no',
        'purchase_order_id',
        'requested_by',
        'status',
        'remarks',
        'requested_at',
    ];
}
