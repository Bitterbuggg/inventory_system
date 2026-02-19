<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'sku',
        'brand_name',
        'generic_name',
        'dosage_form',
        'strength',
        'unit',
        'description',
        'reorder_level',
    ];
}
