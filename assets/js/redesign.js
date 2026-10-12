(function () {
  'use strict';

  // ---- Scroll progress bar ----
  var progressBar = document.getElementById('rdProgressBar');
  function updateProgress() {
    if (!progressBar) return;
    var h = document.documentElement;
    var scrollTop = h.scrollTop || document.body.scrollTop;
    var scrollHeight = (h.scrollHeight || document.body.scrollHeight) - h.clientHeight;
    var pct = scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0;
    progressBar.style.width = pct + '%';
  }
  window.addEventListener('scroll', updateProgress, { passive: true });
  updateProgress();

  // ---- Back to top ----
  var backToTop = document.getElementById('rdBackToTop');
  if (backToTop) {
    window.addEventListener('scroll', function () {
      backToTop.classList.toggle('rd-visible', window.scrollY > 480);
    }, { passive: true });
    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ---- Generic typewriter (progressive enhancement: real text already in DOM) ----
  document.querySelectorAll('[data-typewriter]').forEach(function (el) {
    var text = el.textContent.trim();
    if (!text) return;
    el.textContent = '';
    el.classList.add('rd-typing');
    var i = 0;
    (function step() {
      el.textContent = text.substring(0, i);
      i++;
      if (i <= text.length) {
        setTimeout(step, 28);
      } else {
        el.classList.remove('rd-typing');
      }
    })();
  });

  // ---- Chat panel ----
  var chatFab = document.getElementById('rdChatFab');
  var chatPanel = document.getElementById('rdChatPanel');
  var chatForm = document.getElementById('rdChatForm');
  var chatInput = document.getElementById('rdChatInput');
  var chatBody = document.getElementById('rdChatBody');
  if (chatFab && chatPanel) {
    chatFab.addEventListener('click', function () {
      var open = chatPanel.classList.toggle('rd-open');
      chatFab.classList.toggle('rd-open', open);
      if (open && chatInput) setTimeout(function () { chatInput.focus(); }, 250);
    });
  }
  if (chatForm && chatInput && chatBody) {
    chatForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var text = chatInput.value.trim();
      if (!text) return;
      var userMsg = document.createElement('div');
      userMsg.className = 'rd-chat-msg rd-chat-msg-user';
      userMsg.textContent = text;
      chatBody.appendChild(userMsg);
      chatInput.value = '';
      chatBody.scrollTop = chatBody.scrollHeight;
      setTimeout(function () {
        var botMsg = document.createElement('div');
        botMsg.className = 'rd-chat-msg rd-chat-msg-bot';
        botMsg.textContent = chatBody.dataset.autoreply || 'Thank you for your message!';
        chatBody.appendChild(botMsg);
        chatBody.scrollTop = chatBody.scrollHeight;
      }, 700);
    });
  }

  // ---- Expert / Teklif Al modal (site-wide) ----
  var expertModal = document.getElementById('rdExpertModal');
  if (expertModal) {
    document.querySelectorAll('[data-rd-modal-open]').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        expertModal.classList.add('rd-open');
        document.body.style.overflow = 'hidden';
      });
    });
    expertModal.querySelectorAll('[data-rd-modal-close]').forEach(function (el) {
      el.addEventListener('click', function () {
        expertModal.classList.remove('rd-open');
        document.body.style.overflow = '';
      });
    });
  }

  // ---- Newsletter signup (AJAX) ----
  var newsletterForm = document.getElementById('rdNewsletterForm');
  var newsletterMsg = document.getElementById('rdNewsletterMsg');
  if (newsletterForm && newsletterMsg) {
    newsletterForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var email = newsletterForm.querySelector('input[name="email"]').value.trim();
      var btn = newsletterForm.querySelector('button[type="submit"]');
      if (!email) return;
      btn.disabled = true;
      var body = new URLSearchParams();
      body.set('email', email);
      body.set('language', newsletterForm.dataset.lang || '');
      fetch('newsletter-subscribe.php', { method: 'POST', body: body })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          btn.disabled = false;
          newsletterMsg.textContent = data.success ? newsletterForm.dataset.success : newsletterForm.dataset.error;
          newsletterMsg.classList.toggle('rd-error', !data.success);
          if (data.success) newsletterForm.reset();
        })
        .catch(function () {
          btn.disabled = false;
          newsletterMsg.textContent = newsletterForm.dataset.error;
          newsletterMsg.classList.add('rd-error');
        });
    });
  }

  // ---- Formlar: sayfa yenilenmeden gönder, butonda durum göster ----
  // JavaScript kapalıysa form normal şekilde POST edilir (form-submit.php
  // geri yönlendirir), yani bu sadece deneyimi iyileştirir.
  document.querySelectorAll('.rd-ajax-form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var btn = form.querySelector('.rd-submit-btn');
      var label = btn ? btn.querySelector('.rd-submit-label') : null;
      var feedback = form.querySelector('.rd-form-feedback');
      if (!btn || btn.classList.contains('rd-sending') || btn.classList.contains('rd-sent')) return;

      var originalLabel = label ? label.textContent : '';
      btn.classList.add('rd-sending');
      if (label && form.dataset.sending) label.textContent = form.dataset.sending;
      if (feedback) { feedback.textContent = ''; feedback.classList.remove('rd-show', 'rd-error'); }

      fetch(form.getAttribute('action'), {
        method: 'POST',
        body: new FormData(form),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
      })
        .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
        .then(function (res) {
          btn.classList.remove('rd-sending');
          var success = res.ok && res.data && res.data.success;

          if (success) {
            // Sınıfı tamamen değiştir: cascade yarışı olmadan kesin görsel sonuç
            btn.classList.remove('rd-btn-primary', 'rd-btn-white');
            btn.classList.add('rd-sent', 'rd-btn-sent');
            btn.disabled = true;
            if (label) label.textContent = form.dataset.sent || originalLabel;
            form.classList.add('rd-done');
            form.reset();
            if (typeof grecaptcha !== 'undefined' && grecaptcha.reset) { try { grecaptcha.reset(); } catch (err) {} }
          } else {
            if (label) label.textContent = originalLabel;
          }

          if (feedback && res.data && res.data.message) {
            feedback.textContent = res.data.message;
            feedback.classList.toggle('rd-error', !success);
            feedback.classList.add('rd-show');
          }
        })
        .catch(function () {
          btn.classList.remove('rd-sending');
          if (label) label.textContent = originalLabel;
          if (feedback) {
            feedback.textContent = feedback.dataset.networkError || 'Bağlantı hatası, lütfen tekrar deneyin.';
            feedback.classList.add('rd-show', 'rd-error');
          }
        });
    });
  });

  // ---- Mobile nav: tap-to-expand submenus (hover doesn't exist on touch) ----
  var mobileNav = document.querySelector('.rd-nav');
  if (mobileNav) {
    mobileNav.querySelectorAll('li.menu-item-has-children > a').forEach(function (link) {
      link.addEventListener('click', function (e) {
        if (window.innerWidth > 960) return; // desktop: hover already handles it
        e.preventDefault();
        var li = link.parentElement;
        var wasOpen = li.classList.contains('rd-submenu-open');
        li.parentElement.querySelectorAll(':scope > li.rd-submenu-open').forEach(function (l) {
          if (l !== li) l.classList.remove('rd-submenu-open');
        });
        li.classList.toggle('rd-submenu-open', !wasOpen);
      });
    });
  }

  // ---- Navbar search toggle ----
  var searchToggle = document.getElementById('rdSearchToggle');
  var headerEl = document.querySelector('.rd-header');
  var searchForm = document.getElementById('rdSearchForm');
  if (searchToggle && headerEl && searchForm) {
    searchToggle.addEventListener('click', function () {
      var open = headerEl.classList.toggle('rd-search-open');
      if (open) {
        setTimeout(function () { searchForm.querySelector('input').focus(); }, 260);
      }
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') headerEl.classList.remove('rd-search-open');
    });
  }

  // ---- Generic count-up on scroll into view ----
  var countEls = document.querySelectorAll('[data-countup]');
  function animateCount(el) {
    var raw = el.textContent.trim();
    var match = raw.match(/^(\D*)([\d.,]+)(\D*)$/);
    if (!match) { return; }
    var prefix = match[1], numStr = match[2].replace(/\./g, '').replace(',', '.'), suffix = match[3];
    var target = parseFloat(numStr);
    if (isNaN(target)) return;
    var duration = 1400;
    var startTime = null;
    function step(ts) {
      if (!startTime) startTime = ts;
      var progress = Math.min((ts - startTime) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3);
      var current = Math.round(target * eased);
      el.textContent = prefix + current.toLocaleString('tr-TR') + suffix;
      if (progress < 1) requestAnimationFrame(step);
      else el.textContent = prefix + target.toLocaleString('tr-TR') + suffix;
    }
    requestAnimationFrame(step);
  }
  if ('IntersectionObserver' in window && countEls.length) {
    var countIo = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animateCount(entry.target);
          countIo.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    countEls.forEach(function (el) { countIo.observe(el); });
  }

})();
