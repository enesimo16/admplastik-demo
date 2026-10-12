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
<div class="our-team-section padding-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="padding-top-70">
                    <div class="h2-our-team-slider-active row">
                      <?php
                        $referenceAsk->execute(array(
                          'status'          => 1,
                          'language'        => $getLangCheck['language_id']
                        ));
                        while ($referenceCheck=$referenceAsk->fetch(PDO::FETCH_ASSOC)){
                          $x = explode("/", $referenceCheck['banner']);
                          $y = explode('.', $x[1]);
                      ?>
                        <div class="col-lg-12">
                            <div class="home-2-our-team-items">
                                <div class="items-inner">
                                    <!-- Team image-->
                                    <div class="team-thumb-area" style="border:none;">
                                        <div class="thumb" style="margin-bottom:0 !important">
                                            <img width="200" height="100" src="<?php echo "uploads/".$y[0].'-200x100'.'.'.$y[1]; ?>" alt="<?php echo $referenceCheck['name']; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      <?php } ?>
                    </div>
                    <div class="team-slider-arrow"></div>
                </div>
            </div>
        </div>
    </div>
</div>
