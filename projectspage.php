<?php
  $getPostAsk->execute(array(
    'seo_url'    => $_GET["seo_url"],
    'post_type'  => 'post_projects'
  ));
  $projectDataCheck=$getPostAsk->fetch(PDO::FETCH_ASSOC);

  $projectDetailAsk=$db->prepare("SELECT * FROM projects WHERE project_post_id={$projectDataCheck['post_id']}");
  $projectDetailAsk->execute();
  $projectDetailCheck=$projectDetailAsk->fetch(PDO::FETCH_ASSOC);
?>
   <div class="breadcrumb-area style-02">
        <div class="container">
            <div class="row">
                <div class="breadcrumb-inner">
                    <div class="breadcrumb-left">
                        <div class="breadcrumb-inner-wrap">
                            <div class="breadcrumb-border">
                                <h1 class="page-title"><?php echo $projectDataCheck['title']; ?></h1>
                                <ul class="page-list">
                                    <li><a href="<?php echo $generalSettingCheck['site_url']; ?>"><?php echo $langArray['LINK_HOME']; ?></a></li>
                                    <li><?php echo $projectDataCheck['title']; ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="breadcrumb-right">
                        <div class="breadcrumb-thumb" style="background-image: url('<?php echo $projectDataCheck['banner']; ?>')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--// Breadcrumb End-->


    <!--Blog Page Content-->
    <div class="blog-page-content padding-top-120 padding-bottom-95">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="blog-details-wrap">
                        <!--Blog Details item-->
                        <div class="blog-details-items">
                            <div class="content bg-none">
                                <h3 class="title"><?php echo $projectDataCheck['title']; ?></h3>
                                <div class="post-meta border-none padding-top-20">
                                    <ul class="list-wrap">
                                        <?php if ($projectDetailCheck['start_date']){?>
                                        <li>
                                            <div class="author"><strong><?php echo $langArray['STARTING_DATE']; ?> </strong>
                     <?php echo $projectDetailCheck['start_date']; ?></div>
                                        </li> <?php } ?>


                                    </ul>
                                    <ul class="list-wrap two">
                                        <?php if ($projectDetailCheck['end_date']){?>

                                         <li>
                                            <div class="author"> <strong><?php echo $langArray['DUE_DATE']; ?></strong>
                     <?php echo $projectDetailCheck['end_date']; ?></div>
                                        </li><?php } ?>

                                        <?php if ($projectDetailCheck['website']){?>
                    <li>
                      <strong><?php echo $langArray['PROJECT_WEBSITE']; ?></strong>
                      <a target="_blank" rel="nofollow" href="http://<?php echo $projectDetailCheck['website']; ?>"><?php echo $projectDetailCheck['website']; ?></a>
                    </li><?php } ?>
                      
                                    </ul>
                                </div>
                                    <div class="thumb padding-30">
                                <img src="<?php echo $projectDataCheck['banner']; ?>" alt="<?php echo $projectDataCheck['title']; ?>">
                            </div>
                                <div class="padding-30">
                                    <p><?php echo $projectDataCheck['content']; ?></p>
                                </div>
                            </div>
                            <!--// Content-->

                        
                            <!--// Thumbnail Area-->
<h3 class="title"><?php echo $langArray['GALLERY_TITLE']; ?></h3>
                         <div class="row padding-30">
       <?php
            $typeandid = 'project_gallery-'.$projectDataCheck['post_id'];
            $allMediaAsk->execute(array(
              'gallery_type_id'        => $typeandid
            ));
            while ($allTypeCheck=$allMediaAsk->fetch(PDO::FETCH_ASSOC)){
          ?>
              <div class="col-lg-4">
                  <!--Process left Content-->
                  <div class="process-section__left__items">
                      <div class="thumb-area">
                          <div class="thumb">
                            <a data-fancybox href="<?php echo $allTypeCheck['media_large']; ?>">
                              <img src="<?php echo $allTypeCheck['media_large']; ?>" alt="<?php echo $documentCheck['title'];?>">
                            </a> 
                          </div>
                      </div>
                    
                  </div>
                  <!--// Process item-->
              </div>
            <?php } ?>
        </div>
 <?php if ($projectDetailCheck['video']){?>
                             <div class="row padding-30">
                                 <h3><?php echo $langArray['PROJECT_VIDEO']; ?></h3>
                                <iframe width="100%" height="400" src="https://www.youtube.com/embed/<?php echo $projectDetailCheck['video']; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div><?php } ?>
                            <?php if ($projectDetailCheck['maps']){?>
                             <div class="row padding-30">
                                 <h3><?php echo $langArray['PROJECT_MAPS']; ?></h3>
                               <iframe src="<?php echo $projectDetailCheck['maps']; ?>" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe><?php } ?>
                            </div>
                        </div>
                        <!--// Blog Details item-->

                        <div class="blog-details-footer">
                            <div class="left">
                                <span class="share--title"><?php echo $langArray['SHARE_TEXT']; ?></span>
             <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
               <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
               <a class="a2a_button_facebook"></a>
               <a class="a2a_button_twitter"></a>
               <a class="a2a_button_email"></a>
               <a class="a2a_button_pinterest"></a>
               <a class="a2a_button_linkedin"></a>
               <a class="a2a_button_reddit"></a>
               <a class="a2a_button_whatsapp"></a>
             </div>
             <script>
             var a2a_config = a2a_config || {};
             a2a_config.locale = "tr";
             </script>
             <script async src="https://static.addtoany.com/menu/page.js"></script>
                            </div>
                            <!--// Left-->

                            <div class="right">
                                <ul class="list-wrap">
                                 <li>
                      <strong><?php echo $langArray['PROJECT_TAG']; ?></strong>
                      <?php echo $projectDataCheck['seo_keywords']; ?>
                    </li>
                                </ul>
                            </div>
                            <!--// Right-->
                        </div>
                        <!--// Blog Details Footer-->

                        <!--Comment area-->

                      
                        <!--// Comment area-->
                    </div>
                </div>

              
            </div>
        </div>
        <!--// Container-->
    </div>