CREATE DATABASE IF NOT EXISTS osr_charger;
USE osr_charger;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    car_number VARCHAR(50) NOT NULL,
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_expiry DATETIME DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS parking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slot INT NOT NULL,
    status ENUM('available','busy') DEFAULT 'available',
    start_time DATETIME DEFAULT NULL,
    end_time DATETIME DEFAULT NULL,
    user_email VARCHAR(150) DEFAULT NULL,
    notified_15m TINYINT DEFAULT 0
);

INSERT INTO parking (slot, status) VALUES (1,'available') ON DUPLICATE KEY UPDATE slot=slot;
INSERT INTO parking (slot, status) VALUES (2,'available') ON DUPLICATE KEY UPDATE slot=slot;
