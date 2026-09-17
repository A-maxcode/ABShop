<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Checkout';

$cart = getCart();
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

$subtotal = cartSubtotal();
$shipping = cartShipping($subtotal);
$total = $subtotal + $shipping;
$error = null;
$success = false;
$orderNumber = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email     = trim($_POST['email'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $address   = trim($_POST['address'] ?? '');
    $city      = trim($_POST['city'] ?? '');
    $postal    = trim($_POST['postal_code'] ?? '');
    $country   = trim($_POST['country'] ?? '');

    if (!$email || !$firstName || !$lastName || !$address || !$city || !$postal || !$country) {
        $error = 'Please fill in all required fields.';
    } else {
        try {
            $db = getDB();
            $db->beginTransaction();

            $orderNumber = generateOrderNumber();

            $stmt = $db->prepare(
                "INSERT INTO orders (order_number, email, first_name, last_name, address, city, postal_code, country, subtotal, shipping, total, status)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')"
            );
            $stmt->execute([
                $orderNumber, $email, $firstName, $lastName,
                $address, $city, $postal, $country,
                $subtotal, $shipping, $total
            ]);
            $orderId = (int)$db->lastInsertId();

            $itemStmt = $db->prepare(
                "INSERT INTO order_items (order_id, product_id, product_name, size, color, quantity, unit_price)
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            foreach ($cart as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['id'],
                    $item['name'],
                    $item['size'],
                    $item['color'],
                    $item['qty'],
                    $item['price']
                ]);
            }

            $db->commit();
            clearCart();
            $success = true;
        } catch (Exception $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            $error = 'Could not place order. Please check your database connection.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="section">
  <div class="container">
    <div class="page-header" style="padding-top:0; text-align:left">
      <h1>Checkout</h1>
      <p class="text-muted">Complete your order</p>
    </div>

    <?php if ($success): ?>
      <div class="cart-empty">
        <h2>Thank you!</h2>
        <p>Your order <strong><?= e($orderNumber) ?></strong> has been placed successfully.</p>
        <p class="text-muted">A confirmation email would normally be sent to your address.</p>
        <a href="index.php" class="btn btn-primary">Back to Home</a>
      </div>
    <?php else: ?>

      <?php if ($error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
      <?php endif; ?>

      <div class="checkout-layout">
        <form class="checkout-form" method="post" action="checkout.php">
          <div class="form-section">
            <h3>Contact</h3>
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" required placeholder="you@example.com"
                     value="<?= e($_POST['email'] ?? '') ?>" />
            </div>
          </div>

          <div class="form-section">
            <h3>Shipping Address</h3>
            <div class="form-row">
              <div class="form-group">
                <label>First name</label>
                <input type="text" name="first_name" required value="<?= e($_POST['first_name'] ?? '') ?>" />
              </div>
              <div class="form-group">
                <label>Last name</label>
                <input type="text" name="last_name" required value="<?= e($_POST['last_name'] ?? '') ?>" />
              </div>
            </div>
            <div class="form-group">
              <label>Address</label>
              <input type="text" name="address" required placeholder="Street address"
                     value="<?= e($_POST['address'] ?? '') ?>" />
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>City</label>
                <input type="text" name="city" required value="<?= e($_POST['city'] ?? '') ?>" />
              </div>
              <div class="form-group">
                <label>Postal code</label>
                <input type="text" name="postal_code" required value="<?= e($_POST['postal_code'] ?? '') ?>" />
              </div>
            </div>
            <div class="form-group">
              <label>Country</label>
              <select name="country" required>
                <?php
                $countries = ['United States','Canada','United Kingdom','Australia','Germany','France','Other'];
                $selected = $_POST['country'] ?? 'United States';
                foreach ($countries as $c):
                ?>
                  <option value="<?= e($c) ?>" <?= $selected === $c ? 'selected' : '' ?>><?= e($c) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-section">
            <h3>Payment</h3>
            <p class="text-muted" style="margin-bottom:1rem; font-size:0.9rem">
              This is a demo store. No real payment is processed. Orders are saved to the database.
            </p>
            <div class="form-group">
              <label>Card number</label>
              <input type="text" placeholder="4242 4242 4242 4242" disabled />
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Expiry</label>
                <input type="text" placeholder="MM / YY" disabled />
              </div>
              <div class="form-group">
                <label>CVC</label>
                <input type="text" placeholder="123" disabled />
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-full">
            Place Order — <?= formatPrice($total) ?>
          </button>
        </form>

        <div class="cart-summary">
          <h3>Order Summary</h3>
          <?php foreach ($cart as $item): ?>
            <div class="summary-row" style="font-size:0.9rem">
              <span><?= e($item['name']) ?> × <?= (int)$item['qty'] ?></span>
              <span><?= formatPrice($item['price'] * $item['qty']) ?></span>
            </div>
          <?php endforeach; ?>
          <div class="summary-row" style="margin-top:1rem">
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
        </div>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
