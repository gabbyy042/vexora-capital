# 🚀 RAILWAY DEPLOYMENT - STEP BY STEP

## COMPLETE VISUAL GUIDE

---

## ✅ STEP 1: Go to Railway.app

**URL:** https://railway.app

Click the link above. You'll see the Railway homepage.

---

## ✅ STEP 2: Sign Up / Log In

**Look for:**
- Blue "Sign Up" button (top right)
- OR "Log In" button if you have account

**Click one of them**

**Sign in with GitHub** (easiest option)

---

## ✅ STEP 3: Click "New Project"

After logging in, you'll see dashboard.

**Look for:** Big blue button that says "New Project" or "+ New Project"

**Click it**

---

## ✅ STEP 4: Select "Deploy from GitHub"

A menu will appear with options:
- Create New
- Deploy from GitHub  ← **CLICK THIS**
- Deploy from Git URL
- Database

**Click "Deploy from GitHub"**

---

## ✅ STEP 5: Connect Your GitHub

**It will ask:** "Authorize Railway to access GitHub?"

**Click:** "Authorize" (green button)

**Then:** Select "gabbyy042/vexora-capital" repository

---

## ✅ STEP 6: Select Repository

You'll see a list of your repositories.

**Look for:** `vexora-capital`

**Click it**

---

## ✅ STEP 7: Railway Will Show This Menu

```
Service
Environment Variables
Domains
Deploy
```

---

## ✅ STEP 8: Add Environment Variables

**Click:** "Environment Variables" (or the gear icon)

**You'll see a form like this:**

```
KEY              VALUE
[input]          [input]
[add more]
```

---

## ✅ STEP 9: Add These 7 Variables

**Copy and paste each one:**

### Variable 1:
```
KEY:   DB_NAME
VALUE: vexora_db
```
Click "Add"

### Variable 2:
```
KEY:   DB_USER
VALUE: admin
```
Click "Add"

### Variable 3:
```
KEY:   DB_PASS
VALUE: (create any secure password - example: SecurePass123!)
```
Click "Add"

### Variable 4:
```
KEY:   SITE_URL
VALUE: https://vexora-capital.railway.app
```
Click "Add"

### Variable 5:
```
KEY:   SMTP_USER
VALUE: (your Gmail address - example: yourname@gmail.com)
```
Click "Add"

### Variable 6:
```
KEY:   SMTP_PASS
VALUE: (your Gmail App Password - 16 chars like: xxxx xxxx xxxx xxxx)
```
Click "Add"

### Variable 7:
```
KEY:   FROM_EMAIL
VALUE: noreply@vexoracapital.com
```
Click "Add"

---

## ✅ STEP 10: Deploy

**Click the big "Deploy" button** (usually blue, bottom right)

---

## ⏳ WAIT 3-5 MINUTES

Railway will:
- Pull code from GitHub
- Create MySQL database
- Import database tables
- Configure everything
- Deploy your app

You'll see a progress bar or logs updating.

---

## 🎉 DEPLOYMENT COMPLETE!

**You'll see:**
```
✅ Deployment successful
Domain: https://vexora-capital-xxxxx.railway.app
```

---

## 📱 NOW ACCESS YOUR SITE

### **Homepage:**
Visit: `https://vexora-capital-xxxxx.railway.app`

### **Register:**
`https://vexora-capital-xxxxx.railway.app/register.html`

### **Admin Panel:**
`https://vexora-capital-xxxxx.railway.app/admin-login.html`

**Admin Login:**
```
Username: Gabbyy042
Password: Chukwu1$
```

---

## ⚠️ IMPORTANT: Change Admin Password!

1. Login to admin panel
2. Go to database (phpMyAdmin)
3. Change "Chukwu1$" to your own password
4. Done!

---

## 💰 YOUR CRYPTO WALLETS

Deposits go to:
- **BTC:** bc1qz69wrtc6nkrqhxmta0h7jzv6wcut8pshaus3hw
- **ETH:** 0xC8E8029Fe2Ea976394563F7a650f417b4dA5bAfE
- **USDT:** TGLf3LMQ5FCjk5HuJx7gmmPLVWWkAxgXLD

---

## ✅ TEST YOUR PLATFORM

After deployment:

1. [ ] Visit homepage - loads?
2. [ ] Register test account
3. [ ] Check email for verification
4. [ ] Click verification link
5. [ ] Login to dashboard
6. [ ] Fill deposit form
7. [ ] Submit deposit
8. [ ] Login to admin panel
9. [ ] Approve deposit
10. [ ] Check user balance updated

---

## 🆘 TROUBLESHOOTING

### Database connection error?
- Check: `DB_HOST`, `DB_USER`, `DB_PASS` are correct
- Make sure all 7 variables are added

### Emails not sending?
- Verify Gmail has 2-Step Verification ON
- Check App Password is correct (16 characters)
- App Password must be from Google, not regular password

### Can't login to admin?
- Username: `Gabbyy042` (exact spelling with capitals)
- Password: `Chukwu1$` (exact)
- Clear browser cache and try again

### Site shows 404 error?
- Make sure SITE_URL matches your Railway domain exactly
- Wait 5 more minutes for deployment to fully complete

---

## 🎊 YOU'RE LIVE!

Your VEXORA CAPITAL platform is now accepting real investors!

**Next steps:**
1. Change admin password
2. Test deposit/withdrawal flow
3. Start accepting deposits
4. Manually approve and process transactions

---

## 📞 SUPPORT

Got stuck? Let me know which step!

Good luck! 🚀
