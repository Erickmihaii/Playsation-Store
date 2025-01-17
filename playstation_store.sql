-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gazdă: 127.0.0.1
-- Timp de generare: ian. 17, 2025 la 06:01 PM
-- Versiune server: 10.4.32-MariaDB
-- Versiune PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Bază de date: `playstation_store`
--

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`) VALUES
(1, 'Action', 'Action-packed games with high intensity'),
(2, 'RPG', 'Role-playing games with immersive stories'),
(3, 'Sports', 'Games simulating various sports activities');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `release_date` date DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `release_date`, `category_id`, `stock`, `image`) VALUES
(1, 'The Last of Us Part II', 'An action-adventure game.', 59.99, '2020-06-19', 1, 109, 'https://cdn.ozone.ro/media/catalog/product/cache/1/image/400x498/a4e40ebdc3e371adff845072e1c73f37/t/h/1b8e07a68e9c2469cbef641cf6e13c10/the-last-of-us--part-ii-ps4-30.jpg'),
(2, 'GTA 5', 'An open-world RPG by FromSoftware.', 69.99, '2022-02-25', 2, 50, 'https://lcdn.mediagalaxy.ro/resize/media/catalog/product/G/r/2bd48d28d1c32adea0e55139a4e6434a/Grand_Theft_Auto_V_Premium_Edition_PS4_2D_COVER_076fd4ac.jpg'),
(3, 'FIFA 23', 'One of the best football simulation game.', 49.99, '2023-09-30', 3, 200, 'https://superretro.com.au/cdn/shop/products/fifa-23-ps4-35582-577974_600x.jpg?v=1705907211'),
(6, 'Minecraft', 'The best game!', 20.00, '2009-05-17', 1, 2, 'uploads/678a89e33bafa_product-image-7.jpg');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `created_at`, `password`) VALUES
(1, 'player1', 'player1@example.com', 'hashedpassword1', '2025-01-13 15:40:11', ''),
(2, 'player2', 'player2@example.com', 'hashedpassword2', '2025-01-13 15:40:11', ''),
(3, 'mihaieric37@gmail.com', '', '', '2025-01-13 20:12:21', '$2y$10$4Le.yWEGhheuCpvPg44Fo.sgxCaAWUIVpD1SXo20Q0niEuQL5kmwO');

--
-- Indexuri pentru tabele eliminate
--

--
-- Indexuri pentru tabele `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexuri pentru tabele `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexuri pentru tabele `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pentru tabele eliminate
--

--
-- AUTO_INCREMENT pentru tabele `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pentru tabele `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pentru tabele `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constrângeri pentru tabele eliminate
--

--
-- Constrângeri pentru tabele `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
