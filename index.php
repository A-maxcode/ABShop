<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Home';

try {
    $featured = getFeaturedProducts(4);
} catch (Exception $e) {
    $featured = [];
    $dbError = true;
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="hero-bg"></div>
  <div class="container">
    <div class="hero-content">
      <span class="hero-label">New Season</span>
      <h1>Quiet luxury for everyday life</h1>
      <p class="lead">Curated essentials in natural materials — designed to last, made to elevate the ordinary.</p>
      <div class="hero-actions">
        <a href="shop.php" class="btn btn-primary">Shop Collection</a>
        <a href="about.php" class="btn btn-outline">Our Story</a>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="products-header">
      <div>
        <h2>Shop by Category</h2>
        <p class="text-muted" style="margin-top:0.35rem">Explore our focused collections</p>
      </div>
    </div>
    <div class="categories-grid">
      <a href="shop.php?cat=apparel" class="category-card">
        <img src="https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=600&h=800&fit=crop" alt="Apparel" />
        <div class="category-overlay">
          <h3>Apparel</h3>
          <span>View collection</span>
        </div>
      </a>
      <a href="shop.php?cat=bags" class="category-card">
        <img src="https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=600&h=800&fit=crop" alt="Bags" />
        <div class="category-overlay">
          <h3>Bags</h3>
          <span>View collection</span>
        </div>
      </a>
      <a href="shop.php?cat=home" class="category-card">
        <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=600&h=800&fit=crop" alt="Home" />
        <div class="category-overlay">
          <h3>Home</h3>
          <span>View collection</span>
        </div>
      </a>
    </div>
  </div>
</section>

<section class="section" style="background: var(--color-surface);">
  <div class="container">
    <div class="products-header">
      <div>
        <h2>Featured Pieces</h2>
        <p class="text-muted" style="margin-top:0.35rem">Selected for their quality and timeless appeal</p>
      </div>
      <a href="shop.php" class="btn btn-outline btn-sm">View All</a>
    </div>

    <?php if (!empty($dbError)): ?>
      <div class="alert alert-error">
        Database not connected. Please import <code>sql/schema.sql</code> and update <code>config/database.php</code>.
      </div>
    <?php elseif (empty($featured)): ?>
      <p class="text-muted">No featured products yet.</p>
    <?php else: ?>
      <div class="products-grid">
        <?php foreach ($featured as $p): ?>
          <article class="product-card">
            <a href="product.php?id=<?= (int)$p['id'] ?>" class="product-image">
              <img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy" />
              <?php if ($p['is_new']): ?>
                <span class="product-badge new">New</span>
              <?php elseif ($p['is_featured']): ?>
                <span class="product-badge">Featured</span>
              <?php endif; ?>
            </a>
            <div class="product-info">
              <p class="product-category"><?= e($p['category_name']) ?></p>
              <h3 class="product-name">
                <a href="product.php?id=<?= (int)$p['id'] ?>"><?= e($p['name']) ?></a>
              </h3>
              <p class="product-price"><?= formatPrice((float)$p['price']) ?></p>
              <div class="product-actions">
                <form action="cart.php" method="post">
                  <input type="hidden" name="action" value="add" />
                  <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>" />
                  <button type="submit" class="btn btn-primary btn-sm btn-full">Add to Cart</button>
                </form>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="section-sm">
  <div class="container">
    <div class="features">
      <div class="feature-card">
        <div class="feature-icon">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h4>Quality Guaranteed</h4>
        <p>Premium materials and careful construction on every piece.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        </div>
        <h4>Free Shipping</h4>
        <p>Complimentary shipping on orders over $150 worldwide.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
        </div>
        <h4>Easy Returns</h4>
        <p>30-day returns. No questions, no hassle.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </div>
        <h4>Thoughtful Design</h4>
        <p>Pieces made to be lived in — not just looked at.</p>
      </div>
    </div>
  </div>
</section>

<section class="newsletter">
  <div class="container">
    <h2>Stay in the loop</h2>
    <p>New arrivals, exclusive offers, and stories from the studio — delivered sparingly.</p>
    <form class="newsletter-form" onsubmit="event.preventDefault(); showToast('Thanks for subscribing'); this.reset();">
      <input type="email" placeholder="Your email address" required />
      <button type="submit" class="btn btn-accent">Subscribe</button>
    </form>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
