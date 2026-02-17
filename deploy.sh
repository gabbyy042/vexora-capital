#!/bin/bash

# VEXORA CAPITAL - ONE-COMMAND DEPLOYMENT
# Just run: bash deploy.sh

set -e

echo "🚀 VEXORA CAPITAL - Auto Deployment"
echo "===================================="
echo ""

# Check if git is installed
if ! command -v git &> /dev/null; then
    echo "❌ Git is required. Please install Git first."
    exit 1
fi

# Step 1: Clone repository
echo "📥 Step 1: Downloading VEXORA CAPITAL..."
git clone https://github.com/gabbyy042/vexora-capital.git vexora-capital-live
cd vexora-capital-live

echo "✅ Downloaded successfully!"
echo ""

# Step 2: Ask for Gmail
echo "📧 Step 2: Enter your Gmail address"
read -p "Your Gmail (example: yourname@gmail.com): " GMAIL_EMAIL

# Step 3: Ask for Gmail App Password
echo ""
echo "🔐 Step 3: Enter your Gmail App Password"
echo "    Get it from: https://myaccount.google.com/security → App passwords"
echo "    It's 16 characters like: xxxx xxxx xxxx xxxx"
read -s -p "Gmail App Password (will not be displayed): " GMAIL_PASSWORD
echo ""

# Generate secure database password
DB_PASSWORD=$(openssl rand -base64 12)

# Generate site URL (using GitHub Pages as default, or ask)
echo ""
echo "🌐 Your site will be deployed to Railway (free!)"
SITE_URL="https://vexora-capital.railway.app"

echo ""
echo "✨ Creating deployment configuration..."

# Create .env file for deployment
cat > .env.deployment << EOF
SITE_URL=$SITE_URL
DB_NAME=vexora_db
DB_USER=admin
DB_PASS=$DB_PASSWORD
SMTP_USER=$GMAIL_EMAIL
SMTP_PASS=$GMAIL_PASSWORD
FROM_EMAIL=noreply@vexoracapital.com
EOF

echo "✅ Configuration created!"
echo ""
echo "=================================================="
echo "🎉 DEPLOYMENT READY!"
echo "=================================================="
echo ""
echo "Your configuration:"
echo "  Gmail: $GMAIL_EMAIL"
echo "  Site URL: $SITE_URL"
echo "  Database: vexora_db"
echo ""
echo "📝 Next Steps:"
echo ""
echo "1. Go to: https://railway.app"
echo "2. Click 'New Project'"
echo "3. Select 'Deploy from GitHub'"
echo "4. Connect your GitHub account"
echo "5. Select 'gabbyy042/vexora-capital'"
echo "6. Add these environment variables:"
echo ""
echo "   SITE_URL=$SITE_URL"
echo "   DB_NAME=vexora_db"
echo "   DB_USER=admin"
echo "   DB_PASS=$DB_PASSWORD"
echo "   SMTP_USER=$GMAIL_EMAIL"
echo "   SMTP_PASS=$GMAIL_PASSWORD"
echo "   FROM_EMAIL=noreply@vexoracapital.com"
echo ""
echo "7. Click Deploy!"
echo ""
echo "⏱️  Wait 3-5 minutes for deployment..."
echo ""
echo "Your admin login:"
echo "  Username: Gabbyy042"
echo "  Password: Chukwu1$"
echo ""
echo "⚠️  CHANGE PASSWORD IMMEDIATELY AFTER LOGIN!"
echo ""
echo "=================================================="
echo "✅ Your VEXORA CAPITAL is LIVE! 🎉"
echo "=================================================="
