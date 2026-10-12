<?php
 $pageDataAsk->execute(array(
   'seo_url'    => $_GET["seo_url"],
   'post_type'  => 'post_page',
   'language'   => $getLangCheck['language_id']
 ));
 $pageDataCheck=$pageDataAsk->fetch(PDO::FETCH_ASSOC);

 switch ($pageDataCheck['template']){
   case 'emptytemplate':
     include "variation/page/emptypage.php";
     break;
   case 'abouttemplate':
     include "variation/page/abouttemplate.php";
     break;
   case 'skillstemplate':
     include "variation/page/skillstemplate.php";
     break;
   case 'stacktemplate':
     include "variation/page/stacktemplate.php";
     break;
   case 'contacttemplate':
     include "variation/page/contacttemplate.php";
     break;
   case 'blogtemplate':
     include "variation/page/blogtemplate.php";
     break;
   case 'projecttemplate':
     include "variation/page/projecttemplate.php";
     break;
   case 'producttemplate':
     include "variation/page/producttemplate.php";
     break;
   case 'servicestemplate':
     include "variation/page/servicestemplate.php";
     break;
   case 'faqtemplate':
     include "variation/page/faqtemplate.php";
     break;
   case 'branchestemplate':
     include "variation/page/branchestemplate.php";
     break;
   case 'dealerstemplate':
     include "variation/page/dealerstemplate.php";
     break;
   case 'bankaccountstemplate':
     include "variation/page/bankaccountstemplate.php";
     break;
   case 'teamtemplate':
     include "variation/page/teamtemplate.php";
     break;
   case 'catalogstemplate':
     include "variation/page/catalogstemplate.php";
     break;
   case 'documentstemplate':
     include "variation/page/documentstemplate.php";
     break;
   case 'announcementstemplate':
     include "variation/page/announcementstemplate.php";
     break;
   case 'referencetemplate':
     include "variation/page/referencetemplate.php";
     break;
   case 'gallerytemplate':
     include "variation/page/gallerytemplate.php";
     break;
 }
?>
