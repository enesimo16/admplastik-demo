<?php
header('Content-Type: text/plain; charset=utf-8');

$host = strtolower((string)($_SERVER['HTTP_HOST'] ?? ''));
$host = preg_replace('/:\d+$/', '', $host);
$isNonProduction = $host === ''
  || $host === 'localhost'
  || strpos($host, '127.0.0.1') === 0
  || strpos($host, 'test.') === 0;

echo "User-agent: *\n";
if ($isNonProduction) {
  echo "Disallow: /\n";
  exit;
}

echo "Disallow: /adminx/\n";
echo "Allow: /\n\n";
echo "Sitemap: https://www.admplastik.com.tr/sitemap.xml\n";
