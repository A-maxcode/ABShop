<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Shop';

$category = $_GET['cat'] ?? null;
$sort = $_GET['sort'] ?? 'featured';

try {
    $products = getProducts($category, $sort);
    $categories = getCategories();
} catch (Exception $e) {
    $products = [];
    $categories = [];
    $dbError = true;
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <h1>Shop</h1>
    <p class="text-muted">Thoughtfully designed pieces for modern living</p>
  </div>
</div>

<section class="section-sm" style="padding-top:0">
  <div class="container">
    <?php if (!empty($dbError)): ?>
      <div class="alert alert-error">
        Database not connected. Please import <code>sql/schema.sql</code> and update <code>config/database.php</code>.
      </div>
    <?php else: ?>
      <form class="filters" method="get" action="shop.php">
        <div class="filter-group">
          <span class="filter-label">Category</span>
          <select class="filter-select" name="cat" onchange="this.form.submit()">
            <option value="">All</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= e($cat['slug']) ?>" <?= $category === $cat['slug'] ? 'selected' : '' ?>>
                <?= e($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="filter-group">
          <span class="filter-label">Sort</span>
          <select class="filter-select" name="sort" onchange="this.form.submit()">
            <option value="featured" <?= $sort === 'featured' ? 'selected' : '' ?>>Featured</option>
            <option value="price-asc" <?= $sort === 'price-asc' ? 'selected' : '' ?>>Price: Low to High</option>
            <option value="price-desc" <?= $sort === 'price-desc' ? 'selected' : '' ?>>Price: High to Low</option>
            <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name</option>
          </select>
        </div>
      </form>

      <?php if (empty($products)): ?>
        <p class="text-muted">No products found.</p>
      <?php else: ?>
        <div class="products-grid">
          <?php foreach ($products as $p): ?>
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
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
