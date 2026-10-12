<?php
  $getPostAsk->execute(array(
    'seo_url'    => $_GET["seo_url"],
    'post_type'  => 'post_Gallery'
  ));
  $postDataCheck=$getPostAsk->fetch(PDO::FETCH_ASSOC);
?>
<!--Breadcrumb Start-->
<div class="breadcrumb-area" style="background-image: url(<?php echo $postDataCheck['banner']; ?>)">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-inner text-center">
                    <h1 class="page-title"><?php echo $postDataCheck['title']; ?></h1>
                    <ul class="page-list">
                        <li><a href="<?php echo $generalSettingCheck['site_url']; ?>"><?php echo $langArray['LINK_HOME']; ?></a></li>
                         <li><?php echo $postDataCheck['title']; ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--// Breadcrumb End-->
<!--About Story Start-->
<section class="about-story-section padding-top-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="common-section-content">
                    <!--Section Title-->
                    <div class="section-title padding-bottom-20">
                        <h2 class="heading-02"><?php echo $postDataCheck['title']; ?></h2>
                    </div>
                    <!--// Section Title End-->
                    <div class="paragraph padding-bottom-15">
                        <?php echo $postDataCheck['content']; ?>
                    </div>
                </div>
            </div>
        </div>
        <!--// Row-->
    </div>
    <!--// Container-->
</section>
<section class="process-section padding-50">
    <div class="container">
        <div class="row">
          <?php
             $typeandid = 'image_gallery-'.$postDataCheck['post_id'];
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
                            <a data-fancybox href="<?php echo $allTypeCheck['media_large'];?>">
                              <img src="<?php echo $allTypeCheck['media_large'];?>">
                            </a>
                          </div>
                      </div>
                  </div>
                  <!--// Process item-->
              </div>
            <?php } ?>
        </div>
    </div>
</section>
