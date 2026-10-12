<?php
  $totalData = $db->query("SELECT COUNT(*) FROM posts WHERE FIND_IN_SET({$categoriesDataCheck['categories_id']},categories)")->fetchColumn();
  $show = 9;
  $totalPage = ceil($totalData / $show);
  $page = @$_GET["p"];
  if ($page < 1) $page = 1;
  if ($totalPage > 0 && $page > $totalPage) { $page = (int)$totalPage; }
  $limit = ($page - 1) * $show;
  $data = $db->prepare("SELECT * FROM posts WHERE FIND_IN_SET({$categoriesDataCheck['categories_id']},categories) ORDER BY post_id DESC LIMIT :basla, :bitir");
  $data->bindValue(":basla", $limit, PDO::PARAM_INT);
  $data->bindValue(":bitir", $show, PDO::PARAM_INT);
  $data->execute();
  $sequence = $data->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="rd-page">

  <div class="rd-page-hero" style="background-image:url('<?= $categoriesDataCheck['banner'] ?>')">
    <h1><?= rd_text(rd_key_of($categoriesDataCheck["seo_url"]), "TITLE", $categoriesDataCheck["title"]) ?></h1>
    <div class="rd-crumbs">
      <a href="<?= $generalSettingCheck['site_url'] ?>"><?= $langArray['LINK_HOME'] ?></a> / <?= rd_text(rd_key_of($categoriesDataCheck["seo_url"]), "TITLE", $categoriesDataCheck["title"]) ?>
    </div>
  </div>

  <section class="rd-block rd-white">
    <div class="rd-container">
      <?php if (!$sequence): ?>
        <div class="rd-empty-state"><?= $langArray['NO_CONTENT'] ?></div>
      <?php else: ?>
        <div class="rd-cards-row">
          <?php foreach ($sequence as $item): ?>
            <a class="rd-blog-card" href="<?= $item['seo_url'] ?>" style="min-width:0">
              <?= rd_image($item["banner"], $item["title"]) ?>
              <h3><?= $item['title'] ?></h3>
              <p class="rd-blog-excerpt"><?= rd_excerpt($item) ?></p>
              <span class="rd-link"><?= $langArray['READ_MORE'] ?> →</span>
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
