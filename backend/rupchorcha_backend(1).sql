-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2025 at 01:43 PM
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Cosrx', 'cosrx', '2025-12-24 06:13:14', '2025-12-24 06:13:14'),
(2, 'Boots', 'boots', '2025-12-24 06:27:13', '2025-12-24 06:27:13'),
(3, 'CareNel', 'carenel', '2025-12-24 06:27:27', '2025-12-24 06:27:27'),
(4, 'Cetaphil', 'cetaphil', '2025-12-24 06:27:48', '2025-12-24 06:27:48'),
(5, 'Colormax', 'colormax', '2025-12-24 06:28:09', '2025-12-24 06:28:09');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `parent_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Skin', 'skin', NULL, NULL, '2025-12-24 06:13:03', '2025-12-24 06:13:03');

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
(7, '2025_12_24_000002_create_brands_table', 1),
(8, '2025_12_24_000003_create_attributes_table', 1),
(9, '2025_12_24_000004_create_attribute_values_table', 1),
(10, '2025_12_24_000005_create_product_attribute_values_table', 1),
(11, '2025_12_24_000006_create_tags_table', 1),
(12, '2025_12_24_000007_create_product_tag_table', 1),
(13, '2025_12_24_000008_create_product_images_table', 2);

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

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `cost_price`, `stock_quantity`, `stock_in`, `sku`, `type`, `status`, `featured`, `barcode`, `manage_stock`, `external_url`, `meta_title`, `meta_description`, `main_image`, `category_id`, `brand_id`, `min_order_qty`, `max_order_qty`, `sale_start_date`, `sale_end_date`, `created_at`, `updated_at`) VALUES
(1, '3w Clinic Charcoal Cleansing Foam 100ml', 'cosrx-advanced-snail-combo', '-', NULL, 750.00, 395.00, NULL, 50, NULL, '10001', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/sG6GggO1QtNYXK5drFyN.jpg', 1, NULL, NULL, NULL, NULL, NULL, '2025-12-24 05:55:40', '2025-12-24 06:36:42'),
(2, 'Boots Sakura Bright Moisturising Cream 50 ml', 'boots-sakura-bright-moisturising-cream-50-ml', '-', NULL, 1250.00, 695.00, NULL, 50, NULL, '10120', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/UiN38V3SOMY3iUIhBfPU.png', 1, 2, NULL, NULL, NULL, NULL, '2025-12-24 06:30:18', '2025-12-24 06:36:23'),
(3, 'CareNel Cicavita B5 Salicylic Acid Gentle Cleanser 150ml', 'carenel-cicavita-b5-salicylic-acid-gentle-cleanser-150ml', '-', NULL, 1300.00, 1045.00, NULL, 50, NULL, '10121', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/Go4wB2dac1yPdegkPzCU.png', 1, 3, NULL, NULL, NULL, NULL, '2025-12-24 06:31:37', '2025-12-24 06:36:25'),
(4, 'CareNel Derma Alpha Arbutin Glutathione Whitening Cream 45ml', 'carenel-derma-alpha-arbutin-glutathione-whitening-cream-45ml', '-', NULL, 1300.00, 1180.00, NULL, 50, NULL, '10122', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/ugFPaRJChpsfQhe1lwMX.png', 1, 3, NULL, NULL, NULL, NULL, '2025-12-24 06:31:39', '2025-12-24 06:36:26'),
(5, 'CareNel Cicavita B5 Tranexamic Acid Toner 155ml', 'carenel-cicavita-b5-tranexamic-acid-toner-155ml', '-', NULL, 1850.00, 1495.00, NULL, 50, NULL, '10123', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/TorHpM30lcutRL2X42kD.jpg', 1, 3, NULL, NULL, NULL, NULL, '2025-12-24 06:31:40', '2025-12-24 06:36:26'),
(6, 'CareNel Berry Lip Night Mask 5g', 'carenel-berry-lip-night-mask-5g', '-', NULL, 580.00, 445.00, NULL, 50, NULL, '10124', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/tEp5RCT52YqnDUqJxZQk.jpg', 1, 3, NULL, NULL, NULL, NULL, '2025-12-24 06:31:41', '2025-12-24 06:36:27'),
(7, 'CareNel Lime Lip Night Mask 5g', 'carenel-lime-lip-night-mask-5g', '-', NULL, 580.00, 445.00, NULL, 50, NULL, '10125', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/UBZAYyQVy2evi3Pt2wox.jpg', 1, 3, NULL, NULL, NULL, NULL, '2025-12-24 06:31:44', '2025-12-24 06:36:28'),
(8, 'CareNel Apricot Lip Night Mask 5g', 'carenel-apricot-lip-night-mask-5g', '-', NULL, 580.00, 445.00, NULL, 50, NULL, '10126', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/xS3q3r3SIc2XGQEOW5k5.jpg', 1, 3, NULL, NULL, NULL, NULL, '2025-12-24 06:31:46', '2025-12-24 06:36:29'),
(9, 'Carenel Anti Melasma Cica Treatment Toner 155ml', 'carenel-anti-melasma-cica-treatment-toner-155ml', '-', NULL, 1700.00, 1345.00, NULL, 50, NULL, '10127', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/LOM4AOCqdCVmglJaKILq.png', 1, 3, NULL, NULL, NULL, NULL, '2025-12-24 06:31:47', '2025-12-24 06:36:30'),
(10, 'CareNel Anti-Melasma Cica Cream 40ml', 'carenel-anti-melasma-cica-cream-40ml', '-', NULL, 1250.00, 1049.00, NULL, 50, NULL, '10128', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/bucbC2R6vzmy9tNknKXw.png', 1, 3, NULL, NULL, NULL, NULL, '2025-12-24 06:31:47', '2025-12-24 06:36:31'),
(11, 'Celimax Fresh Blackhead Jojoba Cleansing Oil 20ml', 'celimax-fresh-blackhead-jojoba-cleansing-oil-20ml', '-', NULL, 650.00, 295.00, NULL, 50, NULL, '10129', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/NBMpFtcHaVXcFnu3syoS.png', 1, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:31:48', '2025-12-24 06:36:32'),
(12, 'Cetaphil Moisturising Lotion For Normal To Combination, Sensitive Skin 100ml', 'cetaphil-moisturising-lotion-for-normal-to-combination-sensitive-skin-100ml', '-', NULL, 1400.00, 1199.00, NULL, 50, NULL, '10136', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/eduPT5gfHu2S25oIq9z3.png', 1, 4, NULL, NULL, NULL, NULL, '2025-12-24 06:31:49', '2025-12-24 06:36:33'),
(13, 'Cetaphil Gentle Skin Cleanser For Normal To Dry &amp; Sensitive Skin - 236ml', 'cetaphil-gentle-skin-cleanser-for-normal-to-dry-amp-sensitive-skin-236ml', '-', NULL, 1850.00, 1650.00, NULL, 50, NULL, '10137', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/BJq15djxM1wde8wpFDHK.jpg', 1, 4, NULL, NULL, NULL, NULL, '2025-12-24 06:31:50', '2025-12-24 06:36:33'),
(14, 'Cetaphil Gentle Skin Cleanser 59 ml', 'cetaphil-gentle-skin-cleanser-59-ml', '-', NULL, 850.00, 695.00, NULL, 50, NULL, '10138', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/p0T94N5kEQvmV2VzC3wR.avif', 1, 4, NULL, NULL, NULL, NULL, '2025-12-24 06:31:50', '2025-12-24 06:36:34'),
(15, 'Christian Dean Secret Tone Up Sun Cream 70ml', 'christian-dean-secret-tone-up-sun-cream-70ml', '-', NULL, 700.00, 399.00, NULL, 50, NULL, '10139', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/IKE5JOubNV41rHbDjxZn.jpg', 1, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:31:51', '2025-12-24 06:36:35'),
(16, 'Clean &amp; Clear Blackhead Clearing Cleanser  200ml', 'clean-amp-clear-blackhead-clearing-cleanser-200ml', '-', NULL, 950.00, 800.00, NULL, 50, NULL, '10140', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/h4FcNy0T1kV7Dwu7jYgc.jpg', 1, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:31:51', '2025-12-24 06:36:36'),
(17, 'Clean &amp; Clear Morning Energy Shine Control Daily Facial Wash 150ml', 'clean-amp-clear-morning-energy-shine-control-daily-facial-wash-150ml', '-', NULL, 900.00, 625.00, NULL, 50, NULL, '10141', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/5USExUSck1Gqscr2Y9qt.jpg', 1, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:31:52', '2025-12-24 06:36:36'),
(18, 'Clear Men Cooling Itch Control Anti-Dandruff Shampoo 315ml', 'clear-men-cooling-itch-control-anti-dandruff-shampoo-315ml', '-', NULL, 1050.00, 795.00, NULL, 50, NULL, '10142', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/oaPQc8HbNVqv7cQT9jZt.jpg', 1, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:31:54', '2025-12-24 06:36:38'),
(19, 'Colormax Diva Glamour Matte Lipcolour 1.4g- Monaco', 'colormax-diva-glamour-matte-lipcolour-14g-monaco', '-', NULL, 1000.00, 649.00, NULL, 50, NULL, '10143', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/rqa0KgDev2pewTzSAsE5.png', 1, 5, NULL, NULL, NULL, NULL, '2025-12-24 06:31:56', '2025-12-24 06:36:39'),
(20, 'Colormax Diva Glamour Matte Lipcolour 1.4g- Tokyo', 'colormax-diva-glamour-matte-lipcolour-14g-tokyo', '-', NULL, 1000.00, 649.00, NULL, 50, NULL, '10144', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/IMKNtxl1QHogrVyJUAca.png', 1, 5, NULL, NULL, NULL, NULL, '2025-12-24 06:31:56', '2025-12-24 06:36:40'),
(21, 'Colormax Diva Glamour Matte Lipcolour 1.4g- Cape Town', 'colormax-diva-glamour-matte-lipcolour-14g-cape-town', '-', NULL, 1000.00, 649.00, NULL, 50, NULL, '10145', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/r12TyLP5mJIuVYHvK5Tj.png', 1, 5, NULL, NULL, NULL, NULL, '2025-12-24 06:31:57', '2025-12-24 06:36:41'),
(22, 'Colormax Diva Glamour Matte Lipcolour 1.4g- Rio', 'colormax-diva-glamour-matte-lipcolour-14g-rio', '-', NULL, 1000.00, 649.00, NULL, 50, NULL, '10146', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/tWEwJxJUg146xYe2PJte.png', 1, 5, NULL, NULL, NULL, NULL, '2025-12-24 06:31:59', '2025-12-24 06:36:41'),
(23, '3W Clinic Brown Rice Foam Cleansing - 100ml', '3w-clinic-brown-rice-foam-cleansing-100ml', '-', NULL, 750.00, 395.00, NULL, 50, NULL, '10002', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/xU1L8X8VxtiHZxOUDGbN.png', 1, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:01', '2025-12-24 06:36:43'),
(24, '3W Clinic Aloe Vera Soothing Gel-300ml', '3w-clinic-aloe-vera-soothing-gel-300ml', '-', NULL, 1250.00, 845.00, NULL, 50, NULL, '10003', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/gaTisXc7onvxx2pqUlGW.png', 1, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:03', '2025-12-24 06:36:45'),
(25, '3W Clinic Aloe Clear Cleansing Foam - 180ml', '3w-clinic-aloe-clear-cleansing-foam-180ml', '-', NULL, 850.00, 695.00, NULL, 50, NULL, '10004', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/n5p8FkMxkt8Im8MOmVpx.png', 1, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:04', '2025-12-24 06:36:46'),
(26, '3W Clinic Fresh Green Tea Sheet Mask', '3w-clinic-fresh-green-tea-sheet-mask', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10005', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/d7gpKKg0EQ3Lx6o5c255.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:06', '2025-12-24 06:36:47'),
(27, '3W Clinic Fresh Royal Jelly Sheet Mask', '3w-clinic-fresh-royal-jelly-sheet-mask', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10006', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/eKiew7O2HwCbms1qMQmn.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:07', '2025-12-24 06:36:49'),
(28, '3W Clinic Fresh Pomegranate Sheet Mask', '3w-clinic-fresh-pomegranate-sheet-mask', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10007', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/Ov2Q9WIJ6yUVGGzKL7ye.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:08', '2025-12-24 06:36:50'),
(29, '3W Clinic Dr. K Collagen Whitening Cream 100g', '3w-clinic-dr-k-collagen-whitening-cream-100g', '-', NULL, 950.00, 695.00, NULL, 50, NULL, '10008', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/otJ5NFd5pEOx8UNM9co4.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:10', '2025-12-24 06:36:51'),
(30, '3W Clinic UV Sun Block BB Cream - 50g', '3w-clinic-uv-sun-block-bb-cream-50g', '-', NULL, 750.00, 595.00, NULL, 50, NULL, '10009', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/VEMy14IvRXfhnb6Zv1j2.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:11', '2025-12-24 06:36:52'),
(31, '3W Clinic Fresh Collagen Sheet Mask', '3w-clinic-fresh-collagen-sheet-mask', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10010', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/hC7oewvx9HwCbwctvIrw.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:12', '2025-12-24 06:36:53'),
(32, '3W Clinic Dr. K Aloe Whitening Cream- 100g', '3w-clinic-dr-k-aloe-whitening-cream-100g', '-', NULL, 950.00, 695.00, NULL, 50, NULL, '10011', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/UrkDxw3VdNo7yCWaQHUc.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:14', '2025-12-24 06:36:54'),
(33, '3W Clinic Essential Up Snail Sheet Mask', '3w-clinic-essential-up-snail-sheet-mask', '-', NULL, 250.00, 135.00, NULL, 50, NULL, '10012', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/HkKPTDwHG56XyD9QPKw7.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:14', '2025-12-24 06:36:55'),
(34, '3W Clinic Essential Up Rose Sheet Mask', '3w-clinic-essential-up-rose-sheet-mask', '-', NULL, 250.00, 135.00, NULL, 50, NULL, '10013', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/Y23Cj7OD7TkxmVrWtkbn.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:15', '2025-12-24 06:36:55'),
(35, '3w Clinic Vitamin C Foam Cleansing- 100ml', '3w-clinic-vitamin-c-foam-cleansing-100ml', '-', NULL, 750.00, 395.00, NULL, 50, NULL, '10014', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/SROShhtjxHAaWs5wNjne.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:16', '2025-12-24 06:36:56'),
(36, '3W Clinic Dr. K VITA-C Whitening Cream - 100g', '3w-clinic-dr-k-vita-c-whitening-cream-100g', '-', NULL, 950.00, 695.00, NULL, 50, NULL, '10015', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/9JEvNdAAl2CEqWWkVbzE.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:17', '2025-12-24 06:36:57'),
(37, '3W Clinic DR. K Underarm Whitening Multi Cream 100g', '3w-clinic-dr-k-underarm-whitening-multi-cream-100g', '-', NULL, 1100.00, 745.00, NULL, 50, NULL, '10016', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/MapQ7IDJFYxp1HeL4I3a.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:18', '2025-12-24 06:36:58'),
(38, '3W Clinic Moringa Brightening Cool Soothing Gel 160ml', '3w-clinic-moringa-brightening-cool-soothing-gel-160ml', '-', NULL, 850.00, 695.00, NULL, 50, NULL, '10017', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/gsEK8JN4bG87iMekjFaq.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:19', '2025-12-24 06:36:59'),
(39, '3W Clinic Dr. K Black Rice Whitening Cream - 100g', '3w-clinic-dr-k-black-rice-whitening-cream-100g', '-', NULL, 950.00, 695.00, NULL, 50, NULL, '10018', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/DmybSduubERawFo3eVcF.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:20', '2025-12-24 06:37:00'),
(40, '3W Clinic Fresh Potato Sheet Mask', '3w-clinic-fresh-potato-sheet-mask', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10019', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/RPyMPJ0cQeYdl5ekthf8.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:21', '2025-12-24 06:37:02'),
(41, '3W Clinic Fresh Coenzyme Q10 Sheet Mask', '3w-clinic-fresh-coenzyme-q10-sheet-mask', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10020', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/5wcRt1EBWaM6glxHCwAw.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:23', '2025-12-24 06:37:03'),
(42, '3W Clinic Green Tea Foam Cleansing 100ml', '3w-clinic-green-tea-foam-cleansing-100ml', '-', NULL, 750.00, 395.00, NULL, 50, NULL, '10021', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/Uh4zJ53JseHVq5JS8rny.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:24', '2025-12-24 06:37:03'),
(43, '3W Clinic Fresh Cucumber Mask Sheet', '3w-clinic-fresh-cucumber-mask-sheet', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10022', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/XG1QM13pU5pYHPHglq21.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:25', '2025-12-24 06:37:05'),
(44, '3W Clinic Intensive Dr. Kim Sun Cushion SPF50+ PA++++ (15g)', '3w-clinic-intensive-dr-kim-sun-cushion-spf50-pa-15g', '-', NULL, 1050.00, 825.00, NULL, 50, NULL, '10023', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/sFRfWKzVEtWTpI7C5vHN.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:27', '2025-12-24 06:37:06'),
(45, '3W Clinic Premium Vegan Intensive UV Sunblock Cream 60ml', '3w-clinic-premium-vegan-intensive-uv-sunblock-cream-60ml', '-', NULL, 950.00, 599.00, NULL, 50, NULL, '10024', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/4HlxdSvXL5wOXBIqyggn.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:27', '2025-12-24 06:37:06'),
(46, '3W Clinic Honey Whitening Anti-Wrinkle Eye Cream 40ml', '3w-clinic-honey-whitening-anti-wrinkle-eye-cream-40ml', '-', NULL, 550.00, 399.00, NULL, 50, NULL, '10025', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/aO8rEIxAAiRmWpVE4a1L.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:29', '2025-12-24 06:37:07'),
(47, '3W Clinic Fresh White Sheet Mask', '3w-clinic-fresh-white-sheet-mask', '-', NULL, 150.00, 95.00, NULL, 50, NULL, '10026', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/fNEfMCzx4gucwm79kXAS.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:30', '2025-12-24 06:37:08'),
(48, '3W Clinic Intensive Dr.Kim Sun BB Cream SPF50 50ml', '3w-clinic-intensive-drkim-sun-bb-cream-spf50-50ml', '-', NULL, 750.00, 545.00, NULL, 50, NULL, '10027', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/sVeIKwUsTrcTEFIGMxkb.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:32:31', '2025-12-24 06:37:09'),
(49, '3W Clinic Fresh Red Ginseng Sheet Mask', '3w-clinic-fresh-red-ginseng-sheet-mask', '-', NULL, 250.00, 120.00, NULL, 50, NULL, '10028', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/UyYBEojh9W5Dzswx9JoK.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:25', '2025-12-24 06:37:11'),
(50, '3W Clinic Snail Peptide Ball eye Serum-30ml', '3w-clinic-snail-peptide-ball-eye-serum-30ml', '-', NULL, 650.00, 475.00, NULL, 50, NULL, '10029', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/jrKzm43LdQ833bhp5mhm.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:25', '2025-12-24 06:37:12'),
(51, '3W Clinic Multi Protection UV Sun Block SPF 50+/PA+++ (70ml)', '3w-clinic-multi-protection-uv-sun-block-spf-50pa-70ml', '-', NULL, 600.00, 449.00, NULL, 50, NULL, '10030', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/uXWeT8GZMgx35Q0GiY8Q.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:26', '2025-12-24 06:37:13'),
(52, '3W Clinic Collagen Eye Cream 40ml', '3w-clinic-collagen-eye-cream-40ml', '-', NULL, 550.00, 399.00, NULL, 50, NULL, '10031', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/UzZoCv9gG0V2Bh0ARkDf.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:27', '2025-12-24 06:37:13'),
(53, '3W Clinic Intensive Dr.Kim Sun Body Cream 150g', '3w-clinic-intensive-drkim-sun-body-cream-150g', '-', NULL, 1200.00, 845.00, NULL, 50, NULL, '10032', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/PvrMrB3wuisno2IGltjP.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:28', '2025-12-24 06:37:14'),
(54, '3W Clinic Dr. K Snail Whitening Cream - 100g', '3w-clinic-dr-k-snail-whitening-cream-100g', '-', NULL, 950.00, 695.00, NULL, 50, NULL, '10033', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/NuUTAkZMF6CUNMTEcVfU.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:29', '2025-12-24 06:37:16'),
(55, '3W Clinic Intensive Uv Sunblock Cream Spf 50 Pa+++ 70ml', '3w-clinic-intensive-uv-sunblock-cream-spf-50-pa-70ml', '-', NULL, 750.00, 399.00, NULL, 50, NULL, '10034', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/LZVIL8FVRxlw7Z7ctvm3.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:31', '2025-12-24 06:37:17'),
(56, '3W Clinic Intensive UV Sunstick Balm SPF50+ PA++++ (10gm)', '3w-clinic-intensive-uv-sunstick-balm-spf50-pa-10gm', '-', NULL, 650.00, 495.00, NULL, 50, NULL, '10035', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/NKn0vaq3ikm2VdsbQuC4.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:32', '2025-12-24 06:37:18'),
(57, '3W Clinic Collagen Retinol Ball Eye Serum 30ml', '3w-clinic-collagen-retinol-ball-eye-serum-30ml', '-', NULL, 650.00, 475.00, NULL, 50, NULL, '10036', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/m0RGHoo9tzwdatUInmTO.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:33', '2025-12-24 06:37:18'),
(58, '3W Clinic Intensive Green Tea Sunblock Cream SPF50+ Pa+++ 70ml', '3w-clinic-intensive-green-tea-sunblock-cream-spf50-pa-70ml', '-', NULL, 550.00, 449.00, NULL, 50, NULL, '10037', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/5s4FSVLRA9queChh4I0u.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:33', '2025-12-24 06:37:19'),
(59, '3W Clinic Rose Eye Cream Anti-Wrinkle 40ml', '3w-clinic-rose-eye-cream-anti-wrinkle-40ml', '-', NULL, 550.00, 399.00, NULL, 50, NULL, '10038', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/Zvy3VTyuRoolyiAnikS4.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:35', '2025-12-24 06:37:20'),
(60, '3W Clinic Intensive Aloe Sunblock Cream SPF 50+ PA+++ (70ml)', '3w-clinic-intensive-aloe-sunblock-cream-spf-50-pa-70ml', '-', NULL, 550.00, 449.00, NULL, 50, NULL, '10039', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/BB4bt91zIPpwiAaBrpDb.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:36', '2025-12-24 06:37:21'),
(61, '3W Clinic Intensive Dr. Kim Bright Sun Tone Up Cream SPF 35+ PA++ (50ml)', '3w-clinic-intensive-dr-kim-bright-sun-tone-up-cream-spf-35-pa-50ml', '-', NULL, 850.00, 649.00, NULL, 50, NULL, '10041', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/srIGTw3BnihMcJh6x6oM.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:37', '2025-12-24 06:37:22'),
(62, 'Abib Airy Sunstick Smoothing Bar 23g', 'abib-airy-sunstick-smoothing-bar-23g', '-', NULL, 1900.00, 1445.00, NULL, 50, NULL, '10042', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/TmNdwTzjqyHCP3czJUZU.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:38', '2025-12-24 06:37:23'),
(63, 'Abib Quick Sunstick Protection Bar SPF50+ PA++++ 22g', 'abib-quick-sunstick-protection-bar-spf50-pa-22g', '-', NULL, 1900.00, 1445.00, NULL, 50, NULL, '10043', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/n0uO6XaFBdeIdo7osSAa.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:38', '2025-12-24 06:37:23'),
(64, 'Active Nine Intensive UV Shield Airy Sun Soothing Essence 50ml', 'active-nine-intensive-uv-shield-airy-sun-soothing-essence-50ml', '-', NULL, 1450.00, 1145.00, NULL, 50, NULL, '10044', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/0j5sWY2Y1WzHJb4mylqL.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:40', '2025-12-24 06:37:24'),
(65, 'Acwell Licorice pH Balancing Cleansing Toner 150ml', 'acwell-licorice-ph-balancing-cleansing-toner-150ml', '-', NULL, 1650.00, 1399.00, NULL, 50, NULL, '10045', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/bYPy9XmZgwmyv74AbLU4.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:41', '2025-12-24 06:37:25'),
(66, 'Anastasia Beverly Hills Dipbrow Pomade 4g', 'anastasia-beverly-hills-dipbrow-pomade-4g', '-', NULL, 2150.00, 795.00, NULL, 50, NULL, '10046', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/bJwp0XO6TvML5KK0rc5m.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:41', '2025-12-24 06:37:26'),
(67, 'Anua 8 Hyaluronic Acid Hydrating Gentle Foaming Cleanser 150ml', 'anua-8-hyaluronic-acid-hydrating-gentle-foaming-cleanser-150ml', '-', NULL, 1850.00, 1395.00, NULL, 50, NULL, '10047', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/HmPjJJWXGvZO8bnz0wRU.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:43', '2025-12-24 06:37:26'),
(68, 'Anua Heartleaf Pore Clay Pack 100ml', 'anua-heartleaf-pore-clay-pack-100ml', '-', NULL, 1850.00, 1645.00, NULL, 50, NULL, '10048', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/AkdIhhwgrea0CuUn0P6R.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:44', '2025-12-24 06:37:27'),
(69, 'Anua Peach 70% Niacin Serum - 30ml', 'anua-peach-70-niacin-serum-30ml', '-', NULL, 2450.00, 2245.00, NULL, 50, NULL, '10049', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/w3Cj0iO4U7xadVsovP54.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:44', '2025-12-24 06:37:28'),
(70, 'Anua Heartleaf Quercetinol Pore Deep Cleansing Foam 150ml', 'anua-heartleaf-quercetinol-pore-deep-cleansing-foam-150ml', '-', NULL, 1850.00, 1495.00, NULL, 50, NULL, '10050', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/Qf8k7EQNUXmPaIxETJFX.webp', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:46', '2025-12-24 06:37:28'),
(71, 'Anua Heartleaf Pore Control Cleansing Oil - 200ml', 'anua-heartleaf-pore-control-cleansing-oil-200ml', '-', NULL, 2450.00, 1945.00, NULL, 50, NULL, '10051', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/YfI678zHIKUf7zXyXuRd.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:47', '2025-12-24 06:37:29'),
(72, 'Anua Niacinamide 10% + TXA 4% Serum 30ml', 'anua-niacinamide-10-txa-4-serum-30ml', '-', NULL, 2500.00, 1895.00, NULL, 50, NULL, '10052', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/23WHCVKmc7k2SKwhRtAk.webp', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:48', '2025-12-24 06:37:30'),
(73, 'Anua Heartleaf 77% Soothing Toner 40ml', 'anua-heartleaf-77-soothing-toner-40ml', '-', NULL, 1050.00, 499.00, NULL, 50, NULL, '10053', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/i9MZmVSGbejUzddrO8Tc.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:49', '2025-12-24 06:37:30'),
(74, 'Anua Heartleaf Pore Control Cleansing Oil 20ml', 'anua-heartleaf-pore-control-cleansing-oil-20ml', '-', NULL, 850.00, 449.00, NULL, 50, NULL, '10054', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/ZQqPbxg88cRg8BKynUvv.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:49', '2025-12-24 06:37:31'),
(75, 'Astral Face &amp; Body Intensive Moisturiser Cream 50ml', 'astral-face-amp-body-intensive-moisturiser-cream-50ml', '-', NULL, 700.00, 599.00, NULL, 50, NULL, '10055', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/wKDSqiyT1LekDf956vHS.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:50', '2025-12-24 06:37:32'),
(76, 'Aveeno Baby Daily Baby Moisturising Lotion 227g', 'aveeno-baby-daily-baby-moisturising-lotion-227g', '-', NULL, 2250.00, 1950.00, NULL, 50, NULL, '10056', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/Xmxbq8yHEjhwB1XIe6Wo.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:51', '2025-12-24 06:37:33'),
(77, 'Aveeno Protect + Hydrate Sunscreen Broad Spectrum Face Lotion SPF 60 - 88ml', 'aveeno-protect-hydrate-sunscreen-broad-spectrum-face-lotion-spf-60-88ml', '-', NULL, 2050.00, 1945.00, NULL, 50, NULL, '10057', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/0CemgqHXmVgdjXqBRZeT.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:53', '2025-12-24 06:37:34'),
(78, 'Aveeno Baby Soothing Relief Emollient Cream 150ml', 'aveeno-baby-soothing-relief-emollient-cream-150ml', '-', NULL, 2200.00, 1545.00, NULL, 50, NULL, '10058', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/2WEtKy87veTphXXNqRkS.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:55', '2025-12-24 06:37:35'),
(79, 'Aveeno Baby Daily Care Moisturising Lotion For Sensitive Skin 150ml', 'aveeno-baby-daily-care-moisturising-lotion-for-sensitive-skin-150ml', '-', NULL, 1050.00, 845.00, NULL, 50, NULL, '10059', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/Zhw8gYlvdhBpPfuk8sdK.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:55', '2025-12-24 06:37:36'),
(80, 'AXE Dark Temptation Deodorant Body Spray 150ml', 'axe-dark-temptation-deodorant-body-spray-150ml', '-', NULL, 600.00, 425.00, NULL, 50, NULL, '10060', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/dj4pNwhRAK1S1KBSDYwr.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:57', '2025-12-24 06:37:36'),
(81, 'Axe Black Deodorant Body Spray 150ml', 'axe-black-deodorant-body-spray-150ml', '-', NULL, 600.00, 425.00, NULL, 50, NULL, '10061', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/y90YkeR0cQiBHrqHLQX6.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:58', '2025-12-24 06:37:37'),
(82, 'Axe Gold Deodorant Body Spray 150 ml', 'axe-gold-deodorant-body-spray-150-ml', '-', NULL, 600.00, 425.00, NULL, 50, NULL, '10062', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/gG4ij90a7iSvEEphaOsd.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:34:59', '2025-12-24 06:37:38'),
(83, 'AXIS - Y Dark Spot Correcting Glow Toner 125ml', 'axis-y-dark-spot-correcting-glow-toner-125ml', '-', NULL, 1800.00, 1650.00, NULL, 50, NULL, '10063', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/CklvJj4OoLMFQh6X0bEa.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:35:00', '2025-12-24 06:37:38'),
(84, 'AXIS - Y  Biome Double Defense Sunscreen 50ml', 'axis-y-biome-double-defense-sunscreen-50ml', '-', NULL, 1800.00, 1445.00, NULL, 50, NULL, '10064', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/FOLTcJrnFcbGOpq6CLj0.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:35:00', '2025-12-24 06:37:39'),
(85, 'AXIS-Y Vegan Collagen Eye Serum 10ml', 'axis-y-vegan-collagen-eye-serum-10ml', '-', NULL, 1800.00, 1349.00, NULL, 50, NULL, '10065', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/D4j1fjHjyyMH85JfmbDJ.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:35:01', '2025-12-24 06:37:41'),
(86, 'AXIS-Y Complete No-Stress Physical Sunscreen 50ml', 'axis-y-complete-no-stress-physical-sunscreen-50ml', '-', NULL, 1800.00, 1395.00, NULL, 50, NULL, '10066', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/aU3TuoTo0cit8MSk3wm4.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:35:02', '2025-12-24 06:37:41'),
(87, 'AXIS-Y The Mini Glow Set - (4pcs)', 'axis-y-the-mini-glow-set-4pcs', '-', NULL, 950.00, 599.00, NULL, 50, NULL, '10067', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/CHQDz44hBKo78BHGsKSz.webp', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:35:03', '2025-12-24 06:37:42'),
(88, 'AXIS - Y Dark Spot Correcting Glow Cream 50ml', 'axis-y-dark-spot-correcting-glow-cream-50ml', '-', NULL, 1800.00, 1295.00, NULL, 50, NULL, '10068', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/W080kbZZU4jDqNIOL1La.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:35:04', '2025-12-24 06:37:43'),
(89, 'AXIS-Y Dark Spot Correcting Glow Serum 50ml', 'axis-y-dark-spot-correcting-glow-serum-50ml', '-', NULL, 1800.00, 1200.00, NULL, 50, NULL, '10069', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/aZDl5M88BMNgqFNekUZF.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:35:05', '2025-12-24 06:37:44'),
(90, 'AXIS-Y PHA Resurfacing Glow Peel 1.5ml', 'axis-y-pha-resurfacing-glow-peel-15ml', '-', NULL, 300.00, 145.00, NULL, 50, NULL, '10070', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/XRVYYhieAZa6c16d14OI.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:35:07', '2025-12-24 06:37:45'),
(91, 'AXIS-Y Dark Spot Correcting Glow Serum 5ml', 'axis-y-dark-spot-correcting-glow-serum-5ml', '-', NULL, 550.00, 185.00, NULL, 50, NULL, '10071', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/HFuifoSeScE0UaC05U0w.webp', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:35:07', '2025-12-24 06:37:45'),
(92, 'Banila Co Clean It Zero Cleansing Balm Original 100ml', 'banila-co-clean-it-zero-cleansing-balm-original-100ml', '-', NULL, 1850.00, 1499.00, NULL, 50, NULL, '10075', 'simple', 'active', 0, NULL, 1, NULL, NULL, NULL, 'products/iXwWM2YMMpSii1T3MllV.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 06:35:08', '2025-12-24 06:37:46');

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` enum('super_admin','admin','shop_manager','content_manager','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
  ADD UNIQUE KEY `products_barcode_unique` (`barcode`);

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
-- Indexes for table `product_tag`
--
ALTER TABLE `product_tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_tag_product_id_foreign` (`product_id`),
  ADD KEY `product_tag_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tags_name_unique` (`name`),
  ADD UNIQUE KEY `tags_slug_unique` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_tag`
--
ALTER TABLE `product_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD CONSTRAINT `attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

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
-- Constraints for table `product_tag`
--
ALTER TABLE `product_tag`
  ADD CONSTRAINT `product_tag_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
