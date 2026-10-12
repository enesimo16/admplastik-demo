<?php
  // Misyon & Vizyon içeriği (sayfa seo_url ile bulunur — bkz. site-config.php)
  $mvAsk = $db->prepare("SELECT * FROM posts WHERE seo_url=:slug LIMIT 1");
  $mvAsk->execute(array('slug' => rd_slug('about-mv')));
  $mvCheck = $mvAsk->fetch(PDO::FETCH_ASSOC);
  $mvContent = $mvCheck ? rd_text('about-mv', 'CONTENT', $mvCheck['content']) : '';
  $mvParts = $mvContent ? explode('<hr', $mvContent) : array('', '');
  $misyonHtml = $mvParts[0] ?? '';
  $vizyonHtml = isset($mvParts[1]) ? preg_replace('/^[^>]*>/', '', $mvParts[1], 1) : '';
?>
<div class="rd-page">

  <div class="rd-page-hero" style="background-image:url('<?= rd_img_url($pageDataCheck['banner']) ?>')">
    <h1><?= rd_text('about-main', 'TITLE', $pageDataCheck['title']) ?></h1>
    <div class="rd-crumbs"><a href="<?= $generalSettingCheck['site_url'] ?>"><?= $langArray['RD_HOME'] ?></a> / <?= $langArray['RD_KURUMSAL_CRUMB'] ?></div>
  </div>

  <div class="rd-tabs" id="rdTabs">
    <button data-tab="hakkimizda" class="rd-active"><?= $langArray['RD_TAB_ABOUT'] ?></button>
    <button data-tab="misyon-vizyon"><?= $langArray['RD_TAB_MV'] ?></button>
    <button data-tab="sertifikalar"><?= $langArray['RD_TAB_CERT'] ?></button>
  </div>

  <section class="rd-tab-panel rd-active" id="tab-hakkimizda">
    <div class="rd-container" style="max-width:820px;margin:0 auto;text-align:center;">
      <div class="rd-typewriter" id="rdTypewriter"></div>
    </div>
    <div class="rd-about-content">
      <?= rd_text('about-main', 'CONTENT', $pageDataCheck['content']) ?>
    </div>

    <div class="rd-faq">
      <?php foreach (rd_faqs($db) as $rdAboutFaqRow): ?>
        <div class="rd-faq-item">
          <button class="rd-faq-q"><?= $rdAboutFaqRow['q'] ?> <span class="rd-plus">+</span></button>
          <div class="rd-faq-a"><p><?= $rdAboutFaqRow['a'] ?></p></div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="rd-tab-panel" id="tab-misyon-vizyon">
    <div class="rd-mv-grid">
      <div class="rd-mv-card"><?= $misyonHtml ?></div>
      <div class="rd-mv-card rd-vizyon"><?= $vizyonHtml ?></div>
    </div>
  </section>

  <section class="rd-tab-panel" id="tab-sertifikalar">
    <div class="rd-cert-intro">
      <?= $langArray['RD_CERT_INTRO'] ?>
    </div>
    <div class="rd-cert-row">
      <?php
        $placeholderCerts = [
          'ISO 9001:2015', 'ISO 14001:2015', 'IATF 16949', 'ISO 45001',
          'CE', 'TSE'
        ];
        foreach ($placeholderCerts as $cert):
      ?>
        <div class="rd-cert-card">
          <div class="rd-cert-badge">DOC</div>
          <h4><?= $cert ?></h4>
          <span><?= $langArray['RD_SOON'] ?></span>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="rd-cert-note"><?= $langArray['RD_CERT_NOTE'] ?></div>
  </section>

</div>

<script>
(function () {
  var tabs = document.querySelectorAll('#rdTabs button');
  var panels = document.querySelectorAll('.rd-tab-panel');

  function activate(name) {
    tabs.forEach(function (b) { b.classList.toggle('rd-active', b.dataset.tab === name); });
    panels.forEach(function (p) { p.classList.toggle('rd-active', p.id === 'tab-' + name); });
  }

  tabs.forEach(function (btn) {
    btn.addEventListener('click', function () {
      activate(btn.dataset.tab);
      history.replaceState(null, '', '#' + btn.dataset.tab);
    });
  });

  var initial = window.location.hash ? window.location.hash.substring(1) : 'hakkimizda';
  if (document.getElementById('tab-' + initial)) activate(initial);

  // Typewriter
  var tw = document.getElementById('rdTypewriter');
  var phrases = <?= json_encode(array($langArray['RD_TYPE_1'], $langArray['RD_TYPE_2'], $langArray['RD_TYPE_3']), JSON_UNESCAPED_UNICODE) ?>;
  var pi = 0, ci = 0, deleting = false;
  function tick() {
    var current = phrases[pi];
    if (!deleting) {
      ci++;
      tw.textContent = current.substring(0, ci);
      if (ci === current.length) { deleting = true; setTimeout(tick, 1400); return; }
    } else {
      ci--;
      tw.textContent = current.substring(0, ci);
      if (ci === 0) { deleting = false; pi = (pi + 1) % phrases.length; }
    }
    setTimeout(tick, deleting ? 40 : 70);
  }
  tick();

  // FAQ accordion
  document.querySelectorAll('.rd-faq-item').forEach(function (item) {
    item.querySelector('.rd-faq-q').addEventListener('click', function () {
      var wasOpen = item.classList.contains('rd-open');
      document.querySelectorAll('.rd-faq-item').forEach(function (i) { i.classList.remove('rd-open'); });
      if (!wasOpen) item.classList.add('rd-open');
    });
  });

  // Scroll-reveal (staggered) for FAQ items, mission/vision cards, certificate cards
  var revealSelectors = ['.rd-faq-item', '.rd-mv-card', '.rd-cert-card'];
  var revealEls = document.querySelectorAll(revealSelectors.join(','));
  var groups = {};
  revealEls.forEach(function (el) {
    var parentKey = el.className.match(/rd-(faq-item|mv-card|cert-card)/)[0];
    groups[parentKey] = groups[parentKey] || 0;
    el.style.animationDelay = (groups[parentKey] * 90) + 'ms';
    groups[parentKey]++;
  });
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('rd-in-view');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });
    revealEls.forEach(function (el) { io.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('rd-in-view'); });
  }
})();
</script>
