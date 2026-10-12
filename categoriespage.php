<?php
  $categoriesDataAsk->execute(array(
    'seo_url'          => $_GET["seo_url"]
  ));
  $categoriesDataCheck=$categoriesDataAsk->fetch(PDO::FETCH_ASSOC);

  switch ($categoriesDataCheck['categories_type']){
    case 'project_categories':
      include "variation/categories/projectcategories.php";
      break;
    case 'post_categories':
      include "variation/categories/postcategories.php";
      break;
    case 'product_categories':
      include "variation/categories/productcategories.php";
      break;
  }

?>
