/* ABShop — Front-end interactions */

function showToast(message) {
  const toast = document.getElementById('toast');
  if (!toast) return;
  toast.textContent = message;
  toast.classList.add('show');
  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => toast.classList.remove('show'), 2800);
}

// Header scroll effect
document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('.header');
  if (header) {
    const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 20);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // Mobile nav
  const toggle = document.querySelector('.mobile-toggle');
  const mobileNav = document.querySelector('.mobile-nav');
  const closeBtn = document.querySelector('.mobile-close');

  if (toggle && mobileNav) {
    toggle.addEventListener('click', () => mobileNav.classList.add('open'));
    closeBtn?.addEventListener('click', () => mobileNav.classList.remove('open'));
    mobileNav.querySelectorAll('a').forEach(a =>
      a.addEventListener('click', () => mobileNav.classList.remove('open'))
    );
  }

  // Product detail size/color selection
  document.querySelectorAll('.size-btn, .color-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const group = this.parentElement;
      group.querySelectorAll('button').forEach(b => b.classList.remove('active'));
      this.classList.add('active');

      // Update hidden inputs if present
      const input = document.getElementById(this.dataset.target);
      if (input) input.value = this.dataset.value;
    });
  });

  // Gallery thumbs
  document.querySelectorAll('.gallery-thumb').forEach(thumb => {
    thumb.addEventListener('click', function () {
      document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
      this.classList.add('active');
      const main = document.getElementById('main-img');
      if (main) main.src = this.dataset.img;
    });
  });

  // Quantity controls on product page
  const qtyValue = document.getElementById('qty-value');
  const qtyInput = document.getElementById('qty-input');
  if (qtyValue && qtyInput) {
    document.getElementById('qty-minus')?.addEventListener('click', () => {
      let v = parseInt(qtyValue.textContent, 10);
      if (v > 1) {
        v--;
        qtyValue.textContent = v;
        qtyInput.value = v;
      }
    });
    document.getElementById('qty-plus')?.addEventListener('click', () => {
      let v = parseInt(qtyValue.textContent, 10) + 1;
      qtyValue.textContent = v;
      qtyInput.value = v;
    });
  }
});
