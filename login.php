<?php
require_once __DIR__ . '/includes/functions.php';

// Already logged in?
if (isLoggedIn()) {
    header('Location: admin/index.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $error = 'Please enter username and password.';
    } else {
        try {
            if (loginAdmin($username, $password)) {
                header('Location: admin/index.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (Exception $e) {
            $error = 'Database error. Make sure you imported schema.sql and set the correct password in config/database.php';
        }
    }
}

$pageTitle = 'Admin Login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login — ABShop</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="icon" type="image/png" href="assets/images/logo.png" />
  <style>
    .login-page {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      background: var(--color-bg);
    }
    .login-box {
      width: 100%;
      max-width: 400px;
      background: var(--color-surface);
      border-radius: var(--radius-lg);
      border: 1px solid var(--color-border);
      padding: 2.5rem 2rem;
      box-shadow: var(--shadow-md);
    }
    .login-logo {
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .login-logo img {
      height: 64px;
      margin: 0 auto 0.75rem;
    }
    .login-box h1 {
      font-size: 1.5rem;
      text-align: center;
      margin-bottom: 0.25rem;
    }
    .login-box .subtitle {
      text-align: center;
      color: var(--color-text-muted);
      font-size: 0.9rem;
      margin-bottom: 1.75rem;
    }
  </style>
</head>
<body>
  <div class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <img src="assets/images/logo.png" alt="ABShop" />
        <h1>Admin Login</h1>
        <p class="subtitle">Sign in to manage ABShop</p>
      </div>

      <?php if ($error): ?>
        <div class="alert alert-error" style="margin-bottom:1.25rem"><?= e($error) ?></div>
      <?php endif; ?>

      <form method="post" action="login.php">
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" required autofocus
                 value="<?= e($_POST['username'] ?? '') ?>" placeholder="admin" />
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" required placeholder="••••••••" />
        </div>
        <button type="submit" class="btn btn-primary btn-full" style="margin-top:0.5rem">
          Sign In
        </button>
      </form>

      <p style="text-align:center; margin-top:1.5rem; font-size:0.85rem; color:var(--color-text-muted)">
        <a href="index.php">← Back to store</a>
      </p>
    </div>
  </div>
</body>
</html>
