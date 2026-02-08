-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2026 at 01:28 PM
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
-- Database: `rupchorcha_backend`
--

-- --------------------------------------------------------

--
-- Table structure for table `abandoned_checkouts`
--

CREATE TABLE `abandoned_checkouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `cart_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`cart_data`)),
  `started_at` timestamp NULL DEFAULT NULL,
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `recovered_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'abandoned',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `line1` varchar(255) NOT NULL,
  `line2` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_values`
--

CREATE TABLE `attribute_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attribute_id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `image`, `banner_image`, `created_at`, `updated_at`) VALUES
(2, 'Boots', 'boots', NULL, NULL, '2025-12-30 22:52:54', '2025-12-30 22:52:54'),
(3, 'CareNel', 'carenel', NULL, NULL, '2026-01-07 22:55:07', '2026-01-07 22:55:07'),
(4, 'Cetaphil', 'cetaphil', NULL, 'brands/banners/Jz3vEoNwwO4BXlkPCq79LnNkEMseEGajpDEyFnv7.png', '2026-01-07 22:56:59', '2026-01-25 05:26:16'),
(5, 'COSRX', 'cosrx', NULL, NULL, '2026-01-07 22:57:15', '2026-01-07 22:57:15'),
(6, 'Anua', 'anua', NULL, NULL, '2026-01-07 22:57:27', '2026-01-07 22:57:27'),
(7, 'Simple', 'simple', NULL, NULL, '2026-01-07 22:57:34', '2026-01-07 22:57:34'),
(8, 'Christian Dean', 'christian-dean', NULL, NULL, '2026-01-12 23:09:58', '2026-01-12 23:09:58'),
(11, 'Celimax', 'celimax', NULL, NULL, '2026-01-12 23:14:34', '2026-01-12 23:14:34'),
(12, 'Colormax', 'colormax', NULL, NULL, '2026-01-12 23:15:44', '2026-01-12 23:15:44'),
(13, 'Axis-Y', 'axis-y', 'brands/6SxSLdn4ORR1ueIskcgF7Uj9mzdaScluJPaTIEtF.jpg', 'brands/banners/hlpsokNjI6M1S9iFnVfYOXF3R05sl06EcrK5xU09.png', '2026-01-13 04:51:57', '2026-01-25 05:23:01');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_histories`
--

CREATE TABLE `campaign_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `recipient_count` int(11) NOT NULL,
  `recipients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`recipients`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `session_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 'e8210df7422f4df4d7bd9ad9c4d14b44968a3f8a8579fbd3a297b7aa18e78e03', '2026-01-22 02:26:30', '2026-01-22 02:26:30'),
(2, NULL, '296f4ba33328eea86f699ee060e66afb9d040e2e50167f556c9faa9c0ec7df4d', '2026-01-26 05:29:26', '2026-01-26 05:29:26');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(14, 2, 95, 1, '2026-01-26 05:29:26', '2026-01-26 05:29:26');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `parent_id`, `description`, `banner_image`, `created_at`, `updated_at`) VALUES
(1, 'Skin Care', 'skin-care', NULL, 'This is mother category', NULL, '2025-12-30 22:53:44', '2025-12-30 22:53:44'),
(2, 'Body', 'body', 1, NULL, NULL, '2025-12-30 23:02:49', '2025-12-30 23:05:07'),
(3, 'Makeup', 'makeup', NULL, NULL, NULL, '2025-12-30 23:03:03', '2025-12-30 23:03:03'),
(4, 'Face', 'face', 3, NULL, NULL, '2025-12-30 23:04:39', '2025-12-30 23:04:39'),
(5, 'Hair', 'hair', NULL, NULL, NULL, '2025-12-30 23:05:23', '2025-12-30 23:05:23'),
(6, 'Hair Care', 'hair-care', 5, NULL, NULL, '2025-12-30 23:05:39', '2025-12-30 23:05:39'),
(7, 'Shop By Concern', 'shop-by-concern', NULL, NULL, NULL, '2025-12-30 23:05:57', '2025-12-30 23:05:57'),
(8, 'Acne Treatment', 'acne-treatment', 7, NULL, NULL, '2025-12-30 23:06:18', '2025-12-30 23:06:18'),
(9, 'Skin Concern', 'skin-concern', 7, NULL, NULL, '2025-12-30 23:06:54', '2025-12-30 23:06:54');

-- --------------------------------------------------------

--
-- Table structure for table `category_product`
--

CREATE TABLE `category_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_product`
--

INSERT INTO `category_product` (`id`, `product_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 1, NULL, NULL),
(3, 3, 1, NULL, NULL),
(4, 4, 1, NULL, NULL),
(5, 5, 1, NULL, NULL),
(6, 6, 1, NULL, NULL),
(7, 7, 1, NULL, NULL),
(8, 8, 1, NULL, NULL),
(9, 9, 1, NULL, NULL),
(10, 10, 1, NULL, NULL),
(11, 93, 2, NULL, NULL),
(12, 74, 1, NULL, NULL),
(13, 95, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` enum('fixed','percent','free_shipping') NOT NULL DEFAULT 'fixed',
  `value` decimal(15,2) DEFAULT NULL,
  `max_discount` decimal(15,2) DEFAULT NULL,
  `min_order_amount` decimal(15,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `usage_limit_per_user` int(11) DEFAULT NULL,
  `start_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `product_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`product_ids`)),
  `category_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`category_ids`)),
  `brand_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`brand_ids`)),
  `user_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`user_ids`)),
  `first_time_customer_only` tinyint(1) NOT NULL DEFAULT 0,
  `exclude_sale_items` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `max_discount`, `min_order_amount`, `usage_limit`, `usage_limit_per_user`, `start_at`, `expires_at`, `active`, `product_ids`, `category_ids`, `brand_ids`, `user_ids`, `first_time_customer_only`, `exclude_sale_items`, `created_at`, `updated_at`) VALUES
(2, 'new', 'percent', 10.00, NULL, NULL, 10, 1, '2026-01-20 18:00:00', '2026-01-28 18:00:00', 1, NULL, NULL, NULL, '[null]', 0, 0, '2026-01-22 00:17:17', '2026-01-22 00:20:55'),
(3, 'SAVE10', 'percent', 10.00, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 0, 0, '2026-01-22 06:26:28', '2026-01-22 06:26:28'),
(5, 'FREESHIP2K', 'free_shipping', NULL, NULL, 2000.00, NULL, NULL, '2026-01-22 06:59:16', '2026-04-22 06:59:16', 1, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(6, 'FREESHIP1500', 'free_shipping', NULL, NULL, 1500.00, NULL, NULL, '2026-01-22 06:59:23', '2026-02-22 06:59:23', 1, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(7, 'CETAPHIL20', 'percent', 20.00, NULL, 1500.00, NULL, NULL, '2026-01-20 18:00:00', '2026-03-30 18:00:00', 1, NULL, NULL, '[\"4\"]', '[null]', 0, 0, NULL, '2026-01-28 05:51:25'),
(8, 'sell10', 'percent', 10.00, NULL, NULL, NULL, NULL, '2026-01-26 18:00:00', '2026-01-29 18:00:00', 1, NULL, NULL, NULL, '[null]', 0, 0, '2026-01-28 06:11:23', '2026-01-28 06:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `couriers`
--

CREATE TABLE `couriers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tracking_url` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `logo` varchar(255) DEFAULT NULL,
  `service_area` varchar(255) DEFAULT NULL,
  `delivery_types` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `couriers`
--

INSERT INTO `couriers` (`id`, `name`, `api_key`, `contact_number`, `email`, `tracking_url`, `status`, `logo`, `service_area`, `delivery_types`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Pahtao', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '2026-01-28 03:23:42', '2026-01-28 03:23:42');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` enum('bogo','combo','category','brand','product') NOT NULL,
  `product_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`product_ids`)),
  `combo_product_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`combo_product_ids`)),
  `category_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`category_ids`)),
  `brand_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`brand_ids`)),
  `discount_value` decimal(10,2) DEFAULT NULL,
  `discount_type` enum('percent','fixed') NOT NULL DEFAULT 'percent',
  `min_quantity` int(11) DEFAULT NULL,
  `start_at` date DEFAULT NULL,
  `expires_at` date DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `title`, `type`, `product_ids`, `combo_product_ids`, `category_ids`, `brand_ids`, `discount_value`, `discount_type`, `min_quantity`, `start_at`, `expires_at`, `active`, `created_at`, `updated_at`) VALUES
(1, 'COSRX Brand Sale', 'brand', NULL, NULL, NULL, '[5]', 15.00, 'percent', NULL, '2026-01-22', '2026-02-28', 1, NULL, NULL),
(2, 'Skin Care Discount', 'category', NULL, NULL, '[1,4]', NULL, 10.00, 'percent', NULL, NULL, NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `discount_conditions`
--

CREATE TABLE `discount_conditions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('product','brand','category') NOT NULL,
  `target_id` bigint(20) UNSIGNED NOT NULL,
  `discount_type` enum('percentage','fixed','free_shipping') NOT NULL,
  `discount_value` decimal(8,2) DEFAULT NULL,
  `free_shipping` tinyint(1) NOT NULL DEFAULT 0,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discount_conditions`
--

INSERT INTO `discount_conditions` (`id`, `type`, `target_id`, `discount_type`, `discount_value`, `free_shipping`, `notes`, `created_at`, `updated_at`) VALUES
(4, 'brand', 5, 'free_shipping', NULL, 1, 'COSRX products get free shipping', NULL, NULL),
(5, 'category', 1, 'free_shipping', NULL, 1, 'Skin Care products free shipping', NULL, NULL),
(6, 'category', 3, 'free_shipping', NULL, 1, 'Makeup category free shipping', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL,
  `status` enum('draft','unpaid','sent','paid','cancelled') NOT NULL DEFAULT 'draft',
  `issued_at` timestamp NULL DEFAULT NULL,
  `due_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `order_id`, `invoice_number`, `amount`, `discount`, `tax`, `total`, `status`, `issued_at`, `due_at`, `paid_at`, `notes`, `meta`, `created_at`, `updated_at`) VALUES
(1, 3, 'INV-2026010001', 2090.00, 0.00, 313.50, 2553.50, 'unpaid', '2026-01-22 02:57:20', '2026-02-21 02:57:20', NULL, NULL, '{\"customer_name\":\"monir\",\"customer_email\":\"pathao@gmail.com\",\"customer_phone\":\"01856958740\",\"shipping_address\":\"Jalkuri, Shiddhirganj, Narayanganj\",\"payment_method\":\"cod\",\"items_count\":1}', '2026-01-22 02:57:20', '2026-01-22 02:58:24'),
(2, 4, 'INV-2026010002', 2090.00, 0.00, 313.50, 2523.50, 'draft', '2026-01-25 05:39:00', '2026-02-24 05:39:00', NULL, NULL, '{\"customer_name\":\"urmi\",\"customer_email\":\"samina@gmail.com\",\"customer_phone\":\"01756589873\",\"shipping_address\":\"MirHazir Bagh\",\"payment_method\":\"cod\",\"items_count\":1}', '2026-01-25 05:39:00', '2026-01-25 05:39:00');

-- --------------------------------------------------------

--
-- Table structure for table `marketing_campaigns`
--

CREATE TABLE `marketing_campaigns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `recipient_count` int(11) NOT NULL,
  `recipients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`recipients`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_12_24_000000_create_products_table', 1),
(6, '2025_12_24_000001_create_categories_table', 1),
(7, '2025_12_24_000003_create_attributes_table', 1),
(8, '2025_12_24_000004_create_attribute_values_table', 1),
(9, '2025_12_24_000005_create_product_attribute_values_table', 1),
(10, '2025_12_24_000006_create_tags_table', 1),
(11, '2025_12_24_000007_create_product_tag_table', 1),
(12, '2025_12_24_000008_create_product_images_table', 1),
(13, '2025_12_29_235959_create_addresses_table', 1),
(14, '2025_12_30_000001_create_coupons_table', 1),
(15, '2025_12_30_060000_create_brands_table', 1),
(16, '2025_12_30_100000_create_orders_table', 1),
(19, '2025_12_30_100040_create_payments_table', 1),
(20, '2025_12_30_200000_create_discounts_table', 2),
(21, '2025_12_30_210000_add_active_to_users_table', 3),
(22, '2025_12_30_220000_create_user_activity_logs_table', 4),
(23, '2026_01_01_000001_create_permissions_table', 5),
(24, '2026_01_01_000002_create_role_permission_table', 5),
(25, '2026_01_01_100000_create_stock_movements_table', 6),
(26, '2026_01_02_100000_create_suppliers_table', 7),
(27, '2026_01_02_100001_create_warehouses_table', 7),
(28, '2026_01_02_100002_add_supplier_and_warehouse_to_products', 7),
(29, '2026_01_02_100003_add_supplier_and_warehouse_to_stock_movements', 7),
(30, '2026_01_01_000001_create_purchase_orders_table', 8),
(31, '2026_01_01_000002_create_purchase_order_items_table', 8),
(32, '2026_01_02_100004_add_warehouse_to_purchase_orders', 9),
(33, '2026_01_02_100005_update_purchase_order_status_enum', 9),
(34, '2026_01_02_100006_add_audit_fields_to_purchase_orders', 9),
(35, '2026_01_02_100007_create_purchase_order_status_histories_table', 10),
(36, '2026_01_01_000000_create_shipping_tables', 11),
(37, '2026_01_02_100008_add_received_quantity_to_purchase_order_items', 11),
(38, '2026_01_01_200000_create_shipping_method_conditions_table', 12),
(39, '2026_01_01_114947_create_shipping_methods_table', 13),
(40, '2026_01_01_114948_create_shipping_zones_table', 14),
(41, '2026_01_04_000000_create_discount_conditions_table', 15),
(50, '2026_01_05_130000_create_feedbacks_table', 22),
(56, '2026_01_06_000002_create_reviews_table', 28),
(57, '2026_01_04_000001_create_payment_gateways_table', 29),
(58, '2026_01_04_000002_create_transactions_table', 30),
(59, '2026_01_04_000003_create_refunds_table', 31),
(60, '2026_01_04_100000_create_couriers_table', 32),
(61, '2026_01_05_000001_create_campaign_histories_table', 33),
(62, '2026_01_05_000002_create_marketing_campaigns_table', 34),
(63, '2026_01_05_100000_create_segments_table', 35),
(64, '2026_01_05_120000_create_product_reviews_table', 36),
(65, '2026_01_06_000011_create_orders_table', 37),
(66, '2026_01_15_000001_create_carts_table', 38),
(67, '2026_01_18_000001_create_category_product_table', 39),
(68, '2026_01_18_120251_create_wishlists_table', 40),
(69, '2026_01_19_094745_add_otp_fields_to_users', 41),
(70, '2026_01_19_102248_add_google_id_to_users_table', 42),
(71, '2025_12_30_100010_create_order_items_table', 43),
(72, '2026_01_22_000000_create_payments_table', 44),
(73, '2026_01_22_000001_create_order_status_histories_table', 45),
(74, '2026_01_22_000002_create_refunds_table', 46),
(75, '2026_01_22_000003_add_fields_to_orders_table', 47),
(76, '2026_01_22_000004_add_previous_status_to_order_status_histories_table', 48),
(77, '2026_01_22_000005_create_invoices_table', 49),
(78, '2026_01_22_000006_create_packing_slips_table', 50),
(79, '2025_01_25_add_brand_banner_to_brands_table', 51),
(80, '2025_01_25_add_category_banner_to_categories_table', 52),
(81, '2026_01_26_000000_create_abandoned_checkouts_table', 53),
(82, '2026_01_26_111600_add_email_verified_at_to_users_table', 54),
(83, '2026_01_26_warehouse_table', 55),
(84, '2025_12_30_100030_create_order_status_histories_table', 56),
(86, '2026_01_28_092536_add_previous_status_to_orders_table', 57),
(87, '2026_01_28_200000_add_created_by_to_orders_table', 58);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `shipping_address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `billing_address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `customer_notes` longtext DEFAULT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `invoice_sent_at` timestamp NULL DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `shipping_method` varchar(255) DEFAULT NULL,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `coupon_code` varchar(255) DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(12,2) DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `previous_status` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `transaction_id` varchar(255) DEFAULT NULL,
  `courier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `created_by`, `shipping_address_id`, `billing_address_id`, `customer_name`, `customer_email`, `customer_phone`, `shipping_address`, `city`, `area`, `notes`, `customer_notes`, `invoice_number`, `invoice_sent_at`, `payment_method`, `shipping_method`, `shipping_cost`, `coupon_code`, `discount_amount`, `total`, `grand_total`, `status`, `previous_status`, `payment_status`, `transaction_id`, `courier_id`, `tracking_number`, `admin_note`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, NULL, 'Badhan Islam', 'badhon@gmail.com', '01758685473', 'KB Square Dhanmondi Dhaka.', 'Dhaka', 'Dhaka Sadar', NULL, NULL, NULL, NULL, 'cod', 'inside_dhaka', 60.00, NULL, 0.00, 2150.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-22 02:30:58', '2026-01-22 02:30:58'),
(2, NULL, NULL, NULL, NULL, 'Badhan Islam', 'monir@gmail.com', '01948283811', 'KB Square Dhanmondi Dhaka.', 'Dhaka', 'Dhaka Sadar', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'inside_dhaka', 60.00, NULL, 0.00, 2150.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-22 02:34:03', '2026-01-22 02:34:03'),
(3, NULL, NULL, NULL, NULL, 'monir', 'pathao@gmail.com', '01856958740', 'Jalkuri, Shiddhirganj, Narayanganj', 'Narayanganj', 'Narayanganj Sadar', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 150.00, NULL, 0.00, 2240.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-22 02:35:59', '2026-01-22 02:35:59'),
(4, NULL, NULL, NULL, NULL, 'urmi', 'samina@gmail.com', '01756589873', 'MirHazir Bagh', 'Chattogram', 'Chawkbazar', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 2210.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-22 03:54:43', '2026-01-22 03:54:43'),
(5, NULL, NULL, NULL, NULL, 'Tisha', 'tisha@gmail.com', '01584788500', 'Dhanmondi Dhaka', 'Cox’s Bazar', 'Maheshkhali', 'Test order', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 5469.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-25 05:42:39', '2026-01-25 05:42:39'),
(6, NULL, NULL, NULL, NULL, 'Salman Khan', 'samina@gmail.com', '01948283812', 'Komuna', 'Dinajpur', 'Kaharole', 'Test order', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 5158.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-26 03:51:27', '2026-01-26 03:51:27'),
(7, NULL, NULL, NULL, NULL, 'Tisha', 'tisha@gmail.com', '01948283811', 'KB Square Dhanmondi Dhaka.', 'Dinajpur', 'Nawabganj', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 719.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-26 04:00:19', '2026-01-26 04:00:19'),
(8, NULL, NULL, NULL, NULL, 'Salman Khan', 'samina@gmail.com', '01948283811', 'KB Square Dhanmondi Dhaka.', 'Dhaka', 'Dhamrai', NULL, NULL, NULL, NULL, 'cod', 'Flat Rate', 60.00, NULL, 0.00, 1060.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-26 04:04:16', '2026-01-26 04:04:16'),
(9, NULL, NULL, NULL, NULL, 'urmi', 'tisha@gmail.com', '01948283811', 'Komuna', 'Brahmanbaria', 'Nabinagar', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 305.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-26 04:06:56', '2026-01-26 04:06:56'),
(10, NULL, NULL, NULL, NULL, 'Tisha', 'badhon@gmail.com', '01948283811', 'Komuna', 'Chuadanga', 'Damurhuda', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 719.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-26 04:08:01', '2026-01-26 04:08:01'),
(11, NULL, NULL, NULL, NULL, 'Salman Khan', 'tisha@gmail.com', '01948283811', 'Komuna', 'Cox’s Bazar', 'Ukhiya', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 569.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-26 04:11:03', '2026-01-26 04:11:03'),
(12, NULL, NULL, NULL, NULL, 'Badhan Islam', 'badhon@gmail.com', '01758685473', 'KB Square Dhanmondi Dhaka.', 'Chandpur', 'Matlab South', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 7990.00, 0.00, 'complete', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-26 04:14:15', '2026-01-27 03:00:18'),
(13, NULL, NULL, NULL, NULL, 'urmi', 'monir@gmail.com', '01758685473', 'Dhanmondi Dhaka', 'Dinajpur', 'Birampur', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 565.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-26 04:54:41', '2026-01-26 04:54:41'),
(14, NULL, NULL, NULL, NULL, 'Badhan Islam', 'monir@gmail.com', '01948283811', 'KB Square Dhanmondi Dhaka.', 'Chuadanga', 'Chuadanga Sadar', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 1120.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-26 04:55:33', '2026-01-26 04:55:33'),
(15, NULL, NULL, NULL, NULL, 'Bella Monir', 'bellamonir33@gmail.com', '01948283811', 'KB Square Dhanmondi Dhaka.', 'Jamalpur', 'Madarganj', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 1120.00, 0.00, 'complete', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-26 05:05:26', '2026-01-27 05:29:27'),
(16, NULL, NULL, NULL, NULL, 'bellamonir33', 'bellamonir33@gmail.com', '01948283811', 'Dhanmondi Dhaka', 'Barguna', 'Amtali', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 2565.00, 0.00, 'complete', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-26 05:15:15', '2026-01-27 05:22:52'),
(17, 4, NULL, NULL, NULL, 'Badhan Islam', 'tisha@gmail.com', '01948283811', 'Dhanmondi Dhaka', 'Bandarban', 'Thanchi', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 1120.00, 0.00, 'complete', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-26 05:22:03', '2026-01-27 05:19:35'),
(18, 11, NULL, NULL, NULL, 'aamn', 'info.rupchorcha@gmail.com', '01844630485', 'KB Square, 753 Satmasjid Road, Dhaka', 'Dhaka', 'Dhaka Sadar', NULL, NULL, NULL, NULL, 'cod', 'Flat Rate', 60.00, NULL, 0.00, 1060.00, 0.00, 'complete', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-26 05:29:49', '2026-01-27 03:13:03'),
(19, 4, NULL, NULL, NULL, 'Badhan Islam', 'tisha@gmail.com', '01584788508', 'Komuna', 'Gazipur', 'Tongi', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 3565.00, 0.00, 'pending', NULL, 'unpaid', NULL, 1, NULL, NULL, '2026-01-27 02:51:47', '2026-01-28 03:26:32'),
(20, 12, NULL, NULL, NULL, 'Lisa', 'lisa@gmail.com', '01951318685', 'Dhanmondi', 'Bhola', 'Bhola Sadar', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 150.00, NULL, 0.00, 1700.00, 1850.00, 'completed', NULL, 'unpaid', NULL, 1, NULL, NULL, '2026-01-28 02:51:34', '2026-01-29 01:11:02'),
(21, 9, NULL, NULL, NULL, 'monir', 'lisamonir@gmail.com', '01951318685', 'Dhanmondi', 'Bagerhat', 'Bagerhat Sadar', NULL, NULL, NULL, NULL, 'cod', 'outside_dhaka', 150.00, NULL, 0.00, 7800.00, 7950.00, 'completed', NULL, 'unpaid', NULL, 1, NULL, NULL, '2026-01-28 03:37:35', '2026-01-28 03:54:21'),
(22, 4, NULL, NULL, NULL, 'urmi', 'badhon@gmail.com', '01948283811', 'KB Square Dhanmondi Dhaka.', 'Jashore', 'Keshabpur', NULL, NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 1499.00, 1619.00, 'complete', NULL, 'paid', NULL, 1, NULL, NULL, '2026-01-28 04:48:40', '2026-01-28 05:44:47'),
(23, 10, 1, NULL, NULL, 'Lisa', 'lisamonir@gmail.com', '01951318685', 'Dhanmondi', 'Chuadanga', 'Alamdanga', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 150.00, NULL, 0.00, 1300.00, 1450.00, 'completed', NULL, 'paid', NULL, 1, NULL, NULL, '2026-01-29 00:48:04', '2026-01-29 01:10:16'),
(24, 4, 1, NULL, NULL, 'monir', 'lisamonir@gmail.com', '01951318685', 'Dhanmondi', 'Cox\'s Bazar', 'Cox\'s Bazar Sadar', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 150.00, NULL, 0.00, 1300.00, 1300.00, 'pending', NULL, 'paid', NULL, NULL, NULL, NULL, '2026-01-29 00:53:57', '2026-01-29 00:53:57'),
(25, 4, NULL, NULL, NULL, 'urmi', 'badhon@gmail.com', '01948283811', 'Komuna', 'Barguna', 'Barguna Sadar', NULL, NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 4730.00, 4850.00, 'completed', NULL, 'paid', NULL, 1, NULL, NULL, '2026-01-29 01:00:19', '2026-01-29 01:09:07'),
(26, 14, NULL, NULL, NULL, 'Badhan Islam', 'badhanislam@gmail.com', '01440256987', 'Bashundhara', 'Dhaka', 'Dhaka Sadar', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'Flat Rate', 60.00, NULL, 0.00, 599.00, 659.00, 'completed', NULL, 'unpaid', NULL, 1, NULL, NULL, '2026-01-29 01:13:13', '2026-01-29 01:16:00'),
(27, 14, NULL, NULL, NULL, 'Badhan Islam', 'tisha@gmail.com', '01584788500', 'KB Square Dhanmondi Dhaka.', 'Gaibandha', 'Sadullapur', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 6193.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-29 01:33:10', '2026-01-29 01:33:10'),
(28, 4, NULL, NULL, NULL, 'Badhan Islam', 'tisha@gmail.com', '01584788500', 'Dhanmondi Dhaka', 'Barishal', 'Babuganj', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 569.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-29 01:44:06', '2026-01-29 01:44:06'),
(29, NULL, NULL, NULL, NULL, 'Tisha', 'badhon@gmail.com', '01584788502', 'KB Square Dhanmondi Dhaka.', 'Habiganj', 'Lakhai', 'This is guest user order', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 850.00, 970.00, 'completed', NULL, 'paid', NULL, 1, NULL, NULL, '2026-01-29 02:33:59', '2026-01-29 02:41:30'),
(30, NULL, NULL, NULL, NULL, 'bellamonir33', 'bellamonir33@gmail.com', '01440256987', 'Dhanmondi Dhaka', 'Gaibandha', 'Gaibandha Sadar', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 1465.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-01-29 02:40:19', '2026-01-29 02:40:19'),
(31, NULL, NULL, NULL, NULL, 'Bella Monir', 'bellamonir33@gmail.com', '01948283811', 'Komuna', 'Chuadanga', 'Alamdanga', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 2685.00, 2805.00, 'completed', NULL, 'paid', NULL, 1, NULL, NULL, '2026-02-02 02:28:52', '2026-02-02 02:30:13'),
(32, NULL, NULL, NULL, NULL, 'urmi', 'bellamonir33@gmail.com', '01584788504', 'Komuna', 'Barguna', 'Amtali', 'This is test Pathao', NULL, NULL, NULL, 'cod', 'outside_dhaka', 120.00, NULL, 0.00, 7090.00, 0.00, 'pending', NULL, 'unpaid', NULL, NULL, NULL, NULL, '2026-02-02 02:41:30', '2026-02-02 02:41:30');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `variant` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `variant`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'CareNel Cicavita B5 Salicylic Acid Gentle Cleanser 150ml', 1045.00, 2, NULL, '2026-01-22 02:34:03', '2026-01-22 02:34:03'),
(2, 3, 2, 'CareNel Cicavita B5 Salicylic Acid Gentle Cleanser 150ml', 1045.00, 2, NULL, '2026-01-22 02:35:59', '2026-01-22 02:35:59'),
(3, 4, 2, 'CareNel Cicavita B5 Salicylic Acid Gentle Cleanser 150ml', 1045.00, 2, NULL, '2026-01-22 03:54:43', '2026-01-22 03:54:43'),
(4, 5, 74, 'Anua Heartleaf Pore Control Cleansing Oil 20ml', 449.00, 1, NULL, '2026-01-25 05:42:39', '2026-01-25 05:42:39'),
(5, 5, 4, 'CareNel Cicavita B5 Tranexamic Acid Toner 155ml', 1495.00, 1, NULL, '2026-01-25 05:42:39', '2026-01-25 05:42:39'),
(6, 5, 2, 'CareNel Cicavita B5 Salicylic Acid Gentle Cleanser 150ml', 1045.00, 1, NULL, '2026-01-25 05:42:39', '2026-01-25 05:42:39'),
(7, 5, 3, 'CareNel Derma Alpha Arbutin Glutathione Whitening Cream 45ml', 1180.00, 2, NULL, '2026-01-25 05:42:39', '2026-01-25 05:42:39'),
(8, 6, 3, 'CareNel Derma Alpha Arbutin Glutathione Whitening Cream 45ml', 1180.00, 3, NULL, '2026-01-26 03:51:27', '2026-01-26 03:51:27'),
(9, 6, 9, 'CareNel Anti-Melasma Cica Cream 40ml', 1049.00, 1, NULL, '2026-01-26 03:51:27', '2026-01-26 03:51:27'),
(10, 6, 74, 'Anua Heartleaf Pore Control Cleansing Oil 20ml', 449.00, 1, NULL, '2026-01-26 03:51:27', '2026-01-26 03:51:27'),
(11, 7, 93, 'Simple Hydrating Light Moisturiser 125ml', 599.00, 1, NULL, '2026-01-26 04:00:19', '2026-01-26 04:00:19'),
(12, 8, 95, 'Boots Sakura Bright Moisturising Cream 50 mll', 1000.00, 1, NULL, '2026-01-26 04:04:16', '2026-01-26 04:04:16'),
(13, 9, 91, 'AXIS-Y Dark Spot Correcting Glow Serum 5ml', 185.00, 1, NULL, '2026-01-26 04:06:56', '2026-01-26 04:06:56'),
(14, 10, 93, 'Simple Hydrating Light Moisturiser 125ml', 599.00, 1, NULL, '2026-01-26 04:08:01', '2026-01-26 04:08:01'),
(15, 11, 74, 'Anua Heartleaf Pore Control Cleansing Oil 20ml', 449.00, 1, NULL, '2026-01-26 04:11:03', '2026-01-26 04:11:03'),
(16, 12, 3, 'CareNel Derma Alpha Arbutin Glutathione Whitening Cream 45ml', 1180.00, 3, NULL, '2026-01-26 04:14:15', '2026-01-26 04:14:15'),
(17, 12, 9, 'CareNel Anti-Melasma Cica Cream 40ml', 1049.00, 1, NULL, '2026-01-26 04:14:15', '2026-01-26 04:14:15'),
(18, 12, 74, 'Anua Heartleaf Pore Control Cleansing Oil 20ml', 449.00, 2, NULL, '2026-01-26 04:14:15', '2026-01-26 04:14:15'),
(19, 12, 93, 'Simple Hydrating Light Moisturiser 125ml', 599.00, 2, NULL, '2026-01-26 04:14:15', '2026-01-26 04:14:15'),
(20, 12, 95, 'Boots Sakura Bright Moisturising Cream 50 mll', 1000.00, 1, NULL, '2026-01-26 04:14:15', '2026-01-26 04:14:15'),
(21, 12, 91, 'AXIS-Y Dark Spot Correcting Glow Serum 5ml', 185.00, 1, NULL, '2026-01-26 04:14:15', '2026-01-26 04:14:15'),
(22, 13, 7, 'CareNel Apricot Lip Night Mask 5g', 445.00, 1, NULL, '2026-01-26 04:54:41', '2026-01-26 04:54:41'),
(23, 14, 95, 'Boots Sakura Bright Moisturising Cream 50 ml', 1000.00, 1, NULL, '2026-01-26 04:55:33', '2026-01-26 04:55:33'),
(24, 15, 95, 'Boots Sakura Bright Moisturising Cream 50 ml', 1000.00, 1, NULL, '2026-01-26 05:05:26', '2026-01-26 05:05:26'),
(25, 16, 7, 'CareNel Apricot Lip Night Mask 5g', 445.00, 1, NULL, '2026-01-26 05:15:15', '2026-01-26 05:15:15'),
(26, 16, 95, 'Boots Sakura Bright Moisturising Cream 50 ml', 1000.00, 2, NULL, '2026-01-26 05:15:15', '2026-01-26 05:15:15'),
(27, 17, 95, 'Boots Sakura Bright Moisturising Cream 50 ml', 1000.00, 1, NULL, '2026-01-26 05:22:03', '2026-01-26 05:22:03'),
(28, 18, 95, 'Boots Sakura Bright Moisturising Cream 50 ml', 1000.00, 1, NULL, '2026-01-26 05:29:49', '2026-01-26 05:29:49'),
(29, 19, 7, 'CareNel Apricot Lip Night Mask 5g', 445.00, 1, NULL, '2026-01-27 02:51:47', '2026-01-27 02:51:47'),
(30, 19, 95, 'Boots Sakura Bright Moisturising Cream 50 ml', 1000.00, 3, NULL, '2026-01-27 02:51:47', '2026-01-27 02:51:47'),
(35, 21, 2, 'CareNel Cicavita B5 Salicylic Acid Gentle Cleanser 150ml', 1300.00, 6, NULL, '2026-01-28 03:54:21', '2026-01-28 03:54:21'),
(40, 22, 92, 'Banila Co Clean It Zero Cleansing Balm Original 100ml', 1499.00, 1, NULL, '2026-01-28 04:52:43', '2026-01-28 04:52:43'),
(45, 24, 3, 'CareNel Derma Alpha Arbutin Glutathione Whitening Cream 45ml', 1300.00, 1, NULL, '2026-01-29 00:53:57', '2026-01-29 00:53:57'),
(51, 25, 7, 'CareNel Apricot Lip Night Mask 5g', 445.00, 1, NULL, '2026-01-29 01:09:07', '2026-01-29 01:09:07'),
(52, 25, 8, 'Carenel Anti Melasma Cica Treatment Toner 155ml', 1345.00, 1, NULL, '2026-01-29 01:09:07', '2026-01-29 01:09:07'),
(53, 25, 4, 'CareNel Cicavita B5 Tranexamic Acid Toner 155ml', 1495.00, 1, NULL, '2026-01-29 01:09:07', '2026-01-29 01:09:07'),
(54, 25, 6, 'CareNel Lime Lip Night Mask 5g', 445.00, 1, NULL, '2026-01-29 01:09:07', '2026-01-29 01:09:07'),
(55, 25, 95, 'Boots Sakura Bright Moisturising Cream 50 ml', 1000.00, 1, NULL, '2026-01-29 01:09:07', '2026-01-29 01:09:07'),
(56, 23, 2, 'CareNel Cicavita B5 Salicylic Acid Gentle Cleanser 150ml', 1300.00, 1, NULL, '2026-01-29 01:10:16', '2026-01-29 01:10:16'),
(57, 20, 74, 'Anua Heartleaf Pore Control Cleansing Oil 20ml', 850.00, 1, NULL, '2026-01-29 01:11:02', '2026-01-29 01:11:02'),
(58, 20, 25, '3W Clinic Aloe Clear Cleansing Foam - 180ml', 850.00, 1, NULL, '2026-01-29 01:11:02', '2026-01-29 01:11:02'),
(62, 26, 93, 'Simple Hydrating Light Moisturiser 125ml', 599.00, 1, NULL, '2026-01-29 01:16:00', '2026-01-29 01:16:00'),
(63, 27, 7, 'CareNel Apricot Lip Night Mask 5g', 445.00, 1, NULL, '2026-01-29 01:33:10', '2026-01-29 01:33:10'),
(64, 27, 8, 'Carenel Anti Melasma Cica Treatment Toner 155ml', 1345.00, 1, NULL, '2026-01-29 01:33:10', '2026-01-29 01:33:10'),
(65, 27, 4, 'CareNel Cicavita B5 Tranexamic Acid Toner 155ml', 1495.00, 1, NULL, '2026-01-29 01:33:10', '2026-01-29 01:33:10'),
(66, 27, 6, 'CareNel Lime Lip Night Mask 5g', 445.00, 1, NULL, '2026-01-29 01:33:10', '2026-01-29 01:33:10'),
(67, 27, 95, 'Boots Sakura Bright Moisturising Cream 50 ml', 1000.00, 1, NULL, '2026-01-29 01:33:10', '2026-01-29 01:33:10'),
(68, 27, 93, 'Simple Hydrating Light Moisturiser 125ml', 599.00, 1, NULL, '2026-01-29 01:33:10', '2026-01-29 01:33:10'),
(69, 27, 74, 'Anua Heartleaf Pore Control Cleansing Oil 20ml', 449.00, 1, NULL, '2026-01-29 01:33:10', '2026-01-29 01:33:10'),
(70, 27, 10, 'Celimax Fresh Blackhead Jojoba Cleansing Oil 20ml', 295.00, 1, NULL, '2026-01-29 01:33:10', '2026-01-29 01:33:10'),
(71, 28, 74, 'Anua Heartleaf Pore Control Cleansing Oil 20ml', 449.00, 1, NULL, '2026-01-29 01:44:06', '2026-01-29 01:44:06'),
(73, 30, 8, 'Carenel Anti Melasma Cica Treatment Toner 155ml', 1345.00, 1, NULL, '2026-01-29 02:40:19', '2026-01-29 02:40:19'),
(74, 29, 25, '3W Clinic Aloe Clear Cleansing Foam - 180ml', 850.00, 1, NULL, '2026-01-29 02:41:30', '2026-01-29 02:41:30'),
(81, 31, 2, 'CareNel Cicavita B5 Salicylic Acid Gentle Cleanser 150ml', 1045.00, 1, NULL, '2026-02-02 02:30:12', '2026-02-02 02:30:12'),
(82, 31, 8, 'Carenel Anti Melasma Cica Treatment Toner 155ml', 1345.00, 1, NULL, '2026-02-02 02:30:13', '2026-02-02 02:30:13'),
(83, 31, 10, 'Celimax Fresh Blackhead Jojoba Cleansing Oil 20ml', 295.00, 1, NULL, '2026-02-02 02:30:13', '2026-02-02 02:30:13'),
(84, 32, 2, 'CareNel Cicavita B5 Salicylic Acid Gentle Cleanser 150ml', 1045.00, 1, NULL, '2026-02-02 02:41:30', '2026-02-02 02:41:30'),
(85, 32, 8, 'Carenel Anti Melasma Cica Treatment Toner 155ml', 1345.00, 2, NULL, '2026-02-02 02:41:30', '2026-02-02 02:41:30'),
(86, 32, 10, 'Celimax Fresh Blackhead Jojoba Cleansing Oil 20ml', 295.00, 1, NULL, '2026-02-02 02:41:30', '2026-02-02 02:41:30'),
(87, 32, 95, 'Boots Sakura Bright Moisturising Cream 50 ml', 1000.00, 1, NULL, '2026-02-02 02:41:30', '2026-02-02 02:41:30'),
(88, 32, 6, 'CareNel Lime Lip Night Mask 5g', 445.00, 1, NULL, '2026-02-02 02:41:31', '2026-02-02 02:41:31'),
(89, 32, 4, 'CareNel Cicavita B5 Tranexamic Acid Toner 155ml', 1495.00, 1, NULL, '2026-02-02 02:41:31', '2026-02-02 02:41:31');

-- --------------------------------------------------------

--
-- Table structure for table `order_status_histories`
--

CREATE TABLE `order_status_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `previous_status` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_status_histories`
--

INSERT INTO `order_status_histories` (`id`, `order_id`, `status`, `previous_status`, `note`, `changed_by`, `created_at`, `updated_at`) VALUES
(1, 26, 'pending', 'completed', 'Status changed from completed to pending', 1, '2026-01-29 01:15:54', '2026-01-29 01:15:54'),
(2, 26, 'completed', 'pending', 'Status changed from pending to completed', 1, '2026-01-29 01:16:00', '2026-01-29 01:16:00'),
(3, 29, 'complete', 'pending', 'Status changed from pending to complete', 1, '2026-01-29 02:34:32', '2026-01-29 02:34:32'),
(4, 29, 'completed', 'complete', 'Status changed from complete to completed', 1, '2026-01-29 02:35:02', '2026-01-29 02:35:02'),
(5, 29, 'pending', 'completed', 'Status changed from completed to pending', 1, '2026-01-29 02:36:07', '2026-01-29 02:36:07'),
(6, 29, 'completed', 'pending', 'Status changed from pending to completed', 1, '2026-01-29 02:41:30', '2026-01-29 02:41:30'),
(7, 31, 'completed', 'pending', 'Status changed from pending to completed', 1, '2026-02-02 02:29:49', '2026-02-02 02:29:49');

-- --------------------------------------------------------

--
-- Table structure for table `packing_slips`
--

CREATE TABLE `packing_slips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `slip_number` varchar(255) NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `printed_at` timestamp NULL DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packing_slips`
--

INSERT INTO `packing_slips` (`id`, `order_id`, `slip_number`, `generated_at`, `printed_at`, `meta`, `created_at`, `updated_at`) VALUES
(1, 3, 'PKG-2601-0001', '2026-01-22 09:49:34', '2026-01-22 03:49:34', '{\"customer_name\":\"N\\/A\",\"customer_email\":\"N\\/A\",\"customer_phone\":\"N\\/A\",\"shipping_address\":\"Jalkuri, Shiddhirganj, Narayanganj\",\"notes\":null,\"item_count\":1,\"total_quantity\":2}', '2026-01-22 03:23:08', '2026-01-22 03:49:34');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `method` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateways`
--

CREATE TABLE `payment_gateways` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`config`)),
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_gateways`
--

INSERT INTO `payment_gateways` (`id`, `name`, `slug`, `config`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Bkash', 'bkash', '{\"sandbox\":true}', 1, '2026-01-28 03:53:50', '2026-01-28 03:53:50'),
(2, 'Nagad', 'nagad', '{\"sandbox\":true}', 1, '2026-01-28 03:53:50', '2026-01-28 03:53:50'),
(3, 'SSLCommerz', 'sslcommerz', '{\"sandbox\":true}', 1, '2026-01-28 03:53:50', '2026-01-28 03:53:50');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 4, 'auth-token', '5ad5b2b441412645235e1e2bf26b472f1fb45d4a99f991b309f875dd19d712e2', '[\"*\"]', '2026-01-19 04:56:56', NULL, '2026-01-19 04:50:31', '2026-01-19 04:56:56'),
(2, 'App\\Models\\User', 4, 'auth-token', 'f548f513b5cd890b827d51229c85d06bd981d4ecf5a5f493c260e76eaaacc943', '[\"*\"]', '2026-01-19 04:59:57', NULL, '2026-01-19 04:57:26', '2026-01-19 04:59:57'),
(3, 'App\\Models\\User', 3, 'auth_token', '414267ad2069ddd59801be11d81755aefcc37c7ac8a411df4a3c2af5a57d2b8e', '[\"*\"]', NULL, NULL, '2026-01-19 05:17:27', '2026-01-19 05:17:27'),
(4, 'App\\Models\\User', 3, 'auth_token', 'c74154ac89d22d21c22f47e2f4a9510fd1e36b61f7c84da4a7242b7c53c52fb5', '[\"*\"]', '2026-01-19 05:25:13', NULL, '2026-01-19 05:18:39', '2026-01-19 05:25:13'),
(5, 'App\\Models\\User', 3, 'auth_token', '91fc975572617099c1f588ca7c04c34ac45f353ad06c7a3ba94ea3f172b2d64f', '[\"*\"]', NULL, NULL, '2026-01-19 05:26:41', '2026-01-19 05:26:41'),
(6, 'App\\Models\\User', 5, 'auth_token', 'a99431ae2e74d3118b42f6b99d3934124d56cfc12e3904808d99973b4a669163', '[\"*\"]', '2026-01-19 05:32:17', NULL, '2026-01-19 05:27:28', '2026-01-19 05:32:17'),
(7, 'App\\Models\\User', 6, 'auth_token', '2b43389c6ce3c31ad825475d4349c82f0bb89c7330b0959e25c648a1fefc7efe', '[\"*\"]', NULL, NULL, '2026-01-19 05:41:45', '2026-01-19 05:41:45'),
(8, 'App\\Models\\User', 3, 'auth_token', '56de0fa43b94017e0e41a4e886ceed656d79d4656504d3f8fb30f6513234fd7d', '[\"*\"]', '2026-01-19 05:51:03', NULL, '2026-01-19 05:49:31', '2026-01-19 05:51:03'),
(9, 'App\\Models\\User', 3, 'auth_token', '6f6b68a5e349af9a75743422a1bc1ae5d6da8fe70f67d8b6a5a705e6b9d1a2ef', '[\"*\"]', '2026-01-19 05:56:27', NULL, '2026-01-19 05:51:41', '2026-01-19 05:56:27'),
(10, 'App\\Models\\User', 3, 'auth_token', '6ddea699cdf120d03ba34c772517ebc9f7ee6fd9109c7557d2a8ff358156be01', '[\"*\"]', '2026-01-19 05:59:10', NULL, '2026-01-19 05:56:53', '2026-01-19 05:59:10'),
(11, 'App\\Models\\User', 3, 'auth_token', 'ad1cf9a2264c04d39912d0f145bece30ab6f6ff3fe3963fe93de4f0b4656476d', '[\"*\"]', '2026-01-19 06:02:01', NULL, '2026-01-19 06:01:23', '2026-01-19 06:02:01'),
(12, 'App\\Models\\User', 5, 'auth_token', '9d2d53b5e8aabe6674a3337996a2a589d8a86ea1eae169331e601c8e14f082bd', '[\"*\"]', '2026-01-19 06:06:16', NULL, '2026-01-19 06:02:30', '2026-01-19 06:06:16'),
(13, 'App\\Models\\User', 3, 'auth_token', '40307d4b17fd542bb629a8279cafb68c803a1b4e8836736e0e170466634a5377', '[\"*\"]', '2026-01-19 06:14:39', NULL, '2026-01-19 06:08:05', '2026-01-19 06:14:39'),
(14, 'App\\Models\\User', 3, 'temp_token', '8e7486c0c63d8b470f26eab6ee582d4a0ecadf8bdb6907e1061fbec4b403817c', '[\"*\"]', NULL, NULL, '2026-01-19 06:25:10', '2026-01-19 06:25:10'),
(15, 'App\\Models\\User', 3, 'temp_token', '553fee7f5e48474a2a9dafe6777c1b3f0e6fd78fe9bca90901a64f5cd03b6cdb', '[\"*\"]', NULL, NULL, '2026-01-19 06:28:06', '2026-01-19 06:28:06'),
(16, 'App\\Models\\User', 3, 'temp_token', 'c27311d9b34eaf7a6193f28be04adf9002f423424c0fad1771bbd92f87e5fd57', '[\"*\"]', NULL, NULL, '2026-01-19 06:31:15', '2026-01-19 06:31:15'),
(17, 'App\\Models\\User', 3, 'temp_token', 'd79bc4f29bb1a871d016a5b0bfb0ebad41c1fbf09052f9723cb1c7eda167350e', '[\"*\"]', NULL, NULL, '2026-01-19 06:37:27', '2026-01-19 06:37:27'),
(18, 'App\\Models\\User', 3, 'temp_token', 'b51b69b7ad7e35199b6a66bb3546f71b6df637f6500a5ccfa151f15a6f99ee43', '[\"*\"]', NULL, NULL, '2026-01-19 06:49:19', '2026-01-19 06:49:19'),
(19, 'App\\Models\\User', 3, 'temp_token', 'ee44ed49a3d0a051bdb4f657bbf9d1a45bdac45f2462058cdab3d1fabbad16bc', '[\"*\"]', NULL, NULL, '2026-01-19 06:55:12', '2026-01-19 06:55:12'),
(20, 'App\\Models\\User', 4, 'auth-token', 'f986905949f34c5d8608b1b1aeea1367f42a12f06309b89bcd96b4ef4a76188b', '[\"*\"]', '2026-01-20 00:56:19', NULL, '2026-01-20 00:56:16', '2026-01-20 00:56:19'),
(21, 'App\\Models\\User', 4, 'auth-token', 'f7f586acabe3201b71e777ab95963a6330c71942d896097e6c8878ccefd5414f', '[\"*\"]', '2026-01-20 03:08:34', NULL, '2026-01-20 00:57:03', '2026-01-20 03:08:34'),
(22, 'App\\Models\\User', 4, 'auth-token', '08364379e4e7c52b106fcc6fb43137d09622259ebd69a2b08a41a449c890e035', '[\"*\"]', '2026-01-20 03:03:11', NULL, '2026-01-20 01:27:24', '2026-01-20 03:03:11'),
(23, 'App\\Models\\User', 7, 'auth_token', '7628c2f7a5e8f2bf7884bf9fc00547d14ed860bd8643cb6ae07a547ef2dc5a0d', '[\"*\"]', NULL, NULL, '2026-01-20 03:17:00', '2026-01-20 03:17:00'),
(24, 'App\\Models\\User', 4, 'auth-token', 'c369686e71c32adfbc14bf899f4f14dee496711a721f6efdf3bb3217e6a21275', '[\"*\"]', '2026-01-20 04:35:03', NULL, '2026-01-20 04:15:18', '2026-01-20 04:35:03'),
(25, 'App\\Models\\User', 8, 'auth_token', '119084d4d7c99b21c1272322779a16a0298fdc36d1d9061fc6ca980de3b29ea1', '[\"*\"]', NULL, NULL, '2026-01-20 04:32:04', '2026-01-20 04:32:04'),
(26, 'App\\Models\\User', 9, 'auth_token', '9313ba1ad74d9b863c82a3f26fc67e2280247c1b3be0ab6ef905e565144d55e8', '[\"*\"]', '2026-01-20 04:57:17', NULL, '2026-01-20 04:35:32', '2026-01-20 04:57:17'),
(27, 'App\\Models\\User', 7, 'auth_token', '34be8a57a8c6049299f297862e65094f729c85b63b8b3cf36fb322e702c139f1', '[\"*\"]', NULL, NULL, '2026-01-20 04:57:40', '2026-01-20 04:57:40'),
(28, 'App\\Models\\User', 7, 'auth_token', 'cdf3cdd024dba949658c51831631209de82396f1207f602a8a4c746deb4d3879', '[\"*\"]', '2026-01-20 05:23:36', NULL, '2026-01-20 05:14:47', '2026-01-20 05:23:36'),
(29, 'App\\Models\\User', 7, 'auth_token', 'f534b5b4ef4ce85bcb9953ce3b62bd46120e1cfdb5abdb36ba7ec7b0d8d53da3', '[\"*\"]', '2026-01-20 05:25:23', NULL, '2026-01-20 05:25:21', '2026-01-20 05:25:23'),
(30, 'App\\Models\\User', 7, 'auth_token', 'c4d916b2b5e61b02848b25a92a4c2d6031f2207086ec0710573587c1bfb75a16', '[\"*\"]', '2026-01-20 05:27:43', NULL, '2026-01-20 05:27:25', '2026-01-20 05:27:43'),
(31, 'App\\Models\\User', 7, 'auth_token', '4ef01d996ec48150d2ed4cd943466dbdaed496ea795a0815b43cd0f9f5424691', '[\"*\"]', '2026-01-20 05:36:40', NULL, '2026-01-20 05:36:29', '2026-01-20 05:36:40'),
(32, 'App\\Models\\User', 4, 'auth-token', 'f241fff0fd18cda4bb3d0c052ef1dbc4a5076f5475cd4e9d54020d795fbf0450', '[\"*\"]', '2026-01-21 00:12:07', NULL, '2026-01-21 00:09:13', '2026-01-21 00:12:07'),
(33, 'App\\Models\\User', 4, 'auth-token', '2971829fa2433a8f83f38f9fd616b8cfa156a9ba7a1ec92240fb8c82cd87fff6', '[\"*\"]', '2026-01-21 00:40:38', NULL, '2026-01-21 00:14:31', '2026-01-21 00:40:38'),
(34, 'App\\Models\\User', 4, 'auth-token', 'ed7065513fa2b7ee718fee84dd1334adda87c7e42be826a8890881bfcf5ef796', '[\"*\"]', '2026-01-24 23:28:52', NULL, '2026-01-22 02:13:01', '2026-01-24 23:28:52'),
(35, 'App\\Models\\User', 4, 'auth-token', '5396959c36f838320ab66328d7ee4e8ee6cf2b1dab31bd4fad839502156764e8', '[\"*\"]', '2026-01-25 03:24:45', NULL, '2026-01-24 23:35:40', '2026-01-25 03:24:45'),
(36, 'App\\Models\\User', 4, 'auth-token', '068d63b121a0d5bcf6e5221aefc94f8525f3f0bba90f5e3f82b3c2c8955203d8', '[\"*\"]', '2026-01-25 04:15:08', NULL, '2026-01-25 03:34:07', '2026-01-25 04:15:08'),
(37, 'App\\Models\\User', 4, 'auth-token', '2f66bbc67cdf5fc14a5adf0030270ce5e7e3f6d61f4228bb21e6df4291d650f8', '[\"*\"]', '2026-01-25 06:15:29', NULL, '2026-01-25 05:37:56', '2026-01-25 06:15:29'),
(38, 'App\\Models\\User', 10, 'auth_token', '3e8e4bd89063ff4f901cfbba9654239c806cf5be27a3b006ca0a1bb475a23a5e', '[\"*\"]', '2026-01-25 23:17:57', NULL, '2026-01-25 23:15:09', '2026-01-25 23:17:57'),
(39, 'App\\Models\\User', 4, 'auth-token', '3519cfb617162bc3e46b1c7403b0d1cff19daa53b862b514e8b78761169da943', '[\"*\"]', '2026-01-26 04:03:22', NULL, '2026-01-26 03:23:34', '2026-01-26 04:03:22'),
(40, 'App\\Models\\User', 4, 'auth-token', 'a0bfd73d4cb921070faf39ccd2aba465d4a9e64287b4b6684ac0b97bdd111610', '[\"*\"]', '2026-01-29 00:29:16', NULL, '2026-01-26 04:07:25', '2026-01-29 00:29:16'),
(41, 'App\\Models\\User', 11, 'auth-token', '6ac60f268223f9fe736bb6653574f4d19d3b9038966ae650a35f8a819be6e567', '[\"*\"]', '2026-01-29 04:57:10', NULL, '2026-01-26 05:29:04', '2026-01-29 04:57:10'),
(42, 'App\\Models\\User', 4, 'auth-token', 'a69323b46358eae149946fdc6302ee4744182f487dde1e11a94dc4e6f1edba4d', '[\"*\"]', '2026-01-29 01:11:25', NULL, '2026-01-29 00:58:47', '2026-01-29 01:11:25'),
(43, 'App\\Models\\User', 14, 'auth_token', '8a1d4582d4517f3e56affb3ae41f9bde65b8407db0b3c3d838b1e233428ae2ed', '[\"*\"]', '2026-01-29 01:42:46', NULL, '2026-01-29 01:12:07', '2026-01-29 01:42:46'),
(44, 'App\\Models\\User', 4, 'auth-token', '39b6c884064cc560e934c074f98125d1e1d3792ea694eba1b29123b65ec993dd', '[\"*\"]', '2026-01-29 02:39:38', NULL, '2026-01-29 01:43:43', '2026-01-29 02:39:38'),
(45, 'App\\Models\\User', 4, 'auth-token', '401f1a5859eed8e95ef4accb41b1d36813bd72239af3efeed487df666bc01032', '[\"*\"]', '2026-02-02 02:45:48', NULL, '2026-02-02 02:21:38', '2026-02-02 02:45:48'),
(46, 'App\\Models\\User', 4, 'auth-token', 'bcdcee0e3f7a9a70c2fa663411d72d4ecf9521c9731199612cd53e06aad06325', '[\"*\"]', '2026-02-02 03:16:26', NULL, '2026-02-02 02:46:54', '2026-02-02 03:16:26'),
(47, 'App\\Models\\User', 4, 'auth-token', '023882aba971c6d39b7e14a6301d826d6cc60fc076f3cbeae5337aad14906680', '[\"*\"]', '2026-02-02 03:55:02', NULL, '2026-02-02 03:39:02', '2026-02-02 03:55:02'),
(48, 'App\\Models\\User', 15, 'auth-token', '1a5c9a149255ffa8cccab1ab63cf2cb869c159cb90f87a56e8f3d03263e3a621', '[\"*\"]', '2026-02-02 04:11:27', NULL, '2026-02-02 04:07:56', '2026-02-02 04:11:27'),
(49, 'App\\Models\\User', 15, 'auth-token', 'c997fe1b345587a0eadf098209aa7554ac9205823e414e2fcc34f12c409e0a06', '[\"*\"]', '2026-02-02 04:11:46', NULL, '2026-02-02 04:11:32', '2026-02-02 04:11:46'),
(50, 'App\\Models\\User', 15, 'auth-token', 'a47eb034f0fccbd2866fb90a98d20d66b8c92eab7ecc540d29b2326bdddbcea2', '[\"*\"]', '2026-02-02 04:20:35', NULL, '2026-02-02 04:14:01', '2026-02-02 04:20:35'),
(51, 'App\\Models\\User', 15, 'auth-token', '3a2e017e6f4184a75c5a365a5a59edc7b621a8134b530027118c546e30c3c093', '[\"*\"]', '2026-02-02 04:20:46', NULL, '2026-02-02 04:20:41', '2026-02-02 04:20:46'),
(52, 'App\\Models\\User', 4, 'auth-token', '12e8d9e36a1b43e79b1594196f93ce131260dc5038d96394395096db48ba1bfb', '[\"*\"]', '2026-02-02 04:22:45', NULL, '2026-02-02 04:22:32', '2026-02-02 04:22:45'),
(53, 'App\\Models\\User', 4, 'auth-token', 'f98293b333ea16522af32c790bca548ca7ce376652f74a9d623bf9a3e2dbce67', '[\"*\"]', '2026-02-02 04:37:59', NULL, '2026-02-02 04:22:52', '2026-02-02 04:37:59'),
(54, 'App\\Models\\User', 15, 'auth-token', 'e3e1f575b15ae70f8da5a5f723e4d605d8a007e436daecadc323ec5ceca48779', '[\"*\"]', '2026-02-02 05:09:36', NULL, '2026-02-02 05:07:13', '2026-02-02 05:09:36'),
(55, 'App\\Models\\User', 4, 'auth-token', 'bd7ab05bfb0525acac2a658fd2f51505fe1cb1397f0dda5838c61b693a8aa820', '[\"*\"]', '2026-02-02 05:14:09', NULL, '2026-02-02 05:10:08', '2026-02-02 05:14:09'),
(56, 'App\\Models\\User', 15, 'auth-token', '443299f35eb3560506ed2d54384a0e7b113406f706e72f04811a2a8cf008fe46', '[\"*\"]', '2026-02-02 05:20:13', NULL, '2026-02-02 05:14:40', '2026-02-02 05:20:13'),
(57, 'App\\Models\\User', 4, 'auth-token', '540a2d04d5744fb26b106fd6ddbf2e3f3f69576ffd06dac9eafee20534830203', '[\"*\"]', '2026-02-02 05:21:17', NULL, '2026-02-02 05:20:35', '2026-02-02 05:21:17'),
(58, 'App\\Models\\User', 4, 'auth-token', '53238a47dbfa0e5f88794c816ec5c12db09b0a2a66504067fa9d629cd4cea350', '[\"*\"]', '2026-02-02 05:28:58', NULL, '2026-02-02 05:28:37', '2026-02-02 05:28:58'),
(59, 'App\\Models\\User', 16, 'auth_token', 'f8065e8368b5b3fd7df290cce0161bf0f285f2670b7417a472de9266277a3b4e', '[\"*\"]', '2026-02-02 05:37:44', NULL, '2026-02-02 05:32:03', '2026-02-02 05:37:44');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `short_description` text DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) DEFAULT NULL,
  `cost_price` decimal(15,2) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `stock_in` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'simple',
  `status` enum('active','inactive','draft') NOT NULL DEFAULT 'active',
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `barcode` varchar(255) DEFAULT NULL,
  `manage_stock` tinyint(1) NOT NULL DEFAULT 1,
  `external_url` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `min_order_qty` int(11) DEFAULT NULL,
  `max_order_qty` int(11) DEFAULT NULL,
  `sale_start_date` datetime DEFAULT NULL,
  `sale_end_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `cost_price`, `stock_quantity`, `stock_in`, `sku`, `type`, `status`, `featured`, `barcode`, `manage_stock`, `external_url`, `meta_title`, `meta_description`, `main_image`, `category_id`, `brand_id`, `supplier_id`, `warehouse_id`, `min_order_qty`, `max_order_qty`, `sale_start_date`, `sale_end_date`, `created_at`, `updated_at`) VALUES
(1, 'Boots Sakura Bright Moisturising Cream 50 ml', 'boots-sakura-bright-moisturising-cream-50-ml', '-', NULL, 1250.00, 695.00, NULL, 50, 'Rupchorcha', '10120', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/K9q0BOk9aaNX68kGWfCbqofmw282rryIb6jlJESg.webp', 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:05:58', '2026-01-14 05:16:25'),
(2, 'CareNel Cicavita B5 Salicylic Acid Gentle Cleanser 150ml', 'carenel-cicavita-b5-salicylic-acid-gentle-cleanser-150ml', 'CareNel Cicavita B5 Salicylic Acid Gentle Cleanser 150ml Long Description', 'CareNel Cicavita B5 Salicylic Acid Gentle Cleanser 150ml  Short Description', 1300.00, 1045.00, 900.00, 43, NULL, '10121', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/IHw406pWnm5uvn0sINqB.png', 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:05:59', '2026-02-02 02:41:31'),
(3, 'CareNel Derma Alpha Arbutin Glutathione Whitening Cream 45ml', 'carenel-derma-alpha-arbutin-glutathione-whitening-cream-45ml', '-', NULL, 1300.00, 1180.00, NULL, 50, NULL, '10122', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/TtwUqFOSPu56qBtc3w7u.png', 4, 3, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:01', '2026-01-07 23:02:01'),
(4, 'CareNel Cicavita B5 Tranexamic Acid Toner 155ml', 'carenel-cicavita-b5-tranexamic-acid-toner-155ml', '-', NULL, 1850.00, 1495.00, NULL, 46, NULL, '10123', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/v9gYUnYnzRndQSbZext4.jpg', 4, 3, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:02', '2026-02-02 02:41:31'),
(5, 'CareNel Berry Lip Night Mask 5g', 'carenel-berry-lip-night-mask-5g', '-', NULL, 580.00, 445.00, NULL, 50, NULL, '10124', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/gcs4CsYMIEOdNfadPWQL.jpg', 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:03', '2026-01-07 23:02:33'),
(6, 'CareNel Lime Lip Night Mask 5g', 'carenel-lime-lip-night-mask-5g', '-', NULL, 580.00, 445.00, NULL, 46, NULL, '10125', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/f1dftw9kRKO5BQ25wwo4.jpg', 8, 4, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:04', '2026-02-02 02:41:31'),
(7, 'CareNel Apricot Lip Night Mask 5g', 'carenel-apricot-lip-night-mask-5g', '-', NULL, 580.00, 445.00, NULL, 45, NULL, '10126', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/AxESxT9C628tnv18773O.jpg', 9, 3, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:05', '2026-01-29 01:33:10'),
(8, 'Carenel Anti Melasma Cica Treatment Toner 155ml', 'carenel-anti-melasma-cica-treatment-toner-155ml', '-', NULL, 1700.00, 1345.00, NULL, 41, NULL, '10127', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/H8hHuF5DykSqNTPk5DdM.png', 4, 3, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:07', '2026-02-02 02:41:31'),
(9, 'CareNel Anti-Melasma Cica Cream 40ml', 'carenel-anti-melasma-cica-cream-40ml', '-', NULL, 1250.00, 1049.00, NULL, 50, NULL, '10128', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/PsKz4Cou4GUYeqDmRnLc.png', 4, 3, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:08', '2026-01-12 23:12:59'),
(10, 'Celimax Fresh Blackhead Jojoba Cleansing Oil 20ml', 'celimax-fresh-blackhead-jojoba-cleansing-oil-20ml', '-', NULL, 650.00, 295.00, NULL, 45, NULL, '10129', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/simsyccSDJJFrRcHD1rg.png', 4, 11, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:10', '2026-02-02 02:41:31'),
(11, 'Cetaphil Moisturising Lotion For Normal To Combination, Sensitive Skin 100ml', 'cetaphil-moisturising-lotion-for-normal-to-combination-sensitive-skin-100ml', '-', NULL, 1400.00, 1199.00, NULL, 50, NULL, '10136', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/7Fp90owBH1XJi4l2mg0q.png', 2, 4, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:11', '2026-01-07 23:03:05'),
(12, 'Cetaphil Gentle Skin Cleanser For Normal To Dry &amp; Sensitive Skin - 236ml', 'cetaphil-gentle-skin-cleanser-for-normal-to-dry-amp-sensitive-skin-236ml', '-', NULL, 1850.00, 1650.00, NULL, 50, NULL, '10137', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/XNYZy7xq1udjXAb8CH3K.jpg', 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:12', '2026-01-07 23:03:24'),
(13, 'Cetaphil Gentle Skin Cleanser 59 ml', 'cetaphil-gentle-skin-cleanser-59-ml', '-', NULL, 850.00, 695.00, NULL, 50, NULL, '10138', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/RSrzVuuEGhfxEdwbex2x.avif', 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:13', '2026-01-11 00:03:15'),
(14, 'Christian Dean Secret Tone Up Sun Cream 70ml', 'christian-dean-secret-tone-up-sun-cream-70ml', '-', NULL, 700.00, 399.00, NULL, 50, NULL, '10139', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/mX5slWPCn9sqLU3KHHCc.jpg', 4, 8, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:14', '2026-01-12 23:10:25'),
(15, 'Clean &amp; Clear Blackhead Clearing Cleanser  200ml', 'clean-amp-clear-blackhead-clearing-cleanser-200ml', '-', NULL, 950.00, 800.00, NULL, 50, NULL, '10140', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/E3p24WeBgwANobeer3yW.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:15', '2025-12-30 04:06:15'),
(16, 'Clean &amp; Clear Morning Energy Shine Control Daily Facial Wash 150ml', 'clean-amp-clear-morning-energy-shine-control-daily-facial-wash-150ml', '-', NULL, 900.00, 625.00, NULL, 50, NULL, '10141', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/CYIrkMwmK26rO43sRTHG.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:16', '2025-12-30 04:06:16'),
(17, 'Clear Men Cooling Itch Control Anti-Dandruff Shampoo 315ml', 'clear-men-cooling-itch-control-anti-dandruff-shampoo-315ml', '-', NULL, 1050.00, 795.00, NULL, 50, NULL, '10142', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/uZuOQAiLEeOfHWCBZtcU.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:17', '2025-12-30 04:06:17'),
(18, 'Colormax Diva Glamour Matte Lipcolour 1.4g- Monaco', 'colormax-diva-glamour-matte-lipcolour-14g-monaco', '-', NULL, 1000.00, 649.00, NULL, 50, NULL, '10143', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/bOieNbGETFIpB4a7HbLq.png', 3, 12, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:19', '2026-01-12 23:16:03'),
(19, 'Colormax Diva Glamour Matte Lipcolour 1.4g- Tokyo', 'colormax-diva-glamour-matte-lipcolour-14g-tokyo', '-', NULL, 1000.00, 649.00, NULL, 50, NULL, '10144', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/UruCsy9kHtyEfOjWHsyj.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:20', '2025-12-30 04:06:20'),
(20, 'Colormax Diva Glamour Matte Lipcolour 1.4g- Cape Town', 'colormax-diva-glamour-matte-lipcolour-14g-cape-town', '-', NULL, 1000.00, 649.00, NULL, 50, NULL, '10145', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/ob5mvYBmP1Kwinmygv4D.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:22', '2025-12-30 04:06:22'),
(21, 'Colormax Diva Glamour Matte Lipcolour 1.4g- Rio', 'colormax-diva-glamour-matte-lipcolour-14g-rio', '-', NULL, 1000.00, 649.00, NULL, 50, NULL, '10146', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/7euq4hvNUVEdf31Hx5oF.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:23', '2025-12-30 04:06:23'),
(22, '3w Clinic Charcoal Cleansing Foam 100ml', '3w-clinic-charcoal-cleansing-foam-100ml', '-', NULL, 750.00, 395.00, NULL, 50, NULL, '10001', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/6EDQHD6FXk91IBu1Iy8Q.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:24', '2025-12-30 04:06:24'),
(23, '3W Clinic Brown Rice Foam Cleansing - 100ml', '3w-clinic-brown-rice-foam-cleansing-100ml', '-', NULL, 750.00, 395.00, NULL, 50, NULL, '10002', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/D8KbLK6ZSNBBfvI3s99e.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:26', '2025-12-30 04:06:26'),
(24, '3W Clinic Aloe Vera Soothing Gel-300ml', '3w-clinic-aloe-vera-soothing-gel-300ml', '-', NULL, 1250.00, 845.00, NULL, 50, NULL, '10003', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/4FyxpQUIMUeDtRTvKXts.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:28', '2025-12-30 04:06:28'),
(25, '3W Clinic Aloe Clear Cleansing Foam - 180ml', '3w-clinic-aloe-clear-cleansing-foam-180ml', '-', NULL, 850.00, 695.00, NULL, 48, NULL, '10004', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/LthAuHIvvXaN2ERoohZe.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:30', '2026-01-29 02:41:31'),
(26, '3W Clinic Fresh Green Tea Sheet Mask', '3w-clinic-fresh-green-tea-sheet-mask', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10005', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/jZaV8exX2qgYPpPRgCUg.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:32', '2025-12-30 04:06:32'),
(27, '3W Clinic Fresh Royal Jelly Sheet Mask', '3w-clinic-fresh-royal-jelly-sheet-mask', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10006', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/Gm3S2eDbzj5gEosLzvkl.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:34', '2025-12-30 04:06:34'),
(28, '3W Clinic Fresh Pomegranate Sheet Mask', '3w-clinic-fresh-pomegranate-sheet-mask', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10007', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/eZEqMfODKhff9qOcgRQm.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:35', '2025-12-30 04:06:35'),
(29, '3W Clinic Dr. K Collagen Whitening Cream 100g', '3w-clinic-dr-k-collagen-whitening-cream-100g', '-', NULL, 950.00, 695.00, NULL, 50, NULL, '10008', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/uZTnH9VQnz1nmdPqXTzE.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:37', '2025-12-30 04:06:37'),
(30, '3W Clinic UV Sun Block BB Cream - 50g', '3w-clinic-uv-sun-block-bb-cream-50g', '-', NULL, 750.00, 595.00, NULL, 50, NULL, '10009', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/JImyJOIEmSzdB1wNNNjA.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:39', '2025-12-30 04:06:39'),
(31, '3W Clinic Fresh Collagen Sheet Mask', '3w-clinic-fresh-collagen-sheet-mask', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10010', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/EL5NdeDBAB6ThjZXDkyb.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:41', '2025-12-30 04:06:41'),
(32, '3W Clinic Dr. K Aloe Whitening Cream- 100g', '3w-clinic-dr-k-aloe-whitening-cream-100g', '-', NULL, 950.00, 695.00, NULL, 50, NULL, '10011', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/JG57DSSINZHl8KRL0Dhw.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:42', '2025-12-30 04:06:42'),
(33, '3W Clinic Essential Up Snail Sheet Mask', '3w-clinic-essential-up-snail-sheet-mask', '-', NULL, 250.00, 135.00, NULL, 50, NULL, '10012', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/kMovQn4N8IHgr6DSrauF.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:43', '2025-12-30 04:06:43'),
(34, '3W Clinic Essential Up Rose Sheet Mask', '3w-clinic-essential-up-rose-sheet-mask', '-', NULL, 250.00, 135.00, NULL, 50, NULL, '10013', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/Y3JUQxmgRKmQYz9FhPzJ.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:44', '2025-12-30 04:06:44'),
(35, '3w Clinic Vitamin C Foam Cleansing- 100ml', '3w-clinic-vitamin-c-foam-cleansing-100ml', '-', NULL, 750.00, 395.00, NULL, 50, NULL, '10014', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/ccjFMl5ksoHLWr0QzTHU.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:46', '2025-12-30 04:06:46'),
(36, '3W Clinic Dr. K VITA-C Whitening Cream - 100g', '3w-clinic-dr-k-vita-c-whitening-cream-100g', '-', NULL, 950.00, 695.00, NULL, 50, NULL, '10015', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/hoWMBVLOgi2S4IMkrxno.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:48', '2025-12-30 04:06:48'),
(37, '3W Clinic DR. K Underarm Whitening Multi Cream 100g', '3w-clinic-dr-k-underarm-whitening-multi-cream-100g', '-', NULL, 1100.00, 745.00, NULL, 50, NULL, '10016', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/s6IZBWbh7JQGqPynXqA2.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:49', '2025-12-30 04:06:49'),
(38, '3W Clinic Moringa Brightening Cool Soothing Gel 160ml', '3w-clinic-moringa-brightening-cool-soothing-gel-160ml', '-', NULL, 850.00, 695.00, NULL, 50, NULL, '10017', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/qVhvJdC3KmRYxogrC8Yl.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:51', '2025-12-30 04:06:51'),
(39, '3W Clinic Dr. K Black Rice Whitening Cream - 100g', '3w-clinic-dr-k-black-rice-whitening-cream-100g', '-', NULL, 950.00, 695.00, NULL, 50, NULL, '10018', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/n3t7zYUBg0SmndjZxa1s.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:53', '2025-12-30 04:06:53'),
(40, '3W Clinic Fresh Potato Sheet Mask', '3w-clinic-fresh-potato-sheet-mask', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10019', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/9wPrZ5ZNqghZ6C0NyQ0R.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:54', '2025-12-30 04:06:54'),
(41, '3W Clinic Fresh Coenzyme Q10 Sheet Mask', '3w-clinic-fresh-coenzyme-q10-sheet-mask', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10020', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/4bfWtyE2rV4Fp6yJVmvo.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:56', '2025-12-30 04:06:56'),
(42, '3W Clinic Green Tea Foam Cleansing 100ml', '3w-clinic-green-tea-foam-cleansing-100ml', '-', NULL, 750.00, 395.00, NULL, 50, NULL, '10021', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/4nch0MAgwypNB5gz8IYh.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:57', '2025-12-30 04:06:57'),
(43, '3W Clinic Fresh Cucumber Mask Sheet', '3w-clinic-fresh-cucumber-mask-sheet', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10022', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/SbnG2C7bzYLfitK3uJwc.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:06:59', '2025-12-30 04:06:59'),
(44, '3W Clinic Intensive Dr. Kim Sun Cushion SPF50+ PA++++ (15g)', '3w-clinic-intensive-dr-kim-sun-cushion-spf50-pa-15g', '-', NULL, 1050.00, 825.00, NULL, 50, NULL, '10023', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/mhX5LkHDgWBmxbAzHokJ.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:01', '2025-12-30 04:07:01'),
(45, '3W Clinic Premium Vegan Intensive UV Sunblock Cream 60ml', '3w-clinic-premium-vegan-intensive-uv-sunblock-cream-60ml', '-', NULL, 950.00, 599.00, NULL, 50, NULL, '10024', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/v8WobRIbRBsh2CHKvCjS.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:02', '2025-12-30 04:07:02'),
(46, '3W Clinic Honey Whitening Anti-Wrinkle Eye Cream 40ml', '3w-clinic-honey-whitening-anti-wrinkle-eye-cream-40ml', '-', NULL, 550.00, 399.00, NULL, 50, NULL, '10025', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/pQUozwTq3XrqTenyeprv.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:04', '2025-12-30 04:07:04'),
(47, '3W Clinic Fresh White Sheet Mask', '3w-clinic-fresh-white-sheet-mask', '-', NULL, 150.00, 95.00, NULL, 50, NULL, '10026', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/mgobacOS3q6FD1VBJ2Hw.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:05', '2025-12-30 04:07:05'),
(48, '3W Clinic Intensive Dr.Kim Sun BB Cream SPF50 50ml', '3w-clinic-intensive-drkim-sun-bb-cream-spf50-50ml', '-', NULL, 750.00, 545.00, NULL, 50, NULL, '10027', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/6Xna6ULCtMo5lD6PQuAp.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:07', '2025-12-30 04:07:07'),
(49, '3W Clinic Fresh Red Ginseng Sheet Mask', '3w-clinic-fresh-red-ginseng-sheet-mask', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10028', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/kgcXGteJ6flQxduo90uk.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:09', '2025-12-30 04:07:09'),
(50, '3W Clinic Snail Peptide Ball eye Serum-30ml', '3w-clinic-snail-peptide-ball-eye-serum-30ml', '-', NULL, 650.00, 475.00, NULL, 50, NULL, '10029', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/iSCzSSHj5O7qUQVbPXIE.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:10', '2025-12-30 04:07:10'),
(51, '3W Clinic Multi Protection UV Sun Block SPF 50+/PA+++ (70ml)', '3w-clinic-multi-protection-uv-sun-block-spf-50pa-70ml', '-', NULL, 600.00, 449.00, NULL, 50, NULL, '10030', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/l5PcBfyf2NGEYbKMqO36.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:11', '2025-12-30 04:07:11'),
(52, '3W Clinic Collagen Eye Cream 40ml', '3w-clinic-collagen-eye-cream-40ml', '-', NULL, 550.00, 399.00, NULL, 50, NULL, '10031', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/EBWMVSn7yiPupOQXPUBr.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:12', '2025-12-30 04:07:12'),
(53, '3W Clinic Intensive Dr.Kim Sun Body Cream 150g', '3w-clinic-intensive-drkim-sun-body-cream-150g', '-', NULL, 1200.00, 845.00, NULL, 50, NULL, '10032', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/sM7YRe6bJ8bkk2WxPoR7.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:14', '2025-12-30 04:07:14'),
(54, '3W Clinic Dr. K Snail Whitening Cream - 100g', '3w-clinic-dr-k-snail-whitening-cream-100g', '-', NULL, 950.00, 695.00, NULL, 50, NULL, '10033', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/SoIURPrPzkwmkbryi8Ph.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:16', '2025-12-30 04:07:16'),
(55, '3W Clinic Intensive Uv Sunblock Cream Spf 50 Pa+++ 70ml', '3w-clinic-intensive-uv-sunblock-cream-spf-50-pa-70ml', '-', NULL, 750.00, 399.00, NULL, 50, NULL, '10034', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/2MY7fZ2ibUoHGxQszUh1.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:17', '2025-12-30 04:07:17'),
(56, '3W Clinic Intensive UV Sunstick Balm SPF50+ PA++++ (10gm)', '3w-clinic-intensive-uv-sunstick-balm-spf50-pa-10gm', '-', NULL, 650.00, 495.00, NULL, 50, NULL, '10035', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/jRQj4bybYKMrOoMGQYfB.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:19', '2025-12-30 04:07:19'),
(57, '3W Clinic Collagen Retinol Ball Eye Serum 30ml', '3w-clinic-collagen-retinol-ball-eye-serum-30ml', '-', NULL, 650.00, 475.00, NULL, 50, NULL, '10036', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/n13HGugoP3F9x6AhFyKG.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:20', '2025-12-30 04:07:20'),
(58, '3W Clinic Intensive Green Tea Sunblock Cream SPF50+ Pa+++ 70ml', '3w-clinic-intensive-green-tea-sunblock-cream-spf50-pa-70ml', '-', NULL, 550.00, 449.00, NULL, 50, NULL, '10037', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/p3zVs21FOzW20Gv2Nqz0.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:22', '2025-12-30 04:07:22'),
(59, '3W Clinic Rose Eye Cream Anti-Wrinkle 40ml', '3w-clinic-rose-eye-cream-anti-wrinkle-40ml', '-', NULL, 550.00, 399.00, NULL, 50, NULL, '10038', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/xyl7xu8e0Y4Bmi1Or7eR.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:23', '2025-12-30 04:07:23'),
(60, '3W Clinic Intensive Aloe Sunblock Cream SPF 50+ PA+++ (70ml)', '3w-clinic-intensive-aloe-sunblock-cream-spf-50-pa-70ml', '-', NULL, 550.00, 449.00, NULL, 50, NULL, '10039', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/oG33VYM5WmB9VLndYQvB.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:25', '2025-12-30 04:07:25'),
(61, '3W Clinic Intensive Dr. Kim Bright Sun Tone Up Cream SPF 35+ PA++ (50ml)', '3w-clinic-intensive-dr-kim-bright-sun-tone-up-cream-spf-35-pa-50ml', '-', NULL, 850.00, 649.00, NULL, 50, NULL, '10041', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/LPTunZMabVVocYROLvFT.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:26', '2025-12-30 04:07:26'),
(62, 'Abib Airy Sunstick Smoothing Bar 23g', 'abib-airy-sunstick-smoothing-bar-23g', '-', NULL, 1900.00, 1445.00, NULL, 50, NULL, '10042', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/JjOdIO3ZwTkacYiFUT2X.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:27', '2025-12-30 04:07:27'),
(63, 'Abib Quick Sunstick Protection Bar SPF50+ PA++++ 22g', 'abib-quick-sunstick-protection-bar-spf50-pa-22g', '-', NULL, 1900.00, 1445.00, NULL, 50, NULL, '10043', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/8yFVuU6HKudI5RwbASKP.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:28', '2025-12-30 04:07:28'),
(64, 'Active Nine Intensive UV Shield Airy Sun Soothing Essence 50ml', 'active-nine-intensive-uv-shield-airy-sun-soothing-essence-50ml', '-', NULL, 1450.00, 1145.00, NULL, 50, NULL, '10044', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/dzPRmXWYryJQPkAivYRy.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:30', '2025-12-30 04:07:30'),
(65, 'Acwell Licorice pH Balancing Cleansing Toner 150ml', 'acwell-licorice-ph-balancing-cleansing-toner-150ml', '-', NULL, 1650.00, 1399.00, NULL, 50, NULL, '10045', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/Zb2c9ccvp7FjczhYK62E.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:31', '2025-12-30 04:07:31'),
(66, 'Anastasia Beverly Hills Dipbrow Pomade 4g', 'anastasia-beverly-hills-dipbrow-pomade-4g', '-', NULL, 2150.00, 795.00, NULL, 50, NULL, '10046', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/XVz6k6JbJUcMRn9UC7Un.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:32', '2025-12-30 04:07:32'),
(67, 'Anua 8 Hyaluronic Acid Hydrating Gentle Foaming Cleanser 150ml', 'anua-8-hyaluronic-acid-hydrating-gentle-foaming-cleanser-150ml', '-', NULL, 1850.00, 1395.00, NULL, 50, NULL, '10047', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/yd7vfpgSImQXzXbFTtMu.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:34', '2025-12-30 04:07:34'),
(68, 'Anua Heartleaf Pore Clay Pack 100ml', 'anua-heartleaf-pore-clay-pack-100ml', '-', NULL, 1850.00, 1645.00, NULL, 50, NULL, '10048', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/Cix9uHD4Wom6fbapBtSw.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:35', '2025-12-30 04:07:35'),
(69, 'Anua Peach 70% Niacin Serum - 30ml', 'anua-peach-70-niacin-serum-30ml', '-', NULL, 2450.00, 2245.00, NULL, 50, NULL, '10049', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/uqUBd1SQo5b027Gp7UKg.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:36', '2025-12-30 04:07:36'),
(70, 'Anua Heartleaf Quercetinol Pore Deep Cleansing Foam 150ml', 'anua-heartleaf-quercetinol-pore-deep-cleansing-foam-150ml', '-', NULL, 1850.00, 1495.00, NULL, 50, NULL, '10050', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/dkPKZsSHfyBZBGEERKvb.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:37', '2025-12-30 04:07:37'),
(71, 'Anua Heartleaf Pore Control Cleansing Oil - 200ml', 'anua-heartleaf-pore-control-cleansing-oil-200ml', '-', NULL, 2450.00, 1945.00, NULL, 50, NULL, '10051', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/0IeK8fcAT5fI9bY7b5CW.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:38', '2025-12-30 04:07:38'),
(72, 'Anua Niacinamide 10% + TXA 4% Serum 30ml', 'anua-niacinamide-10-txa-4-serum-30ml', '-', NULL, 2500.00, 1895.00, NULL, 50, NULL, '10052', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/HFUPaq4eziAyolMb2reK.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:39', '2025-12-30 04:07:39'),
(73, 'Anua Heartleaf 77% Soothing Toner 40ml', 'anua-heartleaf-77-soothing-toner-40ml', '-', NULL, 1050.00, 499.00, NULL, 50, NULL, '10053', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/LqYiPy3ILRIcny3BWwqZ.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:40', '2025-12-30 04:07:40'),
(74, 'Anua Heartleaf Pore Control Cleansing Oil 20ml', 'anua-heartleaf-pore-control-cleansing-oil-20ml', '-', NULL, 850.00, 449.00, NULL, 47, NULL, '10054', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/jc18qIHB5ROyTzoJpoLfKBohkwo1czmPa9TFsjAv.webp', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:42', '2026-01-29 01:44:06'),
(75, 'Astral Face &amp; Body Intensive Moisturiser Cream 50ml', 'astral-face-amp-body-intensive-moisturiser-cream-50ml', '-', NULL, 700.00, 599.00, NULL, 50, NULL, '10055', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/s2XS2enVv8TlggMkR1RT.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:43', '2025-12-30 04:07:43'),
(76, 'Aveeno Baby Daily Baby Moisturising Lotion 227g', 'aveeno-baby-daily-baby-moisturising-lotion-227g', '-', NULL, 2250.00, 1950.00, NULL, 50, NULL, '10056', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/DqTmAhwbCrMlCUfqAXfB.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:45', '2025-12-30 04:07:45'),
(77, 'Aveeno Protect + Hydrate Sunscreen Broad Spectrum Face Lotion SPF 60 - 88ml', 'aveeno-protect-hydrate-sunscreen-broad-spectrum-face-lotion-spf-60-88ml', '-', NULL, 2050.00, 1945.00, NULL, 50, NULL, '10057', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/9bK7UN1DiupTQr2ZG9ei.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:47', '2025-12-30 04:07:47'),
(78, 'Aveeno Baby Soothing Relief Emollient Cream 150ml', 'aveeno-baby-soothing-relief-emollient-cream-150ml', '-', NULL, 2200.00, 1545.00, NULL, 50, NULL, '10058', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/muhBjI8wwUNGRYpPj3jJ.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:48', '2025-12-30 04:07:48'),
(79, 'Aveeno Baby Daily Care Moisturising Lotion For Sensitive Skin 150ml', 'aveeno-baby-daily-care-moisturising-lotion-for-sensitive-skin-150ml', '-', NULL, 1050.00, 845.00, NULL, 50, NULL, '10059', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/TzBG3JyFOahTzeVMBAu2.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:50', '2025-12-30 04:07:50'),
(80, 'AXE Dark Temptation Deodorant Body Spray 150ml', 'axe-dark-temptation-deodorant-body-spray-150ml', '-', NULL, 600.00, 425.00, NULL, 50, NULL, '10060', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/QxIwagwrYRTfXZJ0cXor.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:51', '2025-12-30 04:07:51'),
(81, 'Axe Black Deodorant Body Spray 150ml', 'axe-black-deodorant-body-spray-150ml', '-', NULL, 600.00, 425.00, NULL, 50, NULL, '10061', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/Tq5s02sVOISxw8ypIYLM.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:52', '2025-12-30 04:07:52'),
(82, 'Axe Gold Deodorant Body Spray 150 ml', 'axe-gold-deodorant-body-spray-150-ml', '-', NULL, 600.00, 425.00, NULL, 50, NULL, '10062', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/lHbkUvNJgrQ0X830EqM7.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:54', '2025-12-30 04:07:54'),
(83, 'AXIS - Y Dark Spot Correcting Glow Toner 125ml', 'axis-y-dark-spot-correcting-glow-toner-125ml', '-', NULL, 1800.00, 1650.00, NULL, 50, NULL, '10063', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/BB0zuqQxW47oXdGxKZ17.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:55', '2025-12-30 04:07:55'),
(84, 'AXIS - Y  Biome Double Defense Sunscreen 50ml', 'axis-y-biome-double-defense-sunscreen-50ml', '-', NULL, 1800.00, 1445.00, NULL, 50, NULL, '10064', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/ZICwr70eHuvYCSvpQFhs.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:56', '2025-12-30 04:07:56'),
(85, 'AXIS-Y Vegan Collagen Eye Serum 10ml', 'axis-y-vegan-collagen-eye-serum-10ml', '-', NULL, 1800.00, 1349.00, NULL, 50, NULL, '10065', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/cBuB0R1r8GkFu54pHW3c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:57', '2025-12-30 04:07:57'),
(86, 'AXIS-Y Complete No-Stress Physical Sunscreen 50ml', 'axis-y-complete-no-stress-physical-sunscreen-50ml', '-', NULL, 1800.00, 1395.00, NULL, 50, NULL, '10066', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/IA4ff6irFg6PnRA3RwXp.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:07:58', '2025-12-30 04:07:58'),
(87, 'AXIS-Y The Mini Glow Set - (4pcs)', 'axis-y-the-mini-glow-set-4pcs', '-', NULL, 950.00, 599.00, NULL, 50, NULL, '10067', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/hVL5aRWffZPpinFPQF9c.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:08:00', '2025-12-30 04:08:00'),
(88, 'AXIS - Y Dark Spot Correcting Glow Cream 50ml', 'axis-y-dark-spot-correcting-glow-cream-50ml', '-', NULL, 1800.00, 1295.00, NULL, 50, NULL, '10068', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/54RxoEHWLYWeExRnMPHz.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:08:01', '2025-12-30 04:08:01'),
(89, 'AXIS-Y Dark Spot Correcting Glow Serum 50ml', 'axis-y-dark-spot-correcting-glow-serum-50ml', '-', NULL, 1800.00, 1200.00, NULL, 50, NULL, '10069', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/epa3I3u996ducfNxexbQ.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:08:02', '2025-12-30 04:08:02'),
(90, 'AXIS-Y PHA Resurfacing Glow Peel 1.5ml', 'axis-y-pha-resurfacing-glow-peel-15ml', '-', NULL, 300.00, 145.00, NULL, 50, NULL, '10070', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/tcRNFVKAMYYuNfknLYQz.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:08:04', '2025-12-30 04:08:04'),
(91, 'AXIS-Y Dark Spot Correcting Glow Serum 5ml', 'axis-y-dark-spot-correcting-glow-serum-5ml', '-', NULL, 550.00, 185.00, NULL, 50, NULL, '10071', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/Usl8JBVgjigcz4mA2h7v.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:08:05', '2025-12-30 04:08:05'),
(92, 'Banila Co Clean It Zero Cleansing Balm Original 100ml', 'banila-co-clean-it-zero-cleansing-balm-original-100ml', '-', NULL, 1850.00, 1499.00, NULL, 45, NULL, '10075', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/UfmS6Kl2qfOB5TauO9Z1.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-30 04:08:07', '2026-01-28 04:52:43'),
(93, 'Simple Hydrating Light Moisturiser 125ml', 'simple-hydrating-light-moisturiser-125ml', 'Simple Kind to Skin Hydrating Light moisturiser is made with a lightweight and fast-absorbing formulation, to keep your skin feeling soft, smooth and perfectly hydrated for up to 12 hours. This moisturiser is made with skin-loving ingredients such as pro-vitamin B5 and vitamin E. Dermatologically tested and approved, this hydrating moisturiser is perfect for sensitive skin. Hydrating Light Moisturiser contains no artificial perfumes or colours, no alcohol and no harsh chemicals.\r\n\r\n    Moisturises skin, keeping it pure and fresh\r\n    Enriched with Vitamin E, pro-vitamin B5 and Borage Oil\r\n    Makes your skin feel smooth and perfectly hydrated\r\n    No harsh chemicals that can upset your skin\r\n    Makes skin soft, smooth\r\n    Light, silky formula\r\n    Keeps skin hydrated for up to 12 hours\r\n    100% paraben free\r\n    Dermatologically tested\r\n    Best moisturizer for sensitive skin', 'Light, silky formula\r\n    Keeps skin hydrated for up to 12 hours\r\n    100% paraben free\r\n    Dermatologically tested\r\n    Best moisturizer for sensitive skin', 850.00, 599.00, NULL, 16, NULL, '1000058', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/R31qvkNqxhJXyduXhFDovwJAcapkZVhZZoF7a56i.webp', 2, 7, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-14 05:27:49', '2026-01-29 01:33:10'),
(95, 'Boots Sakura Bright Moisturising Cream 50 ml', 'boots-sakura-bright-moisturising-cream-50-mll', 'Boots Sakura Bright Moisturising Cream 50 ml', NULL, 1200.00, 1000.00, NULL, 0, NULL, '525897', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/GeEo34eUfIc0rfpg4VgXo0SPYWfNoj17qB5fSaJ4.png', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-15 03:52:59', '2026-01-29 01:09:07');

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_values`
--

CREATE TABLE `product_attribute_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `attribute_value_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 93, 'products/mFZd2w5angwLQBiBdjebuz6hrWqGYJueOgdwpMNE.jpg', '2026-01-14 05:27:49', '2026-01-14 05:27:49'),
(5, 95, 'products/n12CKK1Nlt8vAg0ul2SKToS7LinlAjlx383CBfVg.webp', '2026-01-15 03:52:59', '2026-01-15 03:52:59'),
(9, 6, 'products/eNa5684RZ4EbnWIbdH8BuWXsk5yzFXTASXLzJEt8.png', '2026-01-15 04:30:40', '2026-01-15 04:30:40'),
(10, 6, 'products/aEeHrTnzYtzpf2yh4uUWZBmuCULnVrjZHSYRKY7e.png', '2026-01-15 04:30:40', '2026-01-15 04:30:40'),
(11, 6, 'products/ZPwayUr2Mvhb63ozKCFPWsjE5HO1L5blMUZq27wk.png', '2026-01-15 04:30:40', '2026-01-15 04:30:40'),
(12, 5, 'products/kLK9cGLWofwlvj6ZBHYeXIehKLYoIoHr8YaSH58D.webp', '2026-01-15 04:51:41', '2026-01-15 04:51:41'),
(13, 1, 'products/rSdghBPTk8uKyIUoITui4AvRuD1DH7dmdOZO1bfE.webp', '2026-01-15 05:16:37', '2026-01-15 05:16:37'),
(14, 1, 'products/tgtKQxxCtmYq7m6jvvD8npPJOYVf0p9thXOPXZ4y.webp', '2026-01-15 05:16:37', '2026-01-15 05:16:37'),
(15, 1, 'products/msScHKXDuubH37hQphGkC3cs9h6wSPVKoXUJgEi2.webp', '2026-01-15 05:16:37', '2026-01-15 05:16:37'),
(16, 74, 'products/0YgLgCAoLsznAzHgAJvcTkySBANI9IbPf1NNdfGB.webp', '2026-01-25 04:15:01', '2026-01-25 04:15:01');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_tag`
--

CREATE TABLE `product_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_tag`
--

INSERT INTO `product_tag` (`id`, `product_id`, `tag_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 2, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `received_by` bigint(20) UNSIGNED DEFAULT NULL,
  `cancelled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `order_date` date NOT NULL,
  `status` enum('draft','pending','ordered','partially_received','received','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `supplier_id`, `warehouse_id`, `created_by`, `updated_by`, `approved_by`, `received_by`, `cancelled_by`, `order_date`, `status`, `notes`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-09', 'ordered', 'This is a test Purchase order', 900.00, '2026-01-01 00:57:25', '2026-01-01 02:21:06');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `received_quantity` int(11) NOT NULL DEFAULT 0,
  `received_date` date DEFAULT NULL,
  `received_by` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `product_id`, `quantity`, `received_quantity`, `received_date`, `received_by`, `unit_price`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 0, NULL, NULL, 900.00, 900.00, '2026-01-01 00:57:25', '2026-01-01 00:57:25');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_status_histories`
--

CREATE TABLE `purchase_order_status_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_status_histories`
--

INSERT INTO `purchase_order_status_histories` (`id`, `purchase_order_id`, `status`, `user_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'ordered', 1, 'Status changed from pending to ordered', '2026-01-01 02:00:05', '2026-01-01 02:00:05'),
(2, 1, 'pending', 1, 'Status changed from ordered to pending', '2026-01-01 02:14:55', '2026-01-01 02:14:55'),
(3, 1, 'cancelled', 1, 'Status changed from pending to cancelled', '2026-01-01 02:15:00', '2026-01-01 02:15:00'),
(4, 1, 'ordered', 1, 'Status changed from cancelled to ordered', '2026-01-01 02:21:06', '2026-01-01 02:21:06');

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `method` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE `role_permission` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `segments`
--

CREATE TABLE `segments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `rules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`rules`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods`
--

CREATE TABLE `shipping_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `min_order` decimal(10,2) DEFAULT NULL,
  `max_order` decimal(10,2) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `name`, `type`, `cost`, `min_order`, `max_order`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Flat Rate', 'flat', 60.00, NULL, NULL, 1, 1, '2026-01-01 05:55:02', '2026-01-01 05:55:02'),
(2, 'Free Delivery', 'free', 0.00, 2000.00, NULL, 1, 2, '2026-01-01 05:55:02', '2026-01-01 05:55:02'),
(3, 'Flat Rate', 'flat', 120.00, NULL, NULL, 1, 1, '2026-01-01 05:55:02', '2026-01-01 05:55:02'),
(4, 'Free Delivery', 'free', 0.00, 2000.00, NULL, 1, 2, '2026-01-01 05:55:02', '2026-01-01 05:55:02');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_method_conditions`
--

CREATE TABLE `shipping_method_conditions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shipping_method_id` bigint(20) UNSIGNED NOT NULL,
  `condition_type` varchar(255) NOT NULL,
  `condition_value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_method_conditions`
--

INSERT INTO `shipping_method_conditions` (`id`, `shipping_method_id`, `condition_type`, `condition_value`, `created_at`, `updated_at`) VALUES
(1, 1, 'free_shipping', 'yes', '2026-01-01 06:29:34', '2026-01-01 06:29:34');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_method_zone`
--

CREATE TABLE `shipping_method_zone` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shipping_method_id` bigint(20) UNSIGNED NOT NULL,
  `shipping_zone_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_method_zone`
--

INSERT INTO `shipping_method_zone` (`id`, `shipping_method_id`, `shipping_zone_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 2),
(4, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_zones`
--

CREATE TABLE `shipping_zones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `postcode` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_zones`
--

INSERT INTO `shipping_zones` (`id`, `name`, `country`, `region`, `postcode`, `created_at`, `updated_at`) VALUES
(1, 'Dhaka', 'Bangladesh', 'Dhaka', NULL, '2026-01-01 05:55:02', '2026-01-01 05:55:02'),
(2, 'Outside Dhaka', 'Bangladesh', NULL, NULL, '2026-01-01 05:55:02', '2026-01-01 05:55:02');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('in','out','adjustment') NOT NULL,
  `quantity` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `type`, `quantity`, `reason`, `user_id`, `supplier_id`, `warehouse_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(2, 2, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(3, 3, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(4, 4, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(5, 5, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(6, 6, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(7, 7, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(8, 8, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(9, 9, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(10, 10, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(11, 11, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(12, 12, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(13, 13, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(14, 14, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(15, 15, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(16, 16, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(17, 17, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(18, 18, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(19, 19, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(20, 20, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(21, 21, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(22, 22, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(23, 23, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(24, 24, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(25, 25, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(26, 26, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(27, 27, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(28, 28, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(29, 29, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(30, 30, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(31, 31, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(32, 32, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(33, 33, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(34, 34, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(35, 35, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(36, 36, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(37, 37, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(38, 38, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(39, 39, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(40, 40, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(41, 41, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(42, 42, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(43, 43, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(44, 44, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(45, 45, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(46, 46, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(47, 47, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(48, 48, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(49, 49, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(50, 50, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(51, 51, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(52, 52, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(53, 53, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(54, 54, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(55, 55, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(56, 56, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(57, 57, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(58, 58, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(59, 59, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(60, 60, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(61, 61, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(62, 62, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(63, 63, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(64, 64, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(65, 65, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(66, 66, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(67, 67, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(68, 68, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(69, 69, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(70, 70, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(71, 71, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(72, 72, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(73, 73, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(74, 74, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(75, 75, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(76, 76, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(77, 77, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(78, 78, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(79, 79, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(80, 80, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(81, 81, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(82, 82, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(83, 83, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(84, 84, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(85, 85, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(86, 86, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(87, 87, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(88, 88, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(89, 89, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(90, 90, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(91, 91, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(92, 92, 'in', 50, 'Initial stock migration', NULL, NULL, NULL, '2025-12-31 00:11:48', '2025-12-31 00:11:48'),
(93, 1, 'in', 20, 'None reason', 1, NULL, NULL, '2025-12-31 00:13:36', '2025-12-31 00:13:36'),
(94, 92, 'out', -1, 'Order Completed (Order ID: 22)', 1, NULL, NULL, '2026-01-28 04:52:43', '2026-01-28 04:52:43'),
(95, 2, 'out', -1, 'Order Completed (Order ID: 23)', 1, NULL, NULL, '2026-01-29 00:52:34', '2026-01-29 00:52:34'),
(96, 7, 'out', -1, 'Order Completed (Order ID: 25)', 1, NULL, NULL, '2026-01-29 01:09:07', '2026-01-29 01:09:07'),
(97, 8, 'out', -1, 'Order Completed (Order ID: 25)', 1, NULL, NULL, '2026-01-29 01:09:07', '2026-01-29 01:09:07'),
(98, 4, 'out', -1, 'Order Completed (Order ID: 25)', 1, NULL, NULL, '2026-01-29 01:09:07', '2026-01-29 01:09:07'),
(99, 6, 'out', -1, 'Order Completed (Order ID: 25)', 1, NULL, NULL, '2026-01-29 01:09:07', '2026-01-29 01:09:07'),
(100, 95, 'out', -1, 'Order Completed (Order ID: 25)', 1, NULL, NULL, '2026-01-29 01:09:07', '2026-01-29 01:09:07'),
(101, 2, 'out', -1, 'Order Completed (Order ID: 23)', 1, NULL, NULL, '2026-01-29 01:10:16', '2026-01-29 01:10:16'),
(102, 74, 'out', -1, 'Order Completed (Order ID: 20)', 1, NULL, NULL, '2026-01-29 01:11:02', '2026-01-29 01:11:02'),
(103, 25, 'out', -1, 'Order Completed (Order ID: 20)', 1, NULL, NULL, '2026-01-29 01:11:02', '2026-01-29 01:11:02'),
(104, 93, 'out', -1, 'Order Completed (Order ID: 26)', 1, NULL, NULL, '2026-01-29 01:15:45', '2026-01-29 01:15:45'),
(105, 93, 'out', -1, 'Order Completed (Order ID: 26)', 1, NULL, NULL, '2026-01-29 01:16:00', '2026-01-29 01:16:00'),
(106, 93, 'in', 19, 'Stock adjustment', 1, NULL, NULL, '2026-01-29 07:23:51', '2026-01-29 07:23:51'),
(107, 25, 'out', -1, 'Order Completed (Order ID: 29)', 1, NULL, NULL, '2026-01-29 02:41:31', '2026-01-29 02:41:31'),
(108, 2, 'out', -1, 'Order Completed (Order ID: 31)', 1, NULL, NULL, '2026-02-02 02:29:49', '2026-02-02 02:29:49'),
(109, 8, 'out', -1, 'Order Completed (Order ID: 31)', 1, NULL, NULL, '2026-02-02 02:29:50', '2026-02-02 02:29:50'),
(110, 10, 'out', -1, 'Order Completed (Order ID: 31)', 1, NULL, NULL, '2026-02-02 02:29:50', '2026-02-02 02:29:50'),
(111, 2, 'out', -1, 'Order Completed (Order ID: 31)', 1, NULL, NULL, '2026-02-02 02:30:14', '2026-02-02 02:30:14'),
(112, 8, 'out', -1, 'Order Completed (Order ID: 31)', 1, NULL, NULL, '2026-02-02 02:30:14', '2026-02-02 02:30:14'),
(113, 10, 'out', -1, 'Order Completed (Order ID: 31)', 1, NULL, NULL, '2026-02-02 02:30:14', '2026-02-02 02:30:14');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `phone`, `email`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Rupchorcha', 'Bellabd', '01948283811', 'rupchorcha@gmail.com', 'Dhanmondi Dhaka', '2025-12-31 00:28:59', '2025-12-31 00:28:59');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Beauty Skin', 'beauty-skin', '2026-01-18 02:27:27', '2026-01-18 02:27:27'),
(2, 'Summer Seals', 'summer-seals', '2026-01-18 02:27:43', '2026-01-18 02:27:43');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_gateway_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'BDT',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `role` enum('super_admin','admin','shop_manager','content_manager','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `otp_code` varchar(255) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `google_id`, `password`, `phone`, `address`, `active`, `role`, `created_at`, `updated_at`, `otp_code`, `otp_expires_at`) VALUES
(1, 'Super Admin', 'superadmin@example.com', NULL, NULL, '$2y$12$mDtrdaF0x6f28UKAB6HNF.KRrV8Ywho5Vv7neehVA8F/O7awByI0y', '01700000001', 'Dhaka', 1, 'super_admin', '2025-12-30 06:10:15', '2026-01-28 03:53:50', NULL, NULL),
(2, 'Admin', 'admin@example.com', NULL, NULL, '$2y$12$zlstio62fcCCZ8.Denmg7uVCLuDBAoKzrxLWZWD09nbFp6ssA9vIO', '01700000002', 'Dhaka', 1, 'admin', '2025-12-30 06:10:15', '2026-01-28 03:53:50', NULL, NULL),
(3, 'Test User', 'test_1768815740@example.com', NULL, NULL, '$2y$12$eFmSlH.Xe8FH2SFReoD0pO664p9rRzgsmLsSepiBq43n34JCB/N8i', '01948283811', NULL, 1, 'customer', '2026-01-19 03:42:20', '2026-01-19 06:55:12', NULL, NULL),
(4, 'Bella Monir', 'bellamonir33@gmail.com', NULL, NULL, '$2y$12$327Lsfphy9EiAtYHMZProO4TKtO.ueyg8eB0IL2HKjv.WWaU6gMhG', '', NULL, 1, 'customer', '2026-01-19 04:50:31', '2026-01-19 04:50:31', NULL, NULL),
(5, 'Test User', 'test_1768822034@example.com', NULL, NULL, '$2y$12$Oh2Myl4l5E/UjyGh9KS83O.7UVf10sWlw/lzFyZcY9nep7kjpef8e', '01719550804', NULL, 1, 'customer', '2026-01-19 05:27:14', '2026-01-19 06:02:30', NULL, NULL),
(6, 'Test User', 'test_1768822877@example.com', NULL, NULL, '$2y$12$Xab82leSfz7IBIbaubVlQ.9mUtsQ5cmB.RF24IPp.ynHM8f.JpdcC', '01719440804', NULL, 1, 'customer', '2026-01-19 05:41:17', '2026-01-19 05:41:45', NULL, NULL),
(7, 'monir Hossain', 'monir@gmail.com', NULL, NULL, '$2y$12$J3M6jIBPEvBugb6aScJqAOPELnGdcxkrwu8hdHBgIpGbt6ffWW85O', '0158695874', NULL, 1, 'customer', '2026-01-20 03:17:00', '2026-01-20 03:17:00', NULL, NULL),
(8, 'mnir', 'monira@gmail.com', NULL, NULL, '$2y$12$waxYBO2fKCEwAYUCQTo.KO8JMotr7q4MMlxZDtYBiC8P99LVVu.Cq', '0185659874', NULL, 1, 'customer', '2026-01-20 04:32:04', '2026-01-20 04:32:04', NULL, NULL),
(9, 'moniraa', 'jalil@gmail.com', NULL, NULL, '$2y$12$Tu6mnGzTx/uVSKu09VRcNuMlPhLZptuDU4imDmo3IQVfD2aXuXbxq', '01698548750', NULL, 1, 'customer', '2026-01-20 04:35:32', '2026-01-20 04:35:32', NULL, NULL),
(10, 'Eva Drong', 'evadrong@gmail.com', NULL, NULL, '$2y$12$/MG91DdMUayWxcfc/.5wQO5tK.1ZggGyOIP107Qhv9VYme9XcmGSC', '01881595103', NULL, 1, 'customer', '2026-01-25 23:15:09', '2026-01-25 23:15:09', NULL, NULL),
(11, 'User', 'monirluvit@gmail.com', '2026-01-26 05:29:04', NULL, '$2y$12$HklsTMYTynIGO6TwOf0AceqGEFNUyhWpsTon12pKatBT4IwnsENfq', '', NULL, 1, 'customer', '2026-01-26 05:29:04', '2026-01-26 05:29:04', NULL, NULL),
(12, 'Lisa', 'lisa@gmail.com', NULL, NULL, '$2y$12$rMO0LHkaYZKFtZI8sYP1X.zb1G8H8ws1C1wsWcXXd0XFRR83GaYdy', '01951318685', 'Dhanmondi', 1, 'customer', '2026-01-28 02:42:43', '2026-01-28 02:42:43', NULL, NULL),
(14, 'Badhan', 'badhanislam@gmail.com', NULL, NULL, '$2y$12$MLMuQQp5.Q5o18DUUeAzSeIAw5FCxyygJvUA3JtOLn5pPn55ffbCW', '01440256987', NULL, 1, 'customer', '2026-01-29 01:12:07', '2026-01-29 01:12:07', NULL, NULL),
(15, 'User', 'info.rupchorcha@gmail.com', '2026-02-02 04:07:56', NULL, '$2y$12$IMVxvI1F9ynwc9WuSysOEur.yh1VFo0vIHPdiboBweO/WOshaW8he', '', NULL, 1, 'customer', '2026-02-02 04:07:56', '2026-02-02 04:07:56', NULL, NULL),
(16, 'Urmi', 'urmi@gmail.com', NULL, NULL, '$2y$12$B.VFBWlHleCdolgGxpLEbevQ13EOJHWOyUEyWBRdNbi9axHQQWtPG', '01858695874', NULL, 1, 'customer', '2026-02-02 05:32:03', '2026-02-02 05:32:03', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_logs`
--

CREATE TABLE `user_activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `manager` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(15, 10, 91, '2026-01-25 23:15:41', '2026-01-25 23:15:41'),
(16, 10, 92, '2026-01-25 23:15:42', '2026-01-25 23:15:42'),
(20, 4, 8, '2026-01-29 00:59:29', '2026-01-29 00:59:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abandoned_checkouts`
--
ALTER TABLE `abandoned_checkouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `abandoned_checkouts_user_id_foreign` (`user_id`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_foreign` (`user_id`);

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_values_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_slug_unique` (`slug`);

--
-- Indexes for table `campaign_histories`
--
ALTER TABLE `campaign_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_histories_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_index` (`user_id`),
  ADD KEY `carts_session_id_index` (`session_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `category_product`
--
ALTER TABLE `category_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_product_product_id_foreign` (`product_id`),
  ADD KEY `category_product_category_id_foreign` (`category_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `couriers`
--
ALTER TABLE `couriers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_conditions`
--
ALTER TABLE `discount_conditions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_order_id_foreign` (`order_id`),
  ADD KEY `invoices_invoice_number_index` (`invoice_number`),
  ADD KEY `invoices_status_index` (`status`);

--
-- Indexes for table `marketing_campaigns`
--
ALTER TABLE `marketing_campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `marketing_campaigns_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_invoice_number_unique` (`invoice_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_shipping_address_id_foreign` (`shipping_address_id`),
  ADD KEY `orders_billing_address_id_foreign` (`billing_address_id`),
  ADD KEY `orders_courier_id_foreign` (`courier_id`),
  ADD KEY `orders_created_by_foreign` (`created_by`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `order_status_histories`
--
ALTER TABLE `order_status_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_status_histories_order_id_foreign` (`order_id`),
  ADD KEY `order_status_histories_changed_by_foreign` (`changed_by`);

--
-- Indexes for table `packing_slips`
--
ALTER TABLE `packing_slips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `packing_slips_slip_number_unique` (`slip_number`),
  ADD KEY `packing_slips_order_id_index` (`order_id`),
  ADD KEY `packing_slips_slip_number_index` (`slip_number`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_gateways_slug_unique` (`slug`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD UNIQUE KEY `products_barcode_unique` (`barcode`),
  ADD KEY `products_supplier_id_foreign` (`supplier_id`),
  ADD KEY `products_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_attribute_values_product_id_foreign` (`product_id`),
  ADD KEY `product_attribute_values_attribute_value_id_foreign` (`attribute_value_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_reviews_product_id_foreign` (`product_id`),
  ADD KEY `product_reviews_user_id_foreign` (`user_id`),
  ADD KEY `product_reviews_order_id_foreign` (`order_id`);

--
-- Indexes for table `product_tag`
--
ALTER TABLE `product_tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_tag_product_id_foreign` (`product_id`),
  ADD KEY `product_tag_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_orders_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_orders_created_by_foreign` (`created_by`),
  ADD KEY `purchase_orders_updated_by_foreign` (`updated_by`),
  ADD KEY `purchase_orders_approved_by_foreign` (`approved_by`),
  ADD KEY `purchase_orders_received_by_foreign` (`received_by`),
  ADD KEY `purchase_orders_cancelled_by_foreign` (`cancelled_by`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `purchase_order_items_product_id_foreign` (`product_id`),
  ADD KEY `purchase_order_items_received_by_foreign` (`received_by`);

--
-- Indexes for table `purchase_order_status_histories`
--
ALTER TABLE `purchase_order_status_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_status_histories_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `purchase_order_status_histories_user_id_foreign` (`user_id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refunds_order_id_foreign` (`order_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`);

--
-- Indexes for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permission_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `segments`
--
ALTER TABLE `segments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping_method_conditions`
--
ALTER TABLE `shipping_method_conditions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_method_conditions_shipping_method_id_foreign` (`shipping_method_id`);

--
-- Indexes for table `shipping_method_zone`
--
ALTER TABLE `shipping_method_zone`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_method_zone_shipping_method_id_foreign` (`shipping_method_id`),
  ADD KEY `shipping_method_zone_shipping_zone_id_foreign` (`shipping_zone_id`);

--
-- Indexes for table `shipping_zones`
--
ALTER TABLE `shipping_zones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_product_id_foreign` (`product_id`),
  ADD KEY `stock_movements_user_id_foreign` (`user_id`),
  ADD KEY `stock_movements_supplier_id_foreign` (`supplier_id`),
  ADD KEY `stock_movements_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tags_name_unique` (`name`),
  ADD UNIQUE KEY `tags_slug_unique` (`slug`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_order_id_foreign` (`order_id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_payment_gateway_id_foreign` (`payment_gateway_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warehouses_name_unique` (`name`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `wishlists_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abandoned_checkouts`
--
ALTER TABLE `abandoned_checkouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attribute_values`
--
ALTER TABLE `attribute_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `campaign_histories`
--
ALTER TABLE `campaign_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `category_product`
--
ALTER TABLE `category_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `couriers`
--
ALTER TABLE `couriers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `discount_conditions`
--
ALTER TABLE `discount_conditions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `marketing_campaigns`
--
ALTER TABLE `marketing_campaigns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `order_status_histories`
--
ALTER TABLE `order_status_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `packing_slips`
--
ALTER TABLE `packing_slips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_tag`
--
ALTER TABLE `product_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_order_status_histories`
--
ALTER TABLE `purchase_order_status_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_permission`
--
ALTER TABLE `role_permission`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `segments`
--
ALTER TABLE `segments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shipping_method_conditions`
--
ALTER TABLE `shipping_method_conditions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shipping_method_zone`
--
ALTER TABLE `shipping_method_zone`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shipping_zones`
--
ALTER TABLE `shipping_zones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `abandoned_checkouts`
--
ALTER TABLE `abandoned_checkouts`
  ADD CONSTRAINT `abandoned_checkouts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD CONSTRAINT `attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `campaign_histories`
--
ALTER TABLE `campaign_histories`
  ADD CONSTRAINT `campaign_histories_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `category_product`
--
ALTER TABLE `category_product`
  ADD CONSTRAINT `category_product_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marketing_campaigns`
--
ALTER TABLE `marketing_campaigns`
  ADD CONSTRAINT `marketing_campaigns_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_billing_address_id_foreign` FOREIGN KEY (`billing_address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_courier_id_foreign` FOREIGN KEY (`courier_id`) REFERENCES `couriers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_shipping_address_id_foreign` FOREIGN KEY (`shipping_address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_status_histories`
--
ALTER TABLE `order_status_histories`
  ADD CONSTRAINT `order_status_histories_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_status_histories_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `packing_slips`
--
ALTER TABLE `packing_slips`
  ADD CONSTRAINT `packing_slips_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD CONSTRAINT `product_attribute_values_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_attribute_values_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_tag`
--
ALTER TABLE `product_tag`
  ADD CONSTRAINT `product_tag_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_orders_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_items_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `purchase_order_status_histories`
--
ALTER TABLE `purchase_order_status_histories`
  ADD CONSTRAINT `purchase_order_status_histories_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_status_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipping_method_conditions`
--
ALTER TABLE `shipping_method_conditions`
  ADD CONSTRAINT `shipping_method_conditions_shipping_method_id_foreign` FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_methods` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipping_method_zone`
--
ALTER TABLE `shipping_method_zone`
  ADD CONSTRAINT `shipping_method_zone_shipping_method_id_foreign` FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_methods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipping_method_zone_shipping_zone_id_foreign` FOREIGN KEY (`shipping_zone_id`) REFERENCES `shipping_zones` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD CONSTRAINT `user_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
