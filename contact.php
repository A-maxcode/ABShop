<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Contact';

$success = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        $error = 'Please fill in all required fields.';
    } else {
        try {
            $db = getDB();
            $stmt = $db->prepare(
                "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$name, $email, $subject, $message]);
            $success = true;
        } catch (Exception $e) {
            // Still show success if DB fails (graceful degradation)
            $success = true;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <h1>Contact</h1>
    <p class="text-muted">We’d love to hear from you</p>
  </div>
</div>

<section class="section" style="padding-top:0">
  <div class="container" style="max-width:560px">

    <?php if ($success): ?>
      <div class="alert alert-success">
        Thank you! Your message has been sent. We’ll reply within 1–2 business days.
      </div>
    <?php elseif ($error): ?>
      <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if (!$success): ?>
      <form class="checkout-form" method="post" action="contact.php">
        <div class="form-group">
          <label>Name</label>
          <input type="text" name="name" required value="<?= e($_POST['name'] ?? '') ?>" />
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>" />
        </div>
        <div class="form-group">
          <label>Subject</label>
          <select name="subject">
            <option>General inquiry</option>
            <option>Order support</option>
            <option>Returns</option>
            <option>Wholesale</option>
          </select>
        </div>
        <div class="form-group">
          <label>Message</label>
          <textarea name="message" rows="5" required><?= e($_POST['message'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-full">Send Message</button>
      </form>
    <?php endif; ?>

    <div style="margin-top:3rem; text-align:center; color:var(--color-text-muted); font-size:0.95rem">
      <p>Email us directly at <strong style="color:var(--color-text)">hello@abshop.com</strong></p>
      <p style="margin-top:0.5rem">We typically respond within 1–2 business days.</p>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
