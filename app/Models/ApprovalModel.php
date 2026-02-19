<?php

namespace App\Models;

use CodeIgniter\Model;

class ApprovalModel extends Model
{
    protected $table = 'approvals';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'workflow_type',
        'reference_id',
        'approver_id',
        'status',
        'payload',
        'remarks',
        'acted_at',
        'created_at',
    ];
}
