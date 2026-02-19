<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExpiryDateToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'expiry_date' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Product expiry date - critical for pharmacy operations',
            ],
            'batch_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Batch/Lot number for tracking',
            ],
        ]);

        // Add index for expired product queries
        $this->forge->addKey('expiry_date');
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'expiry_date');
        $this->forge->dropColumn('products', 'batch_number');
    }
}
