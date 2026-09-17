<?php
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $product = $id ? getProduct($id) : null;
} catch (Exception $e) {
    $product = null;
    $dbError = true;
}

if (!$product) {
    $pageTitle = 'Product Not Found';
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="section"><div class="container"><div class="cart-empty">
            <h2>Product not found</h2>
            <p>This item may have been removed.</p>
            <a href="shop.php" class="btn btn-primary">Back to Shop</a>
          </div></div></div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = $product['name'];
$sizes  = jsonField($product['sizes'], ['One Size']);
$colors = jsonField($product['colors'], []);
$images = jsonField($product['images'], [$product['image']]);
if (empty($images)) $images = [$product['image']];

$related = [];
try {
    $related = getRelatedProducts((int)$product['id'], (int)$product['category_id'], 4);
} catch (Exception $e) {}

require_once __DIR__ . '/includes/header.php';
?>

<main class="section">
  <div class="container">
    <div class="product-detail">
      <div class="product-gallery">
        <div class="gallery-main">
          <img src="<?= e($images[0]) ?>" alt="<?= e($product['name']) ?>" id="main-img" />
        </div>
        <?php if (count($images) > 1): ?>
          <div class="gallery-thumbs">
            <?php foreach ($images as $i => $img): ?>
              <button type="button" class="gallery-thumb <?= $i === 0 ? 'active' : '' ?>" data-img="<?= e($img) ?>">
                <img src="<?= e($img) ?>" alt="" />
              </button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="product-detail-info">
        <p class="product-category"><?= e($product['category_name']) ?></p>
        <h1><?= e($product['name']) ?></h1>
        <p class="product-detail-price"><?= formatPrice((float)$product['price']) ?></p>
        <p class="product-detail-desc"><?= e($product['description']) ?></p>

        <form action="cart.php" method="post">
          <input type="hidden" name="action" value="add" />
          <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>" />

          <div class="option-group">
            <span class="option-label">Size</span>
            <div class="size-options">
              <?php foreach ($sizes as $i => $size): ?>
                <button type="button" class="size-btn <?= $i === 0 ? 'active' : '' ?>"
                        data-target="size-input" data-value="<?= e($size) ?>">
                  <?= e($size) ?>
                </button>
              <?php endforeach; ?>
            </div>
            <input type="hidden" name="size" id="size-input" value="<?= e($sizes[0]) ?>" />
          </div>

          <?php if (!empty($colors)): ?>
            <div class="option-group">
              <span class="option-label">Color</span>
              <div class="color-options">
                <?php foreach ($colors as $i => $color): ?>
                  <button type="button" class="color-btn <?= $i === 0 ? 'active' : '' ?>"
                          data-target="color-input" data-value="<?= e($color) ?>">
                    <?= e($color) ?>
                  </button>
                <?php endforeach; ?>
              </div>
              <input type="hidden" name="color" id="color-input" value="<?= e($colors[0]) ?>" />
            </div>
          <?php endif; ?>

          <div class="option-group">
            <span class="option-label">Quantity</span>
            <div class="qty-control">
              <button type="button" class="qty-btn" id="qty-minus">−</button>
              <span class="qty-value" id="qty-value">1</span>
              <button type="button" class="qty-btn" id="qty-plus">+</button>
            </div>
            <input type="hidden" name="qty" id="qty-input" value="1" />
          </div>

          <div class="add-to-cart-row">
            <button type="submit" class="btn btn-primary" style="flex:1">Add to Cart</button>
            <a href="cart.php" class="btn btn-outline">View Cart</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php if (!empty($related)): ?>
<section class="section" style="background: var(--color-surface);">
  <div class="container">
    <div class="products-header">
      <h2>You may also like</h2>
    </div>
    <div class="products-grid">
      <?php foreach ($related as $p): ?>
        <article class="product-card">
          <a href="product.php?id=<?= (int)$p['id'] ?>" class="product-image">
            <img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy" />
          </a>
          <div class="product-info">
            <p class="product-category"><?= e($p['category_name']) ?></p>
            <h3 class="product-name">
              <a href="product.php?id=<?= (int)$p['id'] ?>"><?= e($p['name']) ?></a>
            </h3>
            <p class="product-price"><?= formatPrice((float)$p['price']) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
