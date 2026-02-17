<?php
/**
 * VEXORA CAPITAL - One-Click Installer
 * Upload this file to your InfinityFree htdocs folder and visit it in browser
 */

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Step tracker
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VEXORA CAPITAL Installer</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', Arial, sans-serif; background: linear-gradient(135deg, #0B1437 0%, #1a1a2e 100%); 
               min-height: 100vh; padding: 20px; }
        .container { max-width: 800px; margin: 40px auto; background: #1a1a2e; padding: 40px; 
                     border-radius: 12px; border: 2px solid #FFD700; box-shadow: 0 10px 40px rgba(0,0,0,0.5); }
        h1 { color: #FFD700; text-align: center; margin-bottom: 10px; font-size: 2.5rem; }
        .subtitle { text-align: center; color: #e0e0e0; margin-bottom: 30px; }
        .step { background: #0B1437; padding: 15px; border-radius: 8px; margin-bottom: 30px; }
        .step h2 { color: #FFD700; font-size: 1.3rem; margin-bottom: 15px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; }
        input, textarea { width: 100%; padding: 12px; border: 2px solid #FFD700; border-radius: 6px; 
                          background: #0f0f23; color: #e0e0e0; font-size: 1rem; }
        input:focus { outline: none; border-color: #FFC700; }
        .btn { padding: 15px 40px; background: #FFD700; color: #0B1437; border: none; border-radius: 6px; 
               font-size: 1.1rem; font-weight: 700; cursor: pointer; }
        .btn:hover { background: #FFC700; }
        .success { background: #10B981; color: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .error { background: #EF4444; color: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .warning { background: #F59E0B; color: #0B1437; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .info { background: #3B82F6; color: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .code { background: #0f0f23; padding: 10px; border-radius: 6px; font-family: monospace; color: #FFD700; 
                margin: 10px 0; }
        ul { margin-left: 20px; color: #e0e0e0; }
        li { margin: 8px 0; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>💎 VEXORA CAPITAL</h1>
        <p class="subtitle">One-Click Installer</p>

<?php
if ($step == 1) {
    // Step 1: Welcome & Requirements Check
    ?>
        <div class="step">
            <h2>Step 1: Server Requirements Check</h2>
            <?php
            $requirements = [
                'PHP Version >= 7.4' => version_compare(PHP_VERSION, '7.4.0', '>='),
                'PDO Extension' => extension_loaded('pdo'),
                'PDO MySQL Extension' => extension_loaded('pdo_mysql'),
                'Session Support' => function_exists('session_start'),
                'JSON Extension' => function_exists('json_encode')
            ];

            $allPassed = true;
            echo '<ul>';
            foreach ($requirements as $req => $pass) {
                $status = $pass ? '✅' : '❌';
                $color = $pass ? '#10B981' : '#EF4444';
                echo "<li style='color: $color;'>$status $req</li>";
                if (!$pass) $allPassed = false;
            }
            echo '</ul>';

            if ($allPassed) {
                echo '<div class="success">✅ All requirements met! Ready to proceed.</div>';
                echo '<div class="center"><a href="?step=2"><button class="btn">Continue to Database Setup</button></a></div>';
            } else {
                echo '<div class="error">❌ Some requirements not met. Contact your hosting provider.</div>';
            }
            ?>
        </div>
    <?php
} elseif ($step == 2) {
    // Step 2: Database Configuration
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process database setup
        $dbHost = $_POST['db_host'];
        $dbName = $_POST['db_name'];
        $dbUser = $_POST['db_user'];
        $dbPass = $_POST['db_pass'];
        $siteUrl = $_POST['site_url'];
        $smtpUser = $_POST['smtp_user'];
        $smtpPass = $_POST['smtp_pass'];

        // Test connection
        try {
            $pdo = new PDO("mysql:host=$dbHost;charset=utf8mb4", $dbUser, $dbPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Create database if not exists
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `$dbName`");

            // Create tables
            $sql = file_get_contents('database/database_schema.sql');
            if ($sql) {
                // Remove CREATE DATABASE lines
                $sql = preg_replace('/CREATE DATABASE.*?;/i', '', $sql);
                $sql = preg_replace('/USE .*?;/i', '', $sql);

                // Execute statements
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                foreach ($statements as $statement) {
                    if (!empty($statement)) {
                        $pdo->exec($statement);
                    }
                }
            }

            // Create config.php
            $configContent = "<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('UTC');

define('DB_HOST', '$dbHost');
define('DB_NAME', '$dbName');
define('DB_USER', '$dbUser');
define('DB_PASS', '$dbPass');

try {
    \$pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException \$e) {
    die("Database connection failed: " . \$e->getMessage());
}

define('SITE_URL', '$siteUrl');
define('SITE_NAME', 'VEXORA CAPITAL');
define('SUPPORT_EMAIL', 'support@vexoracapital.com');
define('ADMIN_EMAIL', 'admin@vexoracapital.com');

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', '$smtpUser');
define('SMTP_PASS', '$smtpPass');
define('FROM_EMAIL', 'noreply@vexoracapital.com');
define('FROM_NAME', 'VEXORA CAPITAL');

define('BTC_WALLET', 'bc1qz69wrtc6nkrqhxmta0h7jzv6wcut8pshaus3hw');
define('ETH_WALLET', '0xC8E8029Fe2Ea976394563F7a650f417b4dA5bAfE');
define('USDT_TRC20_WALLET', 'TGLf3LMQ5FCjk5HuJx7gmmPLVWWkAxgXLD');

define('INVESTMENT_PLANS', json_encode([
    'basic' => ['name' => 'Basic', 'min' => 50, 'max' => 499, 'profit' => 20, 'duration' => 24, 'referral_bonus' => 5],
    'bronze' => ['name' => 'Bronze', 'min' => 500, 'max' => 999, 'profit' => 35, 'duration' => 48, 'referral_bonus' => 10],
    'standard' => ['name' => 'Standard', 'min' => 1000, 'max' => 1999, 'profit' => 80, 'duration' => 92, 'referral_bonus' => 10],
    'gold' => ['name' => 'Gold', 'min' => 2000, 'max' => 2499, 'profit' => 100, 'duration' => 48, 'referral_bonus' => 10],
    'company_shares' => ['name' => 'Company Shares', 'min' => 6000, 'max' => 1000000, 'profit' => 120, 'duration' => 72, 'referral_bonus' => 15],
    'real_estate' => ['name' => 'Real Estate', 'min' => 10000, 'max' => PHP_INT_MAX, 'profit' => 150, 'duration' => 48, 'referral_bonus' => 15]
]));

define('MIN_DEPOSIT', 50);
define('MIN_WITHDRAWAL', 50);
define('WITHDRAWAL_FEE_PERCENT', 2);

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0);
ini_set('session.cookie_lifetime', 1800);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset(\$_SESSION['csrf_token'])) {
    \$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

function getSetting(\$key, \$default = null) {
    global \$pdo;
    \$stmt = \$pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    \$stmt->execute([\$key]);
    \$result = \$stmt->fetch();
    return \$result ? \$result['setting_value'] : \$default;
}

function setSetting(\$key, \$value) {
    global \$pdo;
    \$stmt = \$pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
    return \$stmt->execute([\$key, \$value, \$value]);
}
?>";

            file_put_contents('backend/config.php', $configContent);

            echo '<div class="success">
                <h3 style="margin-bottom: 15px;">✅ Installation Successful!</h3>
                <p><strong>Database:</strong> Connected and tables created</p>
                <p><strong>Configuration:</strong> config.php created</p>
                <p><strong>Admin Account:</strong> Ready to use</p>
            </div>';

            echo '<div class="info">
                <h3 style="margin-bottom: 15px;">🎉 VEXORA CAPITAL is Ready!</h3>
                <p><strong>Your Platform URL:</strong> <a href="' . $siteUrl . '" style="color: #FFD700;">' . $siteUrl . '</a></p>
                <br>
                <p><strong>Admin Login:</strong></p>
                <p>URL: <a href="' . $siteUrl . '/admin-login.html" style="color: #FFD700;">' . $siteUrl . '/admin-login.html</a></p>
                <p>Username: <span style="color: #FFD700;">Gabbyy042</span></p>
                <p>Password: <span style="color: #FFD700;">Chukwu1$</span></p>
                <br>
                <p style="color: #FFC700;"><strong>⚠️ IMPORTANT: Change admin password immediately!</strong></p>
            </div>';

            echo '<div class="warning">
                <h3 style="margin-bottom: 10px;">🔒 Security Steps:</h3>
                <ol>
                    <li>Delete this install.php file NOW</li>
                    <li>Change admin password in database</li>
                    <li>Test user registration</li>
                    <li>Test deposit/withdrawal flow</li>
                </ol>
            </div>';

            echo '<div class="center">
                <a href="index.html"><button class="btn">Go to Homepage</button></a>
                <a href="admin-login.html"><button class="btn" style="background: #EF4444; margin-left: 10px;">Admin Login</button></a>
            </div>';

        } catch (Exception $e) {
            echo '<div class="error">❌ Installation Failed: ' . htmlspecialchars($e->getMessage()) . '</div>';
            echo '<div class="center"><a href="?step=2"><button class="btn">Try Again</button></a></div>';
        }

    } else {
        // Show form
        ?>
        <div class="step">
            <h2>Step 2: Database Configuration</h2>
            <div class="info">
                <strong>📝 Where to find these values:</strong>
                <ul style="margin-top: 10px;">
                    <li>Login to InfinityFree cPanel</li>
                    <li>Go to "MySQL Databases"</li>
                    <li>Create a new database (if not created)</li>
                    <li>Copy: Database name, Username, Host, Password</li>
                </ul>
            </div>

            <form method="POST">
                <div class="form-group">
                    <label>Database Host</label>
                    <input type="text" name="db_host" required 
                           placeholder="e.g., sql201.infinityfree.com"
                           value="sql201.infinityfree.com">
                </div>

                <div class="form-group">
                    <label>Database Name</label>
                    <input type="text" name="db_name" required 
                           placeholder="e.g., if0_40943082_vexora_db">
                </div>

                <div class="form-group">
                    <label>Database Username</label>
                    <input type="text" name="db_user" required 
                           placeholder="e.g., if0_40943082">
                </div>

                <div class="form-group">
                    <label>Database Password</label>
                    <input type="password" name="db_pass" required>
                </div>

                <div class="form-group">
                    <label>Site URL (Your Website Address)</label>
                    <input type="text" name="site_url" required 
                           placeholder="e.g., http://vexoracapital.infinityfreeapp.com"
                           value="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
                </div>

                <div class="form-group">
                    <label>Gmail Email (For Sending Emails)</label>
                    <input type="email" name="smtp_user" required 
                           placeholder="your-email@gmail.com">
                </div>

                <div class="form-group">
                    <label>Gmail App Password (16 characters)</label>
                    <input type="text" name="smtp_pass" required 
                           placeholder="xxxx xxxx xxxx xxxx">
                    <small style="color: #F59E0B;">Get this from: Google Account → Security → 2-Step Verification → App passwords</small>
                </div>

                <div class="center">
                    <button type="submit" class="btn">Install VEXORA CAPITAL</button>
                </div>
            </form>
        </div>
        <?php
    }
}
?>
    </div>
</body>
</html>