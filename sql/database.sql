CREATE DATABASE IF NOT EXISTS filmflux_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE filmflux_db;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ==========================================
-- 1. TABLO: Kullanıcılar (users)
-- ==========================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`username`, `email`, `password`, `role`) VALUES
('Admin', 'admin@filmflux.com', '$2y$10$wI5.L.XH...', 'admin'),
('Misafir', 'user@filmflux.com', '$2y$10$wI5.L.XH...', 'user');

-- ==========================================
-- 2. TABLO: Filmler (movies)
-- ==========================================
DROP TABLE IF EXISTS `movies`;
CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,    
  `director` varchar(100) DEFAULT NULL,
  `release_year` int(11) DEFAULT NULL,
  `cast` varchar(255) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL, 
  `category` varchar(50) DEFAULT NULL,  
  `duration` int(11) DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`movie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `movies` (`title`, `director`, `release_year`, `duration`, `category`, `description`, `image_url`, `status`) VALUES
('Inception', 'Christopher Nolan', 2010, 148, 'Bilim Kurgu', 'Rüyalar içinde rüya...', 'https://image.tmdb.org/t/p/w500/9gk7adHYeDvHkCSEqAvQNLV5Uge.jpg', 1),
('The Dark Knight', 'Christopher Nolan', 2008, 152, 'Aksiyon', 'Batman Joker ile...', 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg', 1);

-- ==========================================
-- 3. TABLO: Yorumlar (reviews)
-- ==========================================
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`review_id`),
  KEY `fk_user` (`user_id`),
  KEY `fk_movie` (`movie_id`),
  CONSTRAINT `fk_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;