CREATE DATABASE IF NOT EXISTS filmflux_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE filmflux_db;

-- 1. TABLO: Kullanıcılar (users)
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Örnek Admin ve Kullanıcı (Şifreleri: 123456 - Hashlenmiş hali)
INSERT INTO `users` (`username`, `email`, `password`, `role`) VALUES
('Admin', 'admin@filmflux.com', '$2y$10$wI5.L.XH...', 'admin'),
('Misafir', 'user@filmflux.com', '$2y$10$wI5.L.XH...', 'user');

-- 2. TABLO: Filmler (movies)
DROP TABLE IF EXISTS `movies`;
CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `director` varchar(100) DEFAULT NULL,
  `cast` text DEFAULT NULL, -- Oyuncular
  `release_year` year(4) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL, -- Dakika
  `category` varchar(50) DEFAULT NULL, -- DÜZELTİLDİ: Kodlarınla uyumlu olması için 'genre' yerine 'category' yapıldı.
  `summary` text DEFAULT NULL,
  `poster_url` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1, -- 1: Vizyonda, 0: Yakında
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`movie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Örnek Film Verisi (Kategori sütunu eklendi)
INSERT INTO `movies` (`title`, `director`, `release_year`, `duration`, `category`, `status`) VALUES
('Inception', 'Christopher Nolan', 2010, 148, 'Bilim Kurgu', 1),
('The Dark Knight', 'Christopher Nolan', 2008, 152, 'Aksiyon', 1),
('Interstellar', 'Christopher Nolan', 2014, 169, 'Bilim Kurgu', 0);

-- 3. TABLO: Yorumlar (reviews)
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  KEY `user_id` (`user_id`),
  KEY `movie_id` (`movie_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;