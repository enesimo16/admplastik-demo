/**
 * Etkileşim toplama (client)
 *
 * Ziyaretçinin sayfada ne yaptığını track.php'ye küçük paketler halinde
 * gönderir: tıklamalar, dış bağlantılar, telefon/e-posta/WhatsApp, dosya
 * indirme, kaydırma derinliği, form başlangıcı/gönderimi, sayfada kalma.
 *
 * Tasarım kararları:
 *  - `navigator.sendBeacon` kullanılır: sayfa kapanırken bile gönderim
 *    tamamlanır ve gezinmeyi geciktirmez.
 *  - Aynı olay aynı sayfada tekrar gönderilmez (kaydırma eşikleri, form
 *    başlangıcı), gereksiz istek oluşmasın.
 *  - Bir buton/bağlantıya `data-track="ad"` verilerek özel isim atanabilir.
 */
(function () {
  'use strict';

  var ENDPOINT = 'track.php';
  var MAX_LABEL = 190;

  // Sayfa başına bir kez gönderilecek olayların kaydı
  var sentOnce = {};

  function send(event, data) {
    data = data || {};
    var payload = {
      e: event,
      l: (data.label || '').toString().slice(0, MAX_LABEL),
      u: location.pathname + location.search,
      t: (data.target || '').toString().slice(0, 255),
      v: data.value || 0
    };

    var body = JSON.stringify(payload);
    if (navigator.sendBeacon) {
      navigator.sendBeacon(ENDPOINT, new Blob([body], { type: 'application/json' }));
    } else {
      // sendBeacon yoksa: gezinmeyi engellememesi için keepalive ile
      fetch(ENDPOINT, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: body,
        keepalive: true
      }).catch(function () { /* istatistik hatası sayfayı etkilemesin */ });
    }
  }

  function sendOnce(key, event, data) {
    if (sentOnce[key]) return;
    sentOnce[key] = true;
    send(event, data);
  }

  function textOf(el) {
    var label = el.getAttribute('data-track')
      || el.getAttribute('aria-label')
      || (el.textContent || '').replace(/\s+/g, ' ').trim();
    if (!label) {
      var img = el.querySelector('img');
      if (img) label = img.getAttribute('alt') || '';
    }
    return label.slice(0, MAX_LABEL);
  }

  // ---- Tıklamalar ----------------------------------------------------

  var DOWNLOAD_EXT = /\.(pdf|docx?|xlsx?|pptx?|zip|rar|csv|dwg|step|stp)($|\?)/i;

  document.addEventListener('click', function (e) {
    var el = e.target.closest ? e.target.closest('a, button, [data-track]') : null;
    if (!el) return;

    var href = (el.getAttribute('href') || '').trim();
    var label = textOf(el);

    if (href.indexOf('tel:') === 0) {
      send('phone', { label: label, target: href.replace('tel:', '') });
      return;
    }
    if (href.indexOf('mailto:') === 0) {
      send('mail', { label: label, target: href.replace('mailto:', '') });
      return;
    }
    if (/wa\.me|api\.whatsapp\.com|web\.whatsapp\.com/i.test(href)) {
      send('whatsapp', { label: label, target: href });
      return;
    }
    if (DOWNLOAD_EXT.test(href)) {
      send('download', { label: label, target: href });
      return;
    }
    if (/^https?:\/\//i.test(href) && href.indexOf(location.host) === -1) {
      send('outbound', { label: label, target: href });
      return;
    }
    // İç bağlantı / buton: yalnızca işaretlenmişse veya bir eylem butonuysa
    if (el.hasAttribute('data-track') || el.tagName === 'BUTTON' ||
        /\brd-btn\b/.test(el.className || '')) {
      send('click', { label: label, target: href });
    }
  }, true);

  // ---- Kaydırma derinliği --------------------------------------------

  var DEPTHS = [25, 50, 75, 100];

  function checkScrollDepth() {
    var doc = document.documentElement;
    var scrollable = doc.scrollHeight - window.innerHeight;
    // Sayfa ekrandan kısaysa tamamı zaten görülmüştür — %100 say
    var percent = scrollable <= 0
      ? 100
      : Math.round(((window.scrollY || doc.scrollTop) / scrollable) * 100);

    for (var i = 0; i < DEPTHS.length; i++) {
      if (percent >= DEPTHS[i]) {
        sendOnce('scroll-' + DEPTHS[i], 'scroll', { value: DEPTHS[i] });
      }
    }
  }

  var scrollTimer = null;
  window.addEventListener('scroll', function () {
    if (scrollTimer) return;
    scrollTimer = setTimeout(function () {
      scrollTimer = null;
      checkScrollDepth();
    }, 400);
  }, { passive: true });

  // ---- Formlar --------------------------------------------------------

  function formName(form) {
    var action = (form.getAttribute('action') || '').split('/').pop().split('?')[0];
    return form.getAttribute('data-track')
      || form.getAttribute('name')
      || form.getAttribute('id')
      || action
      || 'form';
  }

  document.addEventListener('focusin', function (e) {
    var form = e.target.form;
    if (!form) return;
    sendOnce('form-start-' + formName(form), 'form_start', { label: formName(form) });
  });

  document.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form || form.tagName !== 'FORM') return;
    var name = formName(form);
    // Site içi arama formunu ayrı olay olarak işaretle
    if (/search|ara/i.test(name)) {
      send('search', { label: name });
    } else {
      send('form_submit', { label: name });
    }
  }, true);

  // ---- Sayfada kalma süresi -------------------------------------------

  var startedAt = Date.now();
  var hiddenSince = 0;
  var hiddenTotal = 0;

  document.addEventListener('visibilitychange', function () {
    if (document.hidden) {
      hiddenSince = Date.now();
    } else if (hiddenSince) {
      hiddenTotal += Date.now() - hiddenSince;
      hiddenSince = 0;
    }
  });

  function sendEngagement() {
    if (sentOnce.engagement) return;
    var hidden = hiddenTotal + (hiddenSince ? Date.now() - hiddenSince : 0);
    var seconds = Math.round((Date.now() - startedAt - hidden) / 1000);
    if (seconds < 2) return;   // sekmede hiç durmadıysa kaydetme
    sentOnce.engagement = true;
    send('engagement', { value: Math.min(seconds, 3600) });
  }

  // pagehide mobil Safari'de güvenilir; visibilitychange yedek.
  window.addEventListener('pagehide', sendEngagement);
  document.addEventListener('visibilitychange', function () {
    if (document.hidden) sendEngagement();
  });

  // ---- Video ----------------------------------------------------------

  document.addEventListener('play', function (e) {
    if (e.target && e.target.tagName === 'VIDEO') {
      var source = e.target.currentSrc || e.target.getAttribute('src') || '';
      sendOnce('video-' + source, 'video', { label: 'video', target: source });
    }
  }, true);
})();
