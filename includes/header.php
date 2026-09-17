<?php
if (!function_exists('cartCount')) {
    require_once __DIR__ . '/functions.php';
}
$cartCount = cartCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($pageTitle ?? 'ABShop') ?> — Modern Essentials</title>
  <meta name="description" content="ABShop — Thoughtfully designed apparel, bags, and home objects for everyday refinement." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="icon" type="image/png" href="assets/images/logo.png" />
</head>
<body>
  <header class="header">
    <div class="container header-inner">
      <a href="index.php" class="logo">
        <img src="assets/images/logo.png" alt="ABShop" class="logo-img" />
      </a>
      <nav class="nav">
        <a href="index.php" class="nav-link <?= isActive('index.php') ?>">Home</a>
        <a href="shop.php" class="nav-link <?= isActive('shop.php') ?>">Shop</a>
        <a href="about.php" class="nav-link <?= isActive('about.php') ?>">About</a>
        <a href="contact.php" class="nav-link <?= isActive('contact.php') ?>">Contact</a>
      </nav>
      <div class="header-actions">
        <a href="cart.php" class="icon-btn" aria-label="Cart">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M6 6h15l-1.5 9h-12z" />
            <circle cx="9" cy="20" r="1" />
            <circle cx="18" cy="20" r="1" />
            <path d="M6 6L5 2H2" />
          </svg>
          <?php if ($cartCount > 0): ?>
            <span class="cart-count"><?= $cartCount ?></span>
          <?php endif; ?>
        </a>
        <button class="mobile-toggle" aria-label="Menu">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </header>

  <div class="mobile-nav">
    <button class="mobile-close" aria-label="Close">×</button>
    <a href="index.php" class="nav-link">Home</a>
    <a href="shop.php" class="nav-link">Shop</a>
    <a href="about.php" class="nav-link">About</a>
    <a href="contact.php" class="nav-link">Contact</a>
    <a href="cart.php" class="nav-link">Cart (<?= $cartCount ?>)</a>
  </div>
