<?php

  /**
   * Site genelinde kullanılan tek ikon seti (outline/stroke SVG, footer.php'deki
   * back-to-top/chat ikonlarıyla aynı stil). Font Awesome yerine bunu kullanın.
   */
  function rd_icon($name, $size = 20) {
    $paths = array(
      'phone'      => '<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>',
      'mail'       => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/>',
      'comments'   => '<path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>',
      'map-marker' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>',
      'file-text'  => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
      'globe'      => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>',
      'cube'       => '<path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
      'industry'   => '<path d="M3 21V10l6 4v-4l6 4V7l6 4v10H3z"/><line x1="7" y1="21" x2="7" y2="17"/><line x1="13" y1="21" x2="13" y2="17"/>',
      'flask'      => '<path d="M9 2v6L3 20a1 1 0 001 2h16a1 1 0 001-2L15 8V2"/><line x1="8" y1="2" x2="16" y2="2"/>',
      'cogs'       => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>',
      'search'     => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
      'granule'    => '<circle cx="7" cy="7" r="2.3"/><circle cx="16" cy="6" r="2"/><circle cx="12" cy="13" r="2.6"/><circle cx="18" cy="15" r="1.8"/><circle cx="6" cy="16" r="1.8"/>',
      'package'    => '<path d="M21 8l-9-5-9 5 9 5 9-5z"/><path d="M3 8v8l9 5 9-5V8"/><line x1="12" y1="13" x2="12" y2="21"/>',
      'menu'       => '<line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>',
    );
    $inner = $paths[$name] ?? $paths['cube'];
    return '<svg width="' . (int)$size . '" height="' . (int)$size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $inner . '</svg>';
  }

  /**
   * Bir sayfanın seo_url'ini o anki dile (veya verilen $langId'ye) göre bulur.
   * Sayfa çevirisi (trans_group) yoksa $fallback'e düşer.
   */
  function rd_url($pageKey, $fallback, $dbConn = null, $langId = null) {
    // Site tek URL setiyle çalışır (Türkçe adresler); dil sadece içeriği değiştirir.
    // Eşleşme site-config.php'dedir, veritabanı sorgusu gerekmez.
    return rd_slug($pageKey, $fallback);
  }

  /**
   * Sayfa başlığı/içeriğini o anki dile göre döndürür.
   *
   * Türkçe içerik veritabanından (admin panelden düzenlenebilir) gelir.
   * İngilizce içerik dil dosyasındadır (adminx/lang/en.php) — çeviri yoksa
   * Türkçesine düşer, böylece sayfa asla boş kalmaz.
   *
   * @param string $pageKey  site-config.php'deki anahtar (örn. 'skill-arge')
   * @param string $field    'TITLE' | 'CONTENT'
   * @param string $dbValue  Veritabanından gelen (Türkçe) değer
   */
  function rd_text($pageKey, $field, $dbValue) {
    if (($GLOBALS['lang'] ?? 'tr') !== 'en' || !$pageKey) return $dbValue;
    $key = 'RD_PAGE_' . strtoupper($field) . '_' . $pageKey;
    $translated = $GLOBALS['langArray'][$key] ?? '';
    return $translated !== '' ? $translated : $dbValue;
  }

  /**
   * WhatsApp numarasını wa.me formatına çevirir (ülke kodlu, başında sıfır yok).
   *
   * Panelde numara "0532 000 00 00" gibi yerel formatta girilebiliyor; wa.me
   * bu formatı kabul etmediği için link sessizce çalışmıyordu.
   *
   * @param string $raw   Panelden gelen numara
   * @param string $cc    Varsayılan ülke kodu (Türkiye: 90)
   * @return string       Sadece rakam; geçersizse boş string
   */
  /**
   * Sosyal medya bağlantısı gerçek bir adres mi?
   *
   * Tema kurulumundan kalan "http://facebook.com" / "http://instagram" gibi
   * yer tutucular veritabanında duruyor olabilir. Bunlar footer'da göründüğünde
   * ziyaretçiyi ya boş bir sayfaya ya da platformun ana sayfasına atar; hiç
   * göstermemek daha iyidir. Gerçek bir profil adresi en az bir yol parçası
   * (facebook.com/admplastik gibi) taşır.
   */
  function rd_is_real_social_link($url) {
    $url = trim((string)$url);
    if ($url === '') return false;

    $host = parse_url($url, PHP_URL_HOST);
    // Nokta içermeyen host geçersizdir (ör. "http://instagram")
    if (!$host || strpos($host, '.') === false) return false;

    $path = trim((string)parse_url($url, PHP_URL_PATH), '/');
    $query = (string)parse_url($url, PHP_URL_QUERY);

    // Sadece ana sayfa verilmişse (facebook.com) profil adresi değildir
    return $path !== '' || $query !== '';
  }

  /**
   * O anki isteği sunan gerçek adres (şema + host), sonunda / ile.
   *
   * <base href> için kullanılır. Kayıtlı site_url'e SABİT bağlı KALMAZ:
   * site şu an geçici bir tünelden (cloudflare quick tunnel vb.) veya
   * localhost'tan sunuluyor olabilir; site_url veritabanda güncel olmayan
   * eski bir adrese işaret ediyorsa tüm CSS/JS/görseller kırılır (tünel
   * öldüğünde tam olarak bu oldu). Bu fonksiyon her zaman tarayıcının o an
   * konuştuğu host'u döndürür, böylece <base> asla ölü bir adrese kilitlenmez.
   *
   * og:url / og:image / sitemap gibi ARAMA MOTORUNA gidenler bunu KULLANMAZ —
   * onlar bilerek sabit $generalSettingCheck['site_url']'e bağlı kalır,
   * çünkü kalıcı/indekslenebilir bir adres olmaları gerekir.
   */
  function rd_current_origin() {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');
    $scheme = $isHttps ? 'https' : 'http';
    $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';
    // Header enjeksiyonuna karşı: host yalnızca izin verilen karakterleri içerebilir
    $host = preg_replace('/[^a-zA-Z0-9.\-:]/', '', $host);
    return $scheme . '://' . $host . '/';
  }

  function rd_whatsapp_number($raw, $cc = '90') {
    $d = preg_replace('/\D/', '', (string)$raw);
    if ($d === '') return '';

    if (strpos($d, '00') === 0)      $d = substr($d, 2);   // 0090... → 90...
    if (strpos($d, '0') === 0)       $d = $cc . substr($d, 1); // 0532... → 90532...
    elseif (strlen($d) === 10)       $d = $cc . $d;         // 532...   → 90532...

    // Ülke kodu + numara en az 11, en fazla 15 hane olmalı (E.164)
    return (strlen($d) >= 11 && strlen($d) <= 15) ? $d : '';
  }

  /**
   * Kart/liste özeti: ERP'den gelen özet (excerpt → seo_description) varsa
   * onu, yoksa içeriğin ilk cümlelerini kullanır.
   */
  function rd_excerpt($row, $limit = 110) {
    $summary = trim(strip_tags((string)($row['seo_description'] ?? '')));
    if ($summary === '') {
      $summary = trim(strip_tags((string)($row['content'] ?? '')));
    }
    return mb_strimwidth($summary, 0, $limit, '...');
  }

  /**
   * Görsel etiketi üretir; görsel yoksa kırık resim yerine desenli bir
   * yer tutucu döndürür. (ERP'den görselsiz içerik gelebilir.)
   *
   * @param string $path  uploads/... yolu, boş olabilir
   * @param string $alt   alternatif metin
   * @param string $class opsiyonel ek sınıf
   */
  function rd_image($path, $alt = '', $class = '') {
    $path = trim((string)$path);
    $altAttr = htmlspecialchars($alt, ENT_QUOTES);
    $clsAttr = $class ? ' ' . htmlspecialchars($class, ENT_QUOTES) : '';
    if ($path === '') {
      return '<span class="rd-img-placeholder' . $clsAttr . '" role="img" aria-label="' . $altAttr . '">'
           . rd_icon('cube', 30) . '</span>';
    }
    $url = rd_img_url($path);
    return '<img src="' . htmlspecialchars($url, ENT_QUOTES) . '" alt="' . $altAttr . '"'
         . ($class ? ' class="' . htmlspecialchars($class, ENT_QUOTES) . '"' : '') . ' loading="lazy">';
  }

  /**
   * Bir tabloda kolon var mı? (sonuç istek boyunca önbelleklenir)
   *
   * Sonradan eklenen kolonlar (örn. offer.created_at) canlıda henüz
   * oluşturulmamış olabilir; sorgular buna göre uyarlanır.
   */
  function rd_has_column($dbConn, $table, $column) {
    static $cache = array();
    $ck = $table . '.' . $column;
    if (isset($cache[$ck])) return $cache[$ck];
    try {
      $ask = $dbConn->prepare("SHOW COLUMNS FROM `" . str_replace('`', '', $table) . "` LIKE :col");
      $ask->execute(array('col' => $column));
      $cache[$ck] = (bool)$ask->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      $cache[$ck] = false;
    }
    return $cache[$ck];
  }

  /**
   * Menü etiketini arayüz diline çevirir.
   *
   * Menü veritabanından gelir ve tek dilde (Türkçe) tutulur. İngilizce
   * karşılıkları dil dosyasındaki RD_MENU_* anahtarlarındadır; karşılığı
   * olmayan etiket Türkçe kalır (menü asla boş görünmez).
   */
  function rd_menu_label($title) {
    if (($GLOBALS['lang'] ?? 'tr') !== 'en') return $title;
    $map = $GLOBALS['langArray']['RD_MENU_MAP'] ?? array();
    $norm = mb_strtolower(trim($title), 'UTF-8');
    return $map[$norm] ?? $title;
  }

  /**
   * SSS listesi: admin panelde kayıt varsa onlar, yoksa dil dosyasındaki
   * varsayılanlar kullanılır. Böylece veritabanına kayıt eklemek zorunlu değil,
   * ama panelden eklenirse otomatik devreye girer.
   *
   * @return array [['q' => soru, 'a' => cevap], ...]
   */
  function rd_faqs($dbConn = null, $limit = 8) {
    $conn = $dbConn ?: $GLOBALS['db'];
    $langArray = $GLOBALS['langArray'] ?? array();
    $uiLang = $GLOBALS['lang'] ?? 'tr';
    $out = array();

    // Önce arayüz dilinin KENDİ kayıtlarını ara. Böylece İngilizce arayüzde
    // Türkçe SSS gösterilmez; o dilde kayıt yoksa dil dosyasına düşülür.
    try {
      $langIdAsk = $conn->prepare("SELECT language_id FROM language WHERE country_code=:c LIMIT 1");
      $langIdAsk->execute(array('c' => $uiLang));
      $uiLangId = $langIdAsk->fetchColumn();

      if ($uiLangId) {
        $ask = $conn->prepare("SELECT title, content FROM posts WHERE post_type='post_faqs' AND status=1 AND language=:lg ORDER BY order_sort ASC");
        $ask->execute(array('lg' => $uiLangId));
        foreach ($ask->fetchAll(PDO::FETCH_ASSOC) as $row) {
          $out[] = array('q' => $row['title'], 'a' => $row['content']);
        }
      }
    } catch (Exception $e) {
      // sorun olursa dil dosyasına düşülür
    }

    if (!$out) {
      for ($i = 1; $i <= $limit; $i++) {
        $q = $langArray['RD_FAQ_Q' . $i] ?? '';
        $a = $langArray['RD_FAQ_A' . $i] ?? '';
        if ($q === '') continue;
        $out[] = array('q' => $q, 'a' => $a);
      }
    }
    return $out;
  }

  // Language Check
  $generalSettingAsk=$db->prepare("SELECT * FROM general_setting");
  $generalSettingAsk->execute();
  $generalSettingCheck=$generalSettingAsk->fetch(PDO::FETCH_ASSOC);

  // Ask Language
  if(isset($_GET['lang'])){
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
    setcookie('lang', $lang, time() + (3600 * 24 * 30));
  }elseif(isset($_SESSION['lang'])){
    $lang = $_SESSION['lang'];
  }elseif(isset($_COOKIE['lang'])){
    $lang = $_COOKIE['lang'];
  }else{
    $lang = $generalSettingCheck['site_language'];
  }

  // Arayüz dili yalnızca dil DOSYASININ varlığına bağlıdır; veritabanında
  // ayrıca bir dil kaydı olması gerekmez. (Dil dosyaları: adminx/lang/*.php)
  $lang = preg_replace('/[^a-z]/', '', strtolower((string)$lang));
  $lang_file = $lang ? "adminx/lang/$lang.php" : '';
  if (!$lang_file || !file_exists($lang_file)) {
    $lang = 'tr';
    $lang_file = 'adminx/lang/tr.php';
  }
  include $lang_file;

  // İÇERİK dili: sayfa metinleri veritabanında tek dilde (sitenin ana dili)
  // tutulur. Arayüz İngilizce seçilse bile içerik sorguları bu dil kimliğiyle
  // yapılır; İngilizce karşılıklar dil dosyasından uygulanır (bkz. rd_text()).
  $baseLangCode = $generalSettingCheck['site_language'] ?: 'tr';
  $getLangAsk = $db->prepare("SELECT * FROM language WHERE country_code=:country_code");
  $getLangAsk->execute(array('country_code' => $baseLangCode));
  $getLangCheck = $getLangAsk->fetch(PDO::FETCH_ASSOC);
  if (!$getLangCheck) {
    // Ana dil kaydı bulunamadıysa ilk dil kaydına düş (site tamamen boş kalmasın)
    $getLangCheck = $db->query("SELECT * FROM language ORDER BY language_id LIMIT 1")->fetch(PDO::FETCH_ASSOC) ?: array('language_id' => 0, 'country_code' => 'tr');
  }
  // Arayüzde seçili dil kodu, içerik dilinden bağımsızdır
  $getLangCheck['country_code'] = $lang;
  $rdContentLangId = $getLangCheck['language_id'];
  // NOT: Burada eskiden, açılan sayfanın kendi diline göre arayüz dilini zorla
  // değiştiren bir yönlendirme vardı. Site artık tek URL setiyle çalıştığı
  // (içerik dili sabit, arayüz dili dosyadan geldiği) için bu yönlendirme
  // İngilizce seçimini sürekli Türkçe'ye geri atardı — kaldırıldı.

  // Slider
  $sliderAsk=$db->prepare("SELECT * FROM slider WHERE language=:language ORDER BY order_sort");
  $sliderAsk->execute(array(
    'language' => $rdContentLangId
  ));
  // Bu dilde slider girilmemişse (görsel içerik, çeviri gerektirmiyor), Türkçe slider'a düş
  // — böylece hero/sektör banner'ı EN'de boş kalmaz.
  if ($sliderAsk->rowCount() == 0) {
    $trLangForSliderAsk = $db->prepare("SELECT language_id FROM language WHERE country_code='tr'");
    $trLangForSliderAsk->execute();
    $sliderAsk->execute(array(
      'language' => $trLangForSliderAsk->fetchColumn()
    ));
  }

  // Language
  $langAsk=$db->prepare("SELECT * FROM language WHERE status=:status ORDER BY order_sort");
  $langAsk->execute(array(
    'status' => 1
  ));

  // Language Posts
  $postAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND language=:language ORDER BY order_sort");

  // Type Posts
  $typePostAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND language=:language ORDER BY order_sort");

  //Get Post
  $getPostAsk=$db->prepare("SELECT * FROM posts WHERE seo_url=:seo_url AND post_type=:post_type");

  // All Media List
  $allMediaAsk=$db->prepare("SELECT * FROM media WHERE gallery_type_id=:gallery_type_id");

  // Projects Categories
  $projectCatAsk=$db->prepare("SELECT * FROM categories WHERE categories_type=:categories_type AND language=:language AND status=:status ORDER BY order_sort");

  // Projects
  $homeProjectAsk=$db->prepare("SELECT * FROM posts WHERE language=:language AND status=:status AND home_status=:home_status AND post_type=:post_type ORDER BY order_sort");

  // Products
  $homeProductsAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND language=:language AND status=:status AND home_status=:home_status ORDER BY order_sort");

  // Products
  $productAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND language=:language AND status=:status ORDER BY order_sort");

  // Products details
  $homeProductsDetailAsk=$db->prepare("SELECT * FROM products WHERE product_post_id=:product_post_id");

  // Footer Product 3
  $footerProductsAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND language=:language AND status=:status ORDER BY post_id LIMIT 3");

  // PackCat
  $packCatAsk=$db->prepare("SELECT * FROM categories WHERE language=:language AND status=:status AND categories_type=:categories_type ORDER BY order_sort");

  // Pack
  $packAsk=$db->prepare("SELECT * FROM packets WHERE language=:language AND status=:status AND categories=:categories ORDER BY order_sort");
  $packAsk1=$db->prepare("SELECT * FROM packets WHERE language=:language AND status=:status AND packet_id=:packet_id ORDER BY order_sort");

  // Pack Features
  $packFeatureAsk=$db->prepare("SELECT * FROM packet_features WHERE language=:language AND status=:status AND categories=:categories ORDER BY order_sort");

  // Customer Comments
  $cusComAsk=$db->prepare("SELECT * FROM comments WHERE language=:language AND status=:status  ORDER BY order_sort");

  // Pack
  $counterAsk=$db->prepare("SELECT * FROM counter WHERE language=:language AND status=:status  ORDER BY order_sort");

  // Blog 10
  $homeNewsAsk=$db->prepare("SELECT * FROM posts WHERE language=:language AND status=:status AND post_type=:post_type AND home_status=:home_status ORDER BY order_sort LIMIT 10");

  // Blog 5
  $footerNewsAsk=$db->prepare("SELECT * FROM posts WHERE language=:language AND status=:status AND post_type=:post_type ORDER BY post_id LIMIT 5");

  // All Id Category List
  $allCategoriesIdAsk=$db->prepare("SELECT * FROM categories WHERE language=:language AND categories_id=:categories_id AND categories_type=:categories_type");

  // Person
  $personAsk=$db->prepare("SELECT * FROM person WHERE status=:status ORDER BY order_sort");

  // Person Social
  $personSocialAsk=$db->prepare("SELECT * FROM person_social WHERE status=:status AND personel_id=:personel_id");

  // Client Reference
  $referenceAsk=$db->prepare("SELECT * FROM reference WHERE status=:status AND language=:language ORDER BY order_sort");

  // Client Reference
  $socialAsk=$db->prepare("SELECT * FROM social WHERE status=:status ORDER BY order_sort");

  // Categories seo_url
  $categoriesDataAsk=$db->prepare("SELECT * FROM categories WHERE seo_url=:seo_url");

  // page seo type
  $pageDataAsk=$db->prepare("SELECT * FROM posts WHERE seo_url=:seo_url AND post_type=:post_type AND language=:language");

  // All Type Category List
  $allCategoriesAsk=$db->prepare("SELECT * FROM categories WHERE language=:language AND categories_type=:categories_type");

  // Skill List
  $skillAsk=$db->prepare("SELECT * FROM skills WHERE status=:status AND language=:language");

  // ==================================
  // ========= Module Setting =========
  // ==================================

  // Modules
  $moduleAsk=$db->prepare("SELECT * FROM modules WHERE module_name=:module_name");

  /**
   * Resim url'ine cache-buster ekler (dosya değişim süresini query string yapar).
   */
  function rd_img_url($path) {
    $trimmed = trim((string)$path);
    if ($trimmed === '') return '';
    $url = '/' . ltrim($trimmed, '/');
    $localPath = __DIR__ . '/' . ltrim($trimmed, '/');
    if (file_exists($localPath)) {
      return $url . '?v=' . filemtime($localPath);
    }
    return $url;
  }
?>
