<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Cart';

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $productId = (int)($_POST['product_id'] ?? 0);
        $size  = trim($_POST['size'] ?? '');
        $color = trim($_POST['color'] ?? '');
        $qty   = max(1, (int)($_POST['qty'] ?? 1));

        try {
            $product = getProduct($productId);
            if ($product) {
                if (!$size) {
                    $sizes = jsonField($product['sizes'], ['One Size']);
                    $size = $sizes[0] ?? 'One Size';
                }
                if (!$color) {
                    $colors = jsonField($product['colors'], []);
                    $color = $colors[0] ?? '';
                }
                addToCart($product, $size, $color, $qty);
                $_SESSION['flash'] = $product['name'] . ' added to cart';
            }
        } catch (Exception $e) {}

        // Redirect back or to cart
        $redirect = $_SERVER['HTTP_REFERER'] ?? 'cart.php';
        if (strpos($redirect, 'cart.php') !== false) {
            header('Location: cart.php');
        } else {
            header('Location: ' . $redirect);
        }
        exit;
    }

    if ($action === 'update') {
        $key = $_POST['key'] ?? '';
        $qty = (int)($_POST['qty'] ?? 0);
        updateCartQty($key, $qty);
        header('Location: cart.php');
        exit;
    }

    if ($action === 'remove') {
        $key = $_POST['key'] ?? '';
        removeFromCart($key);
        header('Location: cart.php');
        exit;
    }
}

$cart = getCart();
$subtotal = cartSubtotal();
$shipping = cartShipping($subtotal);
$total = $subtotal + $shipping;
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

require_once __DIR__ . '/includes/header.php';
?>

<main class="section">
  <div class="container">
    <div class="page-header" style="padding-top:0; text-align:left">
      <h1>Your Cart</h1>
    </div>

    <?php if ($flash): ?>
      <div class="alert alert-success"><?= e($flash) ?></div>
    <?php endif; ?>

    <?php if (empty($cart)): ?>
      <div class="cart-empty">
        <h2>Your cart is empty</h2>
        <p>Looks like you haven't added anything yet.</p>
        <a href="shop.php" class="btn btn-primary">Continue Shopping</a>
      </div>
    <?php else: ?>
      <div class="cart-layout">
        <div class="cart-items">
          <?php foreach ($cart as $key => $item): ?>
            <div class="cart-item">
              <div class="cart-item-image">
                <img src="<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>" />
              </div>
              <div class="cart-item-info">
                <h4><?= e($item['name']) ?></h4>
                <p class="cart-item-meta">
                  <?= e($item['size']) ?>
                  <?= $item['color'] ? ' · ' . e($item['color']) : '' ?>
                </p>
                <p class="cart-item-price"><?= formatPrice($item['price']) ?></p>
                <form action="cart.php" method="post" style="display:inline">
                  <input type="hidden" name="action" value="remove" />
                  <input type="hidden" name="key" value="<?= e($key) ?>" />
                  <button type="submit" class="cart-item-remove">Remove</button>
                </form>
              </div>
              <div>
                <form action="cart.php" method="post" class="qty-control">
                  <input type="hidden" name="action" value="update" />
                  <input type="hidden" name="key" value="<?= e($key) ?>" />
                  <button type="submit" name="qty" value="<?= $item['qty'] - 1 ?>" class="qty-btn">−</button>
                  <span class="qty-value"><?= (int)$item['qty'] ?></span>
                  <button type="submit" name="qty" value="<?= $item['qty'] + 1 ?>" class="qty-btn">+</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="cart-summary">
          <h3>Order Summary</h3>
          <div class="summary-row">
            <span>Subtotal</span>
            <span><?= formatPrice($subtotal) ?></span>
          </div>
          <div class="summary-row">
            <span>Shipping</span>
            <span><?= $shipping === 0.0 ? 'Free' : formatPrice($shipping) ?></span>
          </div>
          <div class="summary-row total">
            <span>Total</span>
            <span><?= formatPrice($total) ?></span>
          </div>
          <a href="checkout.php" class="btn btn-primary btn-full" style="margin-top:1.5rem">Proceed to Checkout</a>
          <a href="shop.php" class="btn btn-outline btn-full" style="margin-top:0.75rem">Continue Shopping</a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
