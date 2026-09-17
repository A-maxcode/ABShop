-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql106.infinityfree.com
-- Generation Time: Sep 17, 2026 at 06:14 AM
-- Server version: 11.4.13-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_42897329_abshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `name`, `created_at`) VALUES
(1, 'admin', '$2y$10$3pcW2rW7bsIAjCCaw.TZWupOuu46hGQuBy/3iumSj9EndeToYMg9.', 'Administrator', '2026-09-12 13:19:10');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`) VALUES
(1, 'Apparel', 'apparel', '2026-09-12 13:19:10'),
(2, 'Bags', 'bags', '2026-09-12 13:19:10'),
(3, 'Home', 'home', '2026-09-12 13:19:10'),
(4, 'Accessories', 'accessories', '2026-09-12 13:19:10');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_number` varchar(30) NOT NULL,
  `email` varchar(150) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `email`, `first_name`, `last_name`, `address`, `city`, `postal_code`, `country`, `subtotal`, `shipping`, `total`, `status`, `created_at`) VALUES
(1, 'AB260917098E05', 'lising@gmail.com', 'rey', 'lising', 'secret', 'tagbilaran', '6300', 'Other', '89.00', '12.00', '101.00', 'pending', '2026-09-17 09:00:16');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `size`, `color`, `quantity`, `unit_price`) VALUES
(1, 1, 1, 'Linen Oversized Shirt', 'XS', 'Ivory', 1, '89.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(500) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `image`, `images`, `sizes`, `colors`, `is_featured`, `is_new`, `stock`, `created_at`, `updated_at`) VALUES
(1, 1, 'Linen Oversized Shirt', 'linen-oversized-shirt', 'Breathable premium linen with a relaxed silhouette. Perfect for effortless layering in any season.', '89.00', 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600&h=750&fit=crop', '[\"https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600&h=750&fit=crop\",\"https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=600&h=750&fit=crop\"]', '[\"XS\",\"S\",\"M\",\"L\",\"XL\"]', '[\"Ivory\",\"Sand\",\"Olive\"]', 1, 1, 100, '2026-09-12 13:19:10', '2026-09-12 13:19:10'),
(2, 2, 'Structured Leather Tote', 'structured-leather-tote', 'Full-grain leather tote with clean lines and thoughtful interior organization. Designed to age beautifully.', '245.00', 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=600&h=750&fit=crop', '[\"https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=600&h=750&fit=crop\",\"https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&h=750&fit=crop\"]', '[\"One Size\"]', '[\"Cognac\",\"Black\",\"Espresso\"]', 1, 0, 100, '2026-09-12 13:19:10', '2026-09-12 13:19:10'),
(3, 1, 'Cashmere Crew Neck', 'cashmere-crew-neck', 'Ultra-soft Mongolian cashmere in a classic crew silhouette. Lightweight warmth with refined drape.', '168.00', 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=600&h=750&fit=crop', '[\"https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=600&h=750&fit=crop\"]', '[\"S\",\"M\",\"L\",\"XL\"]', '[\"Charcoal\",\"Camel\",\"Navy\"]', 1, 0, 100, '2026-09-12 13:19:10', '2026-09-12 13:19:10'),
(4, 3, 'Ceramic Pour-Over Set', 'ceramic-pour-over-set', 'Hand-finished ceramic dripper and matching mug. Minimal form meets daily ritual.', '78.00', 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?w=600&h=750&fit=crop', '[\"https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?w=600&h=750&fit=crop\"]', '[\"One Size\"]', '[\"Matte White\",\"Stone\"]', 0, 1, 100, '2026-09-12 13:19:10', '2026-09-12 13:19:10'),
(5, 1, 'Wool Blend Overcoat', 'wool-blend-overcoat', 'Tailored silhouette in a soft wool-cashmere blend. Double-faced construction for clean structure.', '320.00', 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=600&h=750&fit=crop', '[\"https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=600&h=750&fit=crop\"]', '[\"S\",\"M\",\"L\",\"XL\"]', '[\"Camel\",\"Black\",\"Grey\"]', 1, 0, 100, '2026-09-12 13:19:10', '2026-09-12 13:19:10'),
(6, 4, 'Minimalist Watch', 'minimalist-watch', 'Swiss movement, sapphire crystal, and a refined 38mm case. Understated luxury for everyday wear.', '195.00', 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&h=750&fit=crop', '[\"https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&h=750&fit=crop\"]', '[\"One Size\"]', '[\"Silver\",\"Gold\"]', 0, 1, 100, '2026-09-12 13:19:10', '2026-09-12 13:19:10'),
(7, 1, 'Organic Cotton Tee', 'organic-cotton-tee', 'Heavyweight organic cotton with a perfect relaxed fit. Softened for immediate comfort.', '48.00', 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&h=750&fit=crop', '[\"https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&h=750&fit=crop\"]', '[\"XS\",\"S\",\"M\",\"L\",\"XL\"]', '[\"White\",\"Black\",\"Heather\"]', 0, 0, 100, '2026-09-12 13:19:10', '2026-09-12 13:19:10'),
(8, 3, 'Sculptural Vase', 'sculptural-vase', 'Hand-thrown stoneware with a matte glaze. An organic form that anchors any surface.', '92.00', 'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?w=600&h=750&fit=crop', '[\"https://images.unsplash.com/photo-1578500494198-246f612d3b3d?w=600&h=750&fit=crop\"]', '[\"One Size\"]', '[\"Terracotta\",\"Ivory\"]', 0, 1, 100, '2026-09-12 13:19:10', '2026-09-12 13:19:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
