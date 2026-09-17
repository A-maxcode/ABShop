<?php
/**
 * ABShop Helper Functions
 */

require_once __DIR__ . '/../config/database.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Format price
 */
function formatPrice(float $price): string
{
    return '$' . number_format($price, 0);
}

/**
 * Get all products (with optional filters)
 */
function getProducts(?string $category = null, string $sort = 'featured'): array
{
    $db = getDB();
    $sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE 1=1";
    $params = [];

    if ($category) {
        $sql .= " AND c.slug = ?";
        $params[] = $category;
    }

    switch ($sort) {
        case 'price-asc':
            $sql .= " ORDER BY p.price ASC";
            break;
        case 'price-desc':
            $sql .= " ORDER BY p.price DESC";
            break;
        case 'name':
            $sql .= " ORDER BY p.name ASC";
            break;
        default:
            $sql .= " ORDER BY p.is_featured DESC, p.is_new DESC, p.id DESC";
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Get featured products
 */
function getFeaturedProducts(int $limit = 4): array
{
    $db = getDB();
    $stmt = $db->prepare(
        "SELECT p.*, c.name AS category_name
         FROM products p
         JOIN categories c ON p.category_id = c.id
         WHERE p.is_featured = 1
         ORDER BY p.id DESC
         LIMIT ?"
    );
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

/**
 * Get single product by ID or slug
 */
function getProduct(int|string $idOrSlug): ?array
{
    $db = getDB();
    if (is_numeric($idOrSlug)) {
        $stmt = $db->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug
             FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.id = ?"
        );
        $stmt->execute([(int)$idOrSlug]);
    } else {
        $stmt = $db->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug
             FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.slug = ?"
        );
        $stmt->execute([$idOrSlug]);
    }
    $product = $stmt->fetch();
    return $product ?: null;
}

/**
 * Get related products
 */
function getRelatedProducts(int $productId, int $categoryId, int $limit = 4): array
{
    $db = getDB();
    $stmt = $db->prepare(
        "SELECT p.*, c.name AS category_name
         FROM products p
         JOIN categories c ON p.category_id = c.id
         WHERE p.category_id = ? AND p.id != ?
         ORDER BY RAND()
         LIMIT ?"
    );
    $stmt->execute([$categoryId, $productId, $limit]);
    return $stmt->fetchAll();
}

/**
 * Get all categories
 */
function getCategories(): array
{
    $db = getDB();
    return $db->query("SELECT * FROM categories ORDER BY name")->fetchAll();
}

/**
 * Decode JSON field safely
 */
function jsonField(?string $json, array $default = []): array
{
    if (!$json) return $default;
    $decoded = json_decode($json, true);
    return is_array($decoded) ? $decoded : $default;
}

/* ========== CART (Session based) ========== */

function getCart(): array
{
    return $_SESSION['cart'] ?? [];
}

function saveCart(array $cart): void
{
    $_SESSION['cart'] = $cart;
}

function addToCart(array $product, string $size = '', string $color = '', int $qty = 1): void
{
    $cart = getCart();
    $key = $product['id'] . '|' . $size . '|' . $color;

    if (isset($cart[$key])) {
        $cart[$key]['qty'] += $qty;
    } else {
        $cart[$key] = [
            'id'    => $product['id'],
            'name'  => $product['name'],
            'price' => (float)$product['price'],
            'image' => $product['image'],
            'size'  => $size,
            'color' => $color,
            'qty'   => $qty,
        ];
    }
    saveCart($cart);
}

function updateCartQty(string $key, int $qty): void
{
    $cart = getCart();
    if ($qty <= 0) {
        unset($cart[$key]);
    } else {
        if (isset($cart[$key])) {
            $cart[$key]['qty'] = $qty;
        }
    }
    saveCart($cart);
}

function removeFromCart(string $key): void
{
    $cart = getCart();
    unset($cart[$key]);
    saveCart($cart);
}

function clearCart(): void
{
    unset($_SESSION['cart']);
}

function cartCount(): int
{
    $count = 0;
    foreach (getCart() as $item) {
        $count += $item['qty'];
    }
    return $count;
}

function cartSubtotal(): float
{
    $total = 0;
    foreach (getCart() as $item) {
        $total += $item['price'] * $item['qty'];
    }
    return $total;
}

function cartShipping(float $subtotal): float
{
    return $subtotal >= 150 ? 0 : 12;
}

/**
 * Generate unique order number
 */
function generateOrderNumber(): string
{
    return 'AB' . date('ymd') . strtoupper(substr(uniqid(), -6));
}

/**
 * Escape for HTML
 */
function e(?string $str): string
{
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Active nav helper
 */
function isActive(string $page): string
{
    $current = basename($_SERVER['PHP_SELF']);
    return $current === $page ? 'active' : '';
}

/* ========== ADMIN AUTH ========== */

function isLoggedIn(): bool
{
    return !empty($_SESSION['admin_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

function loginAdmin(string $username, string $password): bool
{
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['name'] ?: $user['username'];
        return true;
    }
    return false;
}

function logoutAdmin(): void
{
    unset($_SESSION['admin_id'], $_SESSION['admin_name']);
}

function getOrders(int $limit = 50): array
{
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM orders ORDER BY created_at DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function getOrderItems(int $orderId): array
{
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt->execute([$orderId]);
    return $stmt->fetchAll();
}
