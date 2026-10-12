<?php
  $getPostAsk->execute(array(
    'seo_url'    => $_GET["seo_url"],
    'post_type'  => 'post_post'
  ));
  $postDataCheck=$getPostAsk->fetch(PDO::FETCH_ASSOC);

  // Diğer haberler
  $otherNewsAsk = $db->prepare("SELECT * FROM posts WHERE post_type='post_post' AND status=1 AND language=:language AND post_id!=:id ORDER BY post_id DESC LIMIT 3");
  $otherNewsAsk->execute(array('language' => $getLangCheck['language_id'], 'id' => $postDataCheck['post_id']));
  $otherNews = $otherNewsAsk->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="rd-page">

  <div class="rd-page-hero" style="background-image:url('<?= $postDataCheck['banner'] ?>')">
    <h1><?= $postDataCheck['title'] ?></h1>
    <div class="rd-crumbs">
      <a href="<?= $generalSettingCheck['site_url'] ?>"><?= $langArray['LINK_HOME'] ?></a> / <?= $langArray['LINK_BLOG'] ?>
    </div>
  </div>

  <section class="rd-block rd-white">
    <div class="rd-container rd-post-article">
      <?= $postDataCheck['content'] ?>
    </div>
  </section>

  <?php if ($otherNews): ?>
  <section class="rd-block rd-tinted">
    <div class="rd-container">
      <div class="rd-section-heading">
        <span class="rd-eyebrow" style="color:var(--rd-green-dark)">DİĞER HABERLER</span>
        <h2>Bunlar da ilginizi çekebilir</h2>
      </div>
      <div class="rd-cards-row">
        <?php foreach ($otherNews as $n): ?>
          <a class="rd-blog-card" href="<?= $n['seo_url'] ?>" style="min-width:0">
            <?= rd_image($n["banner"], $n["title"]) ?>
            <h3><?= $n['title'] ?></h3>
            <span class="rd-link"><?= $langArray['READ_MORE'] ?> →</span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

</div>
