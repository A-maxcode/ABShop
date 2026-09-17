<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$pageTitle = 'Admin Dashboard';
$orders = [];
$error = null;

try {
    $orders = getOrders(50);
} catch (Exception $e) {
    $error = 'Could not load orders.';
}

$totalOrders = count($orders);
$totalRevenue = 0;
foreach ($orders as $o) {
    $totalRevenue += (float)$o['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin — ABShop</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="icon" type="image/png" href="../assets/images/logo.png" />
  <style>
    .admin-header {
      background: var(--color-dark);
      color: white;
      padding: 1rem 0;
    }
    .admin-header .container {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .admin-header .logo-img {
      height: 40px;
      filter: brightness(0) invert(1);
    }
    .admin-nav a {
      color: rgba(255,255,255,0.8);
      margin-left: 1.5rem;
      font-size: 0.9rem;
    }
    .admin-nav a:hover { color: white; }
    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.25rem;
      margin-bottom: 2rem;
    }
    .stat-card {
      background: var(--color-surface);
      border: 1px solid var(--color-border);
      border-radius: var(--radius-lg);
      padding: 1.5rem;
    }
    .stat-card h3 {
      font-family: var(--font-body);
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--color-text-muted);
      margin-bottom: 0.5rem;
    }
    .stat-card .value {
      font-size: 1.75rem;
      font-weight: 600;
    }
    .orders-table {
      width: 100%;
      border-collapse: collapse;
      background: var(--color-surface);
      border-radius: var(--radius-lg);
      overflow: hidden;
      border: 1px solid var(--color-border);
    }
    .orders-table th,
    .orders-table td {
      padding: 0.9rem 1rem;
      text-align: left;
      border-bottom: 1px solid var(--color-border);
      font-size: 0.9rem;
    }
    .orders-table th {
      background: var(--color-surface-alt);
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--color-text-muted);
    }
    .orders-table tr:last-child td { border-bottom: none; }
    .badge {
      display: inline-block;
      padding: 0.25rem 0.6rem;
      border-radius: 99px;
      font-size: 0.7rem;
      font-weight: 500;
      text-transform: uppercase;
    }
    .badge-pending { background: #fff3cd; color: #856404; }
    .badge-paid { background: #d4edda; color: #155724; }
    .badge-shipped { background: #cce5ff; color: #004085; }
  </style>
</head>
<body>
  <div class="admin-header">
    <div class="container">
      <a href="index.php">
        <img src="../assets/images/logo.png" alt="ABShop" class="logo-img" />
      </a>
      <div class="admin-nav">
        <span style="color:rgba(255,255,255,0.6); font-size:0.85rem">
          Hello, <?= e($_SESSION['admin_name'] ?? 'Admin') ?>
        </span>
        <a href="../index.php" target="_blank">View Store</a>
        <a href="../logout.php">Logout</a>
      </div>
    </div>
  </div>

  <main class="section">
    <div class="container">
      <h1 style="margin-bottom:1.5rem; font-size:1.75rem">Dashboard</h1>

      <?php if ($error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
      <?php endif; ?>

      <div class="stats">
        <div class="stat-card">
          <h3>Total Orders</h3>
          <div class="value"><?= $totalOrders ?></div>
        </div>
        <div class="stat-card">
          <h3>Revenue</h3>
          <div class="value"><?= formatPrice($totalRevenue) ?></div>
        </div>
      </div>

      <h2 style="font-size:1.25rem; margin-bottom:1rem">Recent Orders</h2>

      <?php if (empty($orders)): ?>
        <p class="text-muted">No orders yet.</p>
      <?php else: ?>
        <div style="overflow-x:auto">
          <table class="orders-table">
            <thead>
              <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $order): ?>
                <tr>
                  <td><strong><?= e($order['order_number']) ?></strong></td>
                  <td><?= e($order['first_name'] . ' ' . $order['last_name']) ?></td>
                  <td><?= e($order['email']) ?></td>
                  <td><?= formatPrice((float)$order['total']) ?></td>
                  <td>
                    <span class="badge badge-<?= e($order['status']) ?>">
                      <?= e($order['status']) ?>
                    </span>
                  </td>
                  <td><?= date('M j, Y H:i', strtotime($order['created_at'])) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
