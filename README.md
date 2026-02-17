# VEXORA CAPITAL
## Cryptocurrency Investment Trading Platform

### 🎯 Features
- User Registration & Email Verification
- Cryptocurrency Deposits (BTC, ETH, USDT TRC-20)
- Investment Plans (6 tiers from Basic to Real Estate)
- Withdrawal System with Admin Approval
- Referral Program (5-15% commission)
- Admin Control Panel
- Transaction History
- Real-time Balance Management

### 💻 Tech Stack
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Hosting:** Compatible with shared hosting (InfinityFree, 000webhost, etc.)

### 📦 Installation

#### 1. Database Setup
```bash
# Import database schema
mysql -u username -p database_name < database/database_schema.sql
```

#### 2. Configure Backend
Edit `backend/config.php` and update:
- Database credentials
- SMTP email settings
- Site URL
- Wallet addresses (already configured with real addresses)

#### 3. Upload Files
Upload all files to your web hosting via FTP or cPanel File Manager

#### 4. Set Permissions
```bash
chmod 755 backend/
chmod 644 backend/*.php
```

### 🔐 Default Admin Login
- **URL:** `/admin-login.html`
- **Username:** `Gabbyy042`
- **Password:** `Chukwu1$` ⚠️ CHANGE THIS IMMEDIATELY!

### 💳 Wallet Addresses (Pre-configured)
- **BTC:** `bc1qz69wrtc6nkrqhxmta0h7jzv6wcut8pshaus3hw`
- **ETH:** `0xC8E8029Fe2Ea976394563F7a650f417b4dA5bAfE`
- **USDT (TRC-20):** `TGLf3LMQ5FCjk5HuJx7gmmPLVWWkAxgXLD`

### 📊 Investment Plans
| Plan | Min | Max | Profit | Duration |
|------|-----|-----|--------|----------|
| Basic | $50 | $499 | 20% | 24h |
| Bronze | $500 | $999 | 35% | 48h |
| Standard | $1,000 | $1,999 | 80% | 92h |
| Gold | $2,000 | $2,499 | 100% | 48h |
| Company Shares | $6,000 | - | 120% | 72h |
| Real Estate | $10,000 | - | 150% | 48h |

### ⚙️ Configuration Checklist
- [ ] Import database schema
- [ ] Update `backend/config.php` with your database credentials
- [ ] Configure SMTP email settings
- [ ] Change default admin password
- [ ] Test user registration
- [ ] Test deposit flow
- [ ] Test admin panel

### 🚀 Deployment to InfinityFree
1. Log in to InfinityFree cPanel
2. Go to File Manager
3. Upload all files to `htdocs` folder
4. Create MySQL database in MySQL Databases
5. Import `database/database_schema.sql`
6. Update `backend/config.php` with database details
7. Visit your domain!

### 📧 Email Configuration
For Gmail SMTP:
1. Enable 2FA on your Google account
2. Generate App Password
3. Use App Password in `backend/config.php`

### 🛡️ Security Features
- Password hashing (bcrypt)
- CSRF token protection
- SQL injection prevention (prepared statements)
- XSS protection
- Session management
- Input sanitization

### 📝 File Structure
```
vexora-capital/
├── index.html (Landing page)
├── register.html
├── login.html  
├── dashboard.html
├── deposit.html
├── withdraw.html
├── admin-login.html
├── admin.html
├── backend/
│   ├── config.php
│   ├── functions.php
│   ├── register-process.php
│   ├── login-process.php
│   └── ... (20+ PHP files)
├── css/
│   ├── styles.css
│   └── responsive.css
├── js/
│   ├── script.js
│   ├── auth.js
│   └── ... (more JS files)
└── database/
    └── database_schema.sql
```

### ⚠️ Important Notes
- This is a manual investment platform (admin approves all transactions)
- Deposits go directly to admin wallets
- Admin manually credits user accounts
- Not automated trading - admin manages everything
- Ensure you have proper legal compliance in your jurisdiction

### 📞 Support
For issues or questions, check the code comments or contact the developer.

### 📜 License
Private project - All rights reserved

---
**Built with ❤️ for VEXORA CAPITAL**
