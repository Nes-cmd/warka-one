-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2024 at 06:27 AM
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
('662a838e7fd4b1c7d65757e3b924ed9f5bb13d72e72dcf8caabdf2cae548a1e26bb9d3a35314ea21', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', NULL, '[]', 0, '2024-01-16 17:17:02', '2024-01-16 17:17:02', '2025-01-16 20:17:02'),
('67c79f66811c404a0fc39c1817bd9a13a05011f6a39f7a5f6a0bbfa26ca6e21e01712ef4ca617daf', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', NULL, '[]', 0, '2024-01-16 17:17:38', '2024-01-16 17:17:38', '2025-01-16 20:17:38'),
('a55e3e9f0c616e99e9602cf7a4f742c66c35e80da33d92168eab2acb3a5cb43810c8bb80272dc980', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', NULL, '[]', 0, '2024-01-16 17:08:49', '2024-01-16 17:08:49', '2025-01-16 20:08:49'),
('c874acf72bac236134b8d5f9f1ee393a077643d474078a17d49b86955cf0b8050081382b30069de3', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', NULL, '[]', 0, '2024-01-16 17:33:19', '2024-01-16 17:33:19', '2025-01-16 20:33:19'),
('cc01b8b0ccd6f2cfc86a32e6afdcff14091e2358df3a0547fa9b505fcb31e052403e1579428255c1', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4e65-7e03-4198-8b4e-7ab96e714de7', NULL, '[]', 0, '2024-01-16 17:47:34', '2024-01-16 17:47:34', '2025-01-16 20:47:34'),
('d30cd7d6c9845db4a476069603a27d7d72c3c37e685fa903e306daf9370eef464b3c5a19579d01f8', '98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', NULL, '[]', 0, '2024-01-16 17:14:03', '2024-01-16 17:14:03', '2025-01-16 20:14:03');

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
('6a3e94505e0261d99a7eb786d9207e11b62bd87c160b1bf8b00bb960d08c7711bbcbeeb71e8babb9', 'afd60c21-e7b6-4d97-80b0-6599e9ae1624', '9b1b4d7b-dfd6-489a-b338-78043207b6bf', '[]', 1, '2024-01-16 20:47:00'),
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
('764ea0dd380a9c40027d23a4736ff4e4f8a8bc4acce0fe5a14b0358f8e1ab29f5e884b238250b366', '3b9fb42823699e2ea12eac56ee8c6ebd6710552146a0fa3321779be6dcdb2b193d227036e4895938', 0, '2025-01-16 20:37:02'),
('8f3b2f177ca2aaa1d01c6132241170f2e7b63858cb2e8955345b360c08decbd2942bfb7efd646d71', 'c874acf72bac236134b8d5f9f1ee393a077643d474078a17d49b86955cf0b8050081382b30069de3', 0, '2025-01-16 20:33:19'),
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
('98ef2e7a-366a-4b69-9c1d-61bb0b7be6d8', 'Nesru', 'nesrusadik0@gmail.com', NULL, '2024-01-16 16:55:03', NULL, '$2y$12$mmhST6hlM/eDn1PoXB0Dn.t3qiw39Ptwpr.EHpHagfXGDH.HTuMLy', NULL, NULL, '2024-01-16 16:55:04', '2024-01-16 16:55:04'),
('afd60c21-e7b6-4d97-80b0-6599e9ae1624', 'vomuhiziwa', 'nesrusadik6@gmail.com', '956001894', NULL, NULL, '$2y$12$j4CtL2QghTgc7WYDLMwq1u2vlZLu0Vdil9JhUjeFZg12e63bgOLMy', NULL, NULL, '2024-01-16 17:34:57', '2024-01-16 17:36:48');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
