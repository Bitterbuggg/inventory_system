<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSoftDeletesToCriticalTables extends Migration
{
    private function fieldExists(string $field, string $table): bool
    {
        // Use INFORMATION_SCHEMA to check if a field exists (MySQL/MariaDB)
        $dbName = $this->db->getDatabase();
        $query = $this->db->query(
            "SELECT COUNT(*) AS count FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?",
            [$dbName, $table, $field]
        );
        $row = $query->getRowArray();
        return isset($row['count']) && $row['count'] > 0;
    }

    public function up()
    {
        // Add soft_delete support to transaction tables for audit trail and recovery
        
        // Purchase requests table
        if (!$this->fieldExists('deleted_at', 'purchase_requests')) {
            $this->db->query("
                ALTER TABLE purchase_requests 
                ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at,
                ADD INDEX idx_deleted_at (deleted_at)
            ");
        }
        
        // Purchase orders table
        if (!$this->fieldExists('deleted_at', 'purchase_orders')) {
            $this->db->query("
                ALTER TABLE purchase_orders 
                ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at,
                ADD INDEX idx_deleted_at (deleted_at)
            ");
        }
        
        // Receiving table
        if (!$this->fieldExists('deleted_at', 'receivings')) {
            $this->db->query("
                ALTER TABLE receivings 
                ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at,
                ADD INDEX idx_deleted_at (deleted_at)
            ");
        }
        
        // Issuance table
        if (!$this->fieldExists('deleted_at', 'issuances')) {
            $this->db->query("
                ALTER TABLE issuances 
                ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at,
                ADD INDEX idx_deleted_at (deleted_at)
            ");
        }
        
        // Receiving items table
        if (!$this->fieldExists('deleted_at', 'receiving_items')) {
            $this->db->query("
                ALTER TABLE receiving_items 
                ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER created_at,
                ADD INDEX idx_deleted_at (deleted_at)
            ");
        }
        
        // Issuance items table
        if (!$this->fieldExists('deleted_at', 'issuance_items')) {
            $this->db->query("
                ALTER TABLE issuance_items 
                ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER created_at,
                ADD INDEX idx_deleted_at (deleted_at)
            ");
        }
        
        // Purchase request items table
        if (!$this->fieldExists('deleted_at', 'purchase_request_items')) {
            $this->db->query("
                ALTER TABLE purchase_request_items 
                ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at,
                ADD INDEX idx_deleted_at (deleted_at)
            ");
        }
        
        // Purchase order items table
        if (!$this->fieldExists('deleted_at', 'purchase_order_items')) {
            $this->db->query("
                ALTER TABLE purchase_order_items 
                ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at,
                ADD INDEX idx_deleted_at (deleted_at)
            ");
        }
        
        log_message('info', '✅ Soft delete columns added to critical tables');
    }

    public function down()
    {
        // Remove soft delete columns
        $tables = [
            'purchase_requests',
            'purchase_orders',
            'receivings',
            'issuances',
            'receiving_items',
            'issuance_items',
            'purchase_request_items',
            'purchase_order_items',
        ];
        
        foreach ($tables as $table) {
            if ($this->fieldExists('deleted_at', $table)) {
                $this->db->query("ALTER TABLE {$table} DROP COLUMN deleted_at");
                $this->db->query("ALTER TABLE {$table} DROP INDEX idx_deleted_at");
            }
        }
    }
}
