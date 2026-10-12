<?php
  require_once 'header.php';

  // $_GET['seo_url'] page query
  $pageAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND seo_url=:seo_url");
  $pageAsk->execute(array(
    'post_type' => 'post_page',
    'seo_url'   => $_GET['seo_url']
  ));
  $pagesCount=$pageAsk->rowCount();

  // $_GET['seo_url'] post query
  $postAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND seo_url=:seo_url");
  $postAsk->execute(array(
    'post_type' => 'post_post',
    'seo_url'   => $_GET['seo_url']
  ));
  $postCount=$postAsk->rowCount();

  // $_GET['seo_url'] services query
  $servicesAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND seo_url=:seo_url");
  $servicesAsk->execute(array(
    'post_type' => 'post_services',
    'seo_url'   => $_GET['seo_url']
  ));
  $servicesCount=$servicesAsk->rowCount();

  // $_GET['seo_url'] product query
  $productAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND seo_url=:seo_url");
  $productAsk->execute(array(
    'post_type' => 'post_products',
    'seo_url'   => $_GET['seo_url']
  ));
  $productCount=$productAsk->rowCount();

  // $_GET['seo_url'] project query
  $projectAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND seo_url=:seo_url");
  $projectAsk->execute(array(
    'post_type' => 'post_projects',
    'seo_url'   => $_GET['seo_url']
  ));
  $projectCount=$projectAsk->rowCount();

  // $_GET['seo_url'] project query
  $noticeAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND seo_url=:seo_url");
  $noticeAsk->execute(array(
    'post_type' => 'post_notices',
    'seo_url'   => $_GET['seo_url']
  ));
  $noticeCount=$noticeAsk->rowCount();

  // $_GET['seo_url'] project query
  $galleryAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND seo_url=:seo_url");
  $galleryAsk->execute(array(
    'post_type' => 'post_Gallery',
    'seo_url'   => $_GET['seo_url']
  ));
  $galleryCount=$galleryAsk->rowCount();

  // $_GET['seo_url'] categories query
  $categoriesAsk=$db->prepare("SELECT * FROM categories WHERE seo_url=:seo_url");
  $categoriesAsk->execute(array(
    'seo_url'   => $_GET['seo_url']
  ));
  $categoriesCount=$categoriesAsk->rowCount();

  if ($pagesCount){
    require_once 'pages.php';
  }elseif($postCount){
    require_once 'postspage.php';
  }elseif($categoriesCount){
    require_once 'categoriespage.php';
  }elseif($servicesCount){
    require_once 'servicespage.php';
  }elseif($productCount){
    require_once 'productspage.php';
  }elseif($projectCount){
    require_once 'projectspage.php';
  }elseif($noticeCount){
    require_once 'announcementspage.php';
  }elseif($galleryCount){
    require_once 'gallerypage.php';
  }else{
    // Bilinmeyen SEO adreslerini yönlendirmek yerine gerçek 404 status'u döndür.
    http_response_code(404);
    require_once '404.php';
    exit;
  }
  require_once 'footer.php';
?>
