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
<!--Blog Page Content-->
<div class="blog-page-content padding-top-40 padding-bottom-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
              <?php
      					$totalData = $db->query("SELECT COUNT(*) FROM posts WHERE post_type='post_post' AND status=1 AND language={$getLangCheck['language_id']}")->fetchColumn();
      					$show = 2;
      					$totalPage = ceil($totalData / $show);
      					$page = @$_GET["p"];
      					if($page < 1) $page = 1;
      					if($totalPage > 0 && $page > $totalPage){
      						$page = (int)$totalPage;
      					}
      					$limit = ($page - 1) * $show;
      					$data = $db->prepare("SELECT * FROM posts WHERE post_type='post_post' AND status=1 AND language={$getLangCheck['language_id']} LIMIT :basla, :bitir");
      					$data->bindValue(":basla",$limit,PDO::PARAM_INT);
      					$data->bindValue(":bitir",$show,PDO::PARAM_INT);
      					$data->execute();
      					$sequence = $data->fetchAll(PDO::FETCH_OBJ);
      					if ($sequence == null) {
      					 echo '
      					 <div class="col-sm-12">
      						 <span class="alert alert-danger block">'.$langArray['NO_CONTENT'].'</span>
      					 </div>
      					 ';
      					}
      					foreach ($sequence as $item){
      				?>
                <!--Blog Single item-->
                <div class="blog-single-item margin-bottom-30">
                    <div class="row">
                        <div class="col-md-6 px-lg-0">
                            <div class="thumb-area-wrap">
                                <div class="thumb-area">
                                    <div class="thumb">
                                        <img width="536" height="500" src="<?php echo $item->banner;?>" alt="<?php echo $item->title;?>">
                                    </div>
                                </div>
                            </div>
                            <!--// Thumbnail Area-->
                        </div>
                        <div class="col-md-6 px-lg-0">
                            <div class="content">
                                <h4 class="title"><a href="<?php echo $item->seo_url;?>"><?php echo $item->title;?></a></h4>
                                <div>
                                    <p>
                                        <?php echo strip_tags(shorter($item->content, 350)); ?>
                                    </p>
                                </div>
                                <div class="read-more-area">
                                    <div class="read-more">
                                        <a href="<?php echo $item->seo_url;?>"><?= $langArray['READ_MORE'] ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--// Blog Single item-->
              <?php } ?>
                <!-- Pagination-->
                <div class="col-lg-12 padding-top-60">
                    <div class="blog-pagination text-center">
                        <ul class="page-numbers">
                            <?php for($i = 1; $i<=$totalPage;$i++){?>
                	  					<li><a class="page-numbers" href="<?php echo $pageDataCheck['seo_url']; ?>?p=<?php echo $i;?>"><?php echo $i;?></a></li>
                	          <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="widget-area">
                    <!--// Search Widget -->
                    <div class="widget widget_nav_menu blog-bg radio-button">
                        <h5 class="widget-title border-bottom"><?php echo $langArray['HEAD_CATEGORIES']; ?></h5>
                        <?php
              						 $allCategories = array();
              						 $allCatCheck=$db->query("SELECT * FROM categories where language={$getLangCheck['language_id']} and categories_type='post_categories'", PDO::FETCH_ASSOC);
              						 if ( $allCatCheck->rowCount() ){
              							 foreach( $allCatCheck as $line ){
              									$allCategories[$line['categories_id']]['categories_id'] = $line['categories_id'];
              									$allCategories[$line['categories_id']]['title'] = $line['title'];
              									$allCategories[$line['categories_id']]['categories_top_id'] = $line['categories_top_id'];
              									$allCategories[$line['categories_id']]['seo_url'] = $line['seo_url'];
              							 }
              						 }
              						 function findCategories($categories){
              								$row = array();
              								foreach($categories as $category){
              									if($category['categories_top_id'] == 0) $row[] = $category;
              								}
              								return $row;
              						 }
              						 function findSubCategories($topCat,$categories,$again = 2){
              								$row = array();
              								foreach($categories as $category){
              									if($category['categories_top_id'] == $topCat){
              										$row[] = $category;
              									}
              								}
              								if(count($row) > 0){
              									foreach($row as $cat){
              										echo '<li><a href="'.$cat['seo_url'].'">'.str_repeat('- ',$again).' '.$cat['title'].'</a></li>';
              										findSubCategories($cat['categories_id'],$categories,$again+1);
              									}
              								}
              						 }
              						 $mainCategories = findCategories($allCategories);
              						 echo '
              							 <ul>
              						 ';
              							foreach($mainCategories as $category){
              								echo '
              								 <li><a href="'.$category['seo_url'].'">'.str_repeat('- ',0).' '.$category['title'].'</a></li>
              								';
              								findSubCategories($category['categories_id'],$allCategories);
              							}
              						 echo '</ul>';
            						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--// Container-->
</div>
<!--// Blog Page Content-->
