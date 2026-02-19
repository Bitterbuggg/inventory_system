<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseRequestModel extends Model
{
    protected $table = 'purchase_requests';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'request_no',
        'requested_by',
        'status',
        'requested_at',
        'remarks',
    ];
}
