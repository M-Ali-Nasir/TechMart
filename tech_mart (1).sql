-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2024 at 03:31 PM
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
-- Database: `tech_mart`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `price`) VALUES
(38, 18, 1, 4, 799.99),
(39, 18, 8, 4, 149.99),
(40, 18, 6, 1, 349.99);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Mobile', 'Category for mobile phones'),
(2, 'Laptops', 'Category for laptops'),
(3, 'Watch', 'Category for watches'),
(4, 'Sports', 'Category for sports equipment and accessories');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','shipped','delivered','cancelled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `total`, `status`) VALUES
(2, 15, '2024-08-19 10:44:10', 1799.97, 'cancelled'),
(3, 15, '2024-08-19 14:49:20', 1499.97, 'shipped'),
(4, 15, '2024-08-19 14:50:58', 1499.97, 'cancelled'),
(5, 15, '2024-08-19 14:51:15', 1499.97, 'shipped'),
(12, 15, '2024-08-20 09:44:36', 9999.87, 'pending'),
(13, 15, '2024-08-20 13:31:45', 1799.97, 'shipped'),
(14, 15, '2024-08-20 13:44:05', 499.98, 'delivered'),
(15, 15, '2024-08-20 13:49:12', 1149.98, 'delivered'),
(16, 15, '2024-08-21 12:29:33', 699.99, 'shipped'),
(17, 15, '2024-08-21 14:42:01', 2199.97, 'shipped'),
(18, 15, '2024-08-22 07:47:11', 129.99, 'pending'),
(19, 15, '2024-08-22 07:48:00', 799.99, 'shipped'),
(20, 16, '2024-08-22 07:49:08', 1249.97, 'pending'),
(21, 15, '2024-08-22 17:07:01', 699.99, 'pending'),
(22, 15, '2024-08-24 15:25:52', 2199.95, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_address`
--

CREATE TABLE `order_address` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_address`
--

INSERT INTO `order_address` (`id`, `order_id`, `name`, `address`, `zip_code`, `phone`) VALUES
(1, 5, 'Muhammad Ali Nasir', 'Ittifaq City Dahranwala', '62380', '03086127224'),
(3, 12, 'Muhammad Ali Nasir', 'Ittifaq City Dahranwala', '62380', '03086127224'),
(4, 13, 'Muhammad Ali Nasir', 'Ittifaq City Dahranwala', '62380', '03086127224'),
(5, 14, 'Muhammad Ali Nasir', 'Ittifaq City Dahranwala', '62380', '03086127224'),
(6, 15, 'Muhammad Ali Nasir', 'Ittifaq City Dahranwala', '62380', '03086127224'),
(7, 16, 'Muhammad Ali Nasir', 'Ittifaq City Dahranwala', '62380', '03086127224'),
(8, 17, 'Muhammad Ali Nasir', 'Ittifaq City Dahranwala', '62380', '03086127224'),
(9, 18, 'Muhammad Ali Nasir', 'Ittifaq City Dahranwala', '62380', '03086127224'),
(10, 19, 'Ali Akbar', 'Ittifaq City Dahranwala', '62380', '03086127224'),
(11, 20, 'Ali Akbar', 'Ittifaq City Dahranwala', '62380', '03086127224'),
(12, 21, 'Muhammad Ali Nasir', 'Ittifaq city Dahranwala tehsil Chishtian', '62380', '+923086127224'),
(13, 22, 'Muhammad Ali Nasir', 'Ittifaq city Dahranwala tehsil Chishtian', '62380', '+923086127224');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`) VALUES
(1, 2, 2, 1),
(2, 2, 11, 1),
(3, 2, 39, 1),
(4, 3, 3, 3),
(5, 4, 3, 3),
(6, 5, 3, 3),
(7, 12, 2, 9),
(8, 12, 9, 4),
(9, 13, 1, 1),
(10, 13, 3, 2),
(11, 14, 6, 1),
(12, 14, 8, 1),
(13, 15, 2, 1),
(14, 15, 8, 1),
(15, 16, 38, 1),
(16, 17, 2, 2),
(17, 17, 4, 1),
(18, 18, 10, 1),
(19, 19, 1, 1),
(20, 20, 3, 2),
(21, 20, 9, 1),
(22, 21, 11, 1),
(23, 22, 3, 3),
(24, 22, 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`, `subcategory_id`) VALUES
(1, 'Samsung Galaxy S21', 'Latest Samsung mobile phone with advanced UI and features.', 799.99, 'product_1_66c48e2e9a2c29.60880619.jpg', 1, 1),
(2, 'Apple iPhone 13 pro', 'New generation of Apple iPhone.', 999.99, 'product_2_66c484335db3b7.89297807.jpg', 1, 2),
(3, 'Vivo V21', 'Mid range Vivo smartphone with great camera.', 499.99, 'product_3_66c484a3c77713.93876848.jpg', 1, 3),
(4, 'Tecno Spark 7 pro', 'Affordable Tecno smartphone with decent specs.', 199.99, 'product_4_66c484bbbe3e85.13307922.jpg', 1, 4),
(5, 'Redmi Note 10', 'Popular budget phone from Redmi.', 299.99, 'product_5_66c48f492bd7c4.14282327.jpg', 1, 5),
(6, 'Samsung Galaxy A52', 'Affordable Samsung phone with good performance.', 349.99, 'product_6_66c48f55de4243.33382837.jpg', 1, 1),
(7, 'Apple iPhone SE', 'Compact and powerful Apple iPhone.', 699.99, 'product_7_66c48f63451d60.40670388.jpg', 1, 2),
(8, 'Vivo Y20', 'Entry level Vivo smartphone.', 149.99, 'product_8_66c48f8c3ba117.04130924.jpg', 1, 3),
(9, 'Tecno Camon 16', 'Tecno phone with excellent camera.', 249.99, 'product_9_66c5cef6a64b07.48827536.jpeg', 1, 4),
(10, 'Redmi 9A', 'Entry level phone from Redmi.', 129.99, 'product_10_66c5cf23cd46b8.66807064.jpg', 1, 5),
(11, 'HP Pavilion 15', 'HP laptop with good balance of performance and price.', 699.99, 'product_11_66c5cf3f621748.41169268.jpg', 2, 6),
(12, 'DELL Inspiron 15', 'Dell laptop with powerful specs.', 799.99, 'product_12_66c5cf512f8718.11421107.jpg', 2, 7),
(13, 'ASUS ZenBook 14', 'ASUS laptop with slim design and strong performance.', 899.99, 'product_13_66c5d1044f3a51.05693722.jpg', 2, 8),
(14, 'Lenovo ThinkPad X1', 'Business laptop from Lenovo with top notch security.', 1099.99, 'product_14_66c5d12ab084e4.33939760.jpg', 2, 9),
(15, 'HP Spectre x360', 'High end HP convertible laptop.', 1199.99, 'product_15_66c5d13f4c2744.37484370.jfif', 2, 6),
(16, 'DELL XPS 13', 'Dell flagship laptop with stunning display.', 1299.99, 'product_16_66c5d15421f534.00009299.jpg', 2, 7),
(17, 'ASUS ROG Zephyrus', 'Gaming laptop from ASUS with powerful GPU.', 1499.99, 'product_17_66c5d28c3cbde7.72661932.png', 2, 8),
(18, 'Lenovo Yoga 7i', 'Lenovo convertible laptop with touch display.', 999.99, 'product_18_66c5d2aa53d7c5.72653820.jpg', 2, 9),
(19, 'HP Envy 13', 'Portable and powerful HP laptop.', 899.99, 'product_19_66c5d2bfa44c59.17168135.jpg', 2, 6),
(20, 'DELL G5 15', 'Dell gaming laptop with great value for money.', 1099.99, 'product_20_66c5d2de422f08.06860475.jpg', 2, 7),
(21, 'Huawei Watch GT 2', 'Smartwatch from Huawei with long battery life.', 199.99, 'product_21_66c5d3da4c4099.71726593.jpg', 3, 10),
(22, 'Apple Watch Series 6', 'Latest Apple smartwatch with advanced health features.', 399.99, 'product_22_66c5d3ef0ebe66.94097669.jpg', 3, 11),
(23, 'Google Pixel Watch', 'Google’s first smartwatch with Wear OS.', 349.99, 'product_23_66c5d3fd11cba7.60091271.jpg', 3, 12),
(24, 'Redmi Classic Watch', 'Affordable Regmi classic watch.', 49.99, 'product_24_66c5d416c40747.83387698.jpg', 3, 13),
(25, 'Huawei Watch Fit', 'Fitness oriented smartwatch from Huawei.', 129.99, 'product_25_66c5d4ea78fee4.93994869.jpg', 3, 10),
(26, 'Apple Watch SE', 'Affordable Apple smartwatch with core features.', 279.99, 'product_26_66c5d4fd920755.58026551.jfif', 3, 11),
(27, 'Google Fit Watch', 'Google smartwatch focused on fitness tracking.', 199.99, 'product_27_66c5d518eba037.35838012.png', 3, 12),
(28, 'Redmi Sports Watch', 'Durable and affordable sports watch from Regmi.', 79.99, 'product_28_66c5d53057d5c1.43136645.jpg', 3, 13),
(29, 'Huawei Band 6', 'Huawei fitness band with essential features.', 49.99, 'product_29_66c5d664c3dbd8.05906371.png', 3, 10),
(30, 'Apple Watch Nike', 'Apple smartwatch with special Nike features.', 429.99, 'product_30_66c5d676521b21.93564846.jpg', 3, 11),
(31, 'Garmin Forerunner 945', 'Premium sports watch for runners.', 599.99, 'product_31_66c5d6a22195d5.89821305.jpg', 4, 14),
(32, 'Fitbit Charge 4', 'Fitness tracker from Fitbit with GPS.', 149.99, 'product_32_66c5d6b6e260c7.29738305.jpg', 4, 14),
(33, 'GoPro Hero 9', 'Action camera for sports and adventure.', 399.99, 'product_33_66c5d7cc908289.79627364.jpg', 4, 15),
(34, 'Garmin Vivosmart 4', 'Slim and smart fitness tracker.', 129.99, 'product_34_66c5d7dc10d602.94788375.jpg', 4, 14),
(35, 'Sony Action Cam', 'Compact action camera for sports enthusiasts.', 299.99, 'product_35_66c5d7ee11bae1.83438739.jpg', 4, 15),
(36, 'Fitbit Versa 3', 'Smartwatch from Fitbit with fitness tracking.', 229.99, 'product_36_66c5d7fd8e18c6.55159259.jpg', 4, 14),
(37, 'DJI Osmo Action', 'Versatile action camera for all sports.', 349.99, 'product_37_66c5d8ca9b02d1.07563013.jpg', 4, 15),
(38, 'Garmin Fenix 6', 'Rugged sports watch with advanced features.', 699.99, 'product_38_66c5d8e1e8ab99.44272865.jpg', 4, 14),
(39, 'Fitbit Inspire 2', 'Affordable and easy to use fitness tracker.', 99.99, 'product_39_66c5dcf65807c8.77389038.jpg', 4, 14);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `review` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `review`) VALUES
(10, 2, 15, 3, 'Very Nice Product'),
(13, 1, 15, 4, 'Need a little improvement'),
(14, 3, 15, 5, 'great'),
(15, 11, 15, 5, 'ghjfhgf'),
(16, 9, 15, 4, 'jhgjhcjhbjvjvjhbh');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `name`, `description`, `category_id`) VALUES
(1, 'Samsung', 'Samsung mobile phones', 1),
(2, 'Apple', 'Apple mobile phones', 1),
(3, 'Vivo', 'Vivo mobile phones', 1),
(4, 'Tecno', 'Tecno mobile phones', 1),
(5, 'Redmi', 'Redmi mobile phones', 1),
(6, 'HP', 'HP laptops', 2),
(7, 'DELL', 'DELL laptops', 2),
(8, 'ASUS', 'ASUS laptops', 2),
(9, 'Lenovo', 'Lenovo laptops', 2),
(10, 'Huawei', 'Huawei watches', 3),
(11, 'Apple', 'Apple watches', 3),
(12, 'Google', 'Google watches', 3),
(13, 'Redmi', 'Regmi watches', 3),
(14, 'Watches', 'Sports watches', 4),
(15, 'Gadgets', 'Sports gadgets', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role` enum('customer','admin') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(15, 'Muhammad Ali Nasir', '$2y$10$q5f4d6xiMJvou544nX04C.8wBQaPYcTwuz9pDbXN/0nAx8G6DpF7G', 'man171m@gmail.com', 'customer'),
(16, 'Ali Akbar', '$2y$10$Z1Qsd4eQVmnZt7AMWqOro.q4S0xPSINmtdU9Ql1bM5UrgLfLWYY/i', 'sayaliakbar@gmail.com', 'customer'),
(17, 'Admin', '$2y$10$Z1Qsd4eQVmnZt7AMWqOro.q4S0xPSINmtdU9Ql1bM5UrgLfLWYY/i', 'admin@gmail.com', 'admin'),
(18, 'Muhammad Ahsan', '$2y$10$tO0CY6ONpPI0T/2tp/2sh.v/Hjnpf5NoYIe9fNpwxaKFyeIdVgeYe', 'ahsan@gmail.com', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_address`
--
ALTER TABLE `order_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `subcategory_id` (`subcategory_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_address`
--
ALTER TABLE `order_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_address`
--
ALTER TABLE `order_address`
  ADD CONSTRAINT `order_address_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
