CREATE DATABASE IF NOT EXISTS filmflux_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE filmflux_db;

-- 1. TABLO: Kullanıcılar (users)
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

-- Örnek Admin ve Kullanıcı (Şifreleri: 123456 - Hashlenmiş hali)
INSERT INTO `users` (`username`, `email`, `password`, `role`) VALUES
('Admin', 'admin@filmflux.com', '$2y$10$wI5.L.XH...', 'admin'),
('Misafir', 'user@filmflux.com', '$2y$10$wI5.L.XH...', 'user');

-- 2. TABLO: Filmler (movies)
DROP TABLE IF EXISTS `movies`;
CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `director` varchar(100) DEFAULT NULL,
  `release_year` int(11) DEFAULT NULL,
  `cast` varchar(255) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `duration` int(11) DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `release_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`);



-- Örnek Film Verisi (Kategori sütunu eklendi)
INSERT INTO `movies` (`title`, `director`, `release_year`, `duration`, `category`, `status`) VALUES
('Inception', 'Christopher Nolan', 2010, 148, 'Bilim Kurgu', 1),
('The Dark Knight', 'Christopher Nolan', 2008, 152, 'Aksiyon', 1),
('Interstellar', 'Christopher Nolan', 2014, 169, 'Bilim Kurgu', 0);

-- 3. TABLO: Yorumlar (reviews)
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_movie` (`movie_id`);

-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- Tablo için AUTO_INCREMENT değeri `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;