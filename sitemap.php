<?php
require_once  'adminx/system/db.php';
header('Content-Type: text/xml');

// General Setting Ask
$generalSettingAsk=$db->prepare("SELECT * FROM general_setting");
$generalSettingAsk->execute();
$generalSettingCheck=$generalSettingAsk->fetch(PDO::FETCH_ASSOC);

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
echo "
     <urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">
       <url>
           <loc>" .$generalSettingCheck['site_url'] . "</loc>
           <changefreq>daily</changefreq>
           <priority>0.8</priority>
       </url>
     ";
    $allPostAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type");
    $allCategoryAsk=$db->prepare("SELECT * FROM categories WHERE categories_type=:categories_type");


    // Page Page
    $allPostAsk->execute(array(
      'post_type'   => 'post_page'
    ));
    while ($allPageCheck = $allPostAsk->fetch(PDO::FETCH_ASSOC)){
      echo '
         <url>
             <loc>'. $generalSettingCheck['site_url'] . $allPageCheck['seo_url'].' </loc>
             <changefreq>daily</changefreq>
             <priority>0.8</priority>
         </url>
       ';
    }

    // Post services
    $allPostAsk->execute(array(
      'post_type'   => 'post_services'
    ));
    while ($allServicesCheck = $allPostAsk->fetch(PDO::FETCH_ASSOC)){
      echo '
         <url>
             <loc>'. $generalSettingCheck['site_url'] . $allServicesCheck['seo_url'].' </loc>
             <changefreq>daily</changefreq>
             <priority>0.8</priority>
         </url>
       ';
    }

    // Post post
    $allPostAsk->execute(array(
      'post_type'   => 'post_post'
    ));
    while ($allPostCheck = $allPostAsk->fetch(PDO::FETCH_ASSOC)){
      echo '
         <url>
             <loc>'. $generalSettingCheck['site_url'] . $allPostCheck['seo_url'].' </loc>
             <changefreq>daily</changefreq>
             <priority>0.8</priority>
         </url>
       ';
    }

    // Post Project
    $allPostAsk->execute(array(
      'post_type'   => 'post_projects'
    ));
    while ($allProjectCheck = $allPostAsk->fetch(PDO::FETCH_ASSOC)){
      echo '
         <url>
             <loc>'. $generalSettingCheck['site_url'] . $allProjectCheck['seo_url'].' </loc>
             <changefreq>daily</changefreq>
             <priority>0.8</priority>
         </url>
       ';
    }

    // Post Notices
    $allPostAsk->execute(array(
      'post_type'   => 'post_notices'
    ));
    while ($allNoticesCheck = $allPostAsk->fetch(PDO::FETCH_ASSOC)){
      echo '
         <url>
             <loc>'. $generalSettingCheck['site_url'] . $allNoticesCheck['seo_url'].' </loc>
             <changefreq>daily</changefreq>
             <priority>0.8</priority>
         </url>
       ';
    }

    // Post Gallery
    $allPostAsk->execute(array(
      'post_type'   => 'post_Gallery'
    ));
    while ($allGalleryCheck = $allPostAsk->fetch(PDO::FETCH_ASSOC)){
      echo '
         <url>
             <loc>'. $generalSettingCheck['site_url'] . $allGalleryCheck['seo_url'].' </loc>
             <changefreq>daily</changefreq>
             <priority>0.8</priority>
         </url>
       ';
    }

    // Category Project
    $allCategoryAsk->execute(array(
      'categories_type'   => 'project_categories'
    ));
    while ($allProjectCatCheck = $allCategoryAsk->fetch(PDO::FETCH_ASSOC)){
      echo '
         <url>
             <loc>'. $generalSettingCheck['site_url'] . $allProjectCatCheck['seo_url'].' </loc>
             <changefreq>daily</changefreq>
             <priority>0.8</priority>
         </url>
       ';
    }

    // Category Post
    $allCategoryAsk->execute(array(
      'categories_type'   => 'post_categories'
    ));
    while ($allPostCatCheck = $allCategoryAsk->fetch(PDO::FETCH_ASSOC)){
      echo '
         <url>
             <loc>'. $generalSettingCheck['site_url'] . $allPostCatCheck['seo_url'].' </loc>
             <changefreq>daily</changefreq>
             <priority>0.8</priority>
         </url>
       ';
    }

    // Category Post
    $allCategoryAsk->execute(array(
      'categories_type'   => 'product_categories'
    ));
    while ($allProductCatCheck = $allCategoryAsk->fetch(PDO::FETCH_ASSOC)){
      echo '
         <url>
             <loc>'. $generalSettingCheck['site_url'] . $allProductCatCheck['seo_url'].' </loc>
             <changefreq>daily</changefreq>
             <priority>0.8</priority>
         </url>
       ';
    }
echo "</urlset>";
?>
