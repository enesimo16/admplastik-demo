(function () {
  'use strict';

  var cookieName = 'adm_consent';
  var banner = document.getElementById('rdCookieConsent');
  if (!banner) return;

  function setConsent(value) {
    var maxAge = 180 * 24 * 60 * 60;
    document.cookie = cookieName + '=' + encodeURIComponent(value) + '; Max-Age=' + maxAge + '; Path=/; SameSite=Lax' + (location.protocol === 'https:' ? '; Secure' : '');
    if (value === 'necessary') {
      document.cookie = 'adm_vid=; Max-Age=0; Path=/; SameSite=Lax';
    }
    banner.hidden = true;
    // Analytics scriptleri izin alınmadan yüklenmediği için kabul sonrası
    // sayfa yenilenerek PHP tarafının ölçümü başlatması sağlanır.
    if (value === 'all') window.location.reload();
  }

  document.addEventListener('click', function (event) {
    if (event.target.closest('[data-cookie-settings]')) {
      banner.hidden = false;
    }
  });

  if (!document.cookie.split('; ').some(function (item) { return item.indexOf(cookieName + '=') === 0; })) {
    banner.hidden = false;
  }

  banner.addEventListener('click', function (event) {
    var button = event.target.closest('[data-cookie-choice]');
    if (button) setConsent(button.getAttribute('data-cookie-choice'));
  });
}());
