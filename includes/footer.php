  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <a href="index.php" class="logo">
            <img src="assets/images/logo.png" alt="ABShop" class="logo-img logo-img-footer" />
          </a>
          <p>Modern essentials crafted with intention. Quality materials, clean design, and lasting value.</p>
        </div>
        <div>
          <h4>Shop</h4>
          <div class="footer-links">
            <a href="shop.php">All Products</a>
            <a href="shop.php?cat=apparel">Apparel</a>
            <a href="shop.php?cat=bags">Bags</a>
            <a href="shop.php?cat=home">Home</a>
          </div>
        </div>
        <div>
          <h4>Company</h4>
          <div class="footer-links">
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
          </div>
        </div>
        <div>
          <h4>Support</h4>
          <div class="footer-links">
            <a href="#">Shipping</a>
            <a href="#">Returns</a>
            <a href="#">FAQ</a>
            <a href="#">Size Guide</a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© <?= date('Y') ?> ABShop. All rights reserved.</p>
        <div class="social-links">
          <a href="#" aria-label="Instagram">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <rect x="2" y="2" width="20" height="20" rx="5"/>
              <circle cx="12" cy="12" r="4"/>
              <circle cx="18" cy="6" r="1.5" fill="currentColor"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </footer>

  <div class="toast" id="toast"></div>

  <script src="assets/js/main.js"></script>
</body>
</html>
