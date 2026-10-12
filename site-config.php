<?php
// Bu dosya doğrudan URL'den istenirse durdur (sadece include ile çalışır)
if (isset($_SERVER['SCRIPT_FILENAME']) && realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
  http_response_code(403);
  exit('Forbidden');
}

/**
 * Site sayfa haritası — veritabanı gerektirmeyen sürüm.
 *
 * Yeni tasarım, bazı sayfaları "mantıksal anahtar" ile bulur
 * (örn. kalite politikası sayfası, AR-GE sayfası). Bu eşleşme daha önce
 * veritabanındaki trans_group kolonunda tutuluyordu; artık burada,
 * dosyada tutuluyor. Böylece canlıya alırken ALTER TABLE gerekmez.
 *
 * Anahtar => Türkçe seo_url  (site tek URL setiyle çalışır, dil sadece
 * içeriği değiştirir — bkz. rd_text() / adminx/lang/*.php)
 *
 * Bir sayfanın adresi admin panelden değiştirilirse, buradaki karşılığı
 * da güncellenmelidir.
 */
$RD_PAGE_MAP = array(
  // Kurumsal
  'about-main'         => 'sayfa-hakkimizda',
  'about-mv'           => 'sayfa-misyonumuz-vizyonumuz',
  'cert-index'         => 'sayfa-dokumanlar',
  'contact-index'      => 'sayfa-iletisim',
  'products-index'     => 'sayfa-urunler',

  // Yetkinlikler (skillstemplate bu sırayla listeler)
  'skill-arge'         => 'sayfa-arge',
  'skill-muh'          => 'sayfa-muhendislik',
  'skill-lab'          => 'sayfa-laboratuvar',
  'skill-adt'          => 'sayfa-adt-standartlari',

  // Yönetim sistemleri (stacktemplate bu sırayla listeler)
  'policy-quality'     => 'sayfa-kalite-politikasi',
  'policy-social'      => 'sayfa-sosyal-uygunluk-politikasi',
  'policy-environment' => 'sayfa-cevre-politikasi',

  // Üretim alanları (post_services)
  'service-automotive' => 'hizmet-otomotiv',
  'service-agri'       => 'hizmet-is-ve-tarim-makinalari',
  'service-rail'       => 'hizmet-rayli-sistemler',
);

/** Kategoriler (categories tablosu) */
$RD_CATEGORY_MAP = array(
  'blog-category'          => 'kategori-blog-kategori',
  'cat-product-automotive' => 'kategori-urun-otomotiv',
  'cat-product-agri'       => 'kategori-urun-is-tarim',
  'cat-product-rail'       => 'kategori-urun-rayli',
);

/** Sayfa anahtarından seo_url bul */
function rd_slug($key, $fallback = '') {
  global $RD_PAGE_MAP, $RD_CATEGORY_MAP;
  return $RD_PAGE_MAP[$key] ?? ($RD_CATEGORY_MAP[$key] ?? $fallback);
}

/** seo_url'den sayfa anahtarını bul (ters arama; kategoriler dahil) */
function rd_key_of($seoUrl) {
  global $RD_PAGE_MAP, $RD_CATEGORY_MAP;
  static $flip = null;
  if ($flip === null) $flip = array_flip($RD_PAGE_MAP + $RD_CATEGORY_MAP);
  return $flip[$seoUrl] ?? null;
}

/** Verilen anahtar listesinin seo_url karşılıkları (sıra korunur) */
function rd_slugs(array $keys) {
  $out = array();
  foreach ($keys as $k) {
    $s = rd_slug($k);
    if ($s !== '') $out[] = $s;
  }
  return $out;
}
