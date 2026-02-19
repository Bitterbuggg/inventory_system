<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run()
    {
        // Sample products with inventory
        $products = [
            [
                'sku' => 'CLMP22713-BOSTON-S',
                'brand_name' => 'HM Briefs',
                'generic_name' => 'Underwear',
                'dosage_form' => 'Garment',
                'strength' => 'S',
                'unit' => 'pcs',
                'description' => 'HM Briefs QGSKN-Boston Small',
                'reorder_level' => 100,
            ],
            [
                'sku' => 'CLMP22713-BOSTON-M',
                'brand_name' => 'HM Briefs',
                'generic_name' => 'Underwear',
                'dosage_form' => 'Garment',
                'strength' => 'M',
                'unit' => 'pcs',
                'description' => 'HM Briefs QGSKN-Boston Medium',
                'reorder_level' => 150,
            ],
            [
                'sku' => 'CLMP22713-BOSTON-L',
                'brand_name' => 'HM Briefs',
                'generic_name' => 'Underwear',
                'dosage_form' => 'Garment',
                'strength' => 'L',
                'unit' => 'pcs',
                'description' => 'HM Briefs QGSKN-Boston Large',
                'reorder_level' => 120,
            ],
            [
                'sku' => 'CLMP22713-BOSTON-XL',
                'brand_name' => 'HM Briefs',
                'generic_name' => 'Underwear',
                'dosage_form' => 'Garment',
                'strength' => 'XL',
                'unit' => 'pcs',
                'description' => 'HM Briefs QGSKN-Boston XL',
                'reorder_level' => 100,
            ],
            [
                'sku' => 'CLMP22713-BOSTON-2X',
                'brand_name' => 'HM Briefs',
                'generic_name' => 'Underwear',
                'dosage_form' => 'Garment',
                'strength' => '2X',
                'unit' => 'pcs',
                'description' => 'HM Briefs QGSKN-Boston 2XL',
                'reorder_level' => 80,
            ],
            [
                'sku' => 'CLMP22813-STOKED-S',
                'brand_name' => 'HM Briefs',
                'generic_name' => 'Underwear',
                'dosage_form' => 'Garment',
                'strength' => 'S',
                'unit' => 'pcs',
                'description' => 'HM Briefs QGSKN-Stoked Small',
                'reorder_level' => 90,
            ],
            [
                'sku' => 'CLMP22813-STOKED-M',
                'brand_name' => 'HM Briefs',
                'generic_name' => 'Underwear',
                'dosage_form' => 'Garment',
                'strength' => 'M',
                'unit' => 'pcs',
                'description' => 'HM Briefs OG-Stoked Medium',
                'reorder_level' => 140,
            ],
            [
                'sku' => 'PARACETAMOL-500',
                'brand_name' => 'Panadol',
                'generic_name' => 'Paracetamol',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'unit' => 'tablets',
                'description' => 'Paracetamol 500mg, fever and pain reliever',
                'reorder_level' => 500,
            ],
            [
                'sku' => 'IBUPROFEN-200',
                'brand_name' => 'Advil',
                'generic_name' => 'Ibuprofen',
                'dosage_form' => 'Tablet',
                'strength' => '200mg',
                'unit' => 'tablets',
                'description' => 'Ibuprofen 200mg, anti-inflammatory',
                'reorder_level' => 400,
            ],
            [
                'sku' => 'AMOXICILLIN-500',
                'brand_name' => 'Amoxyl',
                'generic_name' => 'Amoxicillin',
                'dosage_form' => 'Capsule',
                'strength' => '500mg',
                'unit' => 'capsules',
                'description' => 'Amoxicillin 500mg, antibiotic',
                'reorder_level' => 300,
            ],
        ];

        // Insert products
        foreach ($products as $product) {
            $this->db->table('products')->insert($product);
        }

        // Get inserted product IDs
        $insertedProducts = $this->db->table('products')
            ->whereIn('sku', array_column($products, 'sku'))
            ->get()
            ->getResultArray();

        // Create inventory stocks for each product
        $stocks = [];
        $quantities = [
            ['on_hand' => 9, 'reserved' => 0],
            ['on_hand' => 783, 'reserved' => 100],
            ['on_hand' => 828, 'reserved' => 50],
            ['on_hand' => 255, 'reserved' => 0],
            ['on_hand' => 480, 'reserved' => 20],
            ['on_hand' => 354, 'reserved' => 10],
            ['on_hand' => 33, 'reserved' => 5],
            ['on_hand' => 507, 'reserved' => 100],
            ['on_hand' => 216, 'reserved' => 50],
            ['on_hand' => 162, 'reserved' => 30],
            ['on_hand' => 1200, 'reserved' => 200],
            ['on_hand' => 950, 'reserved' => 150],
        ];

        foreach ($insertedProducts as $index => $product) {
            $qty = $quantities[$index % count($quantities)];
            $this->db->table('inventory_stocks')->insert([
                'product_id' => $product['id'],
                'on_hand_qty' => $qty['on_hand'],
                'reserved_qty' => $qty['reserved'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        echo "✓ Inventory seeding completed! Added " . count($insertedProducts) . " products with stock levels.";
    }
}
