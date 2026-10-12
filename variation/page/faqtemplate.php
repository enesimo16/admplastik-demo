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
<div class="accordion padding-top-110 padding-bottom-160">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="accordion-wrapper">
                    <div id="accordionOne">
                      <?php
             					  $postAsk=$db->prepare("SELECT * FROM posts WHERE post_type=:post_type AND language=:language ORDER BY order_sort");
             					 	$postAsk->execute(array(
             							'post_type'		=> 'post_faqs',
             							'language'		=> $getLangCheck['language_id']
             						));
                        $lineNumber = 0;
             						while ($faqsCheck=$postAsk->fetch(PDO::FETCH_ASSOC)) {
                          $lineNumber++;
             				 ?>
                        <div class="card">
                            <div class="card-header" id="heading-<?php echo $faqsCheck['post_id']; ?>">
                                <h5 class="mb-0">
                                    <a role="button" data-toggle="collapse" data-target="#collapse-<?php echo $faqsCheck['post_id']; ?>"
                                        aria-expanded="true" aria-controls="collapse-<?php echo $faqsCheck['post_id']; ?>">
                                        <?php echo $faqsCheck['title']; ?>
                                    </a>
                                </h5>
                            </div>
                            <div id="collapse-<?php echo $faqsCheck['post_id']; ?>" class="collapse <?= $lineNumber == "1" ? 'show' : null?>" aria-labelledby="heading-<?php echo $faqsCheck['post_id']; ?>"
                                data-parent="#accordionOne">
                                <div class="card-body">
                                    <?php echo $faqsCheck['content']; ?>
                                </div>
                            </div>
                        </div>
                      <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
