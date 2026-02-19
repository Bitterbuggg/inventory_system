<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
                'comment' => 'User who performed the action (null for system)',
            ],
            'entity_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'Entity type: products, purchase_requests, inventory_stocks, etc',
            ],
            'entity_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'comment' => 'ID of the entity being audited',
            ],
            'action' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'comment' => 'CREATE, READ, UPDATE, DELETE',
            ],
            'old_values' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Previous values before change',
            ],
            'new_values' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'New values after change',
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
                'comment' => 'IP address of request',
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Browser/Client info',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Human-readable description of change',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'comment' => 'When the change occurred',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['entity_type', 'entity_id']);
        $this->forge->addKey('user_id');
        $this->forge->addKey('action');
        $this->forge->addKey('created_at');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('audit_logs', true);
    }

    public function down()
    {
        $this->forge->dropTable('audit_logs', true);
    }
}
