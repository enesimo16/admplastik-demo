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
												 $postAsk=$db->prepare("SELECT * FROM banks WHERE status=:status ORDER BY order_sort");
												 $postAsk->execute(array(
													 'status'		=> 1
												 ));
												 $lineNumber = 0;
												 while ($banksCheck=$postAsk->fetch(PDO::FETCH_ASSOC)) {
													$lineNumber++;
											?>
												<div class="card">
														<div class="card-header" id="heading-<?php echo $banksCheck['bank_id']; ?>">
																<h5 class="mb-0">
																		<a role="button" data-toggle="collapse" data-target="#collapse-<?php echo $banksCheck['bank_id']; ?>"
																				aria-expanded="true" aria-controls="collapse-<?php echo $banksCheck['bank_id']; ?>">
																				<?php echo $banksCheck['bank_name']; ?>
																		</a>
																</h5>
														</div>
														<div id="collapse-<?php echo $banksCheck['bank_id']; ?>" class="collapse <?= $lineNumber == "1" ? 'show' : null?>" aria-labelledby="heading-<?php echo $banksCheck['bank_id']; ?>"
																data-parent="#accordionOne">
																<div class="card-body">
																	<strong><?php echo $langArray['BANK_TABLE_ACCOUNT_NAME']; ?>:</strong> <?php echo $banksCheck['account_name']; ?><br>
																 	<strong><?php echo $langArray['BANK_TABLE_BRANCH_CODE']; ?>:</strong> <?php echo $banksCheck['branch_code']; ?><br>
																 	<strong><?php echo $langArray['BANK_TABLE_ACCOUNT_NUMBER']; ?>:</strong> <?php echo $banksCheck['account_number']; ?><br>
																 	<strong><?php echo $langArray['BANK_TABLE_BANK_IBAN']; ?>:</strong> <?php echo $banksCheck['bank_iban']; ?><br>
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
