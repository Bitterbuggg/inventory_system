<?php

namespace App\Models;

use CodeIgniter\Model;

class ReceivingModel extends Model
{
    protected $table = 'receivings';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'receiving_no',
        'purchase_order_id',
        'received_by',
        'received_at',
        'status',
        'notes',
    ];
}
