<?php
/**
 * İletişim ve teklif formlarının tek gönderim noktası.
 *
 * Hem JavaScript ile (fetch → JSON) hem de JavaScript kapalıyken
 * (normal form POST → geri yönlendirme) çalışır. Böylece form mantığı
 * tek yerde durur, sayfalarda tekrarlanmaz.
 *
 * POST alanları:
 *   type=contact  → namesurname, email, phone, subject, message
 *   type=offer    → offer_name, offer_email, offer_phone, offer_services
 */

ob_start();
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_samesite', 'Lax');
session_start();

require_once __DIR__ . '/adminx/system/db.php';
require_once __DIR__ . '/adminx/pages/functions.php';
require_once __DIR__ . '/adminx/system/class.phpmailer.php';

date_default_timezone_set('Europe/Istanbul');

// ---- Ayarlar ve dil ----
$generalSettingAsk = $db->prepare("SELECT * FROM general_setting");
$generalSettingAsk->execute();
$generalSettingCheck = $generalSettingAsk->fetch(PDO::FETCH_ASSOC);

$lang = $_SESSION['lang'] ?? ($_COOKIE['lang'] ?? ($generalSettingCheck['site_language'] ?: 'tr'));
$lang = preg_replace('/[^a-z]/', '', strtolower((string)$lang));
$langFile = __DIR__ . "/adminx/lang/$lang.php";
if (!file_exists($langFile)) { $langFile = __DIR__ . '/adminx/lang/tr.php'; }
$langArray = array();
include $langFile;

// ---- İstek türü: AJAX mı normal form mu ----
$wantsJson = (
  (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
  (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
);

/** Yanıtı JSON olarak ya da yönlendirme ile döndür */
function rd_form_respond($ok, $message, $wantsJson) {
  if ($wantsJson) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($ok ? 200 : 422);
    echo json_encode(array('success' => $ok, 'message' => $message), JSON_UNESCAPED_UNICODE);
    exit;
  }
  // JavaScript kapalıysa: geldiği sayfaya durum bilgisiyle dön
  $back = $_SERVER['HTTP_REFERER'] ?? '/';
  $back = preg_replace('/[?&](sent|formerror)=[^&]*/', '', $back);
  $sep  = strpos($back, '?') === false ? '?' : '&';
  header('Location: ' . $back . $sep . ($ok ? 'sent=1' : 'formerror=' . rawurlencode($message)));
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  rd_form_respond(false, 'Geçersiz istek', $wantsJson);
}

// ---- reCAPTCHA (anahtar tanımlıysa) ----
$secretKey = $generalSettingCheck['site_recap_secretkey'] ?? '';
if (!empty($secretKey)) {
  $responseKey = $_POST['g-recaptcha-response'] ?? '';
  $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey)
             . '&response=' . urlencode($responseKey)
             . '&remoteip=' . urlencode($_SERVER['REMOTE_ADDR'] ?? '');
  $verify = @file_get_contents($verifyUrl);
  $verifyData = $verify ? json_decode($verify) : null;
  if (empty($verifyData->success)) {
    rd_form_respond(false, $langArray['VERIFICATION_ERROR'] ?? 'Doğrulama başarısız', $wantsJson);
  }
}

$type = $_POST['type'] ?? 'contact';
$ip   = $_SERVER['REMOTE_ADDR'] ?? '';

// =====================================================================
// TEKLİF FORMU
// =====================================================================
if ($type === 'offer') {
  $name  = trim(strip_tags($_POST['offer_name'] ?? ''));
  $email = trim(strip_tags($_POST['offer_email'] ?? ''));
  $phone = trim(strip_tags($_POST['offer_phone'] ?? ''));
  $svc   = trim(strip_tags($_POST['offer_services'] ?? ''));

  if ($name === '' || $email === '' || $phone === '') {
    rd_form_respond(false, $langArray['ACCEPT_ERROR'] ?? 'Lütfen zorunlu alanları doldurun', $wantsJson);
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    rd_form_respond(false, $langArray['RD_FORM_INVALID_EMAIL'] ?? 'Geçerli bir e-posta girin', $wantsJson);
  }

  // created_at kolonu canlıda henüz olmayabilir
  $hasDate = false;
  try {
    $c = $db->prepare("SHOW COLUMNS FROM offer LIKE 'created_at'");
    $c->execute();
    $hasDate = (bool)$c->fetch(PDO::FETCH_ASSOC);
  } catch (Exception $e) {}

  $add = $db->prepare("INSERT INTO offer SET ip=:ip, offer_name=:n, offer_phone=:p,
    offer_email=:e, offer_services=:s, status=0" . ($hasDate ? ", created_at=NOW()" : ""));
  $ok = $add->execute(array('ip' => $ip, 'n' => $name, 'p' => $phone, 'e' => $email, 's' => $svc));

  if (!$ok) {
    rd_form_respond(false, $langArray['ACCEPT_ERROR'] ?? 'Kayıt oluşturulamadı', $wantsJson);
  }

  $body = '<table style="width:100%;border:0;">'
        . '<tr><td style="border:1px solid #ddd;padding:5px">' . htmlspecialchars($name) . '</td></tr>'
        . '<tr><td style="border:1px solid #ddd;padding:5px">' . htmlspecialchars($phone) . '</td></tr>'
        . '<tr><td style="border:1px solid #ddd;padding:5px">' . htmlspecialchars($email) . '</td></tr>'
        . '</table>';
  $sent = adm_send_mail($generalSettingCheck, $langArray['MSG_HEAD_OFFER'] ?? 'Teklif Formu', $body, $name, $email);

  try {
    $db->prepare("INSERT INTO mail_log SET type='offer', recipient=:r, subject=:s, success=:ok, sent_at=NOW()")
       ->execute(array('r' => $generalSettingCheck['site_email'], 's' => $langArray['MSG_HEAD_OFFER'] ?? 'Teklif', 'ok' => $sent ? 1 : 0));
  } catch (Exception $e) {}

  rd_form_respond(true, $langArray['ACCEPT_OFFER'] ?? 'Talebiniz alındı', $wantsJson);
}

// =====================================================================
// İLETİŞİM FORMU
// =====================================================================
$name    = trim(strip_tags($_POST['namesurname'] ?? ''));
$email   = trim(strip_tags($_POST['email'] ?? ''));
$phone   = trim(strip_tags($_POST['phone'] ?? ''));
$subject = trim(strip_tags($_POST['subject'] ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

if ($name === '' || $email === '' || $subject === '') {
  rd_form_respond(false, $langArray['ACCEPT_ERROR'] ?? 'Lütfen zorunlu alanları doldurun', $wantsJson);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  rd_form_respond(false, $langArray['RD_FORM_INVALID_EMAIL'] ?? 'Geçerli bir e-posta girin', $wantsJson);
}

$add = $db->prepare("INSERT INTO contact_log SET ip=:ip, namesurname=:n, email=:e,
  phone=:p, subject=:s, message=:m, dates=:d, status=0");
$ok = $add->execute(array(
  'ip' => $ip, 'n' => $name, 'e' => $email, 'p' => $phone,
  's' => $subject, 'm' => $message, 'd' => date('Y-m-d H:i:s'),
));

if (!$ok) {
  rd_form_respond(false, $langArray['ACCEPT_ERROR'] ?? 'Mesaj kaydedilemedi', $wantsJson);
}

$body = '<table style="width:100%;border:0;">'
      . '<tr><td style="border:1px solid #ddd;padding:5px">' . htmlspecialchars($name) . '</td></tr>'
      . '<tr><td style="border:1px solid #ddd;padding:5px">' . htmlspecialchars($email) . '</td></tr>'
      . '<tr><td style="border:1px solid #ddd;padding:5px">' . htmlspecialchars($phone) . '</td></tr>'
      . '<tr><td style="border:1px solid #ddd;padding:5px">' . htmlspecialchars($subject) . '</td></tr>'
      . '<tr><td style="border:1px solid #ddd;padding:5px">' . nl2br(htmlspecialchars($message)) . '</td></tr>'
      . '</table>';
$sent = adm_send_mail($generalSettingCheck, $langArray['MSG_HEAD_CONTACT'] ?? 'İletişim Formu', $body, $name, $email);

try {
  $db->prepare("INSERT INTO mail_log SET type='contact', recipient=:r, subject=:s, success=:ok, sent_at=NOW()")
     ->execute(array('r' => $generalSettingCheck['site_email'], 's' => $langArray['MSG_HEAD_CONTACT'] ?? 'İletişim', 'ok' => $sent ? 1 : 0));
} catch (Exception $e) {}

rd_form_respond(true, $langArray['ACCEPT_CONTACT'] ?? 'Mesajınız bize ulaştı', $wantsJson);
