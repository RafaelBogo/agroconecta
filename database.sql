-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 19/11/2025 às 10:49
-- Versão do servidor: 5.7.23-23
-- Versão do PHP: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `rafa2264_agro`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `created_at`, `updated_at`) VALUES
(25, 2, 3, 'Olá', '2025-11-08 20:18:27', '2025-11-08 20:18:27');

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendente',
  `mp_payment_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mp_status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `total_price`, `status`, `mp_payment_id`, `mp_status`, `created_at`, `updated_at`) VALUES
(188, 1, 20, 2, 40.00, 'Retirado', '133059604240', 'approved', '2025-11-08 20:22:57', '2025-11-08 20:23:59'),
(189, 1, 19, 1, 10.00, 'Retirado', '132460000043', 'approved', '2025-11-08 20:53:44', '2025-11-08 20:54:11'),
(190, 2, 17, 1, 10.00, 'Retirado', '132465091061', 'approved', '2025-11-08 21:30:56', '2025-11-08 21:31:23'),
(191, 2, 18, 1, 10.00, 'Concluido', '133069327370', 'approved', '2025-11-08 21:36:17', '2025-11-08 21:36:30'),
(192, 1, 17, 1, 10.00, 'Retirado', '133079951260', 'approved', '2025-11-08 22:52:33', '2025-11-08 22:52:59'),
(193, 1, 17, 1, 10.00, 'Retirado', NULL, NULL, '2025-11-08 22:54:41', '2025-11-09 17:44:39'),
(194, 1, 17, 2, 20.00, 'Concluido', NULL, NULL, '2025-11-09 11:50:55', '2025-11-09 11:50:55'),
(195, 1, 17, 1, 10.00, 'Concluido', NULL, NULL, '2025-11-09 11:52:24', '2025-11-09 11:52:24'),
(196, 1, 18, 2, 20.00, 'Concluido', '132519866397', 'approved', '2025-11-09 12:01:30', '2025-11-09 12:01:47'),
(197, 1, 17, 1, 10.00, 'Concluido', '132520413713', 'approved', '2025-11-09 12:11:29', '2025-11-09 12:11:41'),
(198, 1, 17, 1, 10.00, 'Concluido', '133124504544', 'approved', '2025-11-09 12:16:49', '2025-11-09 12:17:04'),
(199, 1, 17, 2, 20.00, 'Concluido', '133131141560', 'approved', '2025-11-09 13:42:06', '2025-11-09 13:42:17'),
(200, 3, 20, 1, 20.00, 'Retirado', '132873484021', 'approved', '2025-11-11 23:58:18', '2025-11-11 23:59:32'),
(201, 3, 20, 1, 20.00, 'Retirado', '133350433705', 'approved', '2025-11-15 14:43:06', '2025-11-15 14:43:40'),
(202, 3, 20, 1, 20.00, 'Retirado', '133350736317', 'approved', '2025-11-15 14:43:57', '2025-11-15 14:44:21'),
(203, 1, 17, 1, 10.00, 'Retirado', '133990484114', 'approved', '2025-11-15 18:43:01', '2025-11-15 18:43:37'),
(204, 1, 20, 1, 20.00, 'Retirado', '133381860969', 'approved', '2025-11-15 18:45:50', '2025-11-15 18:46:17'),
(205, 1, 17, 1, 10.00, 'Retirado', '133992407162', 'approved', '2025-11-15 19:02:15', '2025-11-15 19:02:36'),
(206, 1, 21, 1, 10.00, 'Retirado', '133459424629', 'approved', '2025-11-16 13:02:18', '2025-11-16 13:02:52'),
(207, 1, 21, 1, 10.00, 'Concluido', '133464852021', 'approved', '2025-11-16 14:07:56', '2025-11-16 14:08:10'),
(208, 1, 21, 1, 10.00, 'Concluido', '134074114630', 'approved', '2025-11-16 14:10:35', '2025-11-16 14:10:49'),
(209, 1, 21, 1, 10.00, 'Concluido', '134074083884', 'approved', '2025-11-16 14:11:53', '2025-11-16 14:12:07'),
(210, 11, 22, 2, 20.00, 'Retirado', '133631567249', 'approved', '2025-11-17 21:46:41', '2025-11-17 21:48:09'),
(211, 1, 22, 1, 10.00, 'Retirado', '133632169773', 'approved', '2025-11-17 21:52:05', '2025-11-17 21:52:53'),
(212, 1, 20, 2, 30.00, 'Concluido', '134242121136', 'approved', '2025-11-17 21:57:29', '2025-11-17 21:57:44'),
(213, 1, 23, 2, 11.00, 'Retirado', '134386585912', 'approved', '2025-11-18 22:46:10', '2025-11-18 23:03:10'),
(214, 1, 24, 2, 15.00, 'Retirado', '134389011944', 'approved', '2025-11-18 23:02:35', '2025-11-18 23:03:08'),
(215, 11, 17, 2, 20.00, 'Retirado', '134389214834', 'approved', '2025-11-18 23:03:46', '2025-11-18 23:07:57'),
(216, 11, 23, 2, 21.00, 'Retirado', '134390421296', 'approved', '2025-11-18 23:14:21', '2025-11-18 23:14:51');

-- --------------------------------------------------------

--
-- Estrutura para tabela `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(169, 188, 20, 2, 20.00, '2025-11-08 20:22:57', '2025-11-08 20:22:57'),
(170, 189, 19, 1, 10.00, '2025-11-08 20:53:44', '2025-11-08 20:53:44'),
(171, 190, 17, 1, 10.00, '2025-11-08 21:30:56', '2025-11-08 21:30:56'),
(172, 191, 18, 1, 10.00, '2025-11-08 21:36:17', '2025-11-08 21:36:17'),
(173, 192, 17, 1, 10.00, '2025-11-08 22:52:33', '2025-11-08 22:52:33'),
(174, 193, 17, 1, 10.00, '2025-11-08 22:54:41', '2025-11-08 22:54:41'),
(175, 194, 17, 2, 10.00, '2025-11-09 11:50:55', '2025-11-09 11:50:55'),
(176, 195, 17, 1, 10.00, '2025-11-09 11:52:24', '2025-11-09 11:52:24'),
(177, 196, 18, 1, 10.00, '2025-11-09 12:01:30', '2025-11-09 12:01:30'),
(178, 196, 17, 1, 10.00, '2025-11-09 12:01:30', '2025-11-09 12:01:30'),
(179, 197, 17, 1, 10.00, '2025-11-09 12:11:29', '2025-11-09 12:11:29'),
(180, 198, 17, 1, 10.00, '2025-11-09 12:16:49', '2025-11-09 12:16:49'),
(181, 199, 17, 1, 10.00, '2025-11-09 13:42:06', '2025-11-09 13:42:06'),
(182, 199, 18, 1, 10.00, '2025-11-09 13:42:06', '2025-11-09 13:42:06'),
(183, 200, 20, 1, 20.00, '2025-11-11 23:58:18', '2025-11-11 23:58:18'),
(184, 201, 20, 1, 20.00, '2025-11-15 14:43:06', '2025-11-15 14:43:06'),
(185, 202, 20, 1, 20.00, '2025-11-15 14:43:57', '2025-11-15 14:43:57'),
(186, 203, 17, 1, 10.00, '2025-11-15 18:43:01', '2025-11-15 18:43:01'),
(187, 204, 20, 1, 20.00, '2025-11-15 18:45:50', '2025-11-15 18:45:50'),
(188, 205, 17, 1, 10.00, '2025-11-15 19:02:15', '2025-11-15 19:02:15'),
(189, 206, 21, 1, 10.00, '2025-11-16 13:02:18', '2025-11-16 13:02:18'),
(190, 207, 21, 1, 10.00, '2025-11-16 14:07:56', '2025-11-16 14:07:56'),
(191, 208, 21, 1, 10.00, '2025-11-16 14:10:35', '2025-11-16 14:10:35'),
(192, 209, 21, 1, 10.00, '2025-11-16 14:11:53', '2025-11-16 14:11:53'),
(193, 210, 22, 1, 10.00, '2025-11-17 21:46:41', '2025-11-17 21:46:41'),
(194, 210, 21, 1, 10.00, '2025-11-17 21:46:41', '2025-11-17 21:46:41'),
(195, 211, 22, 1, 10.00, '2025-11-17 21:52:05', '2025-11-17 21:52:05'),
(196, 212, 20, 1, 20.00, '2025-11-17 21:57:29', '2025-11-17 21:57:29'),
(197, 212, 17, 1, 10.00, '2025-11-17 21:57:29', '2025-11-17 21:57:29'),
(198, 213, 23, 1, 1.00, '2025-11-18 22:46:10', '2025-11-18 22:46:10'),
(199, 213, 24, 1, 10.00, '2025-11-18 22:46:10', '2025-11-18 22:46:10'),
(200, 214, 24, 1, 10.00, '2025-11-18 23:02:35', '2025-11-18 23:02:35'),
(201, 214, 26, 1, 5.00, '2025-11-18 23:02:35', '2025-11-18 23:02:35'),
(202, 215, 17, 1, 10.00, '2025-11-18 23:03:46', '2025-11-18 23:03:46'),
(203, 215, 18, 1, 10.00, '2025-11-18 23:03:46', '2025-11-18 23:03:46'),
(204, 216, 23, 1, 1.00, '2025-11-18 23:14:21', '2025-11-18 23:14:21'),
(205, 216, 20, 1, 20.00, '2025-11-18 23:14:21', '2025-11-18 23:14:21');

-- --------------------------------------------------------

--
-- Estrutura para tabela `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `validity` date DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `city`, `stock`, `is_active`, `created_at`, `updated_at`, `unit`, `validity`, `contact`, `address`, `photo`, `user_id`) VALUES
(17, 'Tomate', 'Tomate', 10.00, 'Chapecó', 97, 1, '2025-11-01 21:09:15', '2025-11-18 23:03:58', 'kg', '2025-12-31', '49988577860', 'Sala 2', 'products/WmQe6ejErBDwp0xSvZGA85NTRDDTWMY1PKatrzJE.png', 3),
(18, 'Alface', 'Alface', 10.00, 'Quilombo', 99, 1, '2025-11-01 21:17:31', '2025-11-18 23:03:58', 'kg', '2025-12-31', '49988665451', 'Avenida Vergílio Sabino da Silva', 'products/VYzjsSDTCPCw12xR9Pk6YoSBK5GZwiaYqkWftgPK.jpg', 3),
(19, 'Beterraba', 'Beterraba Roxa', 10.00, 'Abelardo Luz', 1000, 1, '2025-11-06 13:16:17', '2025-11-06 13:16:17', 'kg', '2025-12-31', '49988665451', 'Centro', 'products/KknmXHp9uF9DLrJv8yXNixfpfMibZzoQf22W0u2b.webp', 3),
(20, 'Tilápia', 'Tilápia Suja', 20.00, 'Chapecó', 496, 1, '2025-11-08 18:54:21', '2025-11-18 23:14:32', 'kg', '2026-01-31', '49 99967-9944', 'Avenida Qualquer coisa', 'products/WI8Iqi527iMBUk9PsUVB3gb34mPDXx6DG5MveTeR.jpg', 2),
(21, 'Morango', 'Morango', 10.00, 'Barra Bonita', 95, 1, '2025-11-15 14:46:18', '2025-11-17 21:47:18', 'un', '2025-12-01', '49988665451', 'Avenida Vergílio Sabino da Silva', 'products/XWtKweF5N1KYRZ6vnAAiIpJBcdzEhKjSla10AvIW.jpg', 3),
(22, 'Abacaxi', 'Abacaxi Grande', 10.00, 'Abelardo Luz', 98, 1, '2025-11-17 21:43:56', '2025-11-17 21:52:18', 'un', '2028-12-01', '49988577860', 'Centro', 'products/84UXDRjMB2fl5FeNKhAraR38IDQ3P4mIs5eDGv8z.jpg', 3),
(23, 'teste', 'teste', 1.00, 'Anchieta', 2, 1, '2025-11-18 22:44:45', '2025-11-18 23:14:32', 'un', '2025-01-01', '49988665451', 'bom jesus', 'products/3V9IF1sMsvmpkdgwhwPXSXiOtTccndLGSN8JgCVp.jpg', 3),
(24, 'teste 2', 'teste 2', 10.00, 'Barra Bonita', 8, 1, '2025-11-18 22:45:44', '2025-11-18 23:02:54', 'g', '2025-12-01', '531651561651', 'BOm jesus', 'products/7pdNtzt7AJNJXdTQafPPuzgvgEFQSufodvIM0u84.jpg', 3),
(25, 'teste 3', 'teste', 21.00, 'Belmonte', 1, 1, '2025-11-18 23:01:08', '2025-11-18 23:01:08', 'kg', '2025-12-31', '49988665451', 'bom jesus', 'products/xjlD3MqULi14YXz4rJJiky3dZqhW2gdaic2Xd1fs.jpg', 1),
(26, 'TESTE3', 'TESTE', 5.00, 'Anchieta', 0, 1, '2025-11-18 23:02:04', '2025-11-18 23:02:54', 'kg', '2025-01-01', '49988665451', 'Centro', 'products/XiaCUxF8LPU2YXBTyZ2dZiopPklyX7jas6EYz0Hq.jpg', 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(7, 18, 1, 5, NULL, '2025-11-03 23:10:14', '2025-11-03 23:10:14'),
(9, 20, 1, 5, 'Ok', '2025-11-08 20:47:52', '2025-11-08 20:47:52'),
(10, 19, 1, 5, NULL, '2025-11-08 22:52:19', '2025-11-08 22:52:19'),
(11, 17, 1, 5, 'top', '2025-11-08 22:53:10', '2025-11-08 22:53:10'),
(12, 17, 2, 5, 'Tomate bom de barato, podem comprar sem medo', '2025-11-11 23:58:33', '2025-11-11 23:58:33'),
(13, 20, 3, 5, 'Ruim', '2025-11-15 18:05:41', '2025-11-15 18:05:41'),
(14, 21, 1, 5, NULL, '2025-11-16 13:03:02', '2025-11-16 13:03:02'),
(15, 22, 11, 5, 'otimo', '2025-11-17 21:48:23', '2025-11-17 21:48:23'),
(16, 22, 1, 5, 'ok', '2025-11-17 21:53:04', '2025-11-17 21:53:04'),
(17, 24, 1, 5, NULL, '2025-11-18 23:03:20', '2025-11-18 23:03:20'),
(18, 23, 1, 5, NULL, '2025-11-18 23:03:23', '2025-11-18 23:03:23'),
(19, 18, 11, 5, NULL, '2025-11-18 23:08:03', '2025-11-18 23:08:03'),
(20, 21, 11, 5, NULL, '2025-11-18 23:08:07', '2025-11-18 23:08:07'),
(21, 17, 11, 5, NULL, '2025-11-18 23:08:10', '2025-11-18 23:08:10'),
(22, 23, 11, 5, NULL, '2025-11-18 23:15:02', '2025-11-18 23:15:02'),
(23, 20, 11, 5, 'top', '2025-11-18 23:15:06', '2025-11-18 23:15:06');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0FCGwBXPOufqbIlutkYbm5ZwnaDk4ZHBa6UuMaUv', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRkRuMFFNRVBqUWhSOTRVZFdIaG5ySVY1dXZtRmwyZFVJWmZjYWtCQiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FjY291bnQvb3JkZXJzIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdXRvcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1761430895),
('4KYBp3mghqvXrXD7QiXPAI0GyhbmYUKafB8YsLXZ', NULL, '18.206.34.84', 'MercadoPago Feed v2.0 merchant_order', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiVDhwa3RJMXNxd3BIelA4bnBwWjRWYnlYQ0FBQjNOZFBUYnF4UW1mRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761430571),
('4sJwiALPDPB6Cm8KhVqiJ1ejwD3KvcrVnhh2bEb6', NULL, '18.215.140.160', 'MercadoPago Feed v2.0 merchant_order', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiYjlqYlg1NHJ3RVZ1Y1NVN3NXc0pQMzVPWlh0VElCck95ZE5wNlhSTCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761431724),
('A1Z1OYXHES3w21oaGi4us93CvvLLIuASZMWDN9vC', NULL, '18.206.34.84', 'MercadoPago WebHook v1.0 payment', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiU1JtR0MwOE1DYUg2NFExc0NUTlVxNkdrdzJJZlpvZzhZaTRiUTFQeCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761430619),
('GWgAIrT54hmY8qXq8L9Hd4XgDDcPFOWhoKAwU9oo', 1, '2804:12c8:8200:9661:419a:9947:50e1:e1e7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiV3BEZ0Z2SjE3b1RyS3FZVzhMZGNRTEJ1MUtmODduN1Jyc3MwbU15UCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1NzoiaHR0cHM6Ly9yYWZhZWxiLjE3MDEyNDQubWV1c2l0ZWhvc3RnYXRvci5jb20uYnIvZGFzaGJvYXJkIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTc6Imh0dHBzOi8vcmFmYWVsYi4xNzAxMjQ0Lm1ldXNpdGVob3N0Z2F0b3IuY29tLmJyL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1761431849),
('H5ovt4uBH0piQYLXkIAaDm2gq7cyDKQBt1Xvl7Md', NULL, '54.88.218.97', 'MercadoPago Feed v2.0 payment', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoielh5cGtLbmNoU3Z5ZmozSFI3dmkzNTQ0SDI4OWdDN0xBODFZQzhkaiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761430617),
('HDTmumnBdqUoVykwDaOmqYiilDCfy5EiaMzlzif6', NULL, '54.88.218.97', 'MercadoPago Feed v2.0 merchant_order', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoidW5icTRRMUNYWXA0VFM5QzlxVzhYbVNsMjlBcUg2MUJ6QVVnUk02WCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761430572),
('lg1lT7oyEWGpSe9Rr23YXb4UkZLyq86UG9lDx5Rd', NULL, '18.213.114.129', 'MercadoPago WebHook v1.0 payment', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiV0Fpa1N5ZVBRazJmNTI4VXB1TkRVTlJCUGl2UGFzUDRZajJzREt5VCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761430571),
('lh8asRtrfhb51w4FjfnAFL2lpGdwXPHDKXHA2H53', NULL, '18.213.114.129', 'MercadoPago Feed v2.0 merchant_order', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiRThBZHdENTVVRXdsdHJPUko2aWlhVWx5dmdrY092NWJuZUx3ZVUwTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761430570),
('Nmo1STlcvVMfjEYwo5g2zKFYJVClUnnwSNZ2J2Pi', NULL, '18.206.34.84', 'MercadoPago Feed v2.0 payment', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWDE4WXIwQUptUFhGbDhyOExnU2xkRERoRGlRNzR6OW53OGpjeVg4dSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761431728),
('Ova2Q4Rbqe9B49t6nJ5PSxUhUY2i9oGCY8GTBvGN', NULL, '18.206.34.84', 'MercadoPago Feed v2.0 merchant_order', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiOTZid21VMmFWSEZNTVo1VE50eG92RHF3aVB0ejRUV2ZPU0tPeWRTciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761430570),
('RC5N88NfrhpyUazYuyc33Un6leqDuuHzugaUpRNO', NULL, '18.206.34.84', 'MercadoPago Feed v2.0 payment', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoidlR5aGM5UDlLcmEyMFpTd294dzJyRnJRdWF2aDZ4NmdHd3N3MEE3diI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761430571),
('TI8Cun2TdAstDSNBUVMnuU2ZoTwfRRIm5SHbCSqP', NULL, '18.213.114.129', 'MercadoPago WebHook v1.0 payment', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZVc3R205aFBlRFBSclhwdUxmUjh6cjVlMEZRcTNQY2NxckFibkozTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761430618),
('ywULRAVCUKqq7GqC2b3jbZELe3n3tgS5jN1AQ2Za', NULL, '18.206.34.84', 'MercadoPago Feed v2.0 payment', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiYno4TEhUY085b2pDeHloNDRXZjNVOUNqelIyR0VNTWNxOVNWMk1veSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761430617),
('yzBIxkU984UwqaTVYTIrbqqE2IBvahzVElV4E1fS', NULL, '54.88.218.97', 'MercadoPago Feed v2.0 merchant_order', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoid0dqbWxwcm9hdzVMUWpnVkVTRklWdkJVckhGbGpoY2lLbkJ4MERQNCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761430571),
('ZCqenW06yOLg5NXynHB29RdJjesprz5Nm4tQliGc', NULL, '18.206.34.84', 'MercadoPago WebHook v1.0 payment', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoicjlCNWFKdTN3bjBTVzZnZXpPV1dGanJRTGNLMHJnVmQzSVUyanVPZiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761431728);

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `city`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Rafael', 'rafaelbogo52@gmail.com', '49988665451', 'teste', 'Saltinho', NULL, '$2y$12$xSbdRdWLeMRXduYJIOmmbOhhL4lWuzBRB1HEEgAW7yYsEOu2E.kZO', NULL, '2025-08-25 16:25:38', '2025-10-14 02:21:21'),
(2, 'Luis Henrique', 'luisscatolin16@gmail.com', NULL, NULL, NULL, NULL, '$2y$12$.0YZc8lKT8zCAr3Gk4V1UeV.Hm2BITYfQ8UODkJfCtzRcwIR6pOaC', NULL, '2025-08-25 17:02:58', '2025-08-25 17:02:58'),
(3, 'Agroconecta', 'suporte.agroconecta@gmail.com', NULL, NULL, NULL, NULL, '$2y$12$NHeph60pemjjtbmZukOZFekHmkrUcPkhSWYQQXfp7SuhQ9HrQCU5O', NULL, '2025-08-28 23:02:19', '2025-08-28 23:02:19'),
(4, 'André Luiz Rossi', 'luizrossi161@gmail.com', NULL, NULL, NULL, NULL, '$2y$12$z1SsSebIDC3h3qyMe03NTOgNHztTVDNniUreQ3CdM6.WtHht7zj7G', NULL, '2025-08-31 13:19:24', '2025-08-31 13:19:24'),
(5, 'ANDREIA MARINI', 'marini2.andreia@gmail.com', NULL, NULL, NULL, NULL, '$2y$12$fnNGbGmAHmpCu2nDP2c34uwFF6BCYYpJleC6DZlMW8J8HSLbZ5OY2', NULL, '2025-09-01 22:09:32', '2025-09-01 22:09:32'),
(6, 'Teodoro Franceski', 'teodoromattosfranceski@gmail.com', NULL, NULL, NULL, NULL, '$2y$12$FTG9CyoRf5ljn48JMFWKq.EaJC.bc0ukAJa/bcUtsbRyjQfNLfEXy', NULL, '2025-09-09 00:50:01', '2025-09-09 00:50:01'),
(11, 'Rafael Bogo', 'rafaelbogo636@gmail.com', '888888888888', 'bom jesus', NULL, NULL, '$2y$12$/7koXFBHgHueXq2IWTbS6.hMqnwaL3P.wpdVIhgLIxn1bXgmeNEoG', NULL, '2025-11-17 21:41:13', '2025-11-17 21:41:13');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Índices de tabela `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Índices de tabela `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_user_id_foreign` (`user_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`);

--
-- Índices de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Índices de tabela `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Índices de tabela `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_product_id_foreign` (`product_id`),
  ADD KEY `idx_orders_mp_payment_id` (`mp_payment_id`);

--
-- Índices de tabela `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Índices de tabela `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Índices de tabela `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_user_id_foreign` (`user_id`);

--
-- Índices de tabela `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`);

--
-- Índices de tabela `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=217;

--
-- AUTO_INCREMENT de tabela `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Restrições para tabelas `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
