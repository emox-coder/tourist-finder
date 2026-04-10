-- Database updates for Top Destinations management
USE tourist_finder_db;

-- Add image_url column if not exists
ALTER TABLE `attractions` 
ADD COLUMN `image_url` VARCHAR(500) DEFAULT NULL AFTER `description`;

-- Add is_top_destination flag to distinguish top destinations
ALTER TABLE `attractions` 
ADD COLUMN `is_top_destination` TINYINT(1) DEFAULT 0 AFTER `image_url`;

-- Add display_order for controlling card order
ALTER TABLE `attractions` 
ADD COLUMN `display_order` INT DEFAULT 0 AFTER `is_top_destination`;

-- Add category column if not exists (for cities/municipalities filter)
-- Note: category already exists in schema, but let's ensure it's properly typed