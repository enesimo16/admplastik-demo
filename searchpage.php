<?php
  require_once 'header.php';

  $q = isset($_GET['q']) ? trim(strip_tags($_GET['q'])) : '';
  $results = array();
  if (mb_strlen($q) >= 2) {
    $searchAsk = $db->prepare("SELECT post_id, title, content, seo_url, post_type, banner FROM posts
      WHERE status=1 AND language=:language AND (title LIKE :q1 OR content LIKE :q2)
      ORDER BY FIELD(post_type,'post_products','post_services','post_page','post_post'), order_sort LIMIT 40");
    $searchAsk->execute(array(
      'language' => $getLangCheck['language_id'],
      'q1' => '%' . $q . '%',
      'q2' => '%' . $q . '%'
    ));
    $results = $searchAsk->fetchAll(PDO::FETCH_ASSOC);
  }

  $typeLabels = array(
    'post_products' => $langArray['RD_SEARCH_TYPE_PRODUCT'],
    'post_services' => $langArray['RD_SEARCH_TYPE_SERVICE'],
    'post_page'     => $langArray['RD_SEARCH_TYPE_PAGE'],
    'post_post'     => $langArray['RD_SEARCH_TYPE_POST'],
    'post_notices'  => $langArray['RD_SEARCH_TYPE_NOTICE'],
    'post_Gallery'  => $langArray['RD_SEARCH_TYPE_GALLERY'],
    'post_projects' => $langArray['RD_SEARCH_TYPE_PROJECT']
  );
?>
<div class="rd-page">

  <div class="rd-page-hero" style="background-image:url('uploads/contact.jpg')">
    <h1><?= $langArray['RD_SEARCH'] ?></h1>
    <div class="rd-crumbs"><a href="<?= $generalSettingCheck['site_url'] ?>"><?= $langArray['RD_HOME'] ?></a> / <?= $langArray['RD_SEARCH'] ?></div>
  </div>

  <section class="rd-block rd-white">
    <div class="rd-container" style="max-width:860px">
      <form class="rd-searchpage-form" action="searchpage.php" method="get">
        <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="<?= $langArray['RD_SEARCH_PLACEHOLDER'] ?>" autofocus>
        <button type="submit" class="rd-btn-primary"><?= $langArray['RD_SEARCH'] ?></button>
      </form>

      <?php if ($q === ''): ?>
        <p class="rd-search-hint"><?= $langArray['RD_SEARCH_HINT_EMPTY'] ?></p>
      <?php elseif (!$results): ?>
        <div class="rd-empty-state">"<strong><?= htmlspecialchars($q) ?></strong>" <?= $langArray['RD_SEARCH_NOT_FOUND'] ?></div>
      <?php else: ?>
        <p class="rd-search-hint"><strong><?= count($results) ?></strong> <?= $langArray['RD_SEARCH_RESULTS_FOUND'] ?></p>
        <div class="rd-search-results">
          <?php foreach ($results as $r): ?>
            <a class="rd-search-result" href="<?= $r['seo_url'] ?>">
              <span class="rd-search-result-type"><?= $typeLabels[$r['post_type']] ?? $langArray['RD_SEARCH_TYPE_CONTENT'] ?></span>
              <h3><?= $r['title'] ?></h3>
              <p><?= mb_strimwidth(strip_tags($r['content']), 0, 140, '...') ?></p>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

</div>
<?php require_once 'footer.php'; ?>
