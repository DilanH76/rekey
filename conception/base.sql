CREATE DATABASE IF NOT EXISTS rekey CHARACTER SET utf8mb4;

USE rekey;

CREATE USER IF NOT EXISTS 'admin_rekey'@'localhost' IDENTIFIED BY 'rekey';

GRANT ALL PRIVILEGES ON rekey.* TO 'admin_rekey'@'localhost';

CREATE TABLE users(
   id_user INT AUTO_INCREMENT,
   last_name VARCHAR(50) ,
   first_name VARCHAR(50) ,
   pseudo VARCHAR(50) ,
   email VARCHAR(100) ,
   password VARCHAR(255) ,
   is_admin BOOLEAN,
   created_at DATETIME,
   avatar MEDIUMBLOB,
   PRIMARY KEY(id_user)
) ENGINE=InnoDB;

CREATE TABLE categories(
   id_category INT AUTO_INCREMENT,
   label VARCHAR(50) ,
   PRIMARY KEY(id_category)
) ENGINE=InnoDB;

CREATE TABLE platforms(
   id_platform INT AUTO_INCREMENT,
   label VARCHAR(50) ,
   icon_svg TEXT,
   PRIMARY KEY(id_platform)
) ENGINE=InnoDB;

CREATE TABLE ads(
   id_ads INT AUTO_INCREMENT,
   title VARCHAR(100) ,
   description TEXT,
   price DECIMAL(10,2)  ,
   cover_image MEDIUMBLOB,
   game_key VARCHAR(255) ,
   status VARCHAR(20) ,
   created_at DATETIME,
   id_platform INT NOT NULL,
   id_category INT NOT NULL,
   id_user INT NOT NULL,
   PRIMARY KEY(id_ads),
   FOREIGN KEY(id_platform) REFERENCES platforms(id_platform),
   FOREIGN KEY(id_category) REFERENCES categories(id_category),
   FOREIGN KEY(id_user) REFERENCES users(id_user)
) ENGINE=InnoDB;

CREATE TABLE orders(
   id_order INT AUTO_INCREMENT,
   reference VARCHAR(20) ,
   date_order DATETIME,
   id_ads INT NOT NULL,
   id_user INT NOT NULL,
   PRIMARY KEY(id_order),
   UNIQUE(id_ads),
   FOREIGN KEY(id_ads) REFERENCES ads(id_ads),
   FOREIGN KEY(id_user) REFERENCES users(id_user)
) ENGINE=InnoDB;

CREATE TABLE reviews(
   id_review INT AUTO_INCREMENT,
   rating TINYINT,
   comment TEXT,
   date_review DATETIME,
   id_user INT NOT NULL,
   id_order INT NOT NULL,
   PRIMARY KEY(id_review),
   UNIQUE(id_order),
   FOREIGN KEY(id_user) REFERENCES users(id_user),
   FOREIGN KEY(id_order) REFERENCES orders(id_order)
) ENGINE=InnoDB;

CREATE TABLE news(
   id_news INT AUTO_INCREMENT,
   title VARCHAR(255) ,
   content TEXT,
   image_cover MEDIUMBLOB,
   created_at DATETIME,
   id_user INT NOT NULL,
   PRIMARY KEY(id_news),
   FOREIGN KEY(id_user) REFERENCES users(id_user)
) ENGINE=InnoDB;

CREATE TABLE favorites(
   id_user INT,
   id_ads INT,
   PRIMARY KEY(id_user, id_ads),
   FOREIGN KEY(id_user) REFERENCES users(id_user),
   FOREIGN KEY(id_ads) REFERENCES ads(id_ads)
) ENGINE=InnoDB;






-- AUTRES SCRIPT : 
-- On désactive temporairement les contraintes
SET FOREIGN_KEY_CHECKS = 0;

-- On supprime le contenu proprement
DELETE FROM categories;
DELETE FROM platforms;

-- On remet les compteurs d'ID à 1
ALTER TABLE categories AUTO_INCREMENT = 1;
ALTER TABLE platforms AUTO_INCREMENT = 1;

-- On réactive les contraintes (TRÈS IMPORTANT)
SET FOREIGN_KEY_CHECKS = 1;

-- ==========================================
-- 1. INSERTION DES CATÉGORIES
-- ==========================================
INSERT INTO categories (label) VALUES 
('Action'),
('Aventure'),
('RPG / Jeu de rôle'),
('FPS / TPS (Tir)'),
('Stratégie'),
('Sport'),
('Course'),
('Simulation'),
('MMO'),
('Plateforme'),
('Combat');

-- ==========================================
-- 2. INSERTION DES PLATEFORMES
-- ==========================================
INSERT INTO platforms (label, icon_svg) VALUES 
('Steam', '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>'),

('PlayStation', '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle></svg>'),

('Xbox', '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M8 8l8 8"></path><path d="M16 8l-8 8"></path></svg>'),

('Nintendo Switch', '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="8" height="16" rx="2"></rect><rect x="14" y="4" width="8" height="16" rx="2"></rect><circle cx="6" cy="9" r="1"></circle><circle cx="18" cy="15" r="1"></circle></svg>'),

('Epic Games', '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 2 17 12 22 22 17 22 7 12 2"></polygon></svg>'),

('GOG', '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v8"></path><path d="M8 12h8"></path></svg>');