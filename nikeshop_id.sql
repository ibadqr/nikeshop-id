-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Jun 2024 pada 22.06
-- Versi server: 10.4.18-MariaDB
-- Versi PHP: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nikeshop_id`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `order_id` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','success') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_price`, `order_date`, `status`) VALUES
('666f34f77d419', 1, '11464000.00', '2024-06-16 18:54:47', 'success'),
('666f35a27a5c9', 3, '2447000.00', '2024-06-16 18:57:38', 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `total`) VALUES
(7, '666f34f77d419', 9, 2, '1549000.00', '3098000.00'),
(8, '666f34f77d419', 11, 1, '899000.00', '899000.00'),
(9, '666f34f77d419', 12, 3, '2489000.00', '7467000.00'),
(10, '666f35a27a5c9', 11, 1, '899000.00', '899000.00'),
(11, '666f35a27a5c9', 14, 1, '1548000.00', '1548000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT 'images/default_product.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created_at`) VALUES
(9, 'Nike Dunk Low Retro', 'Nike Dunk Low Retro kembali dengan lapisan luar yang tajam dan warna asli tim.', '1549000.00', '../uploads/nike1.png', '2024-06-16 17:20:36'),
(10, 'Nike Pegasus 41', 'Bantalan responsif pada Pegasus memberikan pengendaraan yang berenergi untuk berlari di jalan sehari-hari.', '2099000.00', '../uploads/nike2.png', '2024-06-16 17:22:24'),
(11, 'Nike Downshifter 13', 'Baik Anda baru memulai perjalanan berlari atau seorang ahli yang ingin mengubah kecepatan Anda, Downshifter 13 siap membantu.', '899000.00', '../uploads/nike3.png', '2024-06-16 17:23:40'),
(12, 'Nike Zoom Vomero 5', 'Vomero 5 membawa awal tahun 2000an ke tingkat modern. Kombinasi bahan yang menyerap keringat dan tahan lama siap menghadapi kerasnya hari Anda.', '2489000.00', '../uploads/nike4.png', '2024-06-16 17:25:14'),
(13, 'Nike Dunk Low', 'Dibuat untuk kayu keras namun dibawa ke jalanan, ikon bola basket tahun 80-an ini kembali dengan detail klasik dan gaya throwback hoop.', '1909000.00', '../uploads/nike5.png', '2024-06-16 17:26:59'),
(14, 'Air Jordan 1 Mid', 'Terinspirasi oleh AJ1 asli, Air Jordan 1 Mid menawarkan kesempatan kepada penggemar untuk mengikuti jejak MJ.', '1548000.00', '../uploads/nike6.png', '2024-06-16 17:28:19'),
(15, 'Nike Invincible 3', 'Nikmati bagian atas Flyknit yang menyerap keringat dan platform kokoh dari busa ZoomX ringan yang melembutkan benturan.', '2849000.00', '../uploads/nike7.png', '2024-06-16 17:37:32'),
(16, 'Nike Invincible 3', 'Invincible 3 memiliki tingkat kenyamanan tertinggi di bagian bawah kaki. Busa ZoomX yang mewah dan kenyal membantu Anda tetap stabil dan segar. ', '3049000.00', '../uploads/nike8.png', '2024-06-16 17:39:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `profile_image` varchar(255) DEFAULT '../uploads/default_profile.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `profile_image`, `created_at`, `role`) VALUES
(1, 'ibad', '$2y$10$hwUL4fPKDLSWvMC4SssChul/wVPrVdAMRj50RWb82SC5TEcLMj/cu', 'ibad.qr@gmail.com', '../images/default_profile.jpg', '2024-06-14 10:29:22', 'user'),
(2, 'admin', '$2y$10$CV6g..ujnDVG7BZUYHIcJez54.nxh1zLc9gVZ37tTG7j9D6a6UDy2', 'admin@system.id', '../images/default_profile.jpg', '2024-06-14 10:42:28', 'admin'),
(3, 'zila', '$2y$10$9wONkoe5IWa8brETeWhn9u/q5eg1tuzPggi70q2YUvcJRW/cmr3HG', 'zila@email.com', '../images/default_profile.jpg', '2024-06-14 12:37:09', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
