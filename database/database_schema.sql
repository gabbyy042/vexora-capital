-- VEXORA CAPITAL - Complete Database Schema
-- Import this file in phpMyAdmin to create all tables

CREATE DATABASE IF NOT EXISTS vexora_capital CHARACTER SET utf8mb4;
USE vexora_capital;

-- Users Table
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    country VARCHAR(50),
    password_hash VARCHAR(255) NOT NULL,
    balance DECIMAL(15,2) DEFAULT 0.00,
    total_deposited DECIMAL(15,2) DEFAULT 0.00,
    total_invested DECIMAL(15,2) DEFAULT 0.00,
    total_profit DECIMAL(15,2) DEFAULT 0.00,
    total_withdrawn DECIMAL(15,2) DEFAULT 0.00,
    referral_earnings DECIMAL(15,2) DEFAULT 0.00,
    referral_code VARCHAR(20) UNIQUE NOT NULL,
    referred_by INT NULL,
    email_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(255),
    status ENUM('active', 'suspended', 'unverified') DEFAULT 'unverified',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Deposits Table
CREATE TABLE deposits (
    deposit_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    crypto_type ENUM('BTC', 'ETH', 'USDT') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    wallet_address VARCHAR(255) NOT NULL,
    tx_hash VARCHAR(255),
    status ENUM('pending', 'completed', 'rejected') DEFAULT 'pending',
    admin_approved_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved_at TIMESTAMP NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Withdrawals Table
CREATE TABLE withdrawals (
    withdrawal_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    crypto_type ENUM('BTC', 'ETH', 'USDT') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    fee DECIMAL(15,2) NOT NULL,
    net_amount DECIMAL(15,2) NOT NULL,
    wallet_address VARCHAR(255) NOT NULL,
    tx_hash VARCHAR(255),
    status ENUM('pending', 'processing', 'completed', 'rejected') DEFAULT 'pending',
    admin_processed_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Investments Table
CREATE TABLE investments (
    investment_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    plan_name VARCHAR(50) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    profit_percentage DECIMAL(5,2) NOT NULL,
    expected_profit DECIMAL(15,2) NOT NULL,
    duration_hours INT NOT NULL,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    maturity_date TIMESTAMP NOT NULL,
    completed_at TIMESTAMP NULL,
    profit_paid DECIMAL(15,2) DEFAULT 0.00,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Referrals Table
CREATE TABLE referrals (
    referral_id INT PRIMARY KEY AUTO_INCREMENT,
    referrer_user_id INT NOT NULL,
    referred_user_id INT NOT NULL,
    commission_rate DECIMAL(5,2) NOT NULL,
    total_commission_earned DECIMAL(15,2) DEFAULT 0.00,
    status ENUM('pending', 'active', 'inactive') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_referrer (referrer_user_id),
    INDEX idx_referred (referred_user_id),
    FOREIGN KEY (referrer_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (referred_user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Transactions Table
CREATE TABLE transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type ENUM('deposit', 'withdrawal', 'investment', 'profit', 'referral_commission') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    balance_before DECIMAL(15,2) NOT NULL,
    balance_after DECIMAL(15,2) NOT NULL,
    description TEXT,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_type (type),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Admin Users Table
CREATE TABLE admin_users (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    role ENUM('super_admin', 'admin') DEFAULT 'admin',
    status ENUM('active', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    INDEX idx_username (username)
) ENGINE=InnoDB;

-- Insert default admin (Password: Chukwu1$)
INSERT INTO admin_users (username, password_hash, full_name, role) VALUES
('Gabbyy042', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'super_admin');

-- Activity Logs
CREATE TABLE activity_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL,
    admin_id INT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_admin_id (admin_id),
    INDEX idx_action (action)
) ENGINE=InnoDB;