-- Create three_cards table
USE tourist_finder_db;

CREATE TABLE IF NOT EXISTS three_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(500),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO three_cards (title, description, image_url, display_order) VALUES
('Adventure Awaits', 'Discover amazing destinations and create unforgettable memories in the Philippines.', 'assets/img/three-cards/adventure.jpg', 1),
('Cultural Heritage', 'Experience rich Filipino culture and traditions passed down through generations.', 'assets/img/three-cards/heritage.jpg', 2),
('Natural Beauty', 'Explore pristine beaches, mountains, and natural wonders of the region.', 'assets/img/three-cards/nature.jpg', 3);
