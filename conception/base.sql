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
   avatar BLOB,
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
   cover_image BLOB,
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
   image_cover BLOB,
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
