-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2024 at 08:37 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warka_one`
--

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dial_code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_length` int(11) NOT NULL DEFAULT 10,
  `flag_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `dial_code`, `country_code`, `phone_length`, `flag_url`, `created_at`, `updated_at`) VALUES
(1, 'Ethiopia', '+251', 'ET', 9, 'flags/et.svg', '2023-12-31 08:04:27', '2023-12-31 08:04:27');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2013_12_22_084715_create_countries_table', 1),
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(4, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(5, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(6, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(7, '2016_06_01_000004_create_oauth_clients_table', 1),
(8, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(9, '2019_08_19_000000_create_failed_jobs_table', 1),
(10, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(11, '2023_12_23_204710_create_verification_codes_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('0b98c0fb8550284446c09a70ff1f704acb84231e12c7171c6a8e6612c0463b99a6f8db81345734b6', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, NULL, '[]', 0, '2024-01-08 16:24:00', '2024-01-08 16:24:00', '2025-01-08 19:24:00'),
('1b112f81900a25863676a4332c8ca5d1dd141b095b43a5c394574b841305d3c18b173ebffa73f846', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, NULL, '[]', 0, '2024-01-08 16:25:49', '2024-01-08 16:25:49', '2025-01-08 19:25:49'),
('4a578fa9a69b9a5e4c5f556711e1826dc26236fdb3bc1a65851ae311b8eda8a1665c4c55305e2ead', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, NULL, '[]', 0, '2024-01-09 16:45:02', '2024-01-09 16:45:02', '2025-01-09 19:45:02'),
('4b527762887c78583f3cef5191e5fcf83cac10c959671a30fa69b457a970484062acf3685f614b18', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, NULL, '[]', 0, '2024-01-09 05:18:38', '2024-01-09 05:18:38', '2025-01-09 08:18:38'),
('5e55617c29315423a910b5f77337d20142bfe320b18f718bd8b68fd847995a4e3e8e9a3ea1802867', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, NULL, '[]', 0, '2024-01-08 16:36:26', '2024-01-08 16:36:26', '2025-01-08 19:36:26'),
('7906d965241bdde50445137f7439976b5dc92c4e551cd5c6d62206cf0cdff74cf695b832cd01cdff', '922b628e-73e4-485a-8ead-fc4536a51abe', 3, NULL, '[]', 0, '2024-01-09 05:18:46', '2024-01-09 05:18:46', '2025-01-09 08:18:46'),
('7e1f1f87a61ba4c8cee0076e442fafe21fd8f270dbfa762431f9d369c5460ffbcfffa74467101e19', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, NULL, '[]', 0, '2024-01-08 16:37:17', '2024-01-08 16:37:17', '2025-01-08 19:37:17'),
('9ad7caa5d9a6e59638e6ce5820d486b254524d83d9834407cbfb17269fc3f9fef897144bbe80f7a6', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, NULL, '[]', 0, '2024-01-08 16:24:53', '2024-01-08 16:24:53', '2025-01-08 19:24:53'),
('ada3607527b0a8acf1d48a1cd73588506af6071b0d8cf6e6e0fac6b699abfd0941606388b0c0aead', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, NULL, '[]', 0, '2024-01-09 05:15:28', '2024-01-09 05:15:28', '2025-01-09 08:15:28'),
('af51804a1d6a26705e040711e2a22a067a22164bc48b016203ca51a458bdf93d904e61da6717a3fb', '63f2d82c-72ab-4b53-b666-59e0577fe2fd', 2, NULL, '[]', 0, '2024-01-11 05:54:52', '2024-01-11 05:54:52', '2025-01-11 08:54:52'),
('ceaeb4e8112180545bb6cb1cbbd75cb5fbd64f338d9e3f905df7b5de743aa563c3c3273d3221b0fe', '63f2d82c-72ab-4b53-b666-59e0577fe2fd', 2, NULL, '[]', 0, '2024-01-10 02:44:28', '2024-01-10 02:44:28', '2025-01-10 05:44:28'),
('dedbb0e67cd7e3c980f6514fa8dfc950c3ce1ded93bcd075a3b3af242b7923bb614fb54b0f988085', '63f2d82c-72ab-4b53-b666-59e0577fe2fd', 2, NULL, '[]', 0, '2024-01-09 16:19:59', '2024-01-09 16:19:59', '2025-01-09 19:19:59'),
('efd143c7d8b28f1dbd6f3b38b47541babc669bcc18d50a0569dbf796d67439f9656705995e34f046', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, NULL, '[]', 0, '2024-01-08 16:25:06', '2024-01-08 16:25:06', '2025-01-08 19:25:06');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_auth_codes`
--

INSERT INTO `oauth_auth_codes` (`id`, `user_id`, `client_id`, `scopes`, `revoked`, `expires_at`) VALUES
('042d1275f0d031b155401244138533ba28c4874a985ff521d0336bebf46feeec84977efde2462d1c', '63f2d82c-72ab-4b53-b666-59e0577fe2fd', 3, '[]', 1, '2024-01-08 12:29:23'),
('099aaf7983de1f6afd377f3d7f620af7e9faf0dfdd8df855452ed552a6cfb275889c81c34f5985b2', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, '[]', 1, '2024-01-08 03:31:37'),
('0e28888bcdc7e5ebc0c45f652c654b21b49960d45e9ffcf11a1cd98a87edda429bfdfd03e40ad4bd', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, '[]', 1, '2024-01-08 19:46:24'),
('112760208cd5011d6c0f2629a0d726fee4500f83fccb84b60e6727a4036dd0aa489f320b371e7853', '922b628e-73e4-485a-8ead-fc4536a51abe', 3, '[]', 1, '2024-01-08 03:55:44'),
('1a3c21ee1df0b7e2480773a8060e359aede3f067e351da440097662d631297d7cf9861f91a70ce08', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 18:28:42'),
('20266a3109a2bfbe131933b951326c531c5c4b66320ad947a991f3b50019781fb3493a9d2ccef27c', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 15:25:14'),
('23e384426be07da6cdb9c08a6ce406866cf615da5f8a6a548d7865b4fa58922d8d77fb389db5f67a', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, '[]', 1, '2023-12-31 18:47:52'),
('2b726b36195628bbd2783270a9703f1c45d3855d7c4eeb037c1ca5f65f5061f5a40c784047d97fe1', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, '[]', 1, '2024-01-08 19:20:43'),
('2c892157c298a49a45eecdb0fbbb5221907b4df238034ce4a4da425e8f9623d8962c7916e78f9570', '63f2d82c-72ab-4b53-b666-59e0577fe2fd', 2, '[]', 1, '2024-01-09 19:29:49'),
('340494e30d7ad33a5d3df7f136f04f2f8c1eb9d7607cf798e78a301ecf9c432fdb245c31367c78a4', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2024-01-01 12:04:52'),
('35bd9472f81b9719cb875affa049048d6babb9af83884c7e90c1266eca0bac21a41173abcbda6bb9', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 15:24:58'),
('361eb1b2450eefc7d6a9cb97c1c6596953882470b1f3abdcceab60a1d50e0decdb54c18c26dbe4f1', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 17:35:35'),
('39b33af38e38da5f00f1ea9f03c280eafa64314f174c677af928336ca649f5cd21c23481fe1533b2', '63f2d82c-72ab-4b53-b666-59e0577fe2fd', 2, '[]', 1, '2024-01-02 11:32:14'),
('3b44b35050d65b1261e3a29793784b577e31aa2fd3e62e2d814aa56e13d5efba93ab137bfed3258f', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 17:24:15'),
('3bb0f3b85664263d92c88cc591a2d0bd2f81f76900f512b3655a3b0fa2c3ef6fc66ed4e63a2bbaa3', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 0, '2023-12-31 15:24:57'),
('3ed65381f480c0014b8cadca0fe3b22368e7f3d1146469a3e9cffbad084dfdd3ff72434ccf6d7439', '922b628e-73e4-485a-8ead-fc4536a51abe', 3, '[]', 0, '2024-01-08 03:08:42'),
('407398e853c2f1c3a86b88cd414b32571edf6c226ec21d234b42508b0eb5fe9d32ce18fb7096f148', '922b628e-73e4-485a-8ead-fc4536a51abe', 3, '[]', 0, '2024-01-08 03:09:11'),
('408a122c1facbdb8bd3229a62eb2d07bab264b98dae87f830b6004517d70dce7641c8372b05402f8', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 18:32:47'),
('427df2e006f3a490380cc24501a180663f0a4881609a6aed7b9f5a99677cb604abad34ecc11ca44e', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, '[]', 1, '2024-01-08 03:25:34'),
('4399b529d8b086b9264aae00f0ec79112cd0794040b0a0b85bb5055fda3c005657fa1a09649699b3', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 17:34:40'),
('4ce8cdf0c67cee8011a2e70e0eaa447085a715156ee50b9169d4b335ab0d8c26a3a8e05db6146cee', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 17:28:02'),
('4d468d0408112c52c8bfe056ea60316ca77a8f686f3b952b0ccc851c4ad4d6f90fe20f1e0c6257ad', '922b628e-73e4-485a-8ead-fc4536a51abe', 3, '[]', 1, '2024-01-08 03:21:39'),
('5063c247110309c3cfbcc2ee2cd9fb1cc87ea95f49203d45088226315d746cd9332901bee7ec04ae', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 15:22:43'),
('50687dd8888499909c26634f2ff30f2d344eb1cd2d1acd8cf9fa0350fd5f2bac694d0de47789b918', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 0, '2023-12-31 15:26:06'),
('580a6279fc1b12cae1c0efba732ded52431735e5d2bd7fa029bd1943fb3f2f280ff92f5908ebc5fc', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 17:14:53'),
('599bade94e21e7cb2d3fec7eb34d28e73dd11d78560eea2fae67854dd0357f57802a8154b1a4b24a', '63f2d82c-72ab-4b53-b666-59e0577fe2fd', 1, '[]', 1, '2024-01-01 17:34:29'),
('59de78196f4579e88e5c5e89cddde9b6bc65e18ccb4aff32b3b188990ad65dbb8ac8c62e3ed3a8f0', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 15:10:49'),
('5cd8bf597e90648be147594f77decb08d39178dccfee6110c43305530496641b2d9e344335aa0090', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 2, '[]', 1, '2024-01-08 12:37:40'),
('5fda8f3d26ce8f7ed86ceb09a1f07b2941b4f3d3297ae5c3c21703e10706999383df59a92f16ad7d', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, '[]', 1, '2024-01-01 16:26:25'),
('6074bbab97d0f46ce420444bf9ce9a5d7971a0637daefdc88f9564ebefd99e1ecf6c6fec4d9a89e3', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, '[]', 1, '2024-01-01 16:14:16'),
('6133dea65c90c06a23d0070317d109a30f3f734dcb4fb08eab9cfb9e548865476a490e9ddc8d1d78', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, '[]', 1, '2024-01-08 03:26:24'),
('61a222fb4f05a82767289b6006da34ed1f5bdd55ad7d5a8ee6a8de67ff32ae0a1ff9eeae2ffa7729', 'b1509b94-5bbc-41ce-92de-1d89d1980c27', 2, '[]', 1, '2024-01-02 09:38:35'),
('6207467912941faea14a3352fdf7fd4f85c02319385ab7e44b324080789de8e8827a3665b09f5e97', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 15:27:04'),
('64cca4e8c57b4a16af744b99c30d525bbdcc2b6872dd87a067df84586a5764cee5ab14ebefeed712', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 11:23:03'),
('734da20dedb51cf6c4c5ce832e2f5edd0f266ad8e3ebb2a8bdc8270fc0731e2dea12e8d632802c9f', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 17:28:56'),
('76b151a8eb5ed565446691c7488e42f64556f754d9ed9adf93b6496027af3b51eb98a3d9772926ce', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, '[]', 1, '2024-01-02 10:41:52'),
('76e7c0a334099b5679a63cc05e9c0b2ce9ff9fa449cb650b757c108607ce68e3c3e8616271e5548a', '922b628e-73e4-485a-8ead-fc4536a51abe', 3, '[]', 1, '2024-01-08 08:23:35'),
('786bee2d169b2bb8f2fd5731a7308e5ed759936c062ec395700c15d44d1448b6fdab97b885e94d1e', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, '[]', 1, '2024-01-08 19:23:55'),
('78fc0c7a03114f2b37cdf12d2bca72b31b1f9f5ee1edb744c7d9dbc88df6e44287efc827bd1dc568', '922b628e-73e4-485a-8ead-fc4536a51abe', 3, '[]', 0, '2024-01-08 03:11:00'),
('85092f7d3f7cba10d77c1ec5df40ffb36aa9d42442ba953b416cc732146082cbdd4533acdbc54d48', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, '[]', 0, '2024-01-08 19:34:51'),
('85a23ea4c074d9222433a81a842cb5a6f55c5f4fbbbc6819182697f596c1999cd1051d159b3788bd', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2024-01-01 17:28:25'),
('913c04c0e8e800bec2d4f6efe67e04b198966ffefb78f07a83b5ba1ffcfef6069d9e2ce3dd6b8148', '63f2d82c-72ab-4b53-b666-59e0577fe2fd', 2, '[]', 1, '2024-01-03 14:17:58'),
('97c083fd37aec2fedaeef6a3f62d9f3e8c2dbd8aae6b71c9de76ef439294721e0075f2f3ac86d57c', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, '[]', 0, '2023-12-31 18:44:22'),
('9c08ec8a09ab5f97148cf17df0b9643caf9f89c907789cf4457a609adf1be48281dfad43a4b54a0f', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 15:25:29'),
('9c312411b23561db980b64be575c97fdcef86fbab934f90593cde977881fc513cfe9f1521283cb9a', '922b628e-73e4-485a-8ead-fc4536a51abe', 3, '[]', 1, '2024-01-08 03:30:24'),
('9dc351d1f9197c23b7a3a1c9c453089fb8d7d81137a33858236bf80140f8616e82dd96e2c213ab51', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, '[]', 1, '2024-01-08 19:35:05'),
('9df420a9d26dc1c79f4b2ea3679c16c671de915d2530a6e5abaac701a97e082de11419e7d041bdfc', '922b628e-73e4-485a-8ead-fc4536a51abe', 3, '[]', 0, '2024-01-08 03:18:15'),
('9f760e8b1eacb4f39a1a1969aed137969f0944f6e487aab4946dad389d92f5319cc51c6012034ef0', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 18:34:29'),
('a4a6d07518799ee0bec0de4f439c1850f7cd00ac0c7c58680467f17377a66e2d8faf6406dd3278e9', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 15:28:41'),
('acccfe9a870b88fff0cfc2f668e928aedd3613822e917cce44e7f7dcd0ad8a16e8eada7a59b7bd04', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 17:25:25'),
('b6513a1bfbfde2af1fa1043b2b7532619ab8b2188fcd67bc614646f9bb32bf783a76247af78fb97a', '63f2d82c-72ab-4b53-b666-59e0577fe2fd', 2, '[]', 1, '2024-01-11 09:04:31'),
('c2f0cd7746189430903a9f566baca03437652cc57ffaf998cb77a9688ff0e1d36a1a27a3be4be505', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, '[]', 1, '2024-01-08 19:33:58'),
('c77d3ea5f5b83cb67922afc290e0e9cc5f66ccd4c34c269c3be27ac4df2ca452a65bbac97de887c4', 'b1509b94-5bbc-41ce-92de-1d89d1980c27', 2, '[]', 1, '2024-01-01 16:38:25'),
('c7eb8f04c9d91877166d930e8afc6c9c638d7403df9cc50321083345600a11054ceeca5026ee527a', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, '[]', 1, '2024-01-08 05:41:48'),
('ca957c55d085229bfc66ca57a8db08bd23fc381aafebf78fb36764b6e5d273cfdacb303fc988a4e0', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, '[]', 0, '2023-12-31 18:43:41'),
('cca021c9bdf4a6a0b7e51cc2bbcec77a41bfc15367999d5b4cf801a9732e82cb1f9166735c9832dd', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 17:33:21'),
('ccdb528f3079fc7d8fbae57e4fc8b1dd796d0edb4f9df589eb11fdd29c70aeda1799bb8568103006', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, '[]', 1, '2024-01-08 19:47:16'),
('ce35029c17323faacf8c29ec713a308144d25a5f1b9cf48a2c0c84e6e564e019eacb15d93c880f70', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 15:25:01'),
('ce6d23815e6657380e0a89b7d66608a517bb2d52911aefa3ea022ea564f02083640112f03f305338', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 17:24:01'),
('d3c77d7623ecd3d7a8f9035d6b1c1f105c4cc0d241f5ddb93f269d35b64005bc3348bae4d4210208', '63f2d82c-72ab-4b53-b666-59e0577fe2fd', 2, '[]', 1, '2024-01-07 20:03:24'),
('da1accbfc75f737cc78ba3d22bede248ce426d22ebe2979c077bef80fa82f01aaa20920fcbd19b0f', '63f2d82c-72ab-4b53-b666-59e0577fe2fd', 2, '[]', 1, '2024-01-10 05:54:25'),
('dddb8e6fee91d3f7efffc9174d2ebe6d7d26ccb2f06d17301ddd22f41f9b735802e82be4bc56bff5', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, '[]', 1, '2024-01-09 08:25:25'),
('de57879098bc7901ab37d0981c42421ab74b548c0e19f865e47d71d3155d2e51397cf4d77ff00d8b', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, '[]', 1, '2024-01-09 19:55:00'),
('e2f846ad6453d4c01fb2b98df94a593f8834df060f1f93580376db4e2320a267aa310075adbf78d8', 'b1509b94-5bbc-41ce-92de-1d89d1980c27', 2, '[]', 1, '2024-01-01 13:56:38'),
('e372d1a0e46473864e5f840947b73ade20e64d51e695591f1389e57417e08f3cb1dda36e550af934', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, '[]', 1, '2024-01-08 19:34:52'),
('e67a2a687632816d9ce712dbfb310515369ef90c7847f6492af2195c872053dff95833be3dec4f84', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, '[]', 1, '2024-01-08 03:24:13'),
('e707b00a5f0f6720d60cabf7730f01bf64872bc672b674967a04d725db84210e1cbb1b9f202a53fe', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, '[]', 1, '2024-01-08 19:21:05'),
('ec3ae87abd9c256bdd8e5ab6884a30b8af9a5b6ab7cb31ba3ac25cc1bccaa23d8a79643ce6eacf64', '922b628e-73e4-485a-8ead-fc4536a51abe', 2, '[]', 1, '2024-01-09 08:28:36'),
('efc7dbc9d98b9cdcec2c8938b70a083356a1605c52f9c9c5abd6c63b82537817d144d131583cbe70', '922b628e-73e4-485a-8ead-fc4536a51abe', 3, '[]', 0, '2024-01-08 03:09:50'),
('f08f3a763fce701de29b2492c25b245b0af06920d862e0006f22e24a83cae193e84f940a2df34737', 'b1509b94-5bbc-41ce-92de-1d89d1980c27', 1, '[]', 1, '2024-01-01 13:40:27'),
('f2d7c231318ccfcfaa8396cff2a4d14ccf0adf005622662b11846b51f5994e5a30789d5e05358ede', '63f2d82c-72ab-4b53-b666-59e0577fe2fd', 2, '[]', 1, '2024-01-08 12:30:10'),
('f4b824234fa946680614d1fc33f620f3dc1235fc95284b8590b64b23e98aea469b28a6233b4895bb', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 17:36:31'),
('f57eed57933af2b6bc9832b290a20d364b9ef071ee18bcf1696d9f24cb3126d65da67847af95aeb6', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, '[]', 1, '2024-01-08 08:47:52'),
('f7df6203a14c43b27acd4e661f57a9323dbb3affc4e6695f8b12ba2ea58611ad0cc04193eace3efc', 'be5de43b-3469-4ee6-9cf7-c137db341ac9', 3, '[]', 1, '2024-01-08 19:35:47'),
('f82de69838ccd96410b88644632fc598b4b28a12e4692feb9c192794eb9af00b9eb7a1b8a099d95a', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 17:36:44'),
('fab086ae710be9ad87b2030808257abb80f2742b8c207bfc2ad0bdd5bdda1edbcdf76282329aa454', '922b628e-73e4-485a-8ead-fc4536a51abe', 3, '[]', 1, '2024-01-09 08:28:45'),
('fcaf9f200a3bb69d9ff837997b5e4c22053b9b02efb137c148d63e32fa5e39dfd3def0f3c13db38a', '63f2d82c-72ab-4b53-b666-59e0577fe2fd', 2, '[]', 1, '2024-01-01 18:14:16'),
('fd5a50ba81c5404ccb87ad5d1500dcb03dabc21308576d8c3aa939a56ebeb03b5cea6c41ebcce6fa', '922b628e-73e4-485a-8ead-fc4536a51abe', 1, '[]', 1, '2023-12-31 15:26:07');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, '1', 'testing client', 'dB0k3ytUsWfFAcn4Y89hqustSBePalTnz2Cogniw', NULL, 'http://localhost:8000/auth/callback', 0, 0, 0, '2023-12-31 08:05:56', '2023-12-31 08:05:56'),
(2, NULL, 'Payment app', 'JmMOmYWExK2IocYA7PP0uscXAIOCqMnvkQHHGTLr', NULL, 'http://localhost:8080/auth/callback', 0, 0, 0, '2023-12-31 15:31:05', '2023-12-31 15:31:05'),
(3, NULL, 'Warka PMS', 's68jZ52HTOsOAahXvOV5i9sfa5lGpj4M67UaN305', NULL, 'http://localhost:8000/auth/callback', 0, 0, 0, '2024-01-03 08:32:05', '2024-01-03 08:32:05');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_refresh_tokens`
--

INSERT INTO `oauth_refresh_tokens` (`id`, `access_token_id`, `revoked`, `expires_at`) VALUES
('0534f43269f3820645eb32c30c23b3be2aedea7d5895693baeb502a0076039a774147687be7cd78d', 'c128b7e74949cac2ff842833fd088e6e6ac6903e696d6f19ef757e63a2674e4b29ab12e7942d1f80', 0, '2025-01-01 16:28:27'),
('1016f5be5761694b634b63dd95f8c617697a8f8047fdef25879fea7e75583490595a8cca48cea6fb', '5820d6e95e8a864fc842db153f617e5802a64df4e61aecf934df83c1682c0e93252087dc9973281f', 0, '2025-01-08 08:13:39'),
('13c69f1d0fc26547b5891aeced4a5316d1a8d84b9bda52588b9f92caf30543e854fcd2ff977f5de8', '4a578fa9a69b9a5e4c5f556711e1826dc26236fdb3bc1a65851ae311b8eda8a1665c4c55305e2ead', 0, '2025-01-09 19:45:02'),
('198fc4eaa37b91ff37b2a57e7ff4be7b5deedb88651e0393ffdbcdb58ce1908a53f3e1643f0fba62', '11ba6e460ab472c175d791a3e93db5977d261309317f804c996bc60b6c6227b933dca3036051c317', 0, '2025-01-08 03:18:47'),
('1b1a9e89b11cc115a08776d44a3030ab5b33e40f2744a13210d9f09d2477d0fd1cb645001fa66675', '35031c288c9acf373035e089c33f61988d936eee3f770fee227a2f0ad82f748dda0447a9ad3ca235', 0, '2025-01-08 03:21:39'),
('1f86fca3140289ed7deaf5a695751762dce86a0d79a0a7133dac638987395884e9d07be763e220e6', 'af51804a1d6a26705e040711e2a22a067a22164bc48b016203ca51a458bdf93d904e61da6717a3fb', 0, '2025-01-11 08:54:52'),
('201d6d60cab700035ae12127846b9d7756edb2c0d1925fc4ef918fd91fdb7144eff9b7a49191fd4a', '7db2d5e4f9a81c1dac503ace144c875f1b5877b25791412d9da4f611a788637fddb688dfa724020e', 0, '2025-01-08 05:31:52'),
('27204d16af66a40f9c9456c3f892bd291613b43e9fb70292bfafa7e58aa33335e40b5e1312e5fb19', 'c1a8e815de0694665bf8883358c056d356088155e76cae1f22db50217a451b997c95dfd03d91f118', 0, '2025-01-08 12:27:41'),
('282aeb0a3fd62fb1f07eedc14e5030fce887c01b5ca6cfa7dd1bb024ef3554fc3dc9e72b9568cea2', '65f3487e152db3ca05cb2f6e534f873640a2c6dd48e27781089556632202ea361b154b7b8eccc13d', 0, '2024-12-31 15:16:08'),
('318646917b500dcc1d7340e379a600ace5ed79c6342996085174e875b9b570142c448dd45c72337b', 'f593cb6cd5e17fe1535f40c35959dfcd1719454b562acf0c74808a3aa1f92993acbd1db592211ad3', 0, '2024-12-31 18:24:31'),
('3327318e84f0f21204a273989a3b8ffd82207c5dc84d4407664f34a24c37dad1da34c75970590469', 'e5a6d6137aa6563443674303f8170b8a0858055f26c4579e810626a2deb058c3aff98ecee451b008', 0, '2025-01-08 03:20:26'),
('34db0ad19c73a03955fd82950b9a0cb2a1699bed258d2a840b52894185dbcd7e6c743f1bcd459c3d', '205d64a6392cd248aac3c256aed10f1d63f20ccbeff2387a15ef44cbe5d339bc1a392eb80d43dcc0', 0, '2024-12-31 17:14:02'),
('40c8971367725f9ca758e17a812fe239072ac87a64ee5da38699537f295c72b31e19dfbd63625167', 'd691827e1935de62f467804496ba3689f3220f56b943af6acce8f5ec4fd828c72ab2302eaed782c5', 0, '2025-01-03 14:08:01'),
('4a3363773b34ad7a437729aaab4b3c2b1c37f3e8b97dd48efd46f47a48a48c135c8dab6e91e96098', 'ec5e2025c6a27293f0be1fc81db9a4073ac2cb09b6b01bb0fc0f5a2b350004f606d2a2c81cc217d5', 0, '2024-12-31 17:14:16'),
('548911d66238b2e158d435587963ac0eac5243f94971f1df04d81b5376fb65d6afa9f263b1a5c328', 'dedbb0e67cd7e3c980f6514fa8dfc950c3ce1ded93bcd075a3b3af242b7923bb614fb54b0f988085', 0, '2025-01-09 19:19:59'),
('567f6fe3729acc7bb0d9a87df1f230e3866575b9ef586a32e747b184d2eef268e3ca8f86cb9a5420', '379394b79d311e99722bbc6a4af59f738f7e7ace9638ec6a64d397eaf5d15891767adf5f9a97350d', 0, '2025-01-01 11:54:55'),
('5821f5418b6a4ae6fbdd386a70dd33dfec77722b1777d14db76f183f4661fe4d7f482c6d91141f1c', '4b527762887c78583f3cef5191e5fcf83cac10c959671a30fa69b457a970484062acf3685f614b18', 0, '2025-01-09 08:18:38'),
('5bf0cf20236eb02ec349175f15e0c601a2250af6d25d1917db794c658530f8b3fb54889f967642c1', 'dadd595e827cac75d593714a25155302c1915d2deca17dbc7ca06d89eeadf30db8ae93a78e04c2ef', 0, '2024-12-31 15:00:59'),
('5e44a8f7eb25e1f69346f923c16b6673cafcfe4b205986b3080e2bf3b9fea319466f1c14b2c36fd7', 'e87190758785c022b344df2f4fc623aff10fcc91a496431a6d2b41dee441fc70b3d44f109085f269', 0, '2025-01-01 16:16:28'),
('60c77a5d577a38e9716df24a89fb91e032fa722ad6465a923a8d4d3aa66d269ad7c9f1460450f9e0', '491f19c21c44121b319f0349ed10d04c104f2327044d93c4edc3658b752670240f819629cb560c7f', 0, '2024-12-31 17:04:58'),
('6300c9f722906f6be1166b4a6c0df46741c11ff7fe70aa09f8eed0eb637950ec7c1627948cb72b74', '43cadab48ff882a7b018a4d57bd350cbd3f20baef503eaf86f55f324c2f92c43f9fc928f28b31234', 0, '2025-01-08 19:10:47'),
('64cd1f8b0384735f054f5a24d639e543c0c807b63d4af8841dbbe391534ce5e8ffa546345466d724', 'd85ee1ca5cbc9046a7aacc6f1b8abf7c6413a63489e50b1f93e79fba35f05d244c6c7a5dbe038579', 0, '2025-01-01 13:30:30'),
('6a5d81e870ba893700347dc2e01667653792f79c25916bc9630da5284a8f1f3feb3ded03d1f7239d', '4df648f135a447a2262a0e13f0657b3d7b41c03b9d1c2b418c65df45dbbf077cc0c82eacbda94f11', 0, '2025-01-08 12:20:13'),
('6d5e3ce12fe02a71bd22a3d9c6a5b45f609cae263ef5ba9035402db193de8983a3f7ce01c265d555', '107a4cd811c32cc786c4aeb9eab817ff1daea062caf1e060591b2bb549d8ec37dc8657435aadfd64', 0, '2024-12-31 15:12:46'),
('6f31e8de64031bbef184f0685037e4792fa97207e1964790807c33c0806fd0a75cd2f4e1c95ba45a', 'ad1f435fd6473d086fcd789e1f8fb25812cca3a25018f97eeb26175f129f076dcd119d7eb46eb12a', 0, '2024-12-31 18:37:53'),
('70ad9a6244944b6b38cbae284a3ae0016007be370316f8a3444a86e139185ff7edf4603a4ef09707', '7e1f1f87a61ba4c8cee0076e442fafe21fd8f270dbfa762431f9d369c5460ffbcfffa74467101e19', 0, '2025-01-08 19:37:17'),
('744d2ead58d414279067dfa4b446b3bd34f71eb73837835a8569a7d9d18a63602890c50ecf914f13', 'deda0178da403a7ea8b6eb3d9d901136c746e996518df509c954b5872be47bf3ccf1dfd8a754003a', 0, '2024-12-31 17:18:56'),
('76fa412b3ba6c064948c60edf909be8144af9b38831c3948f194f4f14a63dffac58ff26d12e08134', '76bc6b8897d2847ba5cc2da31536eb67877b887baa9c7ac93d7ec68feb6578fc7455d82ac31ba5ba', 0, '2024-12-31 11:13:07'),
('77792d8494aa54b6b869c3fde42ac8710526cc77504bb24cb2efb2b9d35cc0994e4930dbd88e8345', '9ad7caa5d9a6e59638e6ce5820d486b254524d83d9834407cbfb17269fc3f9fef897144bbe80f7a6', 0, '2025-01-08 19:24:53'),
('7922a8f76632ddcc531333da96e260ae3f5bc3fb0e84350d55f28713ae16a3323fe9c261449025f3', '92b27d31ff4ffaa6d780e30418cc658e59c1b435b66258e3e720108105b7804f74105b1cc9a72b05', 0, '2025-01-08 03:14:16'),
('7954e5afe40f61a367b21558b2c8d7c9d9d1a9adbb66efa96395eeee4b09829c7bb4f752c371eca0', '8f97e54f40f3eff4d7467aaba5fe7b5f6a82a3006ae5b621a7f4a14d6f8aff576366d356b3b5ac1b', 0, '2025-01-01 13:46:41'),
('795eb8a2d6d0369aa72ff9def100791573cc47b78739b4fbf4fa831123f176ed1739b1e224315969', '15c8c01fbed25e3bd5f0cbc1f1c62d7699ea32b9b25026c04f53f15528a6c4c40feeb93ba7c1215b', 0, '2024-12-31 15:15:03'),
('82efa169cb824a5c8a4eef19a34b210f8e146e23000a9e1772800372c856f0d85112788eac9b2c3d', '57979ea8d5b15c6c732408bdb18f5389236fc9e6ffe50fba1cb4fcf7ce3ae3e91fcc0895316d2e17', 0, '2025-01-02 11:22:15'),
('87e3d2a9b288865425a937c74e6f8f3e5ec4096831646e87b1232ede94893a87e4138ed2c0d8a9bc', '2f871d4d5d0c44cc40952e79f66d91c5ea6a8b4815bcc519ee6dc62c6b3e173be8a98b7fb744c9a6', 0, '2025-01-02 09:28:38'),
('8a9fc556f1b1ab9992948ba7fefbf29629b3a7c168d57dcfe21a469557b9706dc703960eca1a4962', '0b98c0fb8550284446c09a70ff1f704acb84231e12c7171c6a8e6612c0463b99a6f8db81345734b6', 0, '2025-01-08 19:24:00'),
('8cbf1cd3fbbd2601d92f0716ccff46c78dd6c2a8cba9b6e91f3856432a3f321d20470791135b7073', '67e85fc6da1300b116a0c11ce93e943985f3a86ccad84f4fc338c9f369eba7360fc7133b4746377a', 0, '2024-12-31 15:17:05'),
('8d1518d23e4e6f47c2afb44dfb986e96c5f8cc8eae2ccef82c8b01dcec2310b1be8fc08932c54586', '6f7af9133f99d5c0f1511b9a2463be556ba35b1f9e6ef8f5d5d633a7637bc6f53e39d34df1df692a', 0, '2024-12-31 15:15:16'),
('8e8ca8f7d6487353ec3ddb3febc08d348fb5b5bc5c95e9ae90acbe74113142904db5c6579ffe8f15', '1cc0e5ab3d99432a61bc8aeb89c1968ad9061fa502f6ad6cc45788f3744bba7a76e42cee8d11ed33', 0, '2025-01-08 03:45:46'),
('90d68dfbd76ae25c71145c5a63cde79551b0a0ffacebba4634b68d219cde6e0a31d1fd7ca594aff3', '1b112f81900a25863676a4332c8ca5d1dd141b095b43a5c394574b841305d3c18b173ebffa73f846', 0, '2025-01-08 19:25:49'),
('911c7e8d7d847c14ed04441768b174388bc3d453d4e5d7bc187ff173290c1ecc0ba4efbd2cc8d3ab', 'efd143c7d8b28f1dbd6f3b38b47541babc669bcc18d50a0569dbf796d67439f9656705995e34f046', 0, '2025-01-08 19:25:06'),
('93c74f797045c66152ac7c7982ef5ad466dac4574563bcb9a1805edd7a208cd40fe466376c596fb1', '5c51e553bf181d54a292ceedef140db6757a578d50442abc08ce12a8465726c6d0a66f6de3e68fbe', 0, '2024-12-31 15:15:31'),
('9630f609a7c4ee8c6a244b2de50d470b9b24963477035a7e01da4da52c41a0f29639c565b5e99ab5', '4c5a71118424c7f6e3a17c566e96a5d8f63f24fc1313173d8878c001fbaa81800f4be10b87f45c38', 0, '2024-12-31 15:18:42'),
('9744b4e61d0abafc29d76aa24081eb490829218da25af7785f3ed95b42460e589bd16a8ccaecee43', '5106441efe3d499c08b9cc1baf88b56f4b001d9c1f59a8696ca1f13a8e24521de3b24f8ddb49875e', 0, '2025-01-01 16:04:19'),
('999abf2c8242ec360f9ce1ceed777e7bff9b963d6630f549f19f5b139653024980a8a56bcbd3fcae', 'e3e448c5104fa0961fbef1b848ec6028260cfec60fe4164a7e3b7580d5964ebdca9f1b8a530ef675', 0, '2024-12-31 17:25:36'),
('9a4e5127c32d390818f9743214983613f2a0ebeda9824a6fbb224ef6ae1db70d6febc635abc4d856', 'ced08fec38bbe3f022946261bfa070c10778905cf0c2e12f0d069522e32bce50563ee6323cb9afca', 0, '2025-01-08 19:13:57'),
('9ada1c2bbe048dd5aa803ff45d2f19ad420ee3a623e40f44f8cabf63e93022af34c5bfc688c4544a', 'ada3607527b0a8acf1d48a1cd73588506af6071b0d8cf6e6e0fac6b699abfd0941606388b0c0aead', 0, '2025-01-09 08:15:28'),
('9cdf582614bd54d2c08491ba00c066530b095b77a1bdf9ac77b0257cf4fb5933746ee7504c3ffabd', '5a28a93fa6469ec5036fd1582b5a82a83d73e2e7d87c526c9335ea906b875fa516f45ec77ed41f8e', 0, '2025-01-08 12:19:24'),
('9fcfbb404896eca26a74eb2d83a30dfcf62c15e56418bfef18bae375245c794f107cd9305e9289dc', '7906d965241bdde50445137f7439976b5dc92c4e551cd5c6d62206cf0cdff74cf695b832cd01cdff', 0, '2025-01-09 08:18:46'),
('abbe833391266852703a0eb555099027b551f1aaf759a37a93a3b19c1035433361579c6c8a66d08f', '94ced9677cf2664e6f1044037609b7d55c5c915571247df06df5591100244c6a74aa3d04be0a88bb', 0, '2024-12-31 18:22:49'),
('b6d55c1188c47c3cd646886d59a057e958bb61195902d186e023479b9575e958fe1666b01bafa4de', 'c7ac2f707660165ef2441e93e056e8bf45b26fce28894f9409f23b639bfcef1cd2facd54d3088231', 0, '2025-01-01 17:18:28'),
('b8a1a8ad4464c85fbab75ea8e74e4a754421eb5563260f0a68937a2bfc5b4def5ee9055004f568cf', 'dfc15df052d7c3958870a4771408dce88b5dc2d8523f2c9f49d3c1f668a70dc1956ce9ab6de80dd1', 0, '2025-01-08 03:16:25'),
('beb22255900b0176fb19c66e0e06e89cd9494f97ed21c2dc16446a7cef9298a193f4ada236bf63ac', 'dc0b0874c4d5305502cb1fbf84bbfcee4ea951939d57038aae85c6c4f1bec0b6fcf14c0f78c62c5b', 0, '2025-01-01 17:24:30'),
('c34bfcb153a9b26d0ad44c1710160b882c45e0350f12eb1e3bb916f875c2d55dad8946d1510f001b', '5f802ad14541dcc21b3d875589a610d4c02d900aedddc229d046e99a2126af50b82cfa770e7bc065', 0, '2024-12-31 17:23:23'),
('cc796ce8b7387aeaf09e7fca6ac479837f8d99686993b30a181af5ca325bf10c1217eb27c60b9d7f', '5e55617c29315423a910b5f77337d20142bfe320b18f718bd8b68fd847995a4e3e8e9a3ea1802867', 0, '2025-01-08 19:36:26'),
('cd4d61539999c4a09f4bf6b8b4e8424ced4e51ee217633993ca4c78df2509402cb4801cdb010b98c', '23c162ea7428d34dce432fa840b2ef56a042f5f88b64e49ea19eede72859f22a6f5320ff84d6f291', 0, '2024-12-31 17:26:46'),
('cfe344692f3f450aca1c5cf77446c77dec5107354e0dc2b76a3c1a3d628a22f72e767be0498e6640', 'bddd30a9f4073bfd68e2b8852286f319068b32a88935c9fb67f17d1881e773c5d4d26387fcd2bcef', 0, '2024-12-31 17:26:32'),
('d0e2f2d20adc9c9e3e352ee18da4dc03c568e546310980658770b68c307a09a2e69c264fabda7bac', '4247c20fa809b5988143cf7b0bcb7c38f10662fb9784648a57e7b5279e3d80bfb24ae661f82b7f3c', 0, '2024-12-31 17:15:26'),
('d3939a213fa735e8085796fd445a5ee8af1abfe60207a62861f047b1b4b7f71df12f948455eb9bf0', '74450723eb675ad4c193891e93d26d3cba3e0a1a0238ef098ef2f868c64a2740b303fc223b5001d6', 0, '2025-01-08 03:15:36'),
('d92fd091096c74101e993995b670c5d9f8cf9e14d6cb0e4a284cd66a8de49bd116bd4c3fe36b52f7', 'f13af1f78a0de1ec34f54388f50955cc7778a4526ad3b532985cdd52937e9e591119eb32f26a313c', 0, '2025-01-07 19:53:35'),
('db69e7c43bacc5defee5eca13558aa151f759a63dacfbddef6719baaa9c40d3033fcc832949df053', '3a3dbb3ecbab217d0065f02abd19edfc13f92c55e8fc827482603e9d7ac4a37bfe23318d14f4fa63', 0, '2024-12-31 17:24:41'),
('dcd00af4089649b03f96718107cf4bdf8fc8509bd3cdc1cbdc6858e16b983fbf4e7f38f9687347c2', 'c246d31d936c4de3be9be45780ffe99c0752978e0436ed4bd4a7588de7e0736e947f787c84220311', 0, '2024-12-31 17:18:03'),
('e315163cb3c921ecdb449ab6fc9e44de5e9d5d1a4f7bee7cd9214617bb2dd1d3c59a087ef89ef54a', '45b8ca7c5511bb5f17d99bc400806aa36f67704ccd5861b6f1ffb5bff8dc3c57be93ebc2090f008f', 0, '2025-01-01 18:04:18'),
('e64816a1f5f5a140892b39fc5542c5d0c9e41b36529085fc5ff9248e4ed2f047c891ffed88996dea', '9c8b208b3dd9eac25c4dce0d5bd547d994c5293aae0e5bc3fb75fd7b874d7d3d0bef89d204ff2e7d', 0, '2024-12-31 18:18:45'),
('e8f3f7d1692fdc1579f5f9c9f0ab4dc11c687b393aae1a10ff4ee13afacbcab435080ea4e4aaf858', 'ceaeb4e8112180545bb6cb1cbbd75cb5fbd64f338d9e3f905df7b5de743aa563c3c3273d3221b0fe', 0, '2025-01-10 05:44:28'),
('ea8f0661f1b395735d6e9378f0e3450250aefd0ae9c661c42cbd9f948d71c2413d5bb31f39c61117', '23325671e3db1a6a6eb39832a9bab58aeca9386fbeea414e9ab74fdbeb81162749ebd3a49bad06a3', 0, '2025-01-08 08:38:01'),
('ec747c9becb4ef626db1441735da3c01ca115f05914e41ce76d3c9d8f1a618dbc6a38953af87fcff', 'd47fa711533a8ee76fc3a5d1b779362953d1d78ca2fe53ea8a9febc58f037518a93cff2d0ec57d9c', 0, '2025-01-08 19:11:06'),
('ef07eaa85669f6d4e7c38f2bc8cb90c1d6989b364fb6b4bac0b5231fb7f514a511bb57bf7943c7cb', '34471653a9e96b9ce5fde2f55ae26e9ffaf082a2aa32feea85f58b4c3e21db0e5460c142286bbb3e', 0, '2025-01-02 10:31:53'),
('fcd79256e783896c5140c2e6ce3d3e4533271ea2db3d3a016c4fe150318170df413588968d802636', 'c561bcae1c0aaaf4489279de1cbdabfcd9b51215909f938dbe203d639fe7847d5f8602abfa126d9c', 0, '2024-12-31 15:14:59');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `phone_verified_at`, `password`, `country_id`, `remember_token`, `created_at`, `updated_at`) VALUES
('3b930ad5-4195-4eac-83c0-d4f0ddf5192b', 'Preston Barker', 'lurakewul@mailinator.com', '940678777', NULL, NULL, '$2y$12$.S9I00czIfZUK/GdVRLPfOBqmkk8lRrGIFHzYn6l4BKPnDYML/j46', NULL, NULL, '2024-01-08 09:16:56', '2024-01-08 09:16:56'),
('63f2d82c-72ab-4b53-b666-59e0577fe2fd', 'Maruuuf', 'mydreemname@gmail.com', '956001894', NULL, NULL, '$2y$12$VPl8IPHf21iW8X6NtaWHs.p6ZnMwyo3nhGw0.JZ9Xa/z72JkFOIeC', NULL, NULL, '2024-01-01 10:09:52', '2024-01-01 10:09:52'),
('922b628e-73e4-485a-8ead-fc4536a51abe', 'Addis ababaw', 'nesrusadik0@gmail.com', '940678725', '2023-12-31 08:12:55', NULL, '$2y$12$65c.ZdFYTHXPzb0liTqtq.TwGrl/CoHhxnzYAEJWSU80bJVlO5F4i', NULL, NULL, '2023-12-31 08:12:56', '2024-01-07 23:58:17'),
('b1509b94-5bbc-41ce-92de-1d89d1980c27', 'Nuur', 'nesrusadik6@gmail.com', '898989789', NULL, NULL, '$2y$12$mKa10pfDWKK5QUzvPm5N1u1q25ajJzB02xUepnYYUymiC.u3Y3nLW', NULL, NULL, '2024-01-01 09:58:56', '2024-01-01 09:58:56'),
('be5de43b-3469-4ee6-9cf7-c137db341ac9', 'Nudin', 'mekivo@mailinator.com', '982131734', NULL, NULL, '$2y$12$DJuXZB00O.GDP3zJBhrJwOEju9LUZjNemKnSd3rRC7Y4v4.87mWw2', NULL, NULL, '2024-01-08 05:16:34', '2024-01-08 05:30:09');

-- --------------------------------------------------------

--
-- Table structure for table `verification_codes`
--

CREATE TABLE `verification_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code_is_for` enum('email','phone') COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expire_at` timestamp NULL DEFAULT NULL,
  `candidate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `verification_codes`
--

INSERT INTO `verification_codes` (`id`, `code_is_for`, `verification_code`, `expire_at`, `candidate`, `status`, `created_at`, `updated_at`) VALUES
(7, 'phone', '2701', '2024-01-08 05:28:11', '+251982131734', 'sent', '2024-01-08 05:23:11', '2024-01-08 05:23:11'),
(9, 'phone', '7627', '2024-01-08 18:00:52', '+251940678725', 'sent', '2024-01-08 17:55:52', '2024-01-08 17:55:52'),
(10, 'phone', '4382', '2024-01-08 18:01:05', '+251940678725', 'sent', '2024-01-08 17:56:05', '2024-01-08 17:56:05'),
(11, 'phone', '5742', '2024-01-08 18:45:54', '+251940678725', 'sent', '2024-01-08 18:40:54', '2024-01-08 18:40:54'),
(12, 'phone', '9223', '2024-01-08 18:48:00', '+251940678725', 'sent', '2024-01-08 18:43:00', '2024-01-08 18:43:00'),
(13, 'phone', '2932', '2024-01-08 18:48:57', '+251940678725', 'sent', '2024-01-08 18:43:57', '2024-01-08 18:43:57'),
(14, 'phone', '4109', '2024-01-08 18:56:43', '+251940678725', 'sent', '2024-01-08 18:51:43', '2024-01-08 18:51:43'),
(15, 'phone', '4152', '2024-01-08 18:57:53', '+251940678725', 'sent', '2024-01-08 18:52:53', '2024-01-08 18:52:53'),
(16, 'phone', '3073', '2024-01-08 19:01:24', '+251940678725', 'sent', '2024-01-08 18:56:24', '2024-01-08 18:56:24'),
(17, 'phone', '8462', '2024-01-08 19:01:58', '+251940678725', 'sent', '2024-01-08 18:56:58', '2024-01-08 18:56:58'),
(18, 'phone', '9936', '2024-01-08 19:23:40', '+251940678725', 'sent', '2024-01-08 19:18:40', '2024-01-08 19:18:40'),
(19, 'phone', '2143', '2024-01-08 19:28:04', '+251940678725', 'sent', '2024-01-08 19:23:04', '2024-01-08 19:23:04'),
(20, 'phone', '3402', '2024-01-08 19:32:44', '+251940678725', 'sent', '2024-01-08 19:27:44', '2024-01-08 19:27:44'),
(21, 'phone', '7559', '2024-01-08 19:34:17', '+251940678725', 'sent', '2024-01-08 19:29:17', '2024-01-08 19:29:17'),
(22, 'phone', '3629', '2024-01-08 19:35:31', '+251940678725', 'sent', '2024-01-08 19:30:31', '2024-01-08 19:30:31'),
(23, 'phone', '9192', '2024-01-08 19:35:43', '+251940678725', 'sent', '2024-01-08 19:30:43', '2024-01-08 19:30:43'),
(24, 'phone', '3320', '2024-01-08 19:36:10', '+251940678725', 'sent', '2024-01-08 19:31:10', '2024-01-08 19:31:10'),
(25, 'phone', '2695', '2024-01-08 19:36:33', '+251940678725', 'sent', '2024-01-08 19:31:33', '2024-01-08 19:31:33'),
(26, 'phone', '8323', '2024-01-09 03:17:00', '+251940678725', 'sent', '2024-01-09 03:12:00', '2024-01-09 03:12:00'),
(27, 'email', '4868', '2024-01-09 03:25:48', 'nesrusadik0@gmail.com', 'sent', '2024-01-09 03:20:48', '2024-01-09 03:20:48'),
(28, 'email', '5046', '2024-01-09 03:28:29', 'nesrusadik0@gmail.com', 'sent', '2024-01-09 03:23:29', '2024-01-09 03:23:29'),
(29, 'email', '8469', '2024-01-09 03:30:15', 'nesrusadik0@gmail.com', 'sent', '2024-01-09 03:25:15', '2024-01-09 03:25:15'),
(30, 'email', '2659', '2024-01-09 03:48:06', 'nesrusadik0@gmail.com', 'sent', '2024-01-09 03:43:06', '2024-01-09 03:43:06'),
(31, 'email', '9255', '2024-01-09 03:50:02', 'nesrusadik0@gmail.com', 'sent', '2024-01-09 03:45:02', '2024-01-09 03:45:02'),
(32, 'email', '3292', '2024-01-09 03:50:03', 'nesrusadik0@gmail.com', 'sent', '2024-01-09 03:45:03', '2024-01-09 03:45:03'),
(33, 'email', '2080', '2024-01-09 03:51:21', 'nesrusadik0@gmail.com', 'sent', '2024-01-09 03:46:21', '2024-01-09 03:46:21'),
(34, 'email', '4667', '2024-01-09 03:55:10', 'nesrusadik0@gmail.com', 'sent', '2024-01-09 03:50:10', '2024-01-09 03:50:10'),
(35, 'email', '9250', '2024-01-09 03:59:19', 'nesrusadik0@gmail.com', 'sent', '2024-01-09 03:54:19', '2024-01-09 03:54:19'),
(36, 'email', '7079', '2024-01-09 03:59:59', 'nesrusadik0@gmail.com', 'sent', '2024-01-09 03:54:59', '2024-01-09 03:54:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD KEY `users_country_id_foreign` (`country_id`);

--
-- Indexes for table `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_codes`
--
ALTER TABLE `verification_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
