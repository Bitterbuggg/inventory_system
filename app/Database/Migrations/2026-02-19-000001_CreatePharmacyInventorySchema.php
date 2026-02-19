<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePharmacyInventorySchema extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 40],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('roles', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'role_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'full_name' => ['type' => 'VARCHAR', 'constraint' => 120],
            'email' => ['type' => 'VARCHAR', 'constraint' => 120],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'last_login_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('role_id');
        $this->forge->addKey('is_active');
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('users', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 120],
            'contact_person' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'address' => ['type' => 'TEXT', 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('suppliers', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'sku' => ['type' => 'VARCHAR', 'constraint' => 60],
            'brand_name' => ['type' => 'VARCHAR', 'constraint' => 120],
            'generic_name' => ['type' => 'VARCHAR', 'constraint' => 120],
            'dosage_form' => ['type' => 'VARCHAR', 'constraint' => 60],
            'strength' => ['type' => 'VARCHAR', 'constraint' => 60],
            'unit' => ['type' => 'VARCHAR', 'constraint' => 40],
            'description' => ['type' => 'TEXT', 'null' => true],
            'reorder_level' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('sku');
        $this->forge->addKey('brand_name');
        $this->forge->addKey('generic_name');
        $this->forge->createTable('products', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'request_no' => ['type' => 'VARCHAR', 'constraint' => 40],
            'requested_by' => ['type' => 'BIGINT', 'unsigned' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'draft'],
            'requested_at' => ['type' => 'DATETIME', 'null' => true],
            'remarks' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('request_no');
        $this->forge->addKey(['requested_by', 'status']);
        $this->forge->addForeignKey('requested_by', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('purchase_requests', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'purchase_request_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'product_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'requested_qty' => ['type' => 'INT', 'unsigned' => true],
            'approved_qty' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'unit_cost_estimate' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
            'remarks' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['purchase_request_id', 'product_id']);
        $this->forge->addForeignKey('purchase_request_id', 'purchase_requests', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('purchase_request_items', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'workflow_type' => ['type' => 'VARCHAR', 'constraint' => 40],
            'reference_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'approver_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20],
            'payload' => ['type' => 'JSON', 'null' => true],
            'remarks' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'acted_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['workflow_type', 'reference_id']);
        $this->forge->addKey(['approver_id', 'status']);
        $this->forge->addForeignKey('approver_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('approvals', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'po_no' => ['type' => 'VARCHAR', 'constraint' => 40],
            'supplier_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true],
            'approved_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'draft'],
            'order_date' => ['type' => 'DATE'],
            'expected_delivery_date' => ['type' => 'DATE', 'null' => true],
            'total_amount' => ['type' => 'DECIMAL', 'constraint' => '14,2', 'default' => '0.00'],
            'terms' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('po_no');
        $this->forge->addKey(['supplier_id', 'status']);
        $this->forge->addKey('created_by');
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('purchase_orders', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'purchase_order_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'product_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'quantity' => ['type' => 'INT', 'unsigned' => true],
            'received_qty' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'unit_cost' => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'line_total' => ['type' => 'DECIMAL', 'constraint' => '14,2'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['purchase_order_id', 'product_id']);
        $this->forge->addForeignKey('purchase_order_id', 'purchase_orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('purchase_order_items', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'request_no' => ['type' => 'VARCHAR', 'constraint' => 40],
            'purchase_order_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'requested_by' => ['type' => 'BIGINT', 'unsigned' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'remarks' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'requested_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('request_no');
        $this->forge->addKey(['requested_by', 'status']);
        $this->forge->addForeignKey('purchase_order_id', 'purchase_orders', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('requested_by', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('po_requests', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'receiving_no' => ['type' => 'VARCHAR', 'constraint' => 40],
            'purchase_order_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'received_by' => ['type' => 'BIGINT', 'unsigned' => true],
            'received_at' => ['type' => 'DATETIME'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'draft'],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('receiving_no');
        $this->forge->addKey(['purchase_order_id', 'status']);
        $this->forge->addForeignKey('purchase_order_id', 'purchase_orders', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('received_by', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('receivings', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'receiving_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'product_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'received_qty' => ['type' => 'INT', 'unsigned' => true],
            'batch_no' => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'expiry_date' => ['type' => 'DATE', 'null' => true],
            'unit_cost' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['receiving_id', 'product_id']);
        $this->forge->addKey('expiry_date');
        $this->forge->addForeignKey('receiving_id', 'receivings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('receiving_items', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'product_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'on_hand_qty' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'reserved_qty' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('product_id');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('inventory_stocks', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'product_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'reference_type' => ['type' => 'VARCHAR', 'constraint' => 30],
            'reference_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'movement_type' => ['type' => 'VARCHAR', 'constraint' => 10],
            'quantity' => ['type' => 'INT', 'unsigned' => true],
            'balance_after' => ['type' => 'INT', 'unsigned' => true],
            'movement_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true],
            'remarks' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['product_id', 'movement_at']);
        $this->forge->addKey(['reference_type', 'reference_id']);
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('inventory_movements', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'issuance_no' => ['type' => 'VARCHAR', 'constraint' => 40],
            'issued_to' => ['type' => 'VARCHAR', 'constraint' => 120],
            'issued_by' => ['type' => 'BIGINT', 'unsigned' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'draft'],
            'issued_at' => ['type' => 'DATETIME'],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('issuance_no');
        $this->forge->addKey(['issued_by', 'status']);
        $this->forge->addForeignKey('issued_by', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('issuances', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'issuance_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'product_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'quantity' => ['type' => 'INT', 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['issuance_id', 'product_id']);
        $this->forge->addForeignKey('issuance_id', 'issuances', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('issuance_items', true);
    }

    public function down()
    {
        $tables = [
            'issuance_items',
            'issuances',
            'inventory_movements',
            'inventory_stocks',
            'receiving_items',
            'receivings',
            'po_requests',
            'purchase_order_items',
            'purchase_orders',
            'approvals',
            'purchase_request_items',
            'purchase_requests',
            'products',
            'suppliers',
            'users',
            'roles',
        ];

        foreach ($tables as $table) {
            $this->forge->dropTable($table, true);
        }
    }
}
