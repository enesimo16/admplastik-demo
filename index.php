<?php require_once 'header.php'; ?>
<?php
  // Hero sliders query
  $sliderAsk2 = $db->prepare("SELECT * FROM slider WHERE language=:language ORDER BY order_sort");
  $sliderAsk2->execute(array('language' => $getLangCheck['language_id']));
  if ($sliderAsk2->rowCount() == 0) {
    $trLangAskForSlider = $db->prepare("SELECT language_id FROM language WHERE country_code='tr'");
    $trLangAskForSlider->execute();
    $sliderAsk2->execute(array('language' => $trLangAskForSlider->fetchColumn()));
  }
  $sliders = $sliderAsk2->fetchAll(PDO::FETCH_ASSOC);
  $sliderCheck = $sliders[0] ?? array('banner' => '');

  $heroVideo = file_exists(__DIR__ . '/uploads/hero.mp4') ? 'uploads/hero.mp4' : '';

  // Sektörler (sticky stack + kalite kartları için)
  $homeServicesAsk = $db->prepare("SELECT * FROM posts WHERE post_type='post_services' AND language=:language AND status=1 ORDER BY order_sort");
  $homeServicesAsk->execute(array('language' => $getLangCheck['language_id']));
  $homeSectors = $homeServicesAsk->fetchAll(PDO::FETCH_ASSOC);



  // Yetkinlikler (kalite servis kartları) — seo_url ile bulunur, bkz. site-config.php
  $homeSkillKeys = array('skill-arge', 'skill-muh', 'skill-lab', 'skill-adt');
  $homeSkillsAsk = $db->prepare("SELECT * FROM posts WHERE seo_url IN ('" . implode("','", rd_slugs($homeSkillKeys)) . "')");
  $homeSkillsAsk->execute();
  $homeSkillRows = $homeSkillsAsk->fetchAll(PDO::FETCH_ASSOC);
  $homeSkills = array();
  foreach ($homeSkillKeys as $k) {
    foreach ($homeSkillRows as $row) {
      if ($row['seo_url'] === rd_slug($k)) {
        $row['title'] = rd_text($k, 'TITLE', $row['title']);
        $homeSkills[] = $row;
        break;
      }
    }
  }

  // Sektör başlıklarını arayüz diline çevir (içerik veritabanında tek dilde)
  foreach ($homeSectors as $i => $sec) {
    $k = rd_key_of($sec['seo_url']);
    if ($k) $homeSectors[$i]['title'] = rd_text($k, 'TITLE', $sec['title']);
  }

  $stackTones = array('rd-stack-dark', 'rd-stack-black', 'rd-stack-tint');

  $urlProducts = rd_url('products-index', 'sayfa-urunler');
  $urlContact  = rd_url('contact-index', 'sayfa-iletisim');
  $urlDocs     = rd_url('cert-index', 'sayfa-dokumanlar');
  $urlAbout    = rd_url('about-main', 'sayfa-hakkimizda');
  $urlSkills   = rd_url('skill-arge', 'sayfa-arge');
  $urlBlogCat  = rd_url('blog-category', 'kategori-blog-kategori');
?>
<div class="rd-page">

  <!-- ============ HERO (Piedmont style: full-bleed media + rotating words) ============ -->
  <section class="rd-hero-v2">
    <?php if ($heroVideo): ?>
      <video class="rd-hero-v2-media" autoplay muted loop playsinline poster="<?= $sliders[0]['banner'] ?? '' ?>">
        <source src="<?= $heroVideo ?>" type="video/mp4">
      </video>
    <?php else: ?>
      <!-- Slider terk edildi (kararlı biçimde çalışmadı) — tek sabit fotoğraf,
           yavaş yakınlaşma efektiyle. Görsel: sıralamada ilk slider kaydı. -->
      <div class="rd-hero-v2-media rd-hero-v2-media-static" style="background-image:url('<?= rd_img_url($sliderCheck['banner']) ?>')"></div>
    <?php endif; ?>
    <div class="rd-hero-v2-overlay"></div>
    <div class="rd-hero-v2-content">
      <span class="rd-hero-v2-eyebrow"><?= $langArray['RD_HERO_EYEBROW'] ?></span>
      <h1 class="rd-hero-v2-title">
        <span class="rd-rotator" id="rdHeroRotator">
          <span class="rd-rotator-word rd-active"><?= $langArray['RD_HERO_WORD_1'] ?></span>
          <span class="rd-rotator-word"><?= $langArray['RD_HERO_WORD_2'] ?></span>
          <span class="rd-rotator-word"><?= $langArray['RD_HERO_WORD_3'] ?></span>
          <span class="rd-rotator-word"><?= $langArray['RD_HERO_WORD_4'] ?></span>
          <span class="rd-rotator-word"><?= $langArray['RD_HERO_WORD_5'] ?></span>
        </span>
      </h1>
      <svg class="rd-hero-v2-brush" viewBox="0 0 400 12" preserveAspectRatio="none"><path d="M2 9 Q100 2 200 6 T398 4" fill="none" stroke="url(#rdBrushGrad)" stroke-width="4" stroke-linecap="round"/><defs><linearGradient id="rdBrushGrad" x1="0" x2="1"><stop offset="0" stop-color="#6FA562"/><stop offset="1" stop-color="#EAF3E8"/></linearGradient></defs></svg>
      <div class="rd-hero-v2-actions">
        <a href="#" class="rd-btn-primary" data-rd-modal-open><?= $langArray['RD_OFFER_CARD_1_TITLE'] ?></a>
        <a href="<?= $urlProducts ?>" class="rd-hero-v2-link"><?= $langArray['RD_EXPLORE_PRODUCTS'] ?> <span>→</span></a>
      </div>
    </div>
  </section>

  <!-- ============ 3'lü bilgi satırı ============ -->
  <section class="rd-inforow">
    <div class="rd-inforow-item">
      <div class="rd-inforow-head">
        <span class="rd-inforow-icon"><?= rd_icon('comments') ?></span>
        <h3><?= $langArray['RD_INFO_CONTACT_TITLE'] ?></h3>
      </div>
      <p><?= $langArray['RD_INFO_CONTACT_DESC'] ?></p>
      <a href="#" data-rd-modal-open class="rd-inforow-link"><?= $langArray['RD_TALK_EXPERT'] ?> <span>→</span></a>
    </div>
    <div class="rd-inforow-item">
      <div class="rd-inforow-head">
        <span class="rd-inforow-icon"><?= rd_icon('map-marker') ?></span>
        <h3><?= $langArray['RD_INFO_FACILITY_TITLE'] ?></h3>
      </div>
      <p><?= $langArray['RD_INFO_FACILITY_DESC'] ?></p>
      <a href="<?= $urlContact ?>" class="rd-inforow-link"><?= $langArray['RD_VISIT_US'] ?> <span>→</span></a>
    </div>
    <div class="rd-inforow-item">
      <div class="rd-inforow-head">
        <span class="rd-inforow-icon"><?= rd_icon('file-text') ?></span>
        <h3><?= $langArray['RD_INFO_CATALOG_TITLE'] ?></h3>
      </div>
      <p><?= $langArray['RD_INFO_CATALOG_DESC'] ?></p>
      <a href="<?= $urlDocs ?>" class="rd-inforow-link"><?= $langArray['RD_BROWSE_RESOURCES'] ?> <span>→</span></a>
    </div>
  </section>

  <!-- ============ Harita bölümü ============ -->
  <section class="rd-mapzone">
    <div class="rd-mapzone-map">
      <?php if (file_exists(__DIR__ . '/uploads/global-network-turkiye.mp4')): ?>
        <video class="rd-mapzone-video" autoplay muted loop playsinline>
          <source src="uploads/global-network-turkiye.mp4" type="video/mp4">
        </video>
      <?php else: ?>
        <div class="rd-map-area rd-map-area-light">
          <span><?= $langArray['RD_CONTACT_MAP_LABEL'] ?></span>
        </div>
      <?php endif; ?>
    </div>
    <div class="rd-mapzone-grid">
      <div class="rd-mapzone-left">
        <h2 class="rd-title-two-tone"><?= $langArray['RD_MAP_TITLE_L1'] ?><br><em><?= $langArray['RD_MAP_TITLE_L2'] ?></em></h2>
        <p><?= $langArray['RD_MAP_DESC'] ?></p>
        <a href="<?= $urlContact ?>" class="rd-btn-primary"><?= $langArray['RD_CONTACT_GET_IN_TOUCH'] ?></a>
      </div>
      <div class="rd-mapzone-right">
        <a class="rd-mapzone-bigcard" href="<?= $urlContact ?>">
          <?= rd_icon('globe', 24) ?>
          <strong data-countup>40+</strong>
          <span><?= $langArray['RD_EXPORT_COUNTRIES'] ?></span>
        </a>
        <div class="rd-mapzone-smallcards">
          <div class="rd-mapzone-smallcard"><?= rd_icon('cube') ?><strong><?= $langArray['RD_LOCAL'] ?></strong><span><?= $langArray['RD_LOCAL_DESC'] ?></span></div>
          <div class="rd-mapzone-smallcard"><?= rd_icon('industry') ?><strong><?= $langArray['RD_INTEGRATED'] ?></strong><span><?= $langArray['RD_INTEGRATED_DESC'] ?></span></div>
          <div class="rd-mapzone-smallcard"><?= rd_icon('flask') ?><strong><?= $langArray['RD_RND'] ?></strong><span><?= $langArray['RD_RND_DESC'] ?></span></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ Uzman çözümler kartı ============ -->
  <section class="rd-expertcard-wrap">
    <div class="rd-expertcard">
      <div class="rd-expertcard-text">
        <h2 class="rd-title-two-tone"><?= $langArray['RD_EXPERT_TITLE_L1'] ?><br><em><?= $langArray['RD_EXPERT_TITLE_L2'] ?></em></h2>
        <p><?= $langArray['RD_EXPERT_DESC'] ?></p>
        <a href="#" class="rd-btn-primary" data-rd-modal-open><?= $langArray['RD_TALK_EXPERT'] ?></a>
      </div>
      <div class="rd-expertcard-photo" style="background-image:url('<?= rd_img_url('uploads/site-photo-fabrika.jpg') ?>');background-size:cover;background-position:center;">
      </div>
    </div>
  </section>

  <!-- ============ Sticky sektör kartları ============ -->
  <div class="rd-stack-wrap rd-stack-home">
    <?php foreach ($homeSectors as $i => $sec):
      $tone = $stackTones[$i % count($stackTones)];
      $top = 90 + ($i * 24);
    ?>
    <section class="rd-stack-card <?= $tone ?>" style="top:<?= $top ?>px; z-index:<?= $i + 1 ?>; background-image:url('<?= $sec['banner'] ?>')">
      <div class="rd-stack-overlay"></div>
      <div class="rd-stack-content">
        <span class="rd-eyebrow"><?= $langArray['RD_SOLUTION_AREAS'] ?></span>
        <h2><?= $sec['title'] ?></h2>
        <div class="rd-stack-text"><?= mb_strimwidth(strip_tags($sec['content']), 0, 220, '...') ?></div>
        <a href="<?= $sec['seo_url'] ?>" class="rd-btn-primary" style="margin-top:20px"><?= $langArray['RD_DETAILS'] ?></a>
      </div>
    </section>
    <?php endforeach; ?>
  </div>



  <!-- ============ Üretim süreci: hammaddeden son ürüne ============ -->
  <section class="rd-block rd-white rd-process">
    <div class="rd-container">
      <div class="rd-process-head">
        <h2 class="rd-title-two-tone"><?= $langArray['RD_PROCESS_TITLE_L1'] ?><br><em><?= $langArray['RD_PROCESS_TITLE_L2'] ?></em></h2>
        <p><?= $langArray['RD_PROCESS_DESC'] ?></p>
      </div>
      <div class="rd-process-flow">
        <div class="rd-process-line"><div class="rd-process-line-fill"></div></div>
        <?php
          $rdProcessSteps = array(
            array('icon' => 'granule', 'title' => $langArray['RD_PROCESS_STEP1_TITLE'], 'desc' => $langArray['RD_PROCESS_STEP1_DESC']),
            array('icon' => 'cogs',    'title' => $langArray['RD_PROCESS_STEP2_TITLE'], 'desc' => $langArray['RD_PROCESS_STEP2_DESC']),
            array('icon' => 'flask',   'title' => $langArray['RD_PROCESS_STEP3_TITLE'], 'desc' => $langArray['RD_PROCESS_STEP3_DESC']),
            array('icon' => 'package', 'title' => $langArray['RD_PROCESS_STEP4_TITLE'], 'desc' => $langArray['RD_PROCESS_STEP4_DESC']),
          );
          foreach ($rdProcessSteps as $rdStepIndex => $rdStep):
        ?>
          <div class="rd-process-step">
            <span class="rd-process-num"><?= $rdStepIndex + 1 ?></span>
            <span class="rd-process-icon"><?= rd_icon($rdStep['icon'], 26) ?></span>
            <h3><?= $rdStep['title'] ?></h3>
            <p><?= $rdStep['desc'] ?></p>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Kaydırdıkça granüller dağılır, yerini ürün taslağı çizilerek alır -->
      <div class="rd-process-shape">
        <span class="rd-shape-glow" aria-hidden="true"></span>
        <svg viewBox="0 0 300 170" class="rd-shape-svg" aria-hidden="true">
          <title><?= htmlspecialchars($langArray['RD_PROCESS_SHAPE_LABEL']) ?></title>
          <g class="rd-shape-granules">
            <circle cx="90" cy="70" r="9"/>
            <circle cx="130" cy="48" r="12"/>
            <circle cx="172" cy="76" r="7"/>
            <circle cx="150" cy="102" r="10"/>
            <circle cx="203" cy="54" r="8"/>
            <circle cx="108" cy="108" r="6"/>
            <circle cx="187" cy="108" r="11"/>
            <circle cx="222" cy="86" r="7"/>
            <circle cx="140" cy="80" r="5"/>
          </g>
          <g class="rd-shape-product">
            <rect class="rd-shape-body" x="70" y="55" width="170" height="75" rx="16"/>
            <rect class="rd-shape-tab" x="140" y="28" width="26" height="30" rx="7"/>
            <circle class="rd-shape-hole" cx="105" cy="92" r="10"/>
            <circle class="rd-shape-hole" cx="205" cy="92" r="10"/>
          </g>
        </svg>
        <span class="rd-process-shape-label"><?= $langArray['RD_PROCESS_SHAPE_LABEL'] ?></span>
      </div>
    </div>
  </section>

  <!-- ============ Firma/Partner logo marquee ============ -->
  <?php
    $rdRefAsk = $db->prepare("SELECT name, banner, link FROM reference WHERE status=1 AND language=:language ORDER BY order_sort ASC");
    $rdRefAsk->execute(array('language' => $getLangCheck['language_id']));
    $rdRefRows = $rdRefAsk->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <?php if ($rdRefRows): ?>
  <section class="rd-suppliers">
    <div class="rd-container rd-suppliers-head">
      <div>
        <h2 class="rd-title-two-tone"><?= $langArray['RD_PARTNERS_TITLE'] ?></h2>
        <p><?= $langArray['RD_PARTNERS_DESC'] ?></p>
      </div>
    </div>
    <div class="rd-marquee rd-marquee-light">
      <div class="rd-marquee-track rd-marquee-left">
        <?php for ($g = 0; $g < 2; $g++): ?>
        <div class="rd-marquee-group">
          <?php foreach ($rdRefRows as $ref): ?>
            <?php if ($ref['link']): ?><a href="<?= $ref['link'] ?>" target="_blank" rel="noopener" class="rd-supplier-logo"><?php else: ?><span class="rd-supplier-logo"><?php endif; ?>
              <img src="<?= $ref['banner'] ?>" alt="<?= $ref['name'] ?>" loading="lazy">
            <?= $ref['link'] ? '</a>' : '</span>' ?>
          <?php endforeach; ?>
        </div>
        <?php endfor; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ============ Müşteri Yorumları ============ -->
  <?php
    $rdCommentAsk = $db->prepare("SELECT name, mission, comment FROM comments WHERE status=1 AND language=:language ORDER BY order_sort ASC");
    $rdCommentAsk->execute(array('language' => $getLangCheck['language_id']));
    $rdCommentRows = $rdCommentAsk->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <?php if ($rdCommentRows): ?>
  <section class="rd-block rd-white">
    <div class="rd-container">
      <div class="rd-research-head">
        <h2 class="rd-title-two-tone"><?= $langArray['RD_TESTIMONIALS_TITLE_L1'] ?><br><em><?= $langArray['RD_TESTIMONIALS_TITLE_L2'] ?></em></h2>
        <p><?= $langArray['RD_TESTIMONIALS_DESC'] ?></p>
      </div>
      <div class="rd-testimonial-grid">
        <?php foreach ($rdCommentRows as $rdC): ?>
          <div class="rd-testimonial-card">
            <span class="rd-testimonial-quote">&ldquo;</span>
            <p class="rd-testimonial-text"><?= $rdC['comment'] ?></p>
            <div class="rd-testimonial-author">
              <div class="rd-testimonial-avatar"><?= mb_strtoupper(mb_substr($rdC['name'], 0, 1)) ?></div>
              <div class="rd-testimonial-who">
                <strong><?= $rdC['name'] ?></strong>
                <span><?= $rdC['mission'] ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ============ Son Makaleler ============ -->
  <section class="rd-block rd-white">
    <div class="rd-container">
      <div class="rd-research-head">
        <h2 class="rd-title-two-tone"><?= $langArray['RD_LATEST_NEWS'] ?></h2>
        <a href="<?= $urlBlogCat ?>" class="rd-btn-primary"><?= $langArray['RD_VIEW_ALL_NEWS'] ?></a>
      </div>
      <div class="rd-carousel-row">
        <?php
          $homeNewsAsk->execute(array(
            'language'    => $getLangCheck['language_id'],
            'status'      => 1,
            'post_type'   => 'post_post',
            'home_status' => 1
          ));
          while ($newsCheck = $homeNewsAsk->fetch(PDO::FETCH_ASSOC)):
        ?>
          <a class="rd-blog-card" href="<?= $newsCheck['seo_url'] ?>">
            <?= rd_image($newsCheck["banner"], $newsCheck["title"]) ?>
            <h3><?= $newsCheck['title'] ?></h3>
            <p class="rd-blog-excerpt"><?= rd_excerpt($newsCheck) ?></p>
            <span class="rd-link"><?= $langArray['READ_MORE'] ?> <span>→</span></span>
          </a>
        <?php endwhile; ?>
      </div>
    </div>
  </section>

  <!-- ============ Haber bülteni ============ -->
  <section class="rd-newsletter">
    <div class="rd-newsletter-inner">
      <h2><?= $langArray['RD_NEWSLETTER_TITLE'] ?></h2>
      <form class="rd-newsletter-form" id="rdNewsletterForm"
        data-track="bulten-abonelik"
        data-success="<?= htmlspecialchars($langArray['RD_NEWSLETTER_SUCCESS']) ?>"
        data-error="<?= htmlspecialchars($langArray['RD_NEWSLETTER_ERROR']) ?>"
        data-lang="<?= $getLangCheck['language_id'] ?>">
        <span class="rd-newsletter-icon"><?= rd_icon('mail') ?></span>
        <input type="email" name="email" required placeholder="<?= $langArray['RD_EMAIL_PLACEHOLDER'] ?>" aria-label="<?= $langArray['RD_EMAIL_PLACEHOLDER'] ?>">
        <button type="submit" class="rd-newsletter-btn"><?= $langArray['RD_SUBSCRIBE'] ?></button>
      </form>
      <p class="rd-newsletter-msg" id="rdNewsletterMsg" role="status"></p>
    </div>
  </section>

  <!-- ============ Sektör bandı (tam genişlik foto) ============ -->
  <section class="rd-industry-banner" style="background-image:url('<?= rd_img_url($sliderCheck['banner'] ?? '') ?>')">
    <div class="rd-industry-banner-overlay"></div>
    <div class="rd-industry-banner-content">
      <h2><span class="rd-light"><?= $langArray['RD_INDUSTRY_BANNER_L1'] ?></span><br><span class="rd-strong"><?= $langArray['RD_INDUSTRY_BANNER_L2'] ?></span></h2>
      <div class="rd-industry-banner-actions">
        <a href="<?= $urlProducts ?>" class="rd-btn-primary"><?= $langArray['RD_EXPLORE_MORE'] ?></a>
        <a href="<?= $urlContact ?>" class="rd-industry-mail"><?= rd_icon('mail') ?></a>
      </div>
    </div>
  </section>

  <!-- ============ Yılların kalite hizmeti + yetkinlik kartları ============ -->
  <section class="rd-block rd-quality">
    <div class="rd-container">
      <div class="rd-quality-top">
        <div class="rd-quality-photo" style="background-image:url('<?= rd_img_url('uploads/quality_assurance.jpg') ?>');background-size:cover;background-position:center;"></div>
        <div class="rd-quality-text">
          <h2 class="rd-title-two-tone"><?= $langArray['RD_QUALITY_TITLE_L1'] ?><br><em><?= $langArray['RD_QUALITY_TITLE_L2'] ?></em></h2>
          <a href="<?= $urlSkills ?>" class="rd-btn-primary"><?= $langArray['RD_VIEW_ALL_SKILLS'] ?></a>
        </div>
      </div>
      <div class="rd-quality-cards">
        <?php foreach ($homeSkills as $skill): ?>
          <a class="rd-quality-card" href="<?= $skill['seo_url'] ?>" style="background-image:url('<?= rd_img_url($skill['banner']) ?>')">
            <span class="rd-quality-card-title"><?= $skill['title'] ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ SSS ============ -->
  <section class="rd-block rd-white">
    <div class="rd-container">
      <div class="rd-faqbanner">
        <div>
          <h2 class="rd-title-two-tone"><?= $langArray['RD_FAQ_TITLE_L1'] ?><br><em><?= $langArray['RD_FAQ_TITLE_L2'] ?></em></h2>
          <p><?= $langArray['RD_FAQ_DESC'] ?></p>
        </div>
      </div>
      <div class="rd-faq rd-faq-2col">
        <?php foreach (rd_faqs($db) as $rdFaqRow): ?>
          <div class="rd-faq-item"><button class="rd-faq-q"><?= $rdFaqRow['q'] ?> <span class="rd-plus">+</span></button><div class="rd-faq-a"><p><?= $rdFaqRow['a'] ?></p></div></div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ Uzman CTA bandı ============ -->
  <section class="rd-expertband">
    <div class="rd-expertband-grid"></div>
    <div class="rd-expertband-content">
      <h2><?= $langArray['RD_EXPERT_BAND_TITLE_PRE'] ?> <em><?= $langArray['RD_EXPERT_BAND_TITLE_EM'] ?></em> <?= $langArray['RD_EXPERT_BAND_TITLE_POST'] ?></h2>
      <p><?= $langArray['RD_EXPERT_BAND_DESC'] ?></p>
      <a href="#" class="rd-btn-white" data-rd-modal-open><?= $langArray['RD_LETS_TALK'] ?> →</a>
    </div>
  </section>

</div>

<script>
// DOMContentLoaded'a kadar bekle: bu script index.php içinde, gsap/ScrollTrigger
// ise sayfanın en altında footer.php'de yükleniyor — script sırasına göre
// html <script> etiketleri bunlardan ÖNCE çalışırdı ve typeof gsap==='undefined'
// koruması yüzünden tüm kaydırma animasyonları (bu da dahil, yenisi de eskisi de)
// sessizce hiç çalışmazdı. DOMContentLoaded, aşağıdaki tüm senkron script'ler
// (footer.php'dekiler dahil) çalıştıktan SONRA tetiklenir.
document.addEventListener('DOMContentLoaded', function () {
  // Hero rotating words
  var words = document.querySelectorAll('#rdHeroRotator .rd-rotator-word');
  if (words.length > 1) {
    var wi = 0;
    setInterval(function () {
      words[wi].classList.remove('rd-active');
      wi = (wi + 1) % words.length;
      words[wi].classList.add('rd-active');
    }, 2800);
  }

  // FAQ accordion
  document.querySelectorAll('.rd-faq-item').forEach(function (item) {
    item.querySelector('.rd-faq-q').addEventListener('click', function () {
      var wasOpen = item.classList.contains('rd-open');
      document.querySelectorAll('.rd-faq-item').forEach(function (i) { i.classList.remove('rd-open'); });
      if (!wasOpen) item.classList.add('rd-open');
    });
    item.classList.add('rd-in-view');
  });

  // GSAP entrances
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);
    gsap.fromTo('.rd-hero-v2-content > *', { opacity: 0, y: 22 }, { opacity: 1, y: 0, duration: .7, stagger: .14, delay: .15, ease: 'power2.out' });
    gsap.fromTo('.rd-inforow-item', { opacity: 0, y: 28 }, { opacity: 1, y: 0, duration: .55, stagger: .12, ease: 'power2.out', scrollTrigger: { trigger: '.rd-inforow', start: 'top 85%' } });
    gsap.fromTo('.rd-research-card', { opacity: 0, y: 32 }, { opacity: 1, y: 0, duration: .55, stagger: .12, ease: 'power2.out', scrollTrigger: { trigger: '.rd-research-cards', start: 'top 85%' } });
    gsap.fromTo('.rd-quality-card', { opacity: 0, scale: .94 }, { opacity: 1, scale: 1, duration: .5, stagger: .1, ease: 'back.out(1.5)', scrollTrigger: { trigger: '.rd-quality-cards', start: 'top 85%' } });

    // Sektör bandı parallax efekti: resim aşağı kayarken yavaşça dikey pozisyon değiştirir.
    gsap.fromTo('.rd-industry-banner', 
      { backgroundPosition: '50% 12%' }, 
      { 
        backgroundPosition: '50% 88%', 
        ease: 'none', 
        scrollTrigger: {
          trigger: '.rd-industry-banner',
          start: 'top bottom',
          end: 'bottom top',
          scrub: true
        }
      }
    );

    // Üretim süreci: kartlar bölüm göründüğünde belirir; oradan sonra kaydırmadan
    // TAMAMEN bağımsız, kendi kendine sürekli tekrar eden bir döngü çalışır —
    // granüller toplanıp ürüne dönüşür, biraz bekler, tekrar granüle döner.
    // Kullanıcı üzerinde dursa/fareyi çekse de duraklamaz, hiçbir zaman durmaz.
    gsap.fromTo('.rd-process-step', { opacity: 0, y: 24 }, { opacity: 1, y: 0, duration: .5, stagger: .12, ease: 'power2.out', scrollTrigger: { trigger: '.rd-process-flow', start: 'top 85%' } });
    gsap.fromTo('.rd-process-shape', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: .6, delay: .15, ease: 'power2.out', scrollTrigger: { trigger: '.rd-process-flow', start: 'top 85%' } });

    var rdProcessSection = document.querySelector('.rd-process');
    var rdProcessSteps = document.querySelectorAll('.rd-process-step');
    var rdShapeBody = document.querySelector('.rd-shape-body');
    var rdShapeTab = document.querySelector('.rd-shape-tab');
    var rdShapeHoles = document.querySelectorAll('.rd-shape-hole');

    var rdBodyLen = rdShapeBody ? rdShapeBody.getTotalLength() : 0;
    var rdTabLen = rdShapeTab ? rdShapeTab.getTotalLength() : 0;
    var rdHoleLen = rdShapeHoles.length > 0 ? rdShapeHoles[0].getTotalLength() : 0;

    if (rdShapeBody) { rdShapeBody.style.strokeDasharray = rdBodyLen; rdShapeBody.style.strokeDashoffset = rdBodyLen; }
    if (rdShapeTab) { rdShapeTab.style.strokeDasharray = rdTabLen; rdShapeTab.style.strokeDashoffset = rdTabLen; }
    rdShapeHoles.forEach(function (hole) { hole.style.strokeDasharray = rdHoleLen; hole.style.strokeDashoffset = rdHoleLen; });

    // p: 0 = ham granül yığını, 1 = tamamlanmış parça. Tek yerden uygulanır ki
    // çizgi dolumu / adım vurgusu / granül-ürün geçişi hep aynı değeri okusun.
    function rdApplyProcessProgress(p) {
      if (rdProcessSection) rdProcessSection.style.setProperty('--rd-p', p);
      var activeCount = Math.round(p * rdProcessSteps.length);
      rdProcessSteps.forEach(function (step, i) { step.classList.toggle('is-active', i < activeCount); });
      
      // Sıralı çizim animasyonu (Sequential Drawing):
      // 1. Aşama: İlk %60'lık süreçte ana gövde çizilir.
      var bodyP = Math.min(1, p / 0.6);
      if (rdShapeBody) rdShapeBody.style.strokeDashoffset = rdBodyLen * (1 - bodyP);

      // 2. Aşama: %60 ile %80 arasında enjeksiyon kulakçığı çizilir.
      var tabP = p > 0.6 ? Math.min(1, (p - 0.6) / 0.2) : 0;
      if (rdShapeTab) rdShapeTab.style.strokeDashoffset = rdTabLen * (1 - tabP);

      // 3. Aşama: %80 ile %100 arasında iç montaj delikleri çizilir.
      var holeP = p > 0.8 ? Math.min(1, (p - 0.8) / 0.2) : 0;
      rdShapeHoles.forEach(function (hole) { hole.style.strokeDashoffset = rdHoleLen * (1 - holeP); });

      // Enjeksiyon erime ve kalıp dolumu: %70'ten sonra kalıp içine renk akışı olur
      var fillOpacity = Math.max(0, (p - 0.7) / 0.3) * 0.32;
      if (rdShapeBody) rdShapeBody.style.fillOpacity = fillOpacity;
      if (rdShapeTab) rdShapeTab.style.fillOpacity = fillOpacity;

      // Kaynaşma parlaması (Fusion Flash): %95-%100 arasında kalıp bir anlık parlar
      var isFused = (p >= 0.95);
      if (rdProcessSection) rdProcessSection.classList.toggle('is-fused', isFused);
    }

    var rdPrefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (rdProcessSection && rdProcessSteps.length && rdPrefersReducedMotion) {
      // Hareket azaltma tercih edilmişse sonsuz döngüyü hiç başlatma —
      // tamamlanmış parçayı sabit göster, hâlâ ne anlattığı anlaşılsın.
      rdApplyProcessProgress(1);
    } else if (rdProcessSection && rdProcessSteps.length) {
      var rdProcessObj = { p: 0 };
      var rdProcessTl = gsap.timeline({ repeat: -1, paused: true });
      rdProcessTl
        .to(rdProcessObj, { p: 1, duration: 2.8, ease: 'power2.inOut', onUpdate: function () { rdApplyProcessProgress(rdProcessObj.p); } })
        .to(rdProcessObj, { p: 1, duration: 1.4 })   // tamamlanmış parça olarak bekler
        .to(rdProcessObj, { p: 0, duration: 1.7, ease: 'power2.inOut', onUpdate: function () { rdApplyProcessProgress(rdProcessObj.p); } })
        .to(rdProcessObj, { p: 0, duration: 0.6 });  // yeniden başlamadan önce ham granül olarak bekler

      // Ekranda hiç görünmeyen bir bölümde sonsuza dek animasyon çalıştırmak
      // gereksiz; bölüm ilk kez görüldüğünde başlatılır, bir daha durmaz.
      if ('IntersectionObserver' in window) {
        var rdProcessIo = new IntersectionObserver(function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              rdProcessTl.play();
              rdProcessIo.disconnect();
            }
          });
        }, { threshold: 0.15 });
        rdProcessIo.observe(rdProcessSection);
      } else {
        rdProcessTl.play();
      }
    }
  }
});
</script>

<?php require_once 'footer.php'; ?>
