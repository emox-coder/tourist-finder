CREATE DATABASE IF NOT EXISTS tourist_finder_db;
USE tourist_finder_db;

-- --------------------------------------------------------

-- Table structure for table `admins`
CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert a default admin account (password is 'password123' optionally hashed)
-- Password uses a standard BCRYPT hash for "password123"
INSERT INTO `admins` (`email`, `password`) VALUES
('admin@example.com', '$2y$10$e.ox6W3zQNqbQ5ZkGjPGJ.891i4yfZYFsePzlBCXsCLoamgfgM5ou');

-- --------------------------------------------------------

-- Table structure for table `admin_management`
CREATE TABLE IF NOT EXISTS `admin_management` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `attractions`
CREATE TABLE IF NOT EXISTS `attractions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `location` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `category` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
