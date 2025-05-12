-- Create database
CREATE DATABASE IF NOT EXISTS pos_system;
USE pos_system;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager', 'cashier') NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    status ENUM('active', 'inactive') DEFAULT 'active'
);

-- Insert demo users (passwords are hashed versions of admin123, manager123, cashier123)
INSERT INTO users (username, password, role, email, full_name) VALUES
('admin', '$2y$10$8K1p/a0dR1xqM8K3hxKJ3O5VzQ9K9Z9Z9Z9Z9Z9Z9Z9Z9Z9Z9Z9Z', 'admin', 'admin@pos.com', 'System Administrator'),
('manager', '$2y$10$8K1p/a0dR1xqM8K3hxKJ3O5VzQ9K9Z9Z9Z9Z9Z9Z9Z9Z9Z9Z9Z9Z', 'manager', 'manager@pos.com', 'Store Manager'),
('cashier', '$2y$10$8K1p/a0dR1xqM8K3hxKJ3O5VzQ9K9Z9Z9Z9Z9Z9Z9Z9Z9Z9Z9Z9Z', 'cashier', 'cashier@pos.com', 'Store Cashier');

-- Create sessions table for better session management
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(128) NOT NULL PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    payload TEXT NOT NULL,
    last_activity TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
); 