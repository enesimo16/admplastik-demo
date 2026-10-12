<!--Breadcrumb Start-->
<div class="breadcrumb-area" style="background-image: url(<?php echo $pageDataCheck['banner']; ?>)">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-inner text-center">
                    <h1 class="page-title"><?php echo $pageDataCheck['title']; ?></h1>
                    <ul class="page-list">
                        <li><a href="<?php echo $generalSettingCheck['site_url']; ?>"><?php echo $langArray['LINK_HOME']; ?></a></li>
                        <li><?php echo $pageDataCheck['title']; ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--// Breadcrumb End-->
<section class="process-section padding-50">
    <div class="container">
        <div class="row">
          <?php
      				 $postAsk=$db->prepare("SELECT * FROM catalog WHERE status=:status AND language=:language ORDER BY order_sort");
      				 $postAsk->execute(array(
      					 'status'		=> 1,
      					 'language'		=> $getLangCheck['language_id']
      				 ));
      				 while ($catalogCheck=$postAsk->fetch(PDO::FETCH_ASSOC)) {
      			?>
              <div class="col-lg-4">
                  <!--Process left Content-->
                  <div class="process-section__left__items">
                      <div class="thumb-area">
                          <div class="thumb">
                            <a data-fancybox data-type="iframe" data-src="<?php echo $catalogCheck['link'];?>" href="javascript:;">
                              <img src="<?php echo $catalogCheck['image'];?>" alt="<?php echo $catalogCheck['title'];?>">
                            </a>
                          </div>
                      </div>
                      <div class="content-area">
                          <h4 class="title"><?php echo $catalogCheck['name'];?></h4>
                      </div>
                  </div>
                  <!--// Process item-->
              </div>
            <?php } ?>
        </div>
    </div>
</section> 
