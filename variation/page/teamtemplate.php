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
                    <div class="section-title-wrap text-center">
                        <div class="section-title">
                            <div class="padding-bottom-10">
                                <h6 class="title both-line uppercase gray"> <?php echo  $langArray['TEAM_DESC']; ?> </h6>
                            </div>
                            <h2 class="heading-02"><?php echo  $langArray['TEAM_HEAD']; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="padding-top-70">
                        <div class="h2-our-team-slider-active row">
                          <?php
                           $personAsk->execute(array(
                             'status'          => 1
                           ));
                           while ($personCheck=$personAsk->fetch(PDO::FETCH_ASSOC)){
                         ?>
                            <div class="col-lg-12">
                                <div class="home-2-our-team-items">
                                    <div class="items-inner">
                                        <!-- Team image-->
                                        <div class="team-thumb-area">
                                            <div class="thumb">
                                                <img width="235" height="235" src="<?php echo $personCheck['banner']; ?>" alt="<?php echo $personCheck['name']; ?>">
                                                <ul class="social-icon">
                                                  <?php
                                                    $personSocialAsk->execute(array(
                                                      'status'        => 1,
                                                      'personel_id'   => $personCheck['person_id']
                                                    ));
                                                    while ($personSocialCheck=$personSocialAsk->fetch(PDO::FETCH_ASSOC)){
                                                  ?>
                                                    <li>
                                                      <a class="icon" href="<?php echo $personSocialCheck['link']; ?>" target="_blank">
                                                        <i class="fa <?php echo $personSocialCheck['icon']; ?>"></i>
                                                      </a>
                                                    </li>
                                                  <?php } ?>
                                                </ul>
                                            </div>
                                            <div class="number">
                                                <span class="icon">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                                <?php echo $personCheck['email']; ?>
                                            </div>
                                        </div>
                                        <!--// Team image-->
                                        <div class="team-content-area">
                                            <div class="content-bg">
                                                <h4 class="name"><?php echo $personCheck['name']; ?></h4>
                                                <span class="designation"><?php echo $personCheck['mission']; ?></span>
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
