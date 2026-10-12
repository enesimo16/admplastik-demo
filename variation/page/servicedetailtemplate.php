<?php
  // Tüm üretim/sektör sayfaları tek birleşik sayfada. Hangi hizmet URL'i ile
  // girilirse girilsin aynı sayfa render edilir; JS ilgili bölüme kaydırır.
  $allServicesAsk = $db->prepare("SELECT * FROM posts WHERE post_type='post_services' AND language=:language AND status=1 ORDER BY order_sort");
  $allServicesAsk->execute(array('language' => $rdContentLangId));
  $allServices = $allServicesAsk->fetchAll(PDO::FETCH_ASSOC);
  // Başlıkları arayüz diline çevir (içerik veritabanında tek dilde tutulur)
  foreach ($allServices as $i => $svc) {
    $k = rd_key_of($svc['seo_url']);
    if ($k) $allServices[$i]['page_key'] = $k;
    if ($k) $allServices[$i]['title'] = rd_text($k, 'TITLE', $svc['title']);
  }
?>
<div class="rd-page">

  <section class="rd-service-hero rd-service-hero-slim">
    <div class="rd-service-hero-bg" id="rdServiceHeroBg" style="background-image:url('<?= rd_img_url($servicesDataCheck['banner']) ?>')"></div>
    <div class="rd-service-hero-overlay">
      <span class="rd-eyebrow rd-reveal"><?= $langArray['RD_PRODUCTION'] ?></span>
      <h1 class="rd-reveal"><?= $langArray['RD_PRODUCTION_AREAS'] ?></h1>
      <div class="rd-crumbs rd-reveal">
        <a href="<?= $generalSettingCheck['site_url'] ?>"><?= $langArray['RD_HOME'] ?></a> / <?= $langArray['RD_PRODUCTION'] ?>
      </div>
    </div>
    <div class="rd-scroll-cue">↓</div>
  </section>

  <div class="rd-prod-layout">
    <aside class="rd-prod-rail" id="rdProdRail">
      <div class="rd-prod-rail-line"><span id="rdProdRailFill"></span></div>
      <?php foreach ($allServices as $i => $svc):
        $slug = preg_replace('/^(hizmet|service)-/', '', $svc['seo_url']);
      ?>
        <a class="rd-prod-rail-item <?= $i === 0 ? 'rd-active' : '' ?>" href="#sector-<?= $slug ?>" data-target="sector-<?= $slug ?>">
          <span class="rd-prod-rail-num">0<?= $i + 1 ?></span>
          <span class="rd-prod-rail-name"><?= $svc['title'] ?></span>
        </a>
      <?php endforeach; ?>
    </aside>

    <div class="rd-prod-content">
      <?php foreach ($allServices as $i => $svc):
        $slug = preg_replace('/^(hizmet|service)-/', '', $svc['seo_url']);
      ?>
      <section class="rd-prod-sector" id="sector-<?= $slug ?>">
        <div class="rd-prod-watermark">0<?= $i + 1 ?></div>
        <div class="rd-prod-sector-head">
          <div class="rd-prod-sector-thumb" style="background-image:url('<?= rd_img_url($svc['banner']) ?>')"></div>
          <div>
            <span class="rd-eyebrow" style="color:var(--rd-green-light)"><?= $langArray['RD_SECTOR'] ?> 0<?= $i + 1 ?></span>
            <h2><?= $svc['title'] ?></h2>
          </div>
        </div>
        <?php $sectorIntro = $langArray['RD_SECTOR_INTRO_' . ($svc['page_key'] ?? '')] ?? ''; ?>
        <?php if ($sectorIntro): ?>
          <p class="rd-prod-sector-intro"><?= $sectorIntro ?></p>
        <?php endif; ?>
        <h3 class="rd-prod-parts-title"><?= $langArray['RD_SECTOR_PARTS_TITLE'] ?></h3>
        <div class="rd-service-content rd-sector-content">
          <?= $svc['content'] ?>
        </div>
      </section>
      <?php endforeach; ?>
    </div>
  </div>

  <section class="rd-contact-cta">
    <h2><?= $langArray['RD_PRODUCTION_CTA_TITLE'] ?></h2>
    <p><?= $langArray['RD_PRODUCTION_CTA_DESC'] ?></p>
    <a href="#" class="rd-btn-white" data-rd-modal-open><?= $langArray['RD_GET_QUOTE'] ?></a>
  </section>

</div>

<script>
(function () {
  var hasGsap = typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined';

  if (hasGsap) {
    gsap.registerPlugin(ScrollTrigger);
    gsap.fromTo('.rd-service-hero .rd-eyebrow', { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: .6, delay: .1 });
    gsap.fromTo('.rd-service-hero h1', { opacity: 0, y: 24 }, { opacity: 1, y: 0, duration: .7, delay: .25 });
    gsap.fromTo('.rd-service-hero .rd-crumbs', { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: .6, delay: .45 });
    gsap.to('#rdServiceHeroBg', {
      yPercent: 18, ease: 'none',
      scrollTrigger: { trigger: '.rd-service-hero', start: 'top top', end: 'bottom top', scrub: true }
    });

    document.querySelectorAll('.rd-sector-content').forEach(function (contentBlock) {
      var cards = contentBlock.querySelectorAll('td');
      if (!cards.length) return;
      gsap.fromTo(cards, { opacity: 0, y: 40 }, {
        opacity: 1, y: 0, duration: .6, stagger: 0.1, ease: 'power2.out',
        scrollTrigger: { trigger: contentBlock, start: 'top 82%' }
      });
      cards.forEach(function (card) {
        card.addEventListener('mousemove', function (e) {
          var r = card.getBoundingClientRect();
          var x = (e.clientX - r.left) / r.width - 0.5;
          var y = (e.clientY - r.top) / r.height - 0.5;
          gsap.to(card, { rotateY: x * 8, rotateX: -y * 8, duration: .3, ease: 'power1.out', transformPerspective: 600 });
        });
        card.addEventListener('mouseleave', function () {
          gsap.to(card, { rotateY: 0, rotateX: 0, duration: .5, ease: 'power2.out' });
        });
      });
    });
  } else {
    document.querySelectorAll('.rd-reveal').forEach(function (el) { el.style.opacity = 1; });
  }

  // Rail scrollspy + progress
  var railItems = document.querySelectorAll('.rd-prod-rail-item');
  var sections = document.querySelectorAll('.rd-prod-sector');
  var railFill = document.getElementById('rdProdRailFill');
  function updateRail() {
    var current = 0;
    sections.forEach(function (sec, idx) {
      if (sec.getBoundingClientRect().top < window.innerHeight * 0.4) current = idx;
    });
    railItems.forEach(function (item, idx) { item.classList.toggle('rd-active', idx === current); });
    if (railFill && sections.length > 1) {
      railFill.style.height = ((current) / (sections.length - 1) * 100) + '%';
    }
  }
  window.addEventListener('scroll', updateRail, { passive: true });
  updateRail();

  railItems.forEach(function (item) {
    item.addEventListener('click', function (e) {
      e.preventDefault();
      var t = document.getElementById(item.dataset.target);
      if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  // Menüden gelen hash
  var targetId = window.location.hash ? window.location.hash.substring(1) : null;
  if (targetId) {
    var target = document.getElementById(targetId);
    if (target) setTimeout(function () { target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }, 250);
  }
})();
</script>
