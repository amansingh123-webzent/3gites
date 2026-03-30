/* ============================================================
   3Gites-1975 — Shared JS
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  /* ---- Hamburger ---- */
  const hamburger = document.querySelector('.hamburger');
  const mobileNav = document.querySelector('.mobile-nav');
  if (hamburger && mobileNav) {
    hamburger.addEventListener('click', () => {
      mobileNav.classList.toggle('open');
    });
  }

  /* ---- Tab Switching ---- */
  document.querySelectorAll('.tab-group').forEach(group => {
    group.querySelectorAll('.tab-pill').forEach(pill => {
      pill.addEventListener('click', () => {
        group.querySelectorAll('.tab-pill').forEach(p => p.classList.remove('active'));
        pill.classList.add('active');
        const target = pill.dataset.target;
        if (target) {
          document.querySelectorAll('.tab-content').forEach(tc => tc.style.display = 'none');
          const el = document.getElementById(target);
          if (el) el.style.display = '';
        }
      });
    });
  });

  /* ---- Lightbox ---- */
  const overlay = document.querySelector('.lightbox-overlay');
  if (overlay) {
    const img = overlay.querySelector('img');
    const caption = overlay.querySelector('.lightbox-caption');
    const triggers = document.querySelectorAll('[data-lightbox]');
    let items = [], current = 0;

    triggers.forEach((el, i) => {
      items.push({ src: el.dataset.lightbox, caption: el.dataset.caption || '' });
      el.addEventListener('click', () => { current = i; showLB(); });
    });

    function showLB() {
      img.src = items[current].src;
      caption.textContent = items[current].caption + ' (' + (current+1) + '/' + items.length + ')';
      overlay.classList.add('open');
    }
    function hideLB() { overlay.classList.remove('open'); }

    overlay.querySelector('.lightbox-close')?.addEventListener('click', hideLB);
    overlay.querySelector('.lightbox-prev')?.addEventListener('click', () => { current = (current - 1 + items.length) % items.length; showLB(); });
    overlay.querySelector('.lightbox-next')?.addEventListener('click', () => { current = (current + 1) % items.length; showLB(); });
    overlay.addEventListener('click', (e) => { if (e.target === overlay) hideLB(); });

    document.addEventListener('keydown', (e) => {
      if (!overlay.classList.contains('open')) return;
      if (e.key === 'Escape') hideLB();
      if (e.key === 'ArrowLeft') { current = (current - 1 + items.length) % items.length; showLB(); }
      if (e.key === 'ArrowRight') { current = (current + 1) % items.length; showLB(); }
    });
  }

  /* ---- RSVP Buttons ---- */
  document.querySelectorAll('.rsvp-btn-group .btn').forEach(btn => {
    btn.addEventListener('click', () => {
      btn.closest('.rsvp-btn-group').querySelectorAll('.btn').forEach(b => {
        b.classList.remove('rsvp-active');
        b.style.background = '';
        b.style.color = '';
      });
      btn.classList.add('rsvp-active');
    });
  });

  /* ---- Poll Vote ---- */
  document.querySelectorAll('.poll-option').forEach(opt => {
    opt.addEventListener('click', () => {
      opt.closest('.poll-options').querySelectorAll('.poll-option').forEach(o => o.classList.remove('selected'));
      opt.classList.add('selected');
      const radio = opt.querySelector('input[type="radio"]');
      if (radio) radio.checked = true;
    });
  });

  /* ---- Amount Presets ---- */
  document.querySelectorAll('.amount-preset').forEach(btn => {
    btn.addEventListener('click', () => {
      btn.closest('.amount-presets').querySelectorAll('.amount-preset').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const input = document.querySelector('#customAmount');
      if (input) input.value = btn.dataset.amount;
    });
  });

  /* ---- Cart Quantity ---- */
  document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const display = btn.parentElement.querySelector('.qty-display');
      let val = parseInt(display.textContent);
      if (btn.classList.contains('qty-minus') && val > 1) val--;
      if (btn.classList.contains('qty-plus') && val < 10) val++;
      display.textContent = val;
    });
  });

  /* ---- Toggle Panels ---- */
  document.querySelectorAll('[data-toggle]').forEach(trigger => {
    trigger.addEventListener('click', () => {
      const target = document.getElementById(trigger.dataset.toggle);
      if (target) target.style.display = target.style.display === 'none' ? '' : 'none';
    });
  });

  /* ---- Character Counter ---- */
  document.querySelectorAll('[data-maxlength]').forEach(el => {
    const max = parseInt(el.dataset.maxlength);
    const counter = el.parentElement.querySelector('.char-counter');
    if (counter) {
      el.addEventListener('input', () => {
        const remaining = max - el.value.length;
        counter.textContent = remaining + ' characters remaining';
        counter.style.color = remaining < 50 ? 'var(--red-500)' : 'var(--slate-400)';
      });
    }
  });

  /* ---- Animated Bars ---- */
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const bar = entry.target;
        bar.style.width = bar.dataset.width;
        observer.unobserve(bar);
      }
    });
  });
  document.querySelectorAll('.result-bar-fill').forEach(bar => {
    bar.dataset.width = bar.style.width;
    bar.style.width = '0';
    observer.observe(bar);
  });

});
