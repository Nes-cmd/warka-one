-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2024 at 09:33 PM
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
(1, 'Ethiopia', '+251', 'ET', 9, 'flags/et.svg', '2024-01-15 02:35:58', '2024-01-15 02:35:58');

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
(12, '2013_12_22_084715_create_countries_table', 1),
(13, '2014_10_12_000000_create_users_table', 1),
(14, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(15, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(16, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(17, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(18, '2016_06_01_000004_create_oauth_clients_table', 1),
(19, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(20, '2019_08_19_000000_create_failed_jobs_table', 1),
(21, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(22, '2023_12_23_204710_create_verification_codes_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
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
('0716e55d42a01df73bb9bb765fe9f64363f38134be3fb2778cc8a89558cd2efde2934e6812d0f2f3', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4e65-7e03-4198-8b4e-7ab96e714de7', NULL, '[]', 0, '2024-01-16 17:49:53', '2024-01-16 17:49:53', '2025-01-16 20:49:53'),
('0ae02d951d26944c09f4975887bc79cce73dc7e5511d48772a315b046b64089f1ef191ca973e0584', '1d239e3b-435c-4a27-9fa6-3dab8b2e20ce', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', NULL, '[]', 0, '2024-01-16 17:32:37', '2024-01-16 17:32:37', '2025-01-16 20:32:37'),
('3b9fb42823699e2ea12eac56ee8c6ebd6710552146a0fa3321779be6dcdb2b193d227036e4895938', 'afd60c21-e7b6-4d97-80b0-6599e9ae1624', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', NULL, '[]', 0, '2024-01-16 17:37:02', '2024-01-16 17:37:02', '2025-01-16 20:37:02'),
('4e01044adf5e4793118da699a85eea5b9aa66e44062a924f6a2c35373885acbf2e39845e42a609ba', '1d239e3b-435c-4a27-9fa6-3dab8b2e20ce', '9b1b4e65-7e03-4198-8b4e-7ab96e714de7', NULL, '[]', 0, '2024-01-22 18:39:37', '2024-01-22 18:39:37', '2025-01-22 21:39:37'),
('57d36e6be9def9a0006b7bded6cea288e91380c5640ae38f9eb42e5839264cc53d5fa524e51dcb2e', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1889f3-fe9d-4ba8-943f-c2d0c0923e49', 'MySecret', '[]', 0, '2024-01-19 17:09:29', '2024-01-19 17:09:29', '2025-01-19 20:09:29'),
('662a838e7fd4b1c7d65757e3b924ed9f5bb13d72e72dcf8caabdf2cae548a1e26bb9d3a35314ea21', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', NULL, '[]', 0, '2024-01-16 17:17:02', '2024-01-16 17:17:02', '2025-01-16 20:17:02'),
('67c79f66811c404a0fc39c1817bd9a13a05011f6a39f7a5f6a0bbfa26ca6e21e01712ef4ca617daf', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', NULL, '[]', 0, '2024-01-16 17:17:38', '2024-01-16 17:17:38', '2025-01-16 20:17:38'),
('7e6d1eee5edf0a3bebea4e2d15c9c1a289c3dbf1bd5837e62acab578ceeb66cb9d25334a0a816422', '7d8ae4a8-22ee-4d7f-b2ed-6e36bca43a85', '9b1889f3-fe9d-4ba8-943f-c2d0c0923e49', 'MySecret', '[]', 0, '2024-01-21 02:50:07', '2024-01-21 02:50:08', '2025-01-21 05:50:07'),
('819cdfa7112726e15bc1d8dacc889386e8997905faf41485708511c8d2d53c78b4ff796af0521964', '7d8ae4a8-22ee-4d7f-b2ed-6e36bca43a85', '9b1889f3-fe9d-4ba8-943f-c2d0c0923e49', 'MySecret', '[]', 0, '2024-01-20 05:51:19', '2024-01-20 05:51:19', '2025-01-20 08:51:19'),
('85efe6399d164b2953cc9e5cfc25e0db029a9720d6249c9e7ff6775ab7aad3bc71c18435932baf59', '7d8ae4a8-22ee-4d7f-b2ed-6e36bca43a85', '9b1889f3-fe9d-4ba8-943f-c2d0c0923e49', 'MySecret', '[]', 0, '2024-01-20 05:58:15', '2024-01-20 05:58:16', '2025-01-20 08:58:15'),
('8636ecd88577490dac6e2c21ab9c9b7db88406bf3dec0a59895e049c95ec627d2c47c1ed44071c0f', '7d8ae4a8-22ee-4d7f-b2ed-6e36bca43a85', '9b1889f3-fe9d-4ba8-943f-c2d0c0923e49', 'MySecret', '[]', 0, '2024-01-20 04:51:00', '2024-01-20 04:51:00', '2025-01-20 07:51:00'),
('8b1043402ad4690bbe999fa498ef4035da6140b24a89e9fe517ed0045030fd54120c6dfac325fbc8', '7d8ae4a8-22ee-4d7f-b2ed-6e36bca43a85', '9b1889f3-fe9d-4ba8-943f-c2d0c0923e49', 'MySecret', '[]', 0, '2024-01-20 04:50:08', '2024-01-20 04:50:08', '2025-01-20 07:50:08'),
('a55e3e9f0c616e99e9602cf7a4f742c66c35e80da33d92168eab2acb3a5cb43810c8bb80272dc980', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', NULL, '[]', 0, '2024-01-16 17:08:49', '2024-01-16 17:08:49', '2025-01-16 20:08:49'),
('af0478acf23fd70220d8a0c4b74e91af8108d9a0ab71cb0111c219b065326c05e5ad6c5fa722901a', '1d239e3b-435c-4a27-9fa6-3dab8b2e20ce', '9b1b4e65-7e03-4198-8b4e-7ab96e714de7', NULL, '[]', 0, '2024-01-23 02:06:47', '2024-01-23 02:06:47', '2025-01-23 05:06:47'),
('bd00d81f400a27e7941bd181588ae3cdad61280a215f54db1dd92c001ef5ba2a76c19006a4fb9bc5', '7d8ae4a8-22ee-4d7f-b2ed-6e36bca43a85', '9b1889f3-fe9d-4ba8-943f-c2d0c0923e49', 'MySecret', '[]', 0, '2024-01-20 06:00:00', '2024-01-20 06:00:00', '2025-01-20 09:00:00'),
('c874acf72bac236134b8d5f9f1ee393a077643d474078a17d49b86955cf0b8050081382b30069de3', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', NULL, '[]', 0, '2024-01-16 17:33:19', '2024-01-16 17:33:19', '2025-01-16 20:33:19'),
('cc01b8b0ccd6f2cfc86a32e6afdcff14091e2358df3a0547fa9b505fcb31e052403e1579428255c1', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4e65-7e03-4198-8b4e-7ab96e714de7', NULL, '[]', 0, '2024-01-16 17:47:34', '2024-01-16 17:47:34', '2025-01-16 20:47:34'),
('d30cd7d6c9845db4a476069603a27d7d72c3c37e685fa903e306daf9370eef464b3c5a19579d01f8', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', NULL, '[]', 0, '2024-01-16 17:14:03', '2024-01-16 17:14:03', '2025-01-16 20:14:03'),
('e43201d5f225478af61860a300ee58990404e08c514abbdc3e8b66e190cae0644cb65b64295ca524', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1889f3-fe9d-4ba8-943f-c2d0c0923e49', 'MySecret', '[]', 0, '2024-01-17 05:36:40', '2024-01-17 05:36:41', '2025-01-17 08:36:40'),
('e605c7d3797acc6352305bd9eacd924ea58c347ae36ff5e4b80849af7e9a33455c8092c7946191dd', '7d8ae4a8-22ee-4d7f-b2ed-6e36bca43a85', '9b1889f3-fe9d-4ba8-943f-c2d0c0923e49', 'MySecret', '[]', 0, '2024-01-20 04:53:35', '2024-01-20 04:53:35', '2025-01-20 07:53:35'),
('ed128f1569656c18f1b3466c37db6c7efa39f615464923dc7369482354872a95edf8c3398073a1f0', '7d8ae4a8-22ee-4d7f-b2ed-6e36bca43a85', '9b1b4e65-7e03-4198-8b4e-7ab96e714de7', NULL, '[]', 0, '2024-01-23 02:27:02', '2024-01-23 02:27:02', '2025-01-23 05:27:02'),
('fa71c34d16c54a1b96ed2329f734bbdee0abb7dc2f84a5ad04167cabe5032331fa85e039ce704a23', '7d8ae4a8-22ee-4d7f-b2ed-6e36bca43a85', '9b1b4e65-7e03-4198-8b4e-7ab96e714de7', NULL, '[]', 0, '2024-01-20 08:38:22', '2024-01-20 08:38:22', '2025-01-20 11:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_auth_codes`
--

INSERT INTO `oauth_auth_codes` (`id`, `user_id`, `client_id`, `scopes`, `revoked`, `expires_at`) VALUES
('2a89c1221e895da9871797103b2303466861a72bdfa38391fff412a42284f6e59ef56afc85bb03af', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', '[]', 1, '2024-01-16 20:18:47'),
('2d1c303172c421cf28ca5b22c7b1d745bdb622505e23022b9ed48c4ae1d6940cc2a2330f4f381970', '1d239e3b-435c-4a27-9fa6-3dab8b2e20ce', '9b1b4e65-7e03-4198-8b4e-7ab96e714de7', '[]', 1, '2024-01-22 21:49:27'),
('6293b89f864246eb11ae00817fd9d685e6d04dd536cb6e8789a6d72194b4c35192ba4e20ddd25c12', '7d8ae4a8-22ee-4d7f-b2ed-6e36bca43a85', '9b1b4e65-7e03-4198-8b4e-7ab96e714de7', '[]', 1, '2024-01-20 11:48:20'),
('6a3e94505e0261d99a7eb786d9207e11b62bd87c160b1bf8b00bb960d08c7711bbcbeeb71e8babb9', 'afd60c21-e7b6-4d97-80b0-6599e9ae1624', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', '[]', 1, '2024-01-16 20:47:00'),
('91f68b4d2ec81858dbd885c6f4fa2e4790f0481d05b8a551d9254252fad6748b46cb9c7621c4cd76', '7d8ae4a8-22ee-4d7f-b2ed-6e36bca43a85', '9b1b4e65-7e03-4198-8b4e-7ab96e714de7', '[]', 1, '2024-01-23 05:36:59'),
('975d517b33048d9bce7a57668779708540ac77a488ef54b596f1e467709f0cd1964053b577cc67b7', '1d239e3b-435c-4a27-9fa6-3dab8b2e20ce', '9b1b4e65-7e03-4198-8b4e-7ab96e714de7', '[]', 1, '2024-01-23 05:16:36'),
('977ea78d05931adf76a9f37b3489de425b42fcae944237a812bea61aec7a39faa36b46b17267bc9d', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', '[]', 1, '2024-01-16 20:24:02'),
('d02ada4781586e403f44c2f17835ab6a836e4f298cec73d26c0a20a71f89aa37943e1206802fb9ff', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', '[]', 1, '2024-01-16 20:43:17'),
('e1bb57ff47451262d9b20d3f881563ab4ae7fb816d44e908ce2eb9efa9fca03f19d63a449b8a29ce', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4e65-7e03-4198-8b4e-7ab96e714de7', '[]', 1, '2024-01-16 20:59:52'),
('e2e1058ee228e0111df7900d487de731bc549e1fe9ee722c90dcad9f7aeb9c582cadcb3fccb4a663', '1d239e3b-435c-4a27-9fa6-3dab8b2e20ce', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', '[]', 1, '2024-01-16 20:42:36'),
('ea62402e8edddf880504ee445f8a1e3c1f6defe7802f3b8e2951a264ddf5d98b647a74408445bb0d', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', '[]', 1, '2024-01-16 20:27:36'),
('efeb59acf6046790a3b0a2fda4f88620cc9b5803392d597540c3a7763bafe136a87a1b636b1ac3f3', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4e65-7e03-4198-8b4e-7ab96e714de7', '[]', 1, '2024-01-16 20:57:32'),
('faffde0e78719ca34f73fe3cd8384ba6a920665ff3178f6a362164be1249653b28d014f92f96f8c7', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', '[]', 1, '2024-01-16 20:27:01');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
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
('9b1889f3-fe9d-4ba8-943f-c2d0c0923e49', NULL, 'Warka One Personal Access Client', 'P7IzPzL4dLz5cfOH2V5XygyWamaq0S55HI620eMH', NULL, 'http://localhost', 1, 0, 0, '2024-01-15 02:36:00', '2024-01-15 02:36:00'),
('9b1889f4-9763-424e-9dbd-18e2138735fe', NULL, 'Warka One Password Grant Client', '2SCqFymVJBJ4Lwxf7XFLScnZojeInUaQ6WJKCgZH', 'users', 'http://localhost', 0, 1, 0, '2024-01-15 02:36:00', '2024-01-15 02:36:00'),
('9b1b4d7b-dfd6-489a-b338-78043207b6bf', NULL, 'Ker PMS local', 'hduUBWgGwYEZ0FcQYq63ULRTFNggoc9lqS46fjiS', NULL, 'http://localhost:8000/auth/callback', 0, 0, 0, '2024-01-16 11:34:24', '2024-01-16 11:34:24'),
('9b1b4de2-8b24-445d-9b11-dbfa46d00463', NULL, 'Ker PMS online', 'N9LAmjIKlMdXCGgG4WnqA3R6Ra7jC7CgT8pTZHGu', NULL, 'https://pms.kertech.co/auth/callback', 0, 0, 0, '2024-01-16 11:35:31', '2024-01-16 11:35:31'),
('9b1b4e65-7e03-4198-8b4e-7ab96e714de7', NULL, 'Ker Wallet local', 'pSliIC8fF18EFXqtT2fRnWhVpQHfVrjj0nA90sV5', NULL, 'http://localhost:8080/auth/callback', 0, 0, 0, '2024-01-16 11:36:56', '2024-01-16 11:36:56'),
('9b1b4e9a-7bdf-41ef-a854-a6066b6d47dc', NULL, 'Ker Wallet online', 'ueSIuUEqyTDJOaBDD5FNJe5ZA87NTzYNXOy2t7pI', NULL, 'https://wallet.kertech.co/auth/callback', 0, 0, 0, '2024-01-16 11:37:31', '2024-01-16 11:37:31');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, '9b1889f3-fe9d-4ba8-943f-c2d0c0923e49', '2024-01-15 02:36:00', '2024-01-15 02:36:00');

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
('01a331f6fd081684314ae69ffe3efba076559dc3266acbf9d088bd56052fa3c189e74101a82b12f3', 'd30cd7d6c9845db4a476069603a27d7d72c3c37e685fa903e306daf9370eef464b3c5a19579d01f8', 0, '2025-01-16 20:14:03'),
('12fed00573856f18ccbfa873ac352e423b7fe18896b71c5b7fe8abf906ab421ac39eb68b10f993d2', 'ed128f1569656c18f1b3466c37db6c7efa39f615464923dc7369482354872a95edf8c3398073a1f0', 0, '2025-01-23 05:27:02'),
('188ce9d73e7350e18a5aed5f8d1c06f398cfa03cc22e73a367cb6a257f958a0b341ca185fc9d8a63', 'fa71c34d16c54a1b96ed2329f734bbdee0abb7dc2f84a5ad04167cabe5032331fa85e039ce704a23', 0, '2025-01-20 11:38:23'),
('764ea0dd380a9c40027d23a4736ff4e4f8a8bc4acce0fe5a14b0358f8e1ab29f5e884b238250b366', '3b9fb42823699e2ea12eac56ee8c6ebd6710552146a0fa3321779be6dcdb2b193d227036e4895938', 0, '2025-01-16 20:37:02'),
('7f1e0b6b22ac0216a34f530e3ac6c988b6d2e941bb77c6212a9b8ea1bc1072a0a9afbc11417707bb', '4e01044adf5e4793118da699a85eea5b9aa66e44062a924f6a2c35373885acbf2e39845e42a609ba', 0, '2025-01-22 21:39:38'),
('8f3b2f177ca2aaa1d01c6132241170f2e7b63858cb2e8955345b360c08decbd2942bfb7efd646d71', 'c874acf72bac236134b8d5f9f1ee393a077643d474078a17d49b86955cf0b8050081382b30069de3', 0, '2025-01-16 20:33:19'),
('8fd09ffd9993512c8c57d3bf64c45880bcc7062f4c958db00cbb8949e84913d0549db98121e6fee7', 'af0478acf23fd70220d8a0c4b74e91af8108d9a0ab71cb0111c219b065326c05e5ad6c5fa722901a', 0, '2025-01-23 05:06:47'),
('acc1078b0101c84a5dd53cf57c6b316d5728579d517f291e6857c0a5c3a47ae2de8d000366139114', 'a55e3e9f0c616e99e9602cf7a4f742c66c35e80da33d92168eab2acb3a5cb43810c8bb80272dc980', 0, '2025-01-16 20:08:50'),
('af95afdb70cf1e59ace57bbd853753c1b26e0c8929acb49f863d33991ea77a9acc0c4c7b2fe45bdb', 'cc01b8b0ccd6f2cfc86a32e6afdcff14091e2358df3a0547fa9b505fcb31e052403e1579428255c1', 0, '2025-01-16 20:47:34'),
('b3854144e1726762807c3dae7bd7042da16e5589517ac130104a46207033af5ba47d122ac0cbd6ea', '67c79f66811c404a0fc39c1817bd9a13a05011f6a39f7a5f6a0bbfa26ca6e21e01712ef4ca617daf', 0, '2025-01-16 20:17:38'),
('d7003df12c449de18e837f8e95aa1896170bdf7c8f84d3a216be0cb08ef707b0c607f0801a5a2172', '0716e55d42a01df73bb9bb765fe9f64363f38134be3fb2778cc8a89558cd2efde2934e6812d0f2f3', 0, '2025-01-16 20:49:53'),
('dc5dd6796500bcdab6810de1ed6a3c66373397c55ca1b770e2b9a332c8cc2e0f9111e686cf3c3af0', '0ae02d951d26944c09f4975887bc79cce73dc7e5511d48772a315b046b64089f1ef191ca973e0584', 0, '2025-01-16 20:32:37'),
('e7de53a98aee3fc511567eee3e5075c7868d491a1f0bc1c981c8b71e2c1a08561f2e35a0d7eb0d6b', '662a838e7fd4b1c7d65757e3b924ed9f5bb13d72e72dcf8caabdf2cae548a1e26bb9d3a35314ea21', 0, '2025-01-16 20:17:02');

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
('1d239e3b-435c-4a27-9fa6-3dab8b2e20ce', 'Addis ababew', 'mydreemname@gmail.com', NULL, '2024-01-16 17:32:35', NULL, '$2y$12$FQgBFoJOUmMUw4np0F7gSOfF5fiXysIGjxaG7yGh.Eid28JJPORCe', NULL, NULL, '2024-01-16 17:32:35', '2024-01-16 17:32:35'),
('66b8a047-5dd8-4041-8d1d-1aa5c91963d9', 'Nuur Al am', 'lurakewmm@mailinator.com', '940678728', NULL, NULL, '$2y$12$njA9HzrVKDn1hOV0qQhOxOcMJcTZxjZ1/47Li0i8x09AwHcdQ9kqa', NULL, NULL, '2024-01-25 01:27:30', '2024-01-25 01:27:30'),
('7d8ae4a8-22ee-4d7f-b2ed-6e36bca43a85', 'Nesru 2', 'nesrusadik0@gmail.com', NULL, '2024-01-20 03:05:31', NULL, '$2y$12$4BU.oKfXo48sQJTBbXgXouIRJ5UEZycI7BjpKlE4S5a9g6NNxCasC', NULL, NULL, '2024-01-20 03:05:32', '2024-01-20 03:58:10'),
('9192a21c-0ebd-4bf0-a2c8-a808446449e3', 'Leyla', 'hyfowo@mailinator.com', '912345678', NULL, NULL, '$2y$12$KWI8aOoXvGwaR9QC9YSjEOqtkjavRxdC5EK6R5GJu/0bNs5b5VRzu', NULL, NULL, '2024-01-25 01:54:45', '2024-01-25 01:54:45'),
('98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', 'Nesru', 'nesrusadik@gmail.com', NULL, '2024-01-16 16:55:03', NULL, '$2y$12$mmhST6hlM/eDn1PoXB0Dn.t3qiw39Ptwpr.EHpHagfXGDH.HTuMLy', NULL, NULL, '2024-01-16 16:55:04', '2024-01-16 16:55:04'),
('afd60c21-e7b6-4d97-80b0-6599e9ae1624', 'vomuhiziwa', 'nesrusadik6@gmail.com', '956001894', NULL, NULL, '$2y$12$j4CtL2QghTgc7WYDLMwq1u2vlZLu0Vdil9JhUjeFZg12e63bgOLMy', NULL, NULL, '2024-01-16 17:34:57', '2024-01-16 17:36:48'),
('f7ae5939-0ff5-49b8-815f-70893228bffd', 'Preston Barker', 'lurakewul@mailinator.com', '940678727', NULL, NULL, '$2y$12$7NOsLVZ06EFuJiUh.Ox9vuTWmFgz6qKIr949SdA7oj7c21p93mQbm', NULL, NULL, '2024-01-18 17:28:16', '2024-01-18 17:28:16'),
('fb672d38-47ac-4279-8e7a-597803f18fb0', 'Nesru 3', NULL, '940678725', NULL, '2024-01-20 03:26:34', '$2y$12$V1LnL3Zwx8fIGRRfYeh1q.H97XLNkR8T9jPfh/SmSItnYUPqjNBre', NULL, NULL, '2024-01-20 03:26:34', '2024-01-20 03:26:34');

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
(7, 'email', '2107', '2024-01-16 17:33:51', 'nesrusadik6@gmail.com', 'sent', '2024-01-16 17:28:51', '2024-01-16 17:28:51'),
(8, 'email', '3113', '2024-01-16 17:34:29', 'nesrusadik6@gmail.com', 'sent', '2024-01-16 17:29:29', '2024-01-16 17:29:29');

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_codes`
--
ALTER TABLE `verification_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
