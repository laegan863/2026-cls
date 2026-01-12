-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2026 at 09:58 AM
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
-- Database: `cls`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional_information`
--

CREATE TABLE `additional_information` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-boost.roster.scan', 'a:2:{s:6:\"roster\";O:21:\"Laravel\\Roster\\Roster\":3:{s:13:\"\0*\0approaches\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:11:\"\0*\0packages\";O:32:\"Laravel\\Roster\\PackageCollection\":2:{s:8:\"\0*\0items\";a:7:{i:0;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^12.0\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:LARAVEL\";s:14:\"\0*\0packageName\";s:17:\"laravel/framework\";s:10:\"\0*\0version\";s:7:\"12.44.0\";s:6:\"\0*\0dev\";b:0;}i:1;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:6:\"v0.3.8\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:PROMPTS\";s:14:\"\0*\0packageName\";s:15:\"laravel/prompts\";s:10:\"\0*\0version\";s:5:\"0.3.8\";s:6:\"\0*\0dev\";b:0;}i:2;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:6:\"v0.5.1\";s:10:\"\0*\0package\";E:33:\"Laravel\\Roster\\Enums\\Packages:MCP\";s:14:\"\0*\0packageName\";s:11:\"laravel/mcp\";s:10:\"\0*\0version\";s:5:\"0.5.1\";s:6:\"\0*\0dev\";b:1;}i:3;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^1.24\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:PINT\";s:14:\"\0*\0packageName\";s:12:\"laravel/pint\";s:10:\"\0*\0version\";s:6:\"1.27.0\";s:6:\"\0*\0dev\";b:1;}i:4;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^1.41\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:SAIL\";s:14:\"\0*\0packageName\";s:12:\"laravel/sail\";s:10:\"\0*\0version\";s:6:\"1.51.0\";s:6:\"\0*\0dev\";b:1;}i:5;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:7:\"^11.5.3\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:PHPUNIT\";s:14:\"\0*\0packageName\";s:15:\"phpunit/phpunit\";s:10:\"\0*\0version\";s:7:\"11.5.46\";s:6:\"\0*\0dev\";b:1;}i:6;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:0:\"\";s:10:\"\0*\0package\";E:41:\"Laravel\\Roster\\Enums\\Packages:TAILWINDCSS\";s:14:\"\0*\0packageName\";s:11:\"tailwindcss\";s:10:\"\0*\0version\";s:6:\"4.1.18\";s:6:\"\0*\0dev\";b:1;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:21:\"\0*\0nodePackageManager\";E:43:\"Laravel\\Roster\\Enums\\NodePackageManager:NPM\";}s:9:\"timestamp\";i:1768202919;}', 1768289319);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `licenses`
--

CREATE TABLE `licenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `primary_contact_info` varchar(255) DEFAULT NULL,
  `legal_name` varchar(255) DEFAULT NULL,
  `dba` varchar(255) DEFAULT NULL,
  `fein` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `permit_type` varchar(255) DEFAULT NULL,
  `permit_subtype` varchar(255) DEFAULT NULL,
  `jurisdiction_country` varchar(255) DEFAULT NULL,
  `jurisdiction_state` varchar(255) DEFAULT NULL,
  `jurisdiction_city` varchar(255) DEFAULT NULL,
  `jurisdiction_federal` varchar(255) DEFAULT NULL,
  `agency_name` varchar(255) DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `renewal_window_open_date` date DEFAULT NULL,
  `assigned_agent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `renewal_status` enum('closed','open','expired') NOT NULL DEFAULT 'closed',
  `billing_status` enum('closed','open','invoiced','paid','overridden') NOT NULL DEFAULT 'closed',
  `submission_confirmation_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','incomplete','approved','active','expired','renewable') NOT NULL DEFAULT 'pending',
  `workflow_status` enum('pending_validation','requirements_pending','requirements_submitted','approved','active','payment_pending','payment_completed','completed','rejected','expired') DEFAULT 'pending_validation',
  `validated_at` timestamp NULL DEFAULT NULL,
  `validated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `licenses`
--

INSERT INTO `licenses` (`id`, `transaction_id`, `client_id`, `email`, `primary_contact_info`, `legal_name`, `dba`, `fein`, `country`, `state`, `city`, `zip_code`, `permit_type`, `permit_subtype`, `jurisdiction_country`, `jurisdiction_state`, `jurisdiction_city`, `jurisdiction_federal`, `agency_name`, `expiration_date`, `renewal_window_open_date`, `assigned_agent_id`, `renewal_status`, `billing_status`, `submission_confirmation_number`, `created_at`, `updated_at`, `status`, `workflow_status`, `validated_at`, `validated_by`, `approved_at`, `approved_by`, `rejection_reason`) VALUES
(1, 'z0YQV6WZSpDi', 3, 'laegan863@gmail.com', '09762016124', 'Test', 'Test', 'Test', 'US', 'CA', 'Alhambra', '72111', 'Test', 'Sub Type Index', 'US', 'DC', 'Chevy Chase', 'Test', 'Test', '2027-01-31', NULL, NULL, 'closed', 'closed', '09762016124', '2026-01-11 22:04:59', '2026-01-12 00:56:17', 'pending', 'active', NULL, NULL, '2026-01-11 23:33:17', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `license_payments`
--

CREATE TABLE `license_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `license_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','open','paid','cancelled','overridden') NOT NULL DEFAULT 'draft',
  `payment_method` enum('online','offline') DEFAULT NULL,
  `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
  `stripe_checkout_session_id` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `paid_by` bigint(20) UNSIGNED DEFAULT NULL,
  `overridden_by` bigint(20) UNSIGNED DEFAULT NULL,
  `override_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `license_payments`
--

INSERT INTO `license_payments` (`id`, `license_id`, `created_by`, `invoice_number`, `total_amount`, `status`, `payment_method`, `stripe_payment_intent_id`, `stripe_checkout_session_id`, `notes`, `paid_at`, `paid_by`, `overridden_by`, `override_reason`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'INV-FVXWEESX-20260112', 1830.00, 'paid', 'online', 'pi_3Sofk0IjnnrGYyuF2gJXtKLU', 'cs_test_b10hqlXIYT93YcRO2xK5KAc4HlytKXczCThxuz89pxGRwUOzNIRk94rerB', 'Hello World', '2026-01-11 23:40:49', 3, NULL, NULL, '2026-01-11 23:34:33', '2026-01-11 23:40:49'),
(2, 1, 1, 'INV-M5R8GSG7-20260112', 2400.00, 'paid', 'offline', NULL, NULL, 'test\n\n=== POS TRANSACTION ===\nDate: Jan 12, 2026 08:30 AM\nCashier: Admin User\n------------------------\nTotal Due: $2,400.00\nAmount Received: $2,400.00\nChange Given: $0.00\n------------------------\nNotes: 132332', '2026-01-12 00:30:37', 1, NULL, NULL, '2026-01-12 00:29:34', '2026-01-12 00:30:37'),
(3, 1, 1, 'INV-BNGUIEUH-20260112', 1500.00, 'paid', 'online', 'pi_3SoguOIjnnrGYyuF0RvXz6Oq', 'cs_test_b17iZIY2mX7IQDDaiUIE6lN2M9gfdRpBHoGDs46K48WSpR3nY7hlVOTG3e', 'testing', '2026-01-12 00:55:38', 3, NULL, NULL, '2026-01-12 00:53:42', '2026-01-12 00:55:38');

-- --------------------------------------------------------

--
-- Table structure for table `license_payment_items`
--

CREATE TABLE `license_payment_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `license_payment_id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `license_payment_items`
--

INSERT INTO `license_payment_items` (`id`, `license_payment_id`, `label`, `description`, `amount`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Test1', 'Test 1 description', 1200.00, 0, '2026-01-11 23:34:33', '2026-01-11 23:34:33'),
(2, 1, 'Test2', 'Test 2 Desc', 500.00, 1, '2026-01-11 23:34:33', '2026-01-11 23:34:33'),
(3, 1, 'Test3', 'Test 3 Desc', 130.00, 2, '2026-01-11 23:34:33', '2026-01-11 23:34:33'),
(4, 2, 'Test1', 'Test 1 description', 1400.00, 0, '2026-01-12 00:29:34', '2026-01-12 00:29:34'),
(5, 2, 'Test 2', 'Test 2 Desc', 1000.00, 1, '2026-01-12 00:29:34', '2026-01-12 00:29:34'),
(6, 3, 'Test1', 'Test 1 description', 1400.00, 0, '2026-01-12 00:53:42', '2026-01-12 00:53:42'),
(7, 3, 'Test2', 'Test2', 100.00, 1, '2026-01-12 00:53:42', '2026-01-12 00:53:42');

-- --------------------------------------------------------

--
-- Table structure for table `license_requirements`
--

CREATE TABLE `license_requirements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `license_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','submitted','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `license_requirements`
--

INSERT INTO `license_requirements` (`id`, `license_id`, `created_by`, `label`, `description`, `value`, `file_path`, `status`, `rejection_reason`, `submitted_at`, `reviewed_at`, `reviewed_by`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Test', 'Test1', 'test', NULL, 'approved', NULL, '2026-01-11 23:31:32', '2026-01-11 23:32:49', 1, '2026-01-11 23:30:47', '2026-01-11 23:32:49'),
(2, 1, 2, 'Test2', 'Test2', 'test', NULL, 'approved', NULL, '2026-01-11 23:31:40', '2026-01-11 23:32:54', 1, '2026-01-11 23:30:47', '2026-01-11 23:32:54');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_09_000001_create_roles_table', 1),
(5, '2026_01_09_000002_create_permissions_table', 1),
(6, '2026_01_09_000003_create_permit_types_table', 1),
(7, '2026_01_09_000004_update_users_table', 1),
(8, '2026_01_09_000005_create_modules_table', 1),
(9, '2026_01_11_003806_create_licenses_table', 1),
(10, '2026_01_11_071131_remove_sub_type_from_permit_types_table', 1),
(11, '2026_01_11_071602_create_permit_sub_types_table', 1),
(12, '2026_01_11_125623_add_transaction_id_to_licenses_table', 1),
(13, '2026_01_12_064711_add_status_to_licenses_table', 2),
(14, '2026_01_12_000001_create_license_requirements_table', 3),
(15, '2026_01_12_000002_create_license_payments_table', 3),
(16, '2026_01_12_000003_add_workflow_status_to_licenses_table', 3),
(17, '2026_01_12_000004_create_notifications_table', 3),
(18, '2026_01_12_070526_create_additional_information_table', 3),
(19, '2026_01_12_100000_add_active_expired_to_workflow_status', 4),
(20, '2026_01_12_160000_update_renewal_billing_status_enums', 5);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_coming_soon` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `slug`, `icon`, `route`, `description`, `parent_id`, `order`, `is_active`, `is_coming_soon`, `created_at`, `updated_at`) VALUES
(1, 'Dashboard', 'dashboard', 'bi bi-speedometer2', 'admin.dashboard', 'Main dashboard overview', NULL, 1, 1, 0, '2026-01-11 21:21:39', '2026-01-11 21:21:39'),
(2, 'Licensing & Permitting', 'licensing-permitting', 'bi bi-file-earmark-text', 'admin.licenses', 'Manage licenses and permits', NULL, 2, 1, 0, '2026-01-11 21:21:39', '2026-01-11 21:36:33'),
(3, 'Reports', 'reports', 'bi bi-bar-chart', NULL, 'System reports and analytics', NULL, 3, 1, 1, '2026-01-11 21:21:39', '2026-01-11 21:21:39'),
(4, 'Trade', 'trade', 'bi bi-arrow-left-right', NULL, 'Trade management module', NULL, 4, 1, 1, '2026-01-11 21:21:39', '2026-01-11 21:21:39'),
(5, 'Property Tax', 'property-tax', 'bi bi-house', NULL, 'Property tax management', NULL, 5, 1, 1, '2026-01-11 21:21:39', '2026-01-11 21:21:39'),
(6, 'Accounting', 'accounting', 'bi bi-journal-text', NULL, 'Accounting and financial management', NULL, 6, 1, 1, '2026-01-11 21:21:39', '2026-01-11 21:21:39'),
(7, 'TCEQ / SIR', 'tceq-sir', 'bi bi-shield-check', NULL, 'TCEQ and SIR compliance management', NULL, 7, 1, 1, '2026-01-11 21:21:39', '2026-01-11 21:21:39'),
(8, 'User Management', 'user-management', 'bi bi-people', 'admin.users.index', 'Manage system users', NULL, 8, 1, 0, '2026-01-11 21:21:39', '2026-01-11 21:21:39'),
(9, 'Admin Control Center', 'admin-control-center', 'bi bi-gear', 'admin.settings', 'System administration and settings', NULL, 9, 1, 0, '2026-01-11 21:21:39', '2026-01-11 21:21:39'),
(10, 'Roles', 'roles', 'bi bi-shield-shaded', 'admin.roles.index', 'Manage user roles', 9, 1, 1, 0, '2026-01-11 21:21:39', '2026-01-11 21:21:39'),
(11, 'Permissions', 'permissions', 'bi bi-key', 'admin.permissions.index', 'Manage role permissions', 9, 2, 1, 0, '2026-01-11 21:21:39', '2026-01-11 21:21:39'),
(12, 'Modules', 'modules', 'bi bi-grid-3x3-gap', 'admin.modules.index', 'Manage system modules', 9, 3, 1, 0, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(13, 'Permit Types', 'permit-types', 'bi bi-card-list', 'admin.permit-types.index', 'Manage permit types', 9, 4, 1, 0, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(14, 'Permit Sub Type', 'permit-sub-type', 'bi bi-circle', 'admin.permit-sub-types.index', 'This module is for permit sub type', 9, 5, 1, 0, '2026-01-11 21:55:20', '2026-01-11 21:56:41');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('043e7332-58d2-42cb-a127-9de0b9aba226', 'App\\Notifications\\PaymentCompletedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_completed\",\"license_id\":1,\"payment_id\":1,\"transaction_id\":\"z0YQV6WZSpDi\",\"invoice_number\":\"INV-FVXWEESX-20260112\",\"amount\":\"1830.00\",\"payment_method\":\"online\",\"message\":\"Payment of $1,830.00 received.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/licenses\\/1\"}', NULL, '2026-01-11 23:40:51', '2026-01-11 23:40:51'),
('32919a58-87c3-46f4-9df3-e2bb7b25c47e', 'App\\Notifications\\RequirementStatusNotification', 'App\\Models\\User', 3, '{\"type\":\"requirement_status\",\"license_id\":1,\"transaction_id\":\"z0YQV6WZSpDi\",\"status\":\"approved\",\"reason\":null,\"message\":\"Your license application has been approved!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/licenses\\/1\\/payments\"}', NULL, '2026-01-11 23:33:18', '2026-01-11 23:33:18'),
('3b9ae4a6-609e-4998-ab17-a73424086395', 'App\\Notifications\\PaymentCompletedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_completed\",\"license_id\":1,\"payment_id\":3,\"transaction_id\":\"z0YQV6WZSpDi\",\"invoice_number\":\"INV-BNGUIEUH-20260112\",\"amount\":\"1500.00\",\"payment_method\":\"online\",\"message\":\"Payment of $1,500.00 received.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/licenses\\/1\"}', NULL, '2026-01-12 00:55:39', '2026-01-12 00:55:39'),
('71fe33e2-e253-4be4-aeca-dadd8670b736', 'App\\Notifications\\PaymentCreatedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_created\",\"license_id\":1,\"payment_id\":3,\"transaction_id\":\"z0YQV6WZSpDi\",\"invoice_number\":\"INV-BNGUIEUH-20260112\",\"amount\":\"1500.00\",\"message\":\"A payment of $1,500.00 is required.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/licenses\\/1\\/payments\"}', NULL, '2026-01-12 00:53:43', '2026-01-12 00:53:43'),
('78496130-da57-4b64-9a41-8ceb7eab3645', 'App\\Notifications\\RequirementStatusNotification', 'App\\Models\\User', 3, '{\"type\":\"requirement_status\",\"license_id\":1,\"transaction_id\":\"z0YQV6WZSpDi\",\"status\":\"approved\",\"reason\":null,\"message\":\"Your license application has been approved!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/licenses\\/1\\/payments\"}', NULL, '2026-01-11 23:32:55', '2026-01-11 23:32:55'),
('8cd22f72-a150-4d33-8fb3-26df66be449d', 'App\\Notifications\\PaymentCompletedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_completed\",\"license_id\":1,\"payment_id\":2,\"transaction_id\":\"z0YQV6WZSpDi\",\"invoice_number\":\"INV-M5R8GSG7-20260112\",\"amount\":\"2400.00\",\"payment_method\":\"offline\",\"message\":\"Payment of $2,400.00 received.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/licenses\\/1\"}', NULL, '2026-01-12 00:30:38', '2026-01-12 00:30:38'),
('91e3bcbf-cd84-4b17-a521-bad6ac91d806', 'App\\Notifications\\PaymentCreatedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_created\",\"license_id\":1,\"payment_id\":2,\"transaction_id\":\"z0YQV6WZSpDi\",\"invoice_number\":\"INV-M5R8GSG7-20260112\",\"amount\":\"2400.00\",\"message\":\"A payment of $2,400.00 is required.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/licenses\\/1\\/payments\"}', NULL, '2026-01-12 00:29:35', '2026-01-12 00:29:35'),
('c10387c9-2512-45bb-a7a1-26e2248545bb', 'App\\Notifications\\RequirementAddedNotification', 'App\\Models\\User', 3, '{\"type\":\"requirement_added\",\"license_id\":1,\"transaction_id\":\"z0YQV6WZSpDi\",\"message\":\"New requirements have been added to your license application.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/licenses\\/1\\/requirements\"}', NULL, '2026-01-11 23:30:48', '2026-01-11 23:30:48'),
('d011e09e-5ecc-4750-b006-539d41209339', 'App\\Notifications\\PaymentCreatedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_created\",\"license_id\":1,\"payment_id\":1,\"transaction_id\":\"z0YQV6WZSpDi\",\"invoice_number\":\"INV-FVXWEESX-20260112\",\"amount\":\"1830.00\",\"message\":\"A payment of $1,830.00 is required.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/licenses\\/1\\/payments\"}', NULL, '2026-01-11 23:34:34', '2026-01-11 23:34:34');

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `module` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permit_sub_types`
--

CREATE TABLE `permit_sub_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permit_sub_types`
--

INSERT INTO `permit_sub_types` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Sub Type Index', 1, '2026-01-11 21:56:58', '2026-01-11 21:56:58');

-- --------------------------------------------------------

--
-- Table structure for table `permit_types`
--

CREATE TABLE `permit_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permit_type` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permit_types`
--

INSERT INTO `permit_types` (`id`, `permit_type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Test', 1, '2026-01-11 21:54:28', '2026-01-11 21:54:28');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'Full access to all modules and functionalities of the system', 1, '2026-01-11 21:21:39', '2026-01-11 21:21:39'),
(2, 'Agent', 'agent', 'Admin can assign specific modules and functionalities', 1, '2026-01-11 21:21:39', '2026-01-11 21:21:39'),
(3, 'Client', 'client', 'Admin can assign specific modules and functionalities', 1, '2026-01-11 21:21:39', '2026-01-11 21:21:39');

-- --------------------------------------------------------

--
-- Table structure for table `role_module`
--

CREATE TABLE `role_module` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT 1,
  `can_create` tinyint(1) NOT NULL DEFAULT 0,
  `can_edit` tinyint(1) NOT NULL DEFAULT 0,
  `can_delete` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_module`
--

INSERT INTO `role_module` (`id`, `role_id`, `module_id`, `can_view`, `can_create`, `can_edit`, `can_delete`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(2, 1, 2, 1, 1, 1, 1, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(3, 1, 3, 1, 1, 1, 1, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(4, 1, 4, 1, 1, 1, 1, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(5, 1, 5, 1, 1, 1, 1, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(6, 1, 6, 1, 1, 1, 1, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(7, 1, 7, 1, 1, 1, 1, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(8, 1, 8, 1, 1, 1, 1, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(9, 1, 9, 1, 1, 1, 1, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(10, 1, 10, 1, 1, 1, 1, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(11, 1, 11, 1, 1, 1, 1, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(12, 1, 12, 1, 1, 1, 1, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(13, 1, 13, 1, 1, 1, 1, '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(14, 2, 1, 1, 1, 1, 0, '2026-01-11 21:49:58', '2026-01-11 21:49:58'),
(15, 2, 2, 1, 1, 1, 0, '2026-01-11 21:49:58', '2026-01-11 21:49:58'),
(16, 3, 1, 1, 0, 0, 0, '2026-01-11 21:53:30', '2026-01-11 21:53:30'),
(17, 3, 2, 1, 1, 0, 0, '2026-01-11 21:53:30', '2026-01-11 21:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE `role_permission` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('A9eWaMrCuIQnZHaKImLIIb2je4UGK3ld9UM36KTx', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU3VnaVN1NW9iZUszNUNzcGtxR3p0V3pkNnFaZHJ1WkxtbW9zQWtCcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9saWNlbnNlcy8xL3BheW1lbnRzIjtzOjU6InJvdXRlIjtzOjI4OiJhZG1pbi5saWNlbnNlcy5wYXltZW50cy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1768207603),
('bAkBUM0z31xPaC5URosBfKKeWsv86KgBvd5CPlFX', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM0V5bmRja3luSUtpbG1Qa0EzazViOXNNQmp2OE1HUktubmpxTFpaOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9saWNlbnNlcy9jcmVhdGUiO3M6NToicm91dGUiO3M6MjE6ImFkbWluLmxpY2Vuc2VzLmNyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1768208217),
('yV0Ye5eZ5X5gaSKDegL8DkEOFKwUaPtKoROCr5mP', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS1VaejA5NE8wTk5ZRXhGWVBWU2I4dHE1UmVvYnlacHBkUmhvS1RzNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9saWNlbnNlcy8xIjtzOjU6InJvdXRlIjtzOjE5OiJhZG1pbi5saWNlbnNlcy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1768208140);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `contact_no`, `role_id`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@gmail.com', NULL, 1, 1, '2026-01-11 21:21:40', '$2y$12$Eoum5OZwfGuP3zBFjnjM8udq/3vlLLlPNmUh6tjQF9.BiZH3Ev.U2', 'HafLd4pFDt', '2026-01-11 21:21:40', '2026-01-11 21:21:40'),
(2, 'Laegan Sasoy Pangantihon', 'laerielle1423@gmail.com', '09947622663', 2, 1, NULL, '$2y$12$TXnAa.1wtESYrfqk94dLLePglJOcMwzlpDSh9Y9Q5LSKBZWjuN30.', NULL, '2026-01-11 21:49:06', '2026-01-11 21:49:06'),
(3, 'Laegan', 'laegan863@gmail.com', '09762016124', 3, 1, NULL, '$2y$12$P0Qs8cam84VrgBia6mtRu.dPINh4TbsFjgM3inFc68uHiYOo2fbIO', NULL, '2026-01-11 21:52:51', '2026-01-11 21:52:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additional_information`
--
ALTER TABLE `additional_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `licenses`
--
ALTER TABLE `licenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `licenses_client_id_foreign` (`client_id`),
  ADD KEY `licenses_assigned_agent_id_foreign` (`assigned_agent_id`),
  ADD KEY `licenses_validated_by_foreign` (`validated_by`),
  ADD KEY `licenses_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `license_payments`
--
ALTER TABLE `license_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_payments_invoice_number_unique` (`invoice_number`),
  ADD KEY `license_payments_license_id_foreign` (`license_id`),
  ADD KEY `license_payments_created_by_foreign` (`created_by`),
  ADD KEY `license_payments_paid_by_foreign` (`paid_by`),
  ADD KEY `license_payments_overridden_by_foreign` (`overridden_by`);

--
-- Indexes for table `license_payment_items`
--
ALTER TABLE `license_payment_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `license_payment_items_license_payment_id_foreign` (`license_payment_id`);

--
-- Indexes for table `license_requirements`
--
ALTER TABLE `license_requirements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `license_requirements_license_id_foreign` (`license_id`),
  ADD KEY `license_requirements_created_by_foreign` (`created_by`),
  ADD KEY `license_requirements_reviewed_by_foreign` (`reviewed_by`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `modules_slug_unique` (`slug`),
  ADD KEY `modules_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`);

--
-- Indexes for table `permit_sub_types`
--
ALTER TABLE `permit_sub_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permit_types`
--
ALTER TABLE `permit_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indexes for table `role_module`
--
ALTER TABLE `role_module`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_module_role_id_module_id_unique` (`role_id`,`module_id`),
  ADD KEY `role_module_module_id_foreign` (`module_id`);

--
-- Indexes for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permission_role_id_permission_id_unique` (`role_id`,`permission_id`),
  ADD KEY `role_permission_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additional_information`
--
ALTER TABLE `additional_information`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `licenses`
--
ALTER TABLE `licenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `license_payments`
--
ALTER TABLE `license_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `license_payment_items`
--
ALTER TABLE `license_payment_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `license_requirements`
--
ALTER TABLE `license_requirements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permit_sub_types`
--
ALTER TABLE `permit_sub_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permit_types`
--
ALTER TABLE `permit_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role_module`
--
ALTER TABLE `role_module`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `role_permission`
--
ALTER TABLE `role_permission`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `licenses`
--
ALTER TABLE `licenses`
  ADD CONSTRAINT `licenses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `licenses_assigned_agent_id_foreign` FOREIGN KEY (`assigned_agent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `licenses_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `licenses_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `license_payments`
--
ALTER TABLE `license_payments`
  ADD CONSTRAINT `license_payments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `license_payments_license_id_foreign` FOREIGN KEY (`license_id`) REFERENCES `licenses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `license_payments_overridden_by_foreign` FOREIGN KEY (`overridden_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `license_payments_paid_by_foreign` FOREIGN KEY (`paid_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `license_payment_items`
--
ALTER TABLE `license_payment_items`
  ADD CONSTRAINT `license_payment_items_license_payment_id_foreign` FOREIGN KEY (`license_payment_id`) REFERENCES `license_payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `license_requirements`
--
ALTER TABLE `license_requirements`
  ADD CONSTRAINT `license_requirements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `license_requirements_license_id_foreign` FOREIGN KEY (`license_id`) REFERENCES `licenses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `license_requirements_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_module`
--
ALTER TABLE `role_module`
  ADD CONSTRAINT `role_module_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_module_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
