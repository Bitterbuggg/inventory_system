<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDatabaseIndexesAndConstraints extends Migration
{
    public function up()
    {
        // Add indexes for performance on high-query columns
        // Using raw SQL for reliable cross-platform compatibility
        
        // Inventory movements - frequently queried by user who created them
        $this->db->query("ALTER TABLE inventory_movements ADD INDEX idx_created_by (created_by)");
        
        // Approvals - frequently filtered by status
        $this->db->query("ALTER TABLE approvals ADD INDEX idx_status (status)");
        
        // Purchase requests and orders - frequently queried by status
        $this->db->query("ALTER TABLE purchase_requests ADD INDEX idx_status (status)");
        $this->db->query("ALTER TABLE purchase_orders ADD INDEX idx_status (status)");
        
        // Receiving and issuance - frequently queried by status
        $this->db->query("ALTER TABLE receivings ADD INDEX idx_status (status)");
        $this->db->query("ALTER TABLE issuances ADD INDEX idx_status (status)");

        // Add NOT NULL constraint to batch_no in receiving_items
        // This is pharmaceutical requirement for batch tracking
        $this->db->query("
            ALTER TABLE receiving_items 
            MODIFY batch_no VARCHAR(60) NOT NULL DEFAULT ''
        ");
        
        log_message('info', '✅ Database indexes and constraints added');
    }

    public function down()
    {
        // Remove the newly added indexes
        $this->db->query("ALTER TABLE inventory_movements DROP INDEX idx_created_by");
        $this->db->query("ALTER TABLE approvals DROP INDEX idx_status");
        $this->db->query("ALTER TABLE purchase_requests DROP INDEX idx_status");
        $this->db->query("ALTER TABLE purchase_orders DROP INDEX idx_status");
        $this->db->query("ALTER TABLE receivings DROP INDEX idx_status");
        $this->db->query("ALTER TABLE issuances DROP INDEX idx_status");
        
        // Make batch_no nullable again
        $this->db->query("
            ALTER TABLE receiving_items 
            MODIFY batch_no VARCHAR(60) NULL DEFAULT NULL
        ");
    }
}
