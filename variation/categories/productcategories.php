<?php
  $totalData = $db->query("SELECT COUNT(*) FROM posts WHERE categories={$categoriesDataCheck['categories_id']}")->fetchColumn();
  $show = 9;
  $totalPage = ceil($totalData / $show);
  $page = @$_GET["p"];
  if ($page < 1) $page = 1;
  if ($totalPage > 0 && $page > $totalPage) { $page = (int)$totalPage; }
  $limit = ($page - 1) * $show;
  $data = $db->prepare("SELECT * FROM posts WHERE categories={$categoriesDataCheck['categories_id']} ORDER BY order_sort LIMIT :basla, :bitir");
  $data->bindValue(":basla", $limit, PDO::PARAM_INT);
  $data->bindValue(":bitir", $show, PDO::PARAM_INT);
  $data->execute();
  $sequence = $data->fetchAll(PDO::FETCH_ASSOC);

  $urlProductsIdx = rd_url('products-index', 'sayfa-urunler');
?>
<div class="rd-page">

  <div class="rd-page-hero" style="background-image:url('<?= $categoriesDataCheck['banner'] ?>')">
    <h1><?= $categoriesDataCheck['title'] ?></h1>
    <div class="rd-crumbs">
      <a href="<?= $generalSettingCheck['site_url'] ?>"><?= $langArray['RD_HOME'] ?></a> /
      <a href="<?= $urlProductsIdx ?>"><?= $langArray['LINK_PRODUCT'] ?></a> /
      <?= $categoriesDataCheck['title'] ?>
    </div>
  </div>

  <section class="rd-block rd-white">
    <div class="rd-container">
      <?php if (!$sequence): ?>
        <div class="rd-empty-state"><?= $langArray['NO_CONTENT'] ?></div>
      <?php else: ?>
        <div class="rd-product-grid">
          <?php foreach ($sequence as $item): ?>
            <a class="rd-product-card-v2" href="<?= $item['seo_url'] ?>">
              <div class="rd-product-thumb">
                <?php if (!empty($item['banner'])): ?>
                  <img src="<?= $item['banner'] ?>" alt="<?= $item['title'] ?>">
                <?php else: ?>
                  <div class="rd-product-placeholder">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                      <path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5M12 13v8"/>
                    </svg>
                    <span><?= $langArray['RD_SOON'] ?></span>
                  </div>
                <?php endif; ?>
              </div>
              <div class="rd-product-body">
                <h3><?= $item['title'] ?></h3>
                <div class="rd-product-footer">
                  <span class="rd-product-arrow">→</span>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ($totalPage > 1): ?>
      <div class="rd-pagination">
        <?php for ($i = 1; $i <= $totalPage; $i++): ?>
          <a class="<?= $i == $page ? 'rd-active' : '' ?>" href="<?= $categoriesDataCheck['seo_url'] ?>?p=<?= $i ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>

</div>
