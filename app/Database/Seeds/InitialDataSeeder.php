<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'Admin', 'description' => 'System administrator', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Employee', 'description' => 'Pharmacy operations employee', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'IT Dev/Staff', 'description' => 'Technical support and developers', 'created_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('roles')->insertBatch($roles);

        $adminRole = $this->db->table('roles')->where('name', 'Admin')->get()->getRowArray();

        $this->db->table('users')->insert([
            'role_id' => $adminRole['id'],
            'full_name' => 'System Admin',
            'email' => 'admin@pharmacy.local',
            'password_hash' => password_hash('Admin@123', PASSWORD_DEFAULT),
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
