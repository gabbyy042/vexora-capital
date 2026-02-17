-- Create database
CREATE DATABASE IF NOT EXISTS vexora_capital CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vexora_capital;

-- USERS TABLE
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
    phone_verified BOOLEAN DEFAULT FALSE,
    kyc_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(255),
    status ENUM('active', 'suspended', 'unverified') DEFAULT 'unverified',
    reset_token VARCHAR(255),
    reset_token_expiry TIMESTAMP NULL,
    two_factor_enabled BOOLEAN DEFAULT FALSE,
    two_factor_secret VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_referral_code (referral_code),
    INDEX idx_status (status),
    FOREIGN KEY (referred_by) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- DEPOSITS TABLE
CREATE TABLE deposits (
    deposit_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    crypto_type ENUM('BTC', 'ETH', 'USDT') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    crypto_amount VARCHAR(50),
    wallet_address VARCHAR(255) NOT NULL,
    tx_hash VARCHAR(255),
    confirmations INT DEFAULT 0,
    status ENUM('pending', 'confirmed', 'completed', 'rejected') DEFAULT 'pending',
    admin_approved_by INT,
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    confirmed_at TIMESTAMP NULL,
    approved_at TIMESTAMP NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- WITHDRAWALS TABLE
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
    admin_notes TEXT,
    rejection_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- INVESTMENTS TABLE
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
    profit_paid_at TIMESTAMP NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- REFERRALS TABLE
CREATE TABLE referrals (
    referral_id INT PRIMARY KEY AUTO_INCREMENT,
    referrer_user_id INT NOT NULL,
    referred_user_id INT NOT NULL,
    commission_rate DECIMAL(5,2) NOT NULL,
    total_commission_earned DECIMAL(15,2) DEFAULT 0.00,
    status ENUM('pending', 'active', 'inactive') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    first_deposit_at TIMESTAMP NULL,
    INDEX idx_referrer (referrer_user_id),
    FOREIGN KEY (referrer_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_referral (referrer_user_id, referred_user_id)
) ENGINE=InnoDB;

-- TRANSACTIONS TABLE
CREATE TABLE transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type ENUM('deposit', 'withdrawal', 'investment', 'profit', 'referral_commission', 'bonus', 'fee', 'adjustment') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    balance_before DECIMAL(15,2) NOT NULL,
    balance_after DECIMAL(15,2) NOT NULL,
    description TEXT,
    reference_type ENUM('deposit', 'withdrawal', 'investment', 'admin_edit') NULL,
    reference_id INT NULL,
    status ENUM('pending', 'completed', 'failed', 'cancelled') DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ADMIN USERS TABLE
CREATE TABLE admin_users (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    role ENUM('super_admin', 'admin', 'moderator') DEFAULT 'admin',
    status ENUM('active', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
) ENGINE=InnoDB;

-- Default admin user (username: Gabbyy042, password: Chukwu1$)
INSERT INTO admin_users (username, password_hash, full_name, role) 
VALUES ('Gabbyy042', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'super_admin');

-- ACTIVITY LOGS TABLE
CREATE TABLE activity_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL,
    admin_id INT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(50),
    user_agent TEXT,
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- NOTIFICATIONS TABLE
CREATE TABLE notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'danger') DEFAULT 'info',
    link_url VARCHAR(500),
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- SETTINGS TABLE
CREATE TABLE settings (
    setting_id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    description TEXT
) ENGINE=InnoDB;

-- Default settings
INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'VEXORA CAPITAL', 'string', 'Website name'),
('min_deposit', '50', 'number', 'Minimum deposit amount in USD'),
('min_withdrawal', '50', 'number', 'Minimum withdrawal amount in USD'),
('withdrawal_fee_percent', '2', 'number', 'Withdrawal fee percentage');
