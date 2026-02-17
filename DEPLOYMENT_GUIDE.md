# 🚀 VEXORA CAPITAL - Complete Deployment Guide

## Option 1: Deploy to InfinityFree (Recommended - 100% FREE)

### Step 1: Create InfinityFree Account
1. Go to https://www.infinityfree.com
2. Click "Sign Up"
3. Choose a subdomain (e.g., `vexoracapital.infinityfreeapp.com`)
4. Complete registration

### Step 2: Access cPanel
1. Log in to InfinityFree Client Area
2. Click "Control Panel" (cPanel)

### Step 3: Create MySQL Database
1. In cPanel, find "MySQL Databases"
2. Click "Create Database"
3. Name it: `vexora_db`
4. Note down:
   - Database name (full): `if0_XXXXX_vexora_db`
   - Database user: `if0_XXXXX`
   - Database password: (set a strong password)
   - Database host: `sqlXXX.infinityfree.com`

### Step 4: Import Database Schema
1. In cPanel, go to "phpMyAdmin"
2. Select your database
3. Click "Import" tab
4. Upload `database/database_schema.sql`
5. Click "Go"

### Step 5: Upload Files
**Method A: File Manager (Easier)**
1. In cPanel, open "File Manager"
2. Navigate to `htdocs` folder
3. Upload all files EXCEPT `.git` folder
4. Make sure structure is:
   ```
   htdocs/
   ├── index.html
   ├── dashboard.html
   ├── backend/
   │   └── config.php
   └── ... (all other files)
   ```

**Method B: FTP (For large uploads)**
1. Get FTP credentials from cPanel (FTP Accounts)
2. Use FileZilla or any FTP client
3. Connect to your site
4. Upload all files to `htdocs`

### Step 6: Configure Backend
1. Edit `backend/config.php`
2. Update these lines:
   ```php
   define('DB_HOST', 'sqlXXX.infinityfree.com'); // Your MySQL host
   define('DB_NAME', 'if0_XXXXX_vexora_db'); // Your database name
   define('DB_USER', 'if0_XXXXX'); // Your database user
   define('DB_PASS', 'YOUR_DATABASE_PASSWORD'); // Your password

   define('SITE_URL', 'http://yoursubdomain.infinityfreeapp.com'); // Your site URL

   // Email settings (use Gmail)
   define('SMTP_USER', 'your-email@gmail.com');
   define('SMTP_PASS', 'your-app-password'); // Generate Gmail App Password
   ```

### Step 7: Set Up Gmail SMTP
1. Go to https://myaccount.google.com/security
2. Enable 2-Step Verification
3. Go to "App passwords"
4. Generate password for "Mail"
5. Copy the 16-character password
6. Paste in `backend/config.php` as `SMTP_PASS`

### Step 8: Test Your Site
1. Visit: `http://yoursubdomain.infinityfreeapp.com`
2. Test registration: Create account
3. Check email for verification link
4. Test login after email verification

### Step 9: Change Admin Password
1. Visit: `/admin-login.html`
2. Login with:
   - Username: `Gabbyy042`
   - Password: `Chukwu1$`
3. Go to database (phpMyAdmin)
4. Open `admin_users` table
5. Generate new password hash:
   ```php
   // Run this in PHP test page or online
   echo password_hash('YourNewPassword123!', PASSWORD_DEFAULT);
   ```
6. Update the `password_hash` column

### Step 10: Go Live!
✅ Your platform is now live!
- Users can register and deposit
- You approve deposits from admin panel
- Manage all investments manually

---

## Option 2: Deploy to 000webhost

### Similar Steps:
1. Sign up at https://www.000webhost.com
2. Create website
3. Create MySQL database
4. Import database schema
5. Upload files
6. Configure `backend/config.php`
7. Done!

---

## ⚠️ IMPORTANT: After Deployment

### Security Checklist:
- [ ] Change admin password immediately
- [ ] Update all database credentials
- [ ] Set up email properly (test verification emails)
- [ ] Test deposit flow completely
- [ ] Test withdrawal flow
- [ ] Check admin panel functions

### Test User Flow:
1. Register new account
2. Verify email
3. Login to dashboard
4. Make test deposit ($50 minimum)
5. Submit deposit with transaction hash
6. Login to admin panel
7. Approve the deposit
8. Check user balance updated
9. Test withdrawal request
10. Process withdrawal from admin

---

## 🔧 Troubleshooting

### "Database connection failed"
- Check database credentials in `config.php`
- Ensure database exists
- Verify user has permissions

### "Email not sending"
- Use Gmail App Password (not regular password)
- Enable "Less secure app access" OR use App Password (recommended)
- Check SMTP settings

### "Page not found" errors
- Check `.htaccess` file exists
- Verify all files uploaded correctly
- Check file permissions (755 for folders, 644 for files)

### Admin can't login
- Check `admin_users` table has default admin
- Password: `Chukwu1$`
- Username: `Gabbyy042`

---

## 📱 Mobile Testing
Test on mobile devices:
- Registration form
- Dashboard layout
- Deposit page (QR code display)
- Admin panel (responsive)

---

## 🎉 You're Done!
Your VEXORA CAPITAL platform is live and ready to accept investors!

**Remember:** This is a manual investment platform. You control everything from the admin panel.
