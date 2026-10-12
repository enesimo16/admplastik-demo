<?php
http_response_code(404);
require_once 'header.php';
?>
<!--Breadcrumb Start-->
<div class="breadcrumb-area" style="background: #000">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-inner text-center">
                    <h1 class="page-title"><?php echo $postDataCheck['title']; ?></h1>
                    <ul class="page-list">
                        <li><a href="<?php echo $generalSettingCheck['site_url']; ?>"><?php echo $langArray['LINK_HOME']; ?></a></li>
                         <li><?php echo $langArray['404_TITLE']; ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--// Breadcrumb End-->
<div class="container">
    <div class="row">
        <div class="col-lg-12 d-flex">
            <div class="padding-50 justify-content-center">
                <!-- <h2 class="title-404">404</h2> -->
                <p class="text-danger"><?php echo $langArray['404_DESC']; ?></p>
                <div class="main-btn-wrap">
                    <a href="<?php echo $generalSettingCheck['site_url']; ?>" class="main-btn"><?php echo $langArray['LINK_HOME']; ?></a>
                </div>

            </div>

        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>
