<?php
/**
 * Etkileşim toplama ucu (public)
 *
 * Tarayıcıdaki assets/js/track.js buraya `navigator.sendBeacon` ile küçük
 * JSON paketleri gönderir: tıklama, dış link, telefon, e-posta, WhatsApp,
 * dosya indirme, kaydırma derinliği, form başlangıcı/gönderimi, sayfada
 * kalma süresi.
 *
 * API anahtarı YOKTUR — tarayıcıdan çağrıldığı için anahtar konulamaz.
 * Bunun yerine sıkı doğrulama uygulanır:
 *   - Sadece POST
 *   - Sadece bilinen olay adları
 *   - Alan uzunlukları kırpılır, HTML saklanmaz
 *   - Oturum başına istek sınırı (spam/şişirme koruması)
 * Veri yalnızca istatistik amaçlıdır; hiçbir yerde HTML olarak basılmaz.
 */

require_once __DIR__ . '/adminx/system/db.php';
require_once __DIR__ . '/adminx/system/analytics.php';

header('Content-Type: application/json; charset=utf-8');

function track_out($code, $data) {
  http_response_code($code);
  echo json_encode($data);
  exit;
}

if (!adm_has_analytics_consent()) {
  track_out(200, array('success' => true, 'skipped' => 'consent_required'));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  track_out(405, array('success' => false));
}

// Kabul edilen olaylar. Listede olmayan hiçbir şey kaydedilmez.
$ALLOWED_EVENTS = array(
  'click',       // izlenen buton/bağlantı tıklaması
  'outbound',    // siteden dışarı çıkan bağlantı
  'phone',       // tel: bağlantısı
  'mail',        // mailto: bağlantısı
  'whatsapp',    // WhatsApp bağlantısı
  'download',    // dosya indirme (pdf, doc, xls, zip...)
  'scroll',      // kaydırma derinliği (value = %25/50/75/100)
  'form_start',  // forma ilk giriş
  'form_submit', // form gönderimi
  'search',      // site içi arama
  'engagement',  // sayfada geçirilen süre (value = saniye)
  'video',       // video oynatma
);

// Gövde: JSON (sendBeacon Blob) veya form-data
$payload = array();
$raw = file_get_contents('php://input');
if ($raw !== '' && $raw !== false) {
  $decoded = json_decode($raw, true);
  if (is_array($decoded)) $payload = $decoded;
}
if (!$payload && $_POST) $payload = $_POST;

$event = isset($payload['e']) ? (string)$payload['e'] : '';
if (!in_array($event, $ALLOWED_EVENTS, true)) {
  track_out(422, array('success' => false));
}

// Bot trafiğini kaydetme
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
if ($userAgent === '' || preg_match('/bot|spider|crawl|slurp|facebookexternalhit/i', $userAgent)) {
  track_out(200, array('success' => true, 'skipped' => true));
}

// Oturum başına istek sınırı — aynı ziyaretçi sonsuz olay üretemesin
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_samesite', 'Lax');
session_start();
$counterKey = 'adm_track_count';
$windowKey  = 'adm_track_window';
$now = time();
if (!isset($_SESSION[$windowKey]) || ($now - $_SESSION[$windowKey]) > 600) {
  $_SESSION[$windowKey] = $now;
  $_SESSION[$counterKey] = 0;
}
$_SESSION[$counterKey]++;
if ($_SESSION[$counterKey] > 300) {   // 10 dakikada en fazla 300 olay
  track_out(429, array('success' => false));
}

// Metin alanlarını temizle: kontrol karakterleri ve HTML işaretleri gitsin
function track_clean($value, $maxLength) {
  $value = is_scalar($value) ? (string)$value : '';
  $value = strip_tags($value);
  $value = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $value);
  return mb_substr(trim($value), 0, $maxLength);
}

function track_ip() {
  foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR') as $key) {
    if (!empty($_SERVER[$key])) {
      $ip = $_SERVER[$key];
      if (strpos($ip, ',') !== false) {
        $parts = explode(',', $ip);
        $ip = trim($parts[0]);
      }
      return $ip;
    }
  }
  return '';
}

list($sessionId, ) = adm_visitor_session();
$agent = adm_parse_user_agent($userAgent);

adm_log_event($db, array(
  'session_id' => $sessionId,
  'event'      => $event,
  'label'      => track_clean($payload['l'] ?? '', 190),
  'url'        => track_clean($payload['u'] ?? ($_SERVER['HTTP_REFERER'] ?? ''), 255),
  'target'     => track_clean($payload['t'] ?? '', 255),
  'value'      => max(0, min(100000, (int)($payload['v'] ?? 0))),
  'device'     => $agent['device'],
  'ip'         => track_ip(),
  'language'   => (int)($payload['lang'] ?? 0),
));

track_out(200, array('success' => true));
