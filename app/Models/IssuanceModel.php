<?php

namespace App\Models;

use CodeIgniter\Model;

class IssuanceModel extends Model
{
    protected $table = 'issuances';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'issuance_no',
        'issued_to',
        'issued_by',
        'status',
        'issued_at',
        'notes',
    ];
}
