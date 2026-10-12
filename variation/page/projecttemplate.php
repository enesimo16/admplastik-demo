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
<div class="blog-page-content padding-bottom-160">
		<div class="container">
				<div class="row">
						<div class="col-lg-8">
								<div class="row">
                  <?php
                    $totalData = $db->query("SELECT COUNT(*) FROM posts WHERE post_type='post_projects' AND status=1 AND language={$getLangCheck['language_id']}")->fetchColumn();
                    $show = 6;
                    $totalPage = ceil($totalData / $show);
                    $page = @$_GET["p"];
                    if($page < 1) $page = 1;
                    if($totalPage > 0 && $page > $totalPage){
                      $page = (int)$totalPage;
                    }
                    $limit = ($page - 1) * $show;
                    $data = $db->prepare("SELECT * FROM posts WHERE post_type='post_projects' AND status=1 AND language={$getLangCheck['language_id']} LIMIT :basla, :bitir");
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
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <!-- Awesome Blog Single Items-->
                        <div class="h5-awesome-blog-single-item">
                            <div class="thumb-area">
                                <img width="250" height="200" src="<?php echo $item->banner;?>" alt="<?php echo $item->title;?>">
                            </div>
                            <div class="content-area">
                                <h5 class="title padding-top-20">
                                  <a href="<?php echo $item->seo_url;?>"><?php echo $item->title;?></a>
                                </h5>
                            </div>
                        </div>
                        <!--// Awesome Blog Single Items-->
                    </div>
                  <?php } ?>
                  <div class="col-lg-12 padding-top-20">
                     <div class="blog-pagination text-center">
                        <ul class="page-numbers">
                          <?php for($i = 1; $i<=$totalPage;$i++){?>
                            <li><a class="page-numbers" href="<?=  $pageDataCheck['seo_url'] ?>?p=<?php echo $i;?>"><?php echo $i;?></a></li>
                          <?php } ?>
                        </ul>
                     </div>
                  </div>
                </div>
						</div>
						<div class="col-lg-4 padding-top-40">
								<div class="widget-area">
										<!--// Search Widget -->
										<div class="widget widget_nav_menu blog-bg radio-button">
												<h5 class="widget-title border-bottom "><?php echo $langArray['HEAD_CATEGORIES']; ?></h5>
                        <ul>
                          <?php
                             $allCategoriesAsk->execute(array(
                               'categories_type' => "project_categories",
                               'language'        => $getLangCheck['language_id']
                             ));
                             while ($postCatCheck=$allCategoriesAsk->fetch(PDO::FETCH_ASSOC)){
                           ?>
                             <li><a href="<?php echo $generalSettingCheck['site_url'] . $postCatCheck['seo_url']; ?>"><?php echo $postCatCheck['title']; ?></a></li>
                           <?php } ?>
                        </ul>
										</div>
								</div>
						</div>
				</div>
		</div>
		<!--// Container-->
</div>
<!--// Blog Page Content-->
