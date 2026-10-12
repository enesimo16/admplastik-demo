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
    <!--Section Single Item-->
    <section class="service-single-item ">
        <div class="container">
          <?php
            $branchesIdAsk=$db->prepare("SELECT * FROM branchdealer WHERE type=:type AND status=:status ORDER BY order_sort");
            $branchesIdAsk->execute(array(
              'type'			=> 1,
              'status'		=> 1
            ));
            $lineNumber=0;
            while ($branchesIdCheck=$branchesIdAsk->fetch(PDO::FETCH_ASSOC)) {
              $lineNumber++;
              if ($lineNumber %2 != 0) :
          ?>
            <div class="row padding-20">
                <div class="col-lg-4 col-md-6">
                    <div class="thumb-wrap">
                        <div class="thumb">
                          <iframe src="<?php echo $branchesIdCheck['map']; ?>" width="536" height="376" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-content">
                        <div class="item-number">0<?= $lineNumber ?></div>
                        <div class="title-wrap">
                            <h3 class="title"><?php echo $branchesIdCheck['name']; ?></h3>
                        </div>
                        <p>
                          <?php echo $branchesIdCheck['email']; ?>
                        </p>
                        <a href="tel:<?php echo $branchesIdCheck['phone']; ?>" class="more-details"><?php echo $langArray['CLICK_CALL']; ?></a>
                    </div>
                </div>
            </div>
          <?php else: ?>
            <div class="row padding-20">
                <div class="col-lg-4 offset-lg-4 col-md-6">
                    <div class="thumb-wrap">
                        <div class="thumb">
                          <iframe src="<?php echo $branchesIdCheck['map']; ?>" width="536" height="376" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-content">
                        <div class="item-number">0<?= $lineNumber ?></div>
                        <div class="title-wrap">
                            <h3 class="title"><?php echo $branchesIdCheck['name']; ?></h3>
                        </div>
                        <p>
                          <?php echo $branchesIdCheck['email']; ?>
                        </p>
                        <a href="tel:<?php echo $branchesIdCheck['phone']; ?>" class="more-details"><?php echo $langArray['CLICK_CALL']; ?></a>
                    </div>
                </div>
            </div>
          <?php
          endif;
            }
         ?>
        </div>
    </section>
    <!--// Section Single Item-->
