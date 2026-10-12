<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/adminx/system/db.php';

$email = trim(strip_tags($_POST['email'] ?? ''));
$langId = (int)($_POST['language'] ?? 0);

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(422);
  echo json_encode(array('success' => false, 'error' => 'invalid_email'));
  exit;
}
if (!$langId) {
  $langId = 2; // tr
}

// newsletter_subscribers tablosu opsiyoneldir (canlıda oluşturulmamış olabilir).
// Tablo yoksa ziyaretçiye hata gösterme — kaydı sessizce atla.
try {
  $check = $db->prepare("SELECT subscriber_id FROM newsletter_subscribers WHERE email=:email");
  $check->execute(array('email' => $email));
  if ($check->fetchColumn()) {
    echo json_encode(array('success' => true, 'already' => true));
    exit;
  }

  $add = $db->prepare("INSERT INTO newsletter_subscribers SET email=:email, language=:language, status=1, created_at=NOW()");
  $ok = $add->execute(array('email' => $email, 'language' => $langId));

  if ($ok) {
    echo json_encode(array('success' => true, 'already' => false));
  } else {
    http_response_code(500);
    echo json_encode(array('success' => false, 'error' => 'insert_failed'));
  }
} catch (Exception $e) {
  error_log('newsletter-subscribe: ' . $e->getMessage());
  echo json_encode(array('success' => true, 'already' => false, 'stored' => false));
}
