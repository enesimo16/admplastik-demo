<?php
  if (isset($_GET['q'])) {
    // Arama sayfası (searchpage.php) seo_url kullanmıyor, 404 başlığına düşmesin
    echo '
      <title>'.$langArray['RD_SEARCH'].' — '.htmlspecialchars($_GET['q']).'</title>
      <meta name="robots" content="noindex,follow">
    ';
  } elseif (isset($_GET['seo_url'])) {
    // $_GET['seo_url'] page query
    $pageAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND seo_url=:seo_url");
    $pageAsk->execute(array(
      'post_type' => 'post_page',
      'seo_url'   => $_GET['seo_url']
    ));
    $pagesCount=$pageAsk->rowCount();
    $pagesCheck=$pageAsk->fetch(PDO::FETCH_ASSOC);

    // $_GET['seo_url'] post query
    $postAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND seo_url=:seo_url");
    $postAsk->execute(array(
      'post_type' => 'post_post',
      'seo_url'   => $_GET['seo_url']
    ));
    $postCount=$postAsk->rowCount();
    $postCheck=$postAsk->fetch(PDO::FETCH_ASSOC);

    // $_GET['seo_url'] services query
    $servicesAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND seo_url=:seo_url");
    $servicesAsk->execute(array(
      'post_type' => 'post_services',
      'seo_url'   => $_GET['seo_url']
    ));
    $servicesCount=$servicesAsk->rowCount();
    $servicesCheck=$servicesAsk->fetch(PDO::FETCH_ASSOC);

    // $_GET['seo_url'] product query
    $productAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND seo_url=:seo_url");
    $productAsk->execute(array(
      'post_type' => 'post_products',
      'seo_url'   => $_GET['seo_url']
    ));
    $productCount=$productAsk->rowCount();
    $productCheck=$productAsk->fetch(PDO::FETCH_ASSOC);

    // $_GET['seo_url'] project query
    $projectAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND seo_url=:seo_url");
    $projectAsk->execute(array(
      'post_type' => 'post_projects',
      'seo_url'   => $_GET['seo_url']
    ));
    $projectCount=$projectAsk->rowCount();
    $projectCheck=$projectAsk->fetch(PDO::FETCH_ASSOC);

    // $_GET['seo_url'] project query
    $noticeAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND seo_url=:seo_url");
    $noticeAsk->execute(array(
      'post_type' => 'post_notices',
      'seo_url'   => $_GET['seo_url']
    ));
    $noticeCount=$noticeAsk->rowCount();
    $noticeCheck=$noticeAsk->fetch(PDO::FETCH_ASSOC);

    // $_GET['seo_url'] project query
    $galleryAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND seo_url=:seo_url");
    $galleryAsk->execute(array(
      'post_type' => 'post_Gallery',
      'seo_url'   => $_GET['seo_url']
    ));
    $galleryCount=$galleryAsk->rowCount();
    $galleryCheck=$galleryAsk->fetch(PDO::FETCH_ASSOC);

    // $_GET['seo_url'] categories query
    $categoriesAsk=$db->prepare("SELECT * FROM categories WHERE seo_url=:seo_url");
    $categoriesAsk->execute(array(
      'seo_url'   => $_GET['seo_url']
    ));
    $categoriesCount=$categoriesAsk->rowCount();
    $categoriesCheck=$categoriesAsk->fetch(PDO::FETCH_ASSOC);

    // Bulunan kayıt hangi türde olursa olsun aynı şekilde işlenir.
    // ÖNEMLİ: seo_title alanı çoğu sayfada boş; boşsa sayfa başlığına düşülür,
    // yoksa tarayıcı sekmesinde adres (localhost/... ) görünüyordu.
    $rdMetaRow = null;
    foreach (array(
      $pagesCount      ? $pagesCheck      : null,
      $postCount       ? $postCheck       : null,
      $categoriesCount ? $categoriesCheck : null,
      $servicesCount   ? $servicesCheck   : null,
      $productCount    ? $productCheck    : null,
      $projectCount    ? $projectCheck    : null,
      $noticeCount     ? $noticeCheck     : null,
      $galleryCount    ? $galleryCheck    : null,
    ) as $rdCandidate) {
      if (is_array($rdCandidate)) { $rdMetaRow = $rdCandidate; break; }
    }

    if ($rdMetaRow) {
      // Arayüz dili İngilizceyse çevirisi olan sayfalarda İngilizce başlık kullan
      $rdPageKey  = function_exists('rd_key_of') ? rd_key_of($rdMetaRow['seo_url'] ?? '') : null;
      $rdRawTitle = trim($rdMetaRow['seo_title'] ?? '');
      if ($rdRawTitle === '') $rdRawTitle = trim($rdMetaRow['title'] ?? '');
      if ($rdPageKey && function_exists('rd_text')) {
        $rdRawTitle = rd_text($rdPageKey, 'TITLE', $rdRawTitle);
      }

      $rdDesc = trim($rdMetaRow['seo_description'] ?? '');
      if ($rdDesc === '') {
        $rdPlain = trim(strip_tags($rdMetaRow['content'] ?? ''));
        $rdDesc  = $rdPlain !== '' ? mb_strimwidth($rdPlain, 0, 155, '…') : $generalSettingCheck['site_desc'];
      }

      $rdSiteName = $generalSettingCheck['site_title'];
      $rdFullTitle = $rdRawTitle !== '' ? $rdRawTitle . ' | ' . $rdSiteName : $rdSiteName;

      echo '
        <title>'.htmlspecialchars($rdFullTitle).'</title>
        <meta name="keywords" content="'.htmlspecialchars($rdMetaRow['seo_keywords'] ?? '').'">
        <meta name="description" content="'.htmlspecialchars($rdDesc).'">
      ';
    } else {
      echo '
        <title>'.htmlspecialchars($langArray['404_TITLE'].' | '.$generalSettingCheck['site_title']).'</title>
        <meta name="robots" content="noindex,follow">
        <meta name="description" content="'.htmlspecialchars($langArray['404_DESC']).'">
      ';
    }
  }else{
    echo '
      <title>'.htmlspecialchars($langArray['404_TITLE'].' | '.$generalSettingCheck['site_title']).'</title>
      <meta name="robots" content="noindex,follow">
      <meta name="description" content="'.htmlspecialchars($langArray['404_DESC']).'">
    ';
  }

?>
