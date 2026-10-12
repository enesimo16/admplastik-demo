<?php
  // Sayfalar seo_url ile bulunur (site-config.php); veritabanında ek kolon gerekmez.
  $policyKeys  = array('policy-quality', 'policy-social', 'policy-environment');
  $policySlugs = rd_slugs($policyKeys);
  $stackAsk = $db->prepare("SELECT * FROM posts WHERE seo_url IN ('" . implode("','", $policySlugs) . "')");
  $stackAsk->execute();
  $stackRows = $stackAsk->fetchAll(PDO::FETCH_ASSOC);

  $stackItems = array();
  foreach ($policyKeys as $k) {
    $slug = rd_slug($k);
    foreach ($stackRows as $row) {
      if ($row['seo_url'] === $slug) {
        $row['title']   = rd_text($k, 'TITLE', $row['title']);
        $row['content'] = rd_text($k, 'CONTENT', $row['content']);
        $stackItems[] = $row;
        break;
      }
    }
  }

  $contactUrl = rd_url('contact-index', 'sayfa-iletisim');
?>
<div class="rd-page">

  <!-- Sayfa başlığı: her sayfada tek bir h1 bulunmalı (SEO + ekran okuyucu).
       Görsel olarak gizli, panellerin tasarımını bozmuyor. -->
  <h1 class="rd-visually-hidden"><?= $langArray['RD_MANAGEMENT_SYSTEMS'] ?></h1>

  <!-- Yan nokta navigasyonu -->
  <nav class="rd-policy-dots" id="rdPolicyDots" aria-label="<?= $langArray['RD_MANAGEMENT_SYSTEMS'] ?>">
    <?php foreach ($stackItems as $i => $item): ?>
      <a href="#policy-<?= $item['post_id'] ?>" data-target="policy-<?= $item['post_id'] ?>" class="<?= $i === 0 ? 'rd-active' : '' ?>">
        <span class="rd-policy-dot-label"><?= $item['title'] ?></span>
        <span class="rd-policy-dot"></span>
      </a>
    <?php endforeach; ?>
  </nav>

  <?php foreach ($stackItems as $i => $item): $even = ($i % 2 === 0); ?>
  <section class="rd-policy-panel <?= $even ? '' : 'rd-policy-flip' ?>" id="policy-<?= $item['post_id'] ?>">
    <div class="rd-policy-media" style="background-image:url('<?= rd_img_url($item['banner']) ?>')">
      <div class="rd-policy-index">0<?= $i + 1 ?></div>
    </div>
    <div class="rd-policy-body">
      <span class="rd-eyebrow" style="color:var(--rd-green-light)"><?= $langArray['RD_MANAGEMENT_SYSTEMS'] ?></span>
      <h2><?= $item['title'] ?></h2>
      <div class="rd-policy-text"><?= $item['content'] ?></div>
    </div>
  </section>
  <?php endforeach; ?>

  <section class="rd-contact-cta">
    <h2><?= $langArray['RD_POLICY_CTA_TITLE'] ?></h2>
    <p><?= $langArray['RD_POLICY_CTA_DESC'] ?></p>
    <a href="<?= $contactUrl ?>" class="rd-btn-white"><?= $langArray['RD_CONTACT_GET_IN_TOUCH'] ?></a>
  </section>

</div>

<script>
(function () {
  var dots = document.querySelectorAll('#rdPolicyDots a');
  var panels = document.querySelectorAll('.rd-policy-panel');

  function updateDots() {
    var current = 0;
    panels.forEach(function (p, idx) {
      if (p.getBoundingClientRect().top < window.innerHeight * 0.45) current = idx;
    });
    dots.forEach(function (d, idx) { d.classList.toggle('rd-active', idx === current); });
  }
  window.addEventListener('scroll', updateDots, { passive: true });
  updateDots();

  dots.forEach(function (dot) {
    dot.addEventListener('click', function (e) {
      e.preventDefault();
      var t = document.getElementById(dot.dataset.target);
      if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);
    panels.forEach(function (panel) {
      var media = panel.querySelector('.rd-policy-media');
      var body = panel.querySelector('.rd-policy-body > *');
      var bodyEls = panel.querySelectorAll('.rd-policy-body > *');
      var flip = panel.classList.contains('rd-policy-flip');
      gsap.fromTo(media, { opacity: 0, x: flip ? 60 : -60 }, {
        opacity: 1, x: 0, duration: .8, ease: 'power2.out',
        scrollTrigger: { trigger: panel, start: 'top 70%' }
      });
      gsap.fromTo(bodyEls, { opacity: 0, y: 26 }, {
        opacity: 1, y: 0, duration: .6, stagger: .12, ease: 'power2.out',
        scrollTrigger: { trigger: panel, start: 'top 65%' }
      });
    });
  }

  var targetId = window.location.hash ? window.location.hash.substring(1) : null;
  if (targetId) {
    var target = document.getElementById(targetId);
    if (target) setTimeout(function () { target.scrollIntoView({ behavior: 'smooth' }); }, 250);
  }
})();
</script>
