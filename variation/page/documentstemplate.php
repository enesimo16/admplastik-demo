<?php
  $docAsk=$db->prepare("SELECT * FROM document WHERE status=:status AND language=:language ORDER BY order_sort");
  $docAsk->execute(array('status' => 1, 'language' => $getLangCheck['language_id']));
  $documents = $docAsk->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="rd-page">

  <div class="rd-page-hero" style="background-image:url('<?= $pageDataCheck['banner'] ?>')">
    <h1><?= $pageDataCheck['title'] ?></h1>
    <div class="rd-crumbs">
      <a href="<?= $generalSettingCheck['site_url'] ?>"><?= $langArray['LINK_HOME'] ?></a> / <?= $pageDataCheck['title'] ?>
    </div>
  </div>

  <section class="rd-block rd-white">
    <div class="rd-container">
      <?php if (!$documents): ?>
        <div class="rd-cert-intro">
          <?= $langArray['RD_CERT_INTRO'] ?>
        </div>
        <div class="rd-cert-row">
          <?php foreach (array('ISO 9001:2015','ISO 14001:2015','ISO 45001','IATF 16949','CE','TSE') as $cert): ?>
            <div class="rd-cert-card rd-in-view">
              <div class="rd-cert-badge">DOC</div>
              <h4><?= $cert ?></h4>
              <span><?= $langArray['RD_SOON'] ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="rd-cert-row">
          <?php foreach ($documents as $doc): ?>
            <a class="rd-cert-card rd-in-view rd-cert-card-doc" data-fancybox href="<?= $doc['image'] ?>">
              <img src="<?= $doc['image'] ?>" alt="<?= $doc['name'] ?>">
              <h4><?= $doc['name'] ?></h4>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

</div>
