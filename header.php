<?php
  ob_start();
  ini_set('session.cookie_httponly', '1');
  ini_set('session.cookie_secure', '1');
  ini_set('session.cookie_samesite', 'Lax');
  session_start();
  require_once 'adminx/system/db.php';
  require_once 'adminx/pages/functions.php';
  require_once 'adminx/system/auto-migrate.php';
  require_once 'adminx/system/analytics.php';
  adm_auto_migrate($db);   // eksik yardımcı tabloları ilk açılışta oluşturur
  require_once 'site-config.php';
  require_once 'func.php';
  // Production'da hata ayrıntılarını ziyaretçiye göstermeyin; sunucu loguna yazın.
  error_reporting(E_ALL);
  ini_set('display_errors', '0');
  ini_set('log_errors', '1');
  $rdRequestHost = strtolower((string)($_SERVER['HTTP_HOST'] ?? ''));
  $rdRequestHost = preg_replace('/:\d+$/', '', $rdRequestHost);
  $rdIsNonProduction = $rdRequestHost === ''
    || $rdRequestHost === 'localhost'
    || strpos($rdRequestHost, '127.0.0.1') === 0
    || strpos($rdRequestHost, 'test.') === 0;
  $rdAnalyticsAllowed = !$rdIsNonProduction && function_exists('adm_has_analytics_consent') && adm_has_analytics_consent();
  if ($rdIsNonProduction) {
    header('X-Robots-Tag: noindex, nofollow, noarchive', true);
  } else {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains', true);
  }
  function GetIP(){
   if(getenv("HTTP_CLIENT_IP")) {
     $ip = getenv("HTTP_CLIENT_IP");
   } elseif(getenv("HTTP_X_FORWARDED_FOR")) {
     $ip = getenv("HTTP_X_FORWARDED_FOR");
     if (strstr($ip, ',')) {
       $tmp = explode (',', $ip);
       $ip = trim($tmp[0]);
     }
   } else {
     $ip = getenv("REMOTE_ADDR");
   }
   return $ip;
  }
  $ip_adresi = GetIP();

  // Ziyaret istatistiği — bot/crawler'ları dışarıda bırak
  $rdUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
  if ($rdAnalyticsAllowed && $rdUserAgent !== '' && !preg_match('/bot|spider|crawl|slurp|facebookexternalhit/i', $rdUserAgent)) {
    list($rdSessionId, $rdIsNewVisitor) = adm_visitor_session();
    $rdAgent = adm_parse_user_agent($rdUserAgent);
    $rdViewParams = array(
      'url'      => mb_substr($_SERVER['REQUEST_URI'] ?? '/', 0, 255),
      'ip'       => $ip_adresi,
      'ua'       => mb_substr($rdUserAgent, 0, 255),
      'ref'      => mb_substr($_SERVER['HTTP_REFERER'] ?? '', 0, 255),
      'language' => $getLangCheck['language_id'] ?? 0,
    );
    try {
      $rdViewLog = $db->prepare("INSERT INTO page_views SET url=:url, ip=:ip, session_id=:sid,
        device=:device, browser=:browser, os=:os, is_new=:isnew,
        user_agent=:ua, referrer=:ref, language=:language, viewed_at=NOW()");
      $rdViewLog->execute($rdViewParams + array(
        'sid'     => $rdSessionId,
        'device'  => $rdAgent['device'],
        'browser' => $rdAgent['browser'],
        'os'      => $rdAgent['os'],
        'isnew'   => $rdIsNewVisitor,
      ));
    } catch (Exception $rdViewErr) {
      // Yeni kolonlar henüz oluşmadıysa (migration yetkisi yoksa) eski
      // şemayla kaydet; istatistik hiç durmasın.
      try {
        $rdViewLog = $db->prepare("INSERT INTO page_views SET url=:url, ip=:ip, user_agent=:ua, referrer=:ref, language=:language, viewed_at=NOW()");
        $rdViewLog->execute($rdViewParams);
      } catch (Exception $rdViewErr2) {
        // istatistik başarısız olsa da sayfa yüklenmeye devam etsin
      }
    }
  }
?>
<!DOCTYPE html>
<html dir="ltr" lang="<?php echo $getLangCheck['country_code']; ?>">
  <head>
    <base href="<?php echo rd_current_origin(); ?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php
      $x = explode("/", $_SERVER['SCRIPT_NAME']);
      $urlNumber = count($x);
      if ($urlNumber == 3) {
        if ($x[2]=="index.php"){
          echo '
            <title>'.htmlspecialchars($generalSettingCheck['site_title'], ENT_QUOTES, 'UTF-8').'</title>
            <meta name="keywords" content="'.htmlspecialchars($generalSettingCheck['site_keyw'], ENT_QUOTES, 'UTF-8').'">
            <meta name="description" content="'.htmlspecialchars($generalSettingCheck['site_desc'], ENT_QUOTES, 'UTF-8').'">
          ';
        }else{
          require_once 'meta.php';
        }
      }elseif($urlNumber == 2){
        if ($x[1]=="index.php"){
          echo '
            <title>'.htmlspecialchars($generalSettingCheck['site_title'], ENT_QUOTES, 'UTF-8').'</title>
            <meta name="keywords" content="'.htmlspecialchars($generalSettingCheck['site_keyw'], ENT_QUOTES, 'UTF-8').'">
            <meta name="description" content="'.htmlspecialchars($generalSettingCheck['site_desc'], ENT_QUOTES, 'UTF-8').'">
          ';
        }else{
          require_once 'meta.php';
        }
      }
    ?>
    <meta name="author" content="<?php echo $generalSettingCheck['site_author']; ?>"/>
    <meta name="copyright" content="<?php echo $generalSettingCheck['site_copyright']; ?>">
    <meta name="revisit-after" content="2 days">
    <?php
      $rdRobots = $rdIsNonProduction ? 'noindex,nofollow,noarchive' : 'index,follow';
      $rdCanonicalBase = $rdIsNonProduction ? rd_current_origin() : rtrim($generalSettingCheck['site_url'], '/') . '/';
      $rdCanonicalPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
      if ($rdCanonicalPath === '/index.php') $rdCanonicalPath = '/';
      $rdCanonicalUrl = $rdCanonicalBase . ltrim($rdCanonicalPath, '/');
    ?>
    <meta name="robots" content="<?= htmlspecialchars($rdRobots, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="canonical" href="<?= htmlspecialchars($rdCanonicalUrl, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="contact" content="<?php echo $generalSettingCheck['site_email']; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="google-site-verification" content="<?php echo $generalSettingCheck['site_webmastertools']; ?>"/>
    <link href="<?php echo $generalSettingCheck['site_favico']; ?>" rel="icon">

    <?php
      // Open Graph / Twitter Card — meta.php'nin bulduğu sayfaya özgü başlık/açıklama/görseli kullan,
      // yoksa genel site ayarlarına düş.
      $rdOgTitle = $generalSettingCheck['site_title'];
      $rdOgDesc  = $generalSettingCheck['site_desc'];
      $rdOgImage = $generalSettingCheck['site_url'] . ltrim($generalSettingCheck['site_logo_2'], '/');
      foreach (array('pagesCheck', 'postCheck', 'categoriesCheck', 'servicesCheck', 'productCheck', 'projectCheck', 'noticeCheck', 'galleryCheck') as $rdMetaVar) {
        if (!empty($$rdMetaVar['seo_title'])) {
          $rdOgTitle = $$rdMetaVar['seo_title'];
          $rdOgDesc  = !empty($$rdMetaVar['seo_description']) ? $$rdMetaVar['seo_description'] : $rdOgDesc;
          if (!empty($$rdMetaVar['banner'])) { $rdOgImage = $generalSettingCheck['site_url'] . ltrim($$rdMetaVar['banner'], '/'); }
          break;
        }
      }
      $rdOgUrl = $rdCanonicalUrl;
    ?>
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= htmlspecialchars($generalSettingCheck['site_title']) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($rdOgTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($rdOgDesc) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($rdOgImage) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($rdOgUrl) ?>">
    <meta property="og:locale" content="<?= $getLangCheck['country_code'] === 'en' ? 'en_US' : 'tr_TR' ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($rdOgTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($rdOgDesc) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($rdOgImage) ?>">
    <script type="application/ld+json">
    <?= json_encode(array(
      '@context' => 'https://schema.org',
      '@type' => 'Organization',
      'name' => $generalSettingCheck['site_title'] ?? 'ADM Plastik',
      'url' => $rdCanonicalBase,
      'logo' => $rdOgImage,
      'email' => $generalSettingCheck['site_email'] ?? '',
    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>
    </script>

    <!-- # flag-icon-css -->
    <link rel="stylesheet" href="adminx/assets/css/flag-icon.min.css">
    <!-- favicon -->
    <link rel=icon href=favicon.png sizes="20x20" type="image/png">
    <!-- flaticon -->
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <!-- Fonts Awesome Icons -->
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <!--Themefy Icons-->
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <!-- bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!--Mean Menu-->
    <link rel="stylesheet" href="assets/css/meanmenu.css">
    <!-- animate -->
    <link rel="stylesheet" href="assets/css/animate.css">
    <!--Video Popup-->
    <link rel="stylesheet" href="assets/css/rpopup.min.css">
    <!--Slick Carousel-->
    <link rel="stylesheet" href="assets/css/slick.css">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- responsive Stylesheet -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <!-- Redesign 2026: font + design system -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=Caveat:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/redesign.css?v=<?= @filemtime(__DIR__ . '/assets/css/redesign.css') ?: time() ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
    <?php if ($rdAnalyticsAllowed && !empty($generalSettingCheck['site_analytics'])): ?>
      <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo htmlspecialchars($generalSettingCheck['site_analytics'], ENT_QUOTES, 'UTF-8'); ?>"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= htmlspecialchars($generalSettingCheck['site_analytics'], ENT_QUOTES, 'UTF-8') ?>');
      </script>
    <?php endif; ?>

    <script src='https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>' async defer></script>
<?= $generalSettingCheck['header_code']; ?>
  </head>
<body>
  <!-- ============ Teklif Al Modalı (rd tasarım, site geneli) ============ -->
  <div class="rd-modal" id="rdExpertModal" aria-hidden="true">
    <div class="rd-modal-backdrop" data-rd-modal-close></div>
    <div class="rd-modal-card" role="dialog" aria-label="<?= $langArray['OFFER_TITLE2'] ?>">
      <button class="rd-modal-close" type="button" data-rd-modal-close aria-label="<?= $langArray['CANCEL_FORM'] ?>">×</button>
      <h3><?= $langArray['OFFER_TITLE2'] ?></h3>
      <form method="post" action="form-submit.php" class="rd-ajax-form"
            data-track="teklif-al"
            data-sending="<?= htmlspecialchars($langArray['RD_FORM_SENDING']) ?>"
            data-sent="<?= htmlspecialchars($langArray['RD_FORM_SENT']) ?>">
        <input type="hidden" name="type" value="offer">
        <div class="rd-form-row">
          <div class="rd-form-group">
            <label for="rdOfferName"><?= $langArray['NAME_SURNAME'] ?></label>
            <input type="text" id="rdOfferName" name="offer_name" autocomplete="name" required>
          </div>
          <div class="rd-form-group">
            <label for="rdOfferPhone"><?= $langArray['PHONE'] ?></label>
            <input type="tel" id="rdOfferPhone" name="offer_phone" inputmode="tel" autocomplete="tel" required>
          </div>
        </div>
        <div class="rd-form-row">
          <div class="rd-form-group">
            <label for="rdOfferEmail"><?= $langArray['EMAIL'] ?></label>
            <input type="email" id="rdOfferEmail" name="offer_email" inputmode="email" autocomplete="email" required>
          </div>
          <div class="rd-form-group">
            <label for="rdOfferService"><?= $langArray['RD_SERVICE'] ?></label>
            <select id="rdOfferService" name="offer_services">
              <?php
              // Language Posts
              $postAsk1111=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND language=:language ORDER BY order_sort");

                $postAsk1111->execute(array(
                  'post_type' => 'post_services',
                  'language'  => $getLangCheck['language_id']
                ));
                while ($offerServices=$postAsk1111->fetch(PDO::FETCH_ASSOC)) {
              ?>
                <option value="<?= $offerServices['post_id'] ?>"><?= $offerServices['title'] ?></option>
             <?php } ?>
            </select>
          </div>
        </div>
        <?php if (!empty($generalSettingCheck['site_recap_sitekey'])): ?>
        <div class="rd-form-group"><div class="g-recaptcha" data-sitekey="<?php echo $generalSettingCheck['site_recap_sitekey']; ?>"></div></div>
        <?php endif; ?>
        <button type="submit" class="rd-btn-primary rd-submit-btn">
          <span class="rd-submit-label"><?= $langArray['RD_GET_QUOTE'] ?></span>
          <span class="rd-submit-spinner" aria-hidden="true"></span>
          <svg class="rd-submit-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
        </button>
        <p class="rd-form-feedback" role="status" aria-live="polite"></p>
      </form>
    </div>
  </div>

  <!--Redesign 2026 header-->
  <a class="rd-skip-link" href="#rd-main"><?= $langArray['RD_SKIP_TO_CONTENT'] ?></a>
  <header class="rd-header">
    <a class="rd-logo" href="/">
      <img src="<?= $generalSettingCheck['site_logo_2'] ?>" alt="<?= $generalSettingCheck['site_title'] ?>">
    </a>
    <div class="rd-header-center">
      <ul class="rd-nav">
        <?php include('datainclude/menu.php'); ?>
      </ul>
      <form class="rd-search-form" id="rdSearchForm" action="searchpage.php" method="get">
        <input type="text" name="q" placeholder="<?= $langArray['RD_SEARCH_PLACEHOLDER'] ?>" autocomplete="off">
        <button type="submit" aria-label="<?= $langArray['RD_SEARCH'] ?>"><?= rd_icon('search') ?></button>
      </form>
    </div>
    <div class="rd-header-right">
      <button class="rd-search-toggle" id="rdSearchToggle" type="button" aria-label="<?= $langArray['RD_SEARCH'] ?>">
        <svg class="rd-search-icon-open" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <svg class="rd-search-icon-close" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
      </button>
      <?php
        // Dil değişince mevcut sayfanın çevirisi garanti olmadığından ana sayfaya yönlendiriyoruz
        // (yanlış dilde içerik gösterme riskini önler). Kök-göreli "/" kullanılır ki
        // site_url güncel olmayan bir adrese işaret etse bile bu link her zaman çalışsın.
        $rdSiteHome = '/';
      ?>
      <div class="rd-lang-switch">
        <a href="<?= $rdSiteHome ?>?lang=tr" class="<?= $lang == 'tr' ? 'rd-active' : '' ?>">TR</a>
        <span>/</span>
        <a href="<?= $rdSiteHome ?>?lang=en" class="<?= $lang == 'en' ? 'rd-active' : '' ?>">EN</a>
      </div>
      <button class="rd-nav-toggle" type="button" aria-label="Menü" onclick="document.querySelector('.rd-nav').classList.toggle('rd-nav-open')"><?= rd_icon('menu', 22) ?></button>
    </div>
    <div class="rd-progress-track"><div class="rd-progress-bar" id="rdProgressBar"></div></div>
  </header>
  <!-- "İçeriğe atla" hedefi: sayfa şablonları kendi sarmalayıcılarını
       açtığı için burada nötr bir çapa kullanılıyor. -->
  <span id="rd-main" tabindex="-1"></span>
  <!--// Redesign header end-->
