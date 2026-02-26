# 📖 Installation Guide - VEXORA CAPITAL

## Quick Start (5 Minutes)

### Step 1: Prerequisites
- [ ] PHP 7.4 or higher
- [ ] MySQL 5.7 or higher
- [ ] Web hosting account
- [ ] Domain or subdomain
- [ ] Email account (Gmail, Outlook, etc.)

### Step 2: Database Setup
1. Login to cPanel → Open phpMyAdmin
2. Click "Import" tab
3. Upload `database/database_schema.sql`
4. Click "Go"
5. ✅ Database created!

### Step 3: Configure Application
1. Copy `config.php.example` → `config.php`
2. Edit `config.php` and update:
   - DB_HOST, DB_NAME, DB_USER, DB_PASS
   - SITE_URL (your domain)
   - SMTP settings
   - Your crypto wallet addresses

### Step 4: Upload Files
**Option A: Using File Manager**
1. Login to cPanel → File Manager
2. Go to `public_html`
3. Drag & drop all files

**Option B: Using FileZilla (FTP)**
1. Download FileZilla: https://filezilla-project.org
2. Enter FTP details
3. Connect and upload files

### Step 5: Set Permissions
Via cPanel File Manager:
1. Right-click folder
2. Select "Change Permissions"
3. Set to: 755

### Step 6: Test
1. Visit: http://your-domain.com
2. Click Register
3. Fill form & check email for verification
4. Click verification link
5. Login ✓

### Step 7: Admin Setup
1. Visit: http://your-domain.com/admin-login.html
2. Login:
   - Username: `Gabbyy042`
   - Password: `Chukwu1$`
3. Change password immediately!

## ✅ Installation Checklist

- [ ] Database created & imported
- [ ] config.php created & configured
- [ ] All files uploaded to hosting
- [ ] File permissions set (755)
- [ ] SMTP email configured
- [ ] Homepage loads correctly
- [ ] Registration works
- [ ] Email verification works
- [ ] Admin login works
- [ ] Admin password changed

## 🐛 Troubleshooting

### "Database Connection Failed"
- Check DB credentials in config.php
- Verify database exists in phpMyAdmin

### "500 Internal Server Error"
- Check PHP version (must be 7.4+)
- Ask hosting for error logs

### "Email Not Sending"
- Verify SMTP settings
- Use Gmail App Password (not regular password)

---

**Installation complete! You're live! 🎉**