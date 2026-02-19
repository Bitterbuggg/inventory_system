-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2026 at 03:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharmacy_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `workflow_type` varchar(40) NOT NULL,
  `reference_id` bigint(20) UNSIGNED NOT NULL,
  `approver_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(20) NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `remarks` varchar(255) DEFAULT NULL,
  `acted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'User who performed the action (null for system)',
  `entity_type` varchar(50) NOT NULL COMMENT 'Entity type: products, purchase_requests, inventory_stocks, etc',
  `entity_id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID of the entity being audited',
  `action` varchar(20) NOT NULL COMMENT 'CREATE, READ, UPDATE, DELETE',
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Previous values before change' CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'New values after change' CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP address of request',
  `user_agent` text DEFAULT NULL COMMENT 'Browser/Client info',
  `description` text DEFAULT NULL COMMENT 'Human-readable description of change',
  `created_at` datetime NOT NULL COMMENT 'When the change occurred'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `reference_type` varchar(30) NOT NULL,
  `reference_id` bigint(20) UNSIGNED NOT NULL,
  `movement_type` varchar(10) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `balance_after` int(10) UNSIGNED NOT NULL,
  `movement_at` datetime NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_stocks`
--

CREATE TABLE `inventory_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `on_hand_qty` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_stocks`
--

INSERT INTO `inventory_stocks` (`id`, `product_id`, `on_hand_qty`, `reserved_qty`, `updated_at`) VALUES
(1, 53, 1437, 56, '2026-02-19 02:30:11'),
(2, 54, 38, 10, '2026-02-19 02:30:11'),
(3, 55, 855, 11, '2026-02-19 02:30:11'),
(4, 56, 47, 4, '2026-02-19 02:30:11'),
(5, 57, 339, 28, '2026-02-19 02:30:11'),
(6, 58, 856, 21, '2026-02-19 02:30:11'),
(7, 59, 156, 41, '2026-02-19 02:30:11'),
(8, 60, 793, 74, '2026-02-19 02:30:11'),
(9, 61, 48, 10, '2026-02-19 02:30:11'),
(10, 62, 443, 11, '2026-02-19 02:30:11'),
(11, 63, 1434, 117, '2026-02-19 02:30:11'),
(12, 64, 1227, 84, '2026-02-19 02:30:11'),
(13, 65, 343, 10, '2026-02-19 02:30:11'),
(14, 66, 760, 20, '2026-02-19 02:30:11'),
(15, 67, 1668, 112, '2026-02-19 02:30:11'),
(16, 68, 945, 65, '2026-02-19 02:30:11'),
(17, 69, 438, 13, '2026-02-19 02:30:11'),
(18, 70, 33, 11, '2026-02-19 02:30:11'),
(19, 71, 921, 87, '2026-02-19 02:30:11'),
(20, 72, 472, 16, '2026-02-19 02:30:11'),
(21, 73, 1903, 73, '2026-02-19 02:30:11'),
(22, 74, 213, 1, '2026-02-19 02:30:11'),
(23, 75, 241, 11, '2026-02-19 02:30:11'),
(24, 76, 514, 62, '2026-02-19 02:30:11'),
(25, 77, 44, 6, '2026-02-19 02:30:11'),
(26, 78, 1469, 71, '2026-02-19 02:30:11'),
(27, 79, 842, 98, '2026-02-19 02:30:11'),
(28, 80, 662, 14, '2026-02-19 02:30:11'),
(29, 81, 1645, 185, '2026-02-19 02:30:11'),
(30, 82, 1229, 15, '2026-02-19 02:30:11'),
(31, 83, 48, 4, '2026-02-19 02:30:11'),
(32, 84, 728, 19, '2026-02-19 02:30:11'),
(33, 85, 595, 12, '2026-02-19 02:30:11'),
(34, 86, 2276, 60, '2026-02-19 02:30:11'),
(35, 87, 29, 13, '2026-02-19 02:30:11'),
(36, 88, 2739, 199, '2026-02-19 02:30:11'),
(37, 89, 896, 59, '2026-02-19 02:30:11'),
(38, 90, 166, 13, '2026-02-19 02:30:11'),
(39, 91, 486, 19, '2026-02-19 02:30:11'),
(40, 92, 387, 11, '2026-02-19 02:30:11'),
(41, 93, 212, 88, '2026-02-19 02:30:11'),
(42, 94, 124, 48, '2026-02-19 02:30:11');

-- --------------------------------------------------------

--
-- Table structure for table `issuances`
--

CREATE TABLE `issuances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `issuance_no` varchar(40) NOT NULL,
  `issued_to` varchar(120) NOT NULL,
  `issued_by` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `issued_at` datetime NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `issuance_items`
--

CREATE TABLE `issuance_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `issuance_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-02-19-000001', 'App\\Database\\Migrations\\CreatePharmacyInventorySchema', 'default', 'App', 1771466875, 1),
(2, '2026-02-19-000002', 'App\\Database\\Migrations\\AddExpiryDateToProducts', 'default', 'App', 1771469334, 2),
(3, '2026-02-19-000003', 'App\\Database\\Migrations\\CreateAuditLogsTable', 'default', 'App', 1771469659, 3);

-- --------------------------------------------------------

--
-- Table structure for table `po_requests`
--

CREATE TABLE `po_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_no` varchar(40) NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `remarks` varchar(255) DEFAULT NULL,
  `requested_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(60) NOT NULL,
  `brand_name` varchar(120) NOT NULL,
  `generic_name` varchar(120) NOT NULL,
  `dosage_form` varchar(60) NOT NULL,
  `strength` varchar(60) NOT NULL,
  `unit` varchar(40) NOT NULL,
  `description` text DEFAULT NULL,
  `reorder_level` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `expiry_date` date DEFAULT NULL COMMENT 'Product expiry date - critical for pharmacy operations',
  `batch_number` varchar(50) DEFAULT NULL COMMENT 'Batch/Lot number for tracking'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `brand_name`, `generic_name`, `dosage_form`, `strength`, `unit`, `description`, `reorder_level`, `created_at`, `updated_at`, `expiry_date`, `batch_number`) VALUES
(53, 'PARA-500-TAB', 'Panadol', 'Paracetamol', 'Tablet', '500mg', 'tablets', 'Paracetamol 500mg tablets for fever and pain relief', 500, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(54, 'PARA-250-SYP', 'Calpol', 'Paracetamol', 'Syrup', '250mg/5ml', 'bottles', 'Paracetamol syrup for children', 150, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(55, 'IBU-200-TAB', 'Advil', 'Ibuprofen', 'Tablet', '200mg', 'tablets', 'Ibuprofen 200mg anti-inflammatory tablets', 400, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(56, 'IBU-400-TAB', 'Motrin', 'Ibuprofen', 'Tablet', '400mg', 'tablets', 'Ibuprofen 400mg tablets', 350, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(57, 'ASP-500-TAB', 'Aspirin', 'Acetylsalicylic Acid', 'Tablet', '500mg', 'tablets', 'Aspirin 500mg for pain and inflammation', 300, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(58, 'AMOX-500-CAP', 'Amoxyl', 'Amoxicillin', 'Capsule', '500mg', 'capsules', 'Amoxicillin 500mg broad-spectrum antibiotic', 400, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(59, 'AMOX-250-SYP', 'Amoxil', 'Amoxicillin', 'Syrup', '250mg/5ml', 'bottles', 'Amoxicillin oral suspension for children', 120, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(60, 'CIPRO-500-TAB', 'Ciprobay', 'Ciprofloxacin', 'Tablet', '500mg', 'tablets', 'Ciprofloxacin 500mg fluoroquinolone antibiotic', 250, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(61, 'AZIT-500-TAB', 'Zithromax', 'Azithromycin', 'Tablet', '500mg', 'tablets', 'Azithromycin 500mg macrolide antibiotic', 200, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(62, 'DOXYCYCL-100', 'Vibramycin', 'Doxycycline', 'Capsule', '100mg', 'capsules', 'Doxycycline 100mg tetracycline antibiotic', 180, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(63, 'CETIRIZINE-10', 'Piriteze', 'Cetirizine', 'Tablet', '10mg', 'tablets', 'Cetirizine 10mg for allergies and itching', 300, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(64, 'LORATADINE-10', 'Claritine', 'Loratadine', 'Tablet', '10mg', 'tablets', 'Loratadine 10mg non-drowsy antihistamine', 280, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(65, 'PHENYLEPH-10', 'Sudafed', 'Phenylephrine', 'Tablet', '10mg', 'tablets', 'Phenylephrine 10mg nasal decongestant', 250, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(66, 'OMEPRAZOLE-20', 'Prilosec', 'Omeprazole', 'Capsule', '20mg', 'capsules', 'Omeprazole 20mg proton pump inhibitor', 350, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(67, 'RANITIDINE-150', 'Zantac', 'Ranitidine', 'Tablet', '150mg', 'tablets', 'Ranitidine 150mg H2 receptor antagonist', 300, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(68, 'LOPERAMIDE-2', 'Imodium', 'Loperamide', 'Tablet', '2mg', 'tablets', 'Loperamide 2mg for diarrhea', 200, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(69, 'BISACODYL-5', 'Dulcolax', 'Bisacodyl', 'Tablet', '5mg', 'tablets', 'Bisacodyl 5mg laxative', 180, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(70, 'LISINOPRIL-10', 'Prinivil', 'Lisinopril', 'Tablet', '10mg', 'tablets', 'Lisinopril 10mg ACE inhibitor for hypertension', 400, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(71, 'AMLODIPINE-5', 'Norvasc', 'Amlodipine', 'Tablet', '5mg', 'tablets', 'Amlodipine 5mg calcium channel blocker', 380, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(72, 'METOPROLOL-50', 'Lopressor', 'Metoprolol', 'Tablet', '50mg', 'tablets', 'Metoprolol 50mg beta-blocker', 320, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(73, 'ATORVASTATIN-20', 'Lipitor', 'Atorvastatin', 'Tablet', '20mg', 'tablets', 'Atorvastatin 20mg statin for cholesterol', 350, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(74, 'SALBUTAMOL-INH', 'Ventolin', 'Salbutamol', 'Inhaler', '100mcg/dose', 'inhalers', 'Salbutamol inhaler for asthma', 150, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(75, 'FLUTICASONE-INH', 'Flovent', 'Fluticasone', 'Inhaler', '110mcg/dose', 'inhalers', 'Fluticasone inhaler for asthma maintenance', 120, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(76, 'NEOSPO-CREAM', 'Neosporin', 'Neomycin/Bacitracin/Polymyxin', 'Ointment', 'Topical', 'tubes', 'Antibiotic ointment for minor cuts and burns', 100, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(77, 'MUPIROCIN-CREAM', 'Bactroban', 'Mupirocin', 'Cream', '2%', 'tubes', 'Mupirocin 2% for bacterial skin infections', 80, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(78, 'VIT-C-500', 'Cerevit', 'Vitamin C', 'Tablet', '500mg', 'tablets', 'Vitamin C 500mg supplement', 600, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(79, 'VIT-D3-1000', 'D-Cure', 'Vitamin D3', 'Capsule', '1000IU', 'capsules', 'Vitamin D3 1000IU supplement', 400, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(80, 'CALCIUM-500', 'CalciCare', 'Calcium Carbonate', 'Tablet', '500mg', 'tablets', 'Calcium 500mg for bone health', 500, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(81, 'IRON-FERROUS', 'Ferofort', 'Ferrous Sulfate', 'Tablet', '325mg', 'tablets', 'Ferrous sulfate 325mg for anemia', 350, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(82, 'METFORMIN-500', 'Glucophage', 'Metformin', 'Tablet', '500mg', 'tablets', 'Metformin 500mg for type 2 diabetes', 600, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(83, 'GLIMEPIRIDE-2', 'Amaryl', 'Glimepiride', 'Tablet', '2mg', 'tablets', 'Glimepiride 2mg sulfonylurea', 400, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(84, 'DICLOFENAC-50', 'Voltaren', 'Diclofenac', 'Tablet', '50mg', 'tablets', 'Diclofenac 50mg NSAID', 300, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(85, 'NAPROXEN-250', 'Naprosyn', 'Naproxen', 'Tablet', '250mg', 'tablets', 'Naproxen 250mg anti-inflammatory', 280, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(86, 'LEVOTHYROX-50', 'Synthroid', 'Levothyroxine', 'Tablet', '50mcg', 'tablets', 'Levothyroxine 50mcg for hypothyroidism', 450, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(87, 'WARFARIN-5', 'Coumadin', 'Warfarin', 'Tablet', '5mg', 'tablets', 'Warfarin 5mg anticoagulant', 250, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(88, 'ASPIRIN-100', 'Cardiprin', 'Aspirin', 'Tablet', '100mg', 'tablets', 'Aspirin 100mg low-dose for heart', 600, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(89, 'TUMS-ANTACID', 'TUMS', 'Calcium Carbonate', 'Tablet', '500mg', 'tablets', 'Antacid tablets for heartburn', 400, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(90, 'MAALOX-SUSP', 'Maalox', 'Aluminum/Magnesium', 'Suspension', 'Liquid', 'bottles', 'Maalox suspension for acid reflux', 120, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(91, 'ONDANSETRON-4', 'Zofran', 'Ondansetron', 'Tablet', '4mg', 'tablets', 'Ondansetron 4mg for nausea', 200, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(92, 'METOCLOPRAMIDE-10', 'Reglan', 'Metoclopramide', 'Tablet', '10mg', 'tablets', 'Metoclopramide 10mg anti-nausea', 180, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(93, 'INSULIN-VIAL', 'Humulin', 'Insulin', 'Injection', '100IU/ml', 'vials', 'Insulin vial for diabetes injection', 80, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL),
(94, 'PENICILLIN-INJ', 'Penicillin G', 'Penicillin', 'Injection', '1MU', 'vials', 'Penicillin G injection antibiotic', 100, '2026-02-19 02:30:11', '2026-02-19 02:30:11', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `po_no` varchar(40) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'draft',
  `order_date` date NOT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `total_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `terms` varchar(120) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `received_qty` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `unit_cost` decimal(12,2) NOT NULL,
  `line_total` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requests`
--

CREATE TABLE `purchase_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_no` varchar(40) NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'draft',
  `requested_at` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_request_items`
--

CREATE TABLE `purchase_request_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_request_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `requested_qty` int(10) UNSIGNED NOT NULL,
  `approved_qty` int(10) UNSIGNED DEFAULT NULL,
  `unit_cost_estimate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receivings`
--

CREATE TABLE `receivings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receiving_no` varchar(40) NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `received_by` bigint(20) UNSIGNED NOT NULL,
  `received_at` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receiving_items`
--

CREATE TABLE `receiving_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receiving_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `received_qty` int(10) UNSIGNED NOT NULL,
  `batch_no` varchar(60) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(40) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'System administrator', '2026-02-19 02:08:07', NULL),
(2, 'Employee', 'Pharmacy operations employee', '2026-02-19 02:08:07', NULL),
(3, 'IT Dev/Staff', 'Technical support and developers', '2026-02-19 02:08:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `full_name`, `email`, `password_hash`, `is_active`, `last_login_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'System Admin', 'admin@pharmacy.local', '$2y$10$txCF3PEP0Uc8AxUTZJ.G4OJNtcvN.Fdcrq7dHeOmC.sTlDKvWaQmC', 1, '2026-02-19 02:23:48', '2026-02-19 02:08:07', '2026-02-19 02:23:48', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workflow_type_reference_id` (`workflow_type`,`reference_id`),
  ADD KEY `approver_id_status` (`approver_id`,`status`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entity_type_entity_id` (`entity_type`,`entity_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `action` (`action`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_movements_created_by_foreign` (`created_by`),
  ADD KEY `product_id_movement_at` (`product_id`,`movement_at`),
  ADD KEY `reference_type_reference_id` (`reference_type`,`reference_id`);

--
-- Indexes for table `inventory_stocks`
--
ALTER TABLE `inventory_stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`);

--
-- Indexes for table `issuances`
--
ALTER TABLE `issuances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `issuance_no` (`issuance_no`),
  ADD KEY `issued_by_status` (`issued_by`,`status`);

--
-- Indexes for table `issuance_items`
--
ALTER TABLE `issuance_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issuance_items_product_id_foreign` (`product_id`),
  ADD KEY `issuance_id_product_id` (`issuance_id`,`product_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `po_requests`
--
ALTER TABLE `po_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `request_no` (`request_no`),
  ADD KEY `po_requests_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `requested_by_status` (`requested_by`,`status`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `brand_name` (`brand_name`),
  ADD KEY `generic_name` (`generic_name`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `po_no` (`po_no`),
  ADD KEY `purchase_orders_approved_by_foreign` (`approved_by`),
  ADD KEY `supplier_id_status` (`supplier_id`,`status`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_items_product_id_foreign` (`product_id`),
  ADD KEY `purchase_order_id_product_id` (`purchase_order_id`,`product_id`);

--
-- Indexes for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `request_no` (`request_no`),
  ADD KEY `requested_by_status` (`requested_by`,`status`);

--
-- Indexes for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_request_items_product_id_foreign` (`product_id`),
  ADD KEY `purchase_request_id_product_id` (`purchase_request_id`,`product_id`);

--
-- Indexes for table `receivings`
--
ALTER TABLE `receivings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receiving_no` (`receiving_no`),
  ADD KEY `receivings_received_by_foreign` (`received_by`),
  ADD KEY `purchase_order_id_status` (`purchase_order_id`,`status`);

--
-- Indexes for table `receiving_items`
--
ALTER TABLE `receiving_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receiving_items_product_id_foreign` (`product_id`),
  ADD KEY `receiving_id_product_id` (`receiving_id`,`product_id`),
  ADD KEY `expiry_date` (`expiry_date`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `is_active` (`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_stocks`
--
ALTER TABLE `inventory_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `issuances`
--
ALTER TABLE `issuances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issuance_items`
--
ALTER TABLE `issuance_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `po_requests`
--
ALTER TABLE `po_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receivings`
--
ALTER TABLE `receivings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receiving_items`
--
ALTER TABLE `receiving_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approvals`
--
ALTER TABLE `approvals`
  ADD CONSTRAINT `approvals_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inventory_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `inventory_stocks`
--
ALTER TABLE `inventory_stocks`
  ADD CONSTRAINT `inventory_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `issuances`
--
ALTER TABLE `issuances`
  ADD CONSTRAINT `issuances_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `issuance_items`
--
ALTER TABLE `issuance_items`
  ADD CONSTRAINT `issuance_items_issuance_id_foreign` FOREIGN KEY (`issuance_id`) REFERENCES `issuances` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `issuance_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `po_requests`
--
ALTER TABLE `po_requests`
  ADD CONSTRAINT `po_requests_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `po_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD CONSTRAINT `purchase_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  ADD CONSTRAINT `purchase_request_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_request_items_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receivings`
--
ALTER TABLE `receivings`
  ADD CONSTRAINT `receivings_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `receivings_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `receiving_items`
--
ALTER TABLE `receiving_items`
  ADD CONSTRAINT `receiving_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `receiving_items_receiving_id_foreign` FOREIGN KEY (`receiving_id`) REFERENCES `receivings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
