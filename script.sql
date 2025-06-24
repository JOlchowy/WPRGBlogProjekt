create schema blog;
use blog;

CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       email VARCHAR(255) NOT NULL UNIQUE,
                       password_hash VARCHAR(255) NOT NULL,
                       role ENUM('admin','author','user') NOT NULL DEFAULT 'user',
                       is_active TINYINT(1) NOT NULL DEFAULT 0,
                       created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE posts (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       user_id INT NOT NULL,
                       title VARCHAR(255) NOT NULL,
                       body TEXT NOT NULL,
                       image_path VARCHAR(255) DEFAULT NULL,
                       created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                       updated_at DATETIME DEFAULT NULL,
                       FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE comments (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          post_id INT NOT NULL,
                          user_id INT DEFAULT NULL,
                          author_name VARCHAR(100) DEFAULT NULL,
                          body TEXT NOT NULL,
                          created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
                          FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);


INSERT INTO users (email, password_hash, role, is_active, created_at)
VALUES ('admin@test.pl', '$2y$10$GW6egDeoqytOcsOITwuD/eURdzNERyFaEcB/tKay/gXmyLsSU/UtC', 'admin', 1, NOW());
