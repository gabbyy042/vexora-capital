# 🚀 000webhost - VEXORA CAPITAL DEPLOYMENT GUIDE

## 5 EASY STEPS - TOTAL TIME: 10 MINUTES

---

## ✅ STEP 1: Create 000webhost Account

**Go to:** https://www.000webhost.com

1. Click **"Sign Up"** (top right)
2. Enter:
   - Email
   - Password
3. Click **"Create Account"**
4. Choose a **domain name** (e.g., `vexoracapital.000webhostapp.com`)
5. Click **"Create Website"**

**Your site domain:** Note this down! You'll need it.

---

## ✅ STEP 2: Download Files from GitHub

1. Go to: https://github.com/gabbyy042/vexora-capital
2. Click **green "Code"** button
3. Click **"Download ZIP"**
4. Extract the ZIP file on your computer

You now have a folder: `vexora-capital-main`

---

## ✅ STEP 3: Upload Files to 000webhost

### **Login to 000webhost:**
1. Go to https://www.000webhost.com
2. Click **"Login"**
3. Enter email & password
4. Click your website

### **Open File Manager:**
1. Look for **"File Manager"** (usually in left sidebar)
2. You'll see a folder structure
3. Open the **`public_html`** folder (this is your main folder)

### **Upload the ZIP:**
1. Right-click inside `public_html`
2. Click **"Upload"**
3. Select the ZIP file you downloaded
4. Wait for upload to complete

### **Extract the ZIP:**
1. Right-click the ZIP file
2. Click **"Extract"**
3. Click **"Extract Here"**
4. The files will appear in `public_html`

---

## ✅ STEP 4: Create MySQL Database

### **In 000webhost Dashboard:**
1. Look for **"MySQL Databases"** or **"Database"**
2. Click **"Create New Database"**
3. Fill in:
   - **Database name:** `vexora_db`
   - **Username:** `admin`
   - **Password:** Create a strong password (save this!)
4. Click **"Create Database"**

### **Note down these values:**
```
Database name: vexora_db
Username: admin
Password: [Your password]
Host: [Usually localhost or shown in details]
```

---

## ✅ STEP 5: Import Database Schema

### **Open phpMyAdmin:**
1. In 000webhost, find **"phpMyAdmin"** link
2. Click it
3. Select your database (`vexora_db`)
4. Click **"Import"** tab (top menu)

### **Import the SQL File:**
1. Click **"Choose File"**
2. Navigate to your extracted folder
3. Find: `database/database_schema.sql`
4. Select it
5. Click **"Import"** button
6. Wait for completion (should say ✅ Success)

---

## ✅ STEP 6: Configure Your App

### **Edit backend/config.php**

1. In 000webhost File Manager
2. Navigate to: `public_html` → `backend` folder
3. Find and click **`config.php`**
4. Click **"Edit"** (or pencil icon)
5. Find these lines and update them:

```php
define('DB_HOST', 'localhost'); // Usually localhost for 000webhost
define('DB_NAME', 'vexora_db'); // Your database name
define('DB_USER', 'admin'); // Your database user
define('DB_PASS', 'YOUR_PASSWORD_HERE'); // Your database password

define('SITE_URL', 'http://yoursite.000webhostapp.com'); // Your 000webhost domain
```

6. Find SMTP settings:

```php
define('SMTP_USER', 'your-email@gmail.com'); // Your Gmail
define('SMTP_PASS', 'your-app-password'); // Gmail App Password (16 chars)
define('FROM_EMAIL', 'noreply@vexoracapital.com');
```

7. Click **"Save"** or **"Update"**

---

## 🎉 YOUR SITE IS LIVE!

Visit: `http://yoursite.000webhostapp.com`

You should see the VEXORA CAPITAL homepage!

---

## 📱 ACCESS YOUR PLATFORM

### **Homepage:**
```
http://yoursite.000webhostapp.com
```

### **Register:**
```
http://yoursite.000webhostapp.com/register.html
```

### **Admin Panel:**
```
http://yoursite.000webhostapp.com/admin-login.html
```

**Default Admin Login:**
- Username: `Gabbyy042`
- Password: `Chukwu1$`

---

## ⚠️ CRITICAL: Change Admin Password!

After first login:
1. Go to phpMyAdmin (in 000webhost)
2. Find `admin_users` table
3. Edit the admin row
4. Generate new password hash:
   - Use online tool: https://www.bcryptgenerator.com
   - Or use: password_hash('NewPassword123!', PASSWORD_DEFAULT)
5. Update the `password_hash` column

---

## 💰 YOUR CRYPTO WALLETS (Pre-configured)

All deposits go to:
- **BTC:** `bc1qz69wrtc6nkrqhxmta0h7jzv6wcut8pshaus3hw`
- **ETH:** `0xC8E8029Fe2Ea976394563F7a650f417b4dA5bAfE`
- **USDT (TRC-20):** `TGLf3LMQ5FCjk5HuJx7gmmPLVWWkAxgXLD`

---

## ✅ TEST CHECKLIST

After deployment:
- [ ] Homepage loads
- [ ] Register test account
- [ ] Check email for verification
- [ ] Verify email and login
- [ ] Dashboard displays
- [ ] Fill deposit form
- [ ] Submit deposit
- [ ] Login to admin panel
- [ ] Approve deposit
- [ ] Check user balance updated

---

## 🆘 TROUBLESHOOTING

### "Cannot connect to database"
- Check DB_HOST is correct (usually `localhost`)
- Check database name is `vexora_db`
- Check username is `admin`
- Check password is correct

### "Emails not sending"
- Verify Gmail has 2-Step Verification ON
- Check App Password is 16 characters
- Make sure SMTP_USER is correct Gmail

### "Admin login not working"
- Username: `Gabbyy042` (exact capitals)
- Password: `Chukwu1$` (exact)
- Clear browser cache

### Files not uploading
- Try uploading smaller files at a time
- Make sure you have space in 000webhost (free = 10GB)
- Use 000webhost File Manager, not FTP

---

## 🎊 YOU'RE LIVE!

Your VEXORA CAPITAL cryptocurrency investment platform is now LIVE and accepting investors!

**Questions? Let me know which step!** 🚀
