<?php
  $getPostAsk->execute(array(
    'seo_url'    => $_GET["seo_url"],
    'post_type'  => 'post_services'
  ));
  $servicesDataCheck=$getPostAsk->fetch(PDO::FETCH_ASSOC);

  include "variation/page/servicedetailtemplate.php";
?>
