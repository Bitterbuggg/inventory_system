<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PharmacyProductSeeder extends Seeder
{
    public function run()
    {
        // Comprehensive pharmaceutical products catalog
        $products = [
            // Analgesics & Antipyretics
            ['sku' => 'PARA-500-TAB', 'brand_name' => 'Panadol', 'generic_name' => 'Paracetamol', 'dosage_form' => 'Tablet', 'strength' => '500mg', 'unit' => 'tablets', 'description' => 'Paracetamol 500mg tablets for fever and pain relief', 'reorder_level' => 500],
            ['sku' => 'PARA-250-SYP', 'brand_name' => 'Calpol', 'generic_name' => 'Paracetamol', 'dosage_form' => 'Syrup', 'strength' => '250mg/5ml', 'unit' => 'bottles', 'description' => 'Paracetamol syrup for children', 'reorder_level' => 150],
            ['sku' => 'IBU-200-TAB', 'brand_name' => 'Advil', 'generic_name' => 'Ibuprofen', 'dosage_form' => 'Tablet', 'strength' => '200mg', 'unit' => 'tablets', 'description' => 'Ibuprofen 200mg anti-inflammatory tablets', 'reorder_level' => 400],
            ['sku' => 'IBU-400-TAB', 'brand_name' => 'Motrin', 'generic_name' => 'Ibuprofen', 'dosage_form' => 'Tablet', 'strength' => '400mg', 'unit' => 'tablets', 'description' => 'Ibuprofen 400mg tablets', 'reorder_level' => 350],
            ['sku' => 'ASP-500-TAB', 'brand_name' => 'Aspirin', 'generic_name' => 'Acetylsalicylic Acid', 'dosage_form' => 'Tablet', 'strength' => '500mg', 'unit' => 'tablets', 'description' => 'Aspirin 500mg for pain and inflammation', 'reorder_level' => 300],

            // Antibiotics
            ['sku' => 'AMOX-500-CAP', 'brand_name' => 'Amoxyl', 'generic_name' => 'Amoxicillin', 'dosage_form' => 'Capsule', 'strength' => '500mg', 'unit' => 'capsules', 'description' => 'Amoxicillin 500mg broad-spectrum antibiotic', 'reorder_level' => 400],
            ['sku' => 'AMOX-250-SYP', 'brand_name' => 'Amoxil', 'generic_name' => 'Amoxicillin', 'dosage_form' => 'Syrup', 'strength' => '250mg/5ml', 'unit' => 'bottles', 'description' => 'Amoxicillin oral suspension for children', 'reorder_level' => 120],
            ['sku' => 'CIPRO-500-TAB', 'brand_name' => 'Ciprobay', 'generic_name' => 'Ciprofloxacin', 'dosage_form' => 'Tablet', 'strength' => '500mg', 'unit' => 'tablets', 'description' => 'Ciprofloxacin 500mg fluoroquinolone antibiotic', 'reorder_level' => 250],
            ['sku' => 'AZIT-500-TAB', 'brand_name' => 'Zithromax', 'generic_name' => 'Azithromycin', 'dosage_form' => 'Tablet', 'strength' => '500mg', 'unit' => 'tablets', 'description' => 'Azithromycin 500mg macrolide antibiotic', 'reorder_level' => 200],
            ['sku' => 'DOXYCYCL-100', 'brand_name' => 'Vibramycin', 'generic_name' => 'Doxycycline', 'dosage_form' => 'Capsule', 'strength' => '100mg', 'unit' => 'capsules', 'description' => 'Doxycycline 100mg tetracycline antibiotic', 'reorder_level' => 180],

            // Antihistamines & Decongestants
            ['sku' => 'CETIRIZINE-10', 'brand_name' => 'Piriteze', 'generic_name' => 'Cetirizine', 'dosage_form' => 'Tablet', 'strength' => '10mg', 'unit' => 'tablets', 'description' => 'Cetirizine 10mg for allergies and itching', 'reorder_level' => 300],
            ['sku' => 'LORATADINE-10', 'brand_name' => 'Claritine', 'generic_name' => 'Loratadine', 'dosage_form' => 'Tablet', 'strength' => '10mg', 'unit' => 'tablets', 'description' => 'Loratadine 10mg non-drowsy antihistamine', 'reorder_level' => 280],
            ['sku' => 'PHENYLEPH-10', 'brand_name' => 'Sudafed', 'generic_name' => 'Phenylephrine', 'dosage_form' => 'Tablet', 'strength' => '10mg', 'unit' => 'tablets', 'description' => 'Phenylephrine 10mg nasal decongestant', 'reorder_level' => 250],

            // Gastrointestinal
            ['sku' => 'OMEPRAZOLE-20', 'brand_name' => 'Prilosec', 'generic_name' => 'Omeprazole', 'dosage_form' => 'Capsule', 'strength' => '20mg', 'unit' => 'capsules', 'description' => 'Omeprazole 20mg proton pump inhibitor', 'reorder_level' => 350],
            ['sku' => 'RANITIDINE-150', 'brand_name' => 'Zantac', 'generic_name' => 'Ranitidine', 'dosage_form' => 'Tablet', 'strength' => '150mg', 'unit' => 'tablets', 'description' => 'Ranitidine 150mg H2 receptor antagonist', 'reorder_level' => 300],
            ['sku' => 'LOPERAMIDE-2', 'brand_name' => 'Imodium', 'generic_name' => 'Loperamide', 'dosage_form' => 'Tablet', 'strength' => '2mg', 'unit' => 'tablets', 'description' => 'Loperamide 2mg for diarrhea', 'reorder_level' => 200],
            ['sku' => 'BISACODYL-5', 'brand_name' => 'Dulcolax', 'generic_name' => 'Bisacodyl', 'dosage_form' => 'Tablet', 'strength' => '5mg', 'unit' => 'tablets', 'description' => 'Bisacodyl 5mg laxative', 'reorder_level' => 180],

            // Cardiovascular
            ['sku' => 'LISINOPRIL-10', 'brand_name' => 'Prinivil', 'generic_name' => 'Lisinopril', 'dosage_form' => 'Tablet', 'strength' => '10mg', 'unit' => 'tablets', 'description' => 'Lisinopril 10mg ACE inhibitor for hypertension', 'reorder_level' => 400],
            ['sku' => 'AMLODIPINE-5', 'brand_name' => 'Norvasc', 'generic_name' => 'Amlodipine', 'dosage_form' => 'Tablet', 'strength' => '5mg', 'unit' => 'tablets', 'description' => 'Amlodipine 5mg calcium channel blocker', 'reorder_level' => 380],
            ['sku' => 'METOPROLOL-50', 'brand_name' => 'Lopressor', 'generic_name' => 'Metoprolol', 'dosage_form' => 'Tablet', 'strength' => '50mg', 'unit' => 'tablets', 'description' => 'Metoprolol 50mg beta-blocker', 'reorder_level' => 320],
            ['sku' => 'ATORVASTATIN-20', 'brand_name' => 'Lipitor', 'generic_name' => 'Atorvastatin', 'dosage_form' => 'Tablet', 'strength' => '20mg', 'unit' => 'tablets', 'description' => 'Atorvastatin 20mg statin for cholesterol', 'reorder_level' => 350],

            // Respiratory
            ['sku' => 'SALBUTAMOL-INH', 'brand_name' => 'Ventolin', 'generic_name' => 'Salbutamol', 'dosage_form' => 'Inhaler', 'strength' => '100mcg/dose', 'unit' => 'inhalers', 'description' => 'Salbutamol inhaler for asthma', 'reorder_level' => 150],
            ['sku' => 'FLUTICASONE-INH', 'brand_name' => 'Flovent', 'generic_name' => 'Fluticasone', 'dosage_form' => 'Inhaler', 'strength' => '110mcg/dose', 'unit' => 'inhalers', 'description' => 'Fluticasone inhaler for asthma maintenance', 'reorder_level' => 120],

            // Antibiotic Creams/Ointments
            ['sku' => 'NEOSPO-CREAM', 'brand_name' => 'Neosporin', 'generic_name' => 'Neomycin/Bacitracin/Polymyxin', 'dosage_form' => 'Ointment', 'strength' => 'Topical', 'unit' => 'tubes', 'description' => 'Antibiotic ointment for minor cuts and burns', 'reorder_level' => 100],
            ['sku' => 'MUPIROCIN-CREAM', 'brand_name' => 'Bactroban', 'generic_name' => 'Mupirocin', 'dosage_form' => 'Cream', 'strength' => '2%', 'unit' => 'tubes', 'description' => 'Mupirocin 2% for bacterial skin infections', 'reorder_level' => 80],

            // Vitamins & Supplements
            ['sku' => 'VIT-C-500', 'brand_name' => 'Cerevit', 'generic_name' => 'Vitamin C', 'dosage_form' => 'Tablet', 'strength' => '500mg', 'unit' => 'tablets', 'description' => 'Vitamin C 500mg supplement', 'reorder_level' => 600],
            ['sku' => 'VIT-D3-1000', 'brand_name' => 'D-Cure', 'generic_name' => 'Vitamin D3', 'dosage_form' => 'Capsule', 'strength' => '1000IU', 'unit' => 'capsules', 'description' => 'Vitamin D3 1000IU supplement', 'reorder_level' => 400],
            ['sku' => 'CALCIUM-500', 'brand_name' => 'CalciCare', 'generic_name' => 'Calcium Carbonate', 'dosage_form' => 'Tablet', 'strength' => '500mg', 'unit' => 'tablets', 'description' => 'Calcium 500mg for bone health', 'reorder_level' => 500],
            ['sku' => 'IRON-FERROUS', 'brand_name' => 'Ferofort', 'generic_name' => 'Ferrous Sulfate', 'dosage_form' => 'Tablet', 'strength' => '325mg', 'unit' => 'tablets', 'description' => 'Ferrous sulfate 325mg for anemia', 'reorder_level' => 350],

            // Diabetic Medications
            ['sku' => 'METFORMIN-500', 'brand_name' => 'Glucophage', 'generic_name' => 'Metformin', 'dosage_form' => 'Tablet', 'strength' => '500mg', 'unit' => 'tablets', 'description' => 'Metformin 500mg for type 2 diabetes', 'reorder_level' => 600],
            ['sku' => 'GLIMEPIRIDE-2', 'brand_name' => 'Amaryl', 'generic_name' => 'Glimepiride', 'dosage_form' => 'Tablet', 'strength' => '2mg', 'unit' => 'tablets', 'description' => 'Glimepiride 2mg sulfonylurea', 'reorder_level' => 400],

            // Anti-inflammatory
            ['sku' => 'DICLOFENAC-50', 'brand_name' => 'Voltaren', 'generic_name' => 'Diclofenac', 'dosage_form' => 'Tablet', 'strength' => '50mg', 'unit' => 'tablets', 'description' => 'Diclofenac 50mg NSAID', 'reorder_level' => 300],
            ['sku' => 'NAPROXEN-250', 'brand_name' => 'Naprosyn', 'generic_name' => 'Naproxen', 'dosage_form' => 'Tablet', 'strength' => '250mg', 'unit' => 'tablets', 'description' => 'Naproxen 250mg anti-inflammatory', 'reorder_level' => 280],

            // Thyroid
            ['sku' => 'LEVOTHYROX-50', 'brand_name' => 'Synthroid', 'generic_name' => 'Levothyroxine', 'dosage_form' => 'Tablet', 'strength' => '50mcg', 'unit' => 'tablets', 'description' => 'Levothyroxine 50mcg for hypothyroidism', 'reorder_level' => 450],

            // Anticoagulants
            ['sku' => 'WARFARIN-5', 'brand_name' => 'Coumadin', 'generic_name' => 'Warfarin', 'dosage_form' => 'Tablet', 'strength' => '5mg', 'unit' => 'tablets', 'description' => 'Warfarin 5mg anticoagulant', 'reorder_level' => 250],
            ['sku' => 'ASPIRIN-100', 'brand_name' => 'Cardiprin', 'generic_name' => 'Aspirin', 'dosage_form' => 'Tablet', 'strength' => '100mg', 'unit' => 'tablets', 'description' => 'Aspirin 100mg low-dose for heart', 'reorder_level' => 600],

            // Antacids
            ['sku' => 'TUMS-ANTACID', 'brand_name' => 'TUMS', 'generic_name' => 'Calcium Carbonate', 'dosage_form' => 'Tablet', 'strength' => '500mg', 'unit' => 'tablets', 'description' => 'Antacid tablets for heartburn', 'reorder_level' => 400],
            ['sku' => 'MAALOX-SUSP', 'brand_name' => 'Maalox', 'generic_name' => 'Aluminum/Magnesium', 'dosage_form' => 'Suspension', 'strength' => 'Liquid', 'unit' => 'bottles', 'description' => 'Maalox suspension for acid reflux', 'reorder_level' => 120],

            // Antiemetics
            ['sku' => 'ONDANSETRON-4', 'brand_name' => 'Zofran', 'generic_name' => 'Ondansetron', 'dosage_form' => 'Tablet', 'strength' => '4mg', 'unit' => 'tablets', 'description' => 'Ondansetron 4mg for nausea', 'reorder_level' => 200],
            ['sku' => 'METOCLOPRAMIDE-10', 'brand_name' => 'Reglan', 'generic_name' => 'Metoclopramide', 'dosage_form' => 'Tablet', 'strength' => '10mg', 'unit' => 'tablets', 'description' => 'Metoclopramide 10mg anti-nausea', 'reorder_level' => 180],

            // Injections
            ['sku' => 'INSULIN-VIAL', 'brand_name' => 'Humulin', 'generic_name' => 'Insulin', 'dosage_form' => 'Injection', 'strength' => '100IU/ml', 'unit' => 'vials', 'description' => 'Insulin vial for diabetes injection', 'reorder_level' => 80],
            ['sku' => 'PENICILLIN-INJ', 'brand_name' => 'Penicillin G', 'generic_name' => 'Penicillin', 'dosage_form' => 'Injection', 'strength' => '1MU', 'unit' => 'vials', 'description' => 'Penicillin G injection antibiotic', 'reorder_level' => 100],
        ];

        // Delete existing data respecting foreign key constraints
        $this->db->table('inventory_movements')->where('1=1')->delete();
        $this->db->table('inventory_stocks')->where('1=1')->delete();
        $this->db->table('products')->where('1=1')->delete();

        // Insert products
        foreach ($products as $product) {
            $this->db->table('products')->insert(array_merge($product, [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]));
        }

        // Get inserted product IDs
        $insertedProducts = $this->db->table('products')->get()->getResultArray();

        // Realistic inventory quantities based on product type
        $inventoryData = [];
        foreach ($insertedProducts as $product) {
            // Vary inventory based on reorder level
            $reorderLevel = $product['reorder_level'];
            
            // Generate realistic quantities
            $scenarios = [
                ['on_hand' => rand(20, 50), 'reserved' => rand(0, 15)],          // Very low
                ['on_hand' => rand($reorderLevel, $reorderLevel * 1.5), 'reserved' => rand(0, 50)],
                ['on_hand' => rand($reorderLevel * 2, $reorderLevel * 3.5), 'reserved' => rand(20, 100)],
                ['on_hand' => rand($reorderLevel * 4, $reorderLevel * 6), 'reserved' => rand(50, 200)],
                ['on_hand' => rand($reorderLevel * 2, $reorderLevel * 2.5), 'reserved' => rand(10, 30)],
            ];
            
            $selected = $scenarios[array_rand($scenarios)];
            
            $this->db->table('inventory_stocks')->insert([
                'product_id' => $product['id'],
                'on_hand_qty' => $selected['on_hand'],
                'reserved_qty' => $selected['reserved'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        echo "✓ Pharmacy product seeding completed! Added " . count($insertedProducts) . " pharmaceutical products with realistic inventory levels.\n";
    }
}
