<div class="rd-page">

  <div class="rd-page-hero" style="background-image:url('<?= $pageDataCheck['banner'] ?>')">
    <h1><?= $pageDataCheck['title'] ?></h1>
    <div class="rd-crumbs">
      <a href="<?= $generalSettingCheck['site_url'] ?>"><?= $langArray['LINK_HOME'] ?></a> / <?= $pageDataCheck['title'] ?>
    </div>
  </div>

  <section class="rd-block rd-white">
    <div class="rd-container rd-post-article">
      <?= $pageDataCheck['content'] ?>
    </div>
  </section>

</div>
