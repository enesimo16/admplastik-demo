<?php
  $getPostAsk->execute(array(
    'seo_url'    => $_GET["seo_url"],
    'post_type'  => 'post_products'
  ));
  $productDataCheck=$getPostAsk->fetch(PDO::FETCH_ASSOC);

  $productDetailAsk=$db->prepare("SELECT * FROM products WHERE product_post_id=:id");
  $productDetailAsk->execute(array('id' => $productDataCheck['post_id']));
  $productDetailCheck=$productDetailAsk->fetch(PDO::FETCH_ASSOC);

  $prodCatAsk = $db->prepare("SELECT * FROM categories WHERE categories_id=:id");
  $prodCatAsk->execute(array('id' => $productDataCheck['categories']));
  $prodCatCheck = $prodCatAsk->fetch(PDO::FETCH_ASSOC);

  $relatedAsk = $db->prepare("SELECT * FROM posts WHERE post_type='post_products' AND categories=:cat AND status=1 AND post_id!=:id ORDER BY order_sort LIMIT 3");
  $relatedAsk->execute(array('cat' => $productDataCheck['categories'], 'id' => $productDataCheck['post_id']));
  $relatedProducts = $relatedAsk->fetchAll(PDO::FETCH_ASSOC);

  $hasPrice = $productDetailCheck && (float)$productDetailCheck['price'] > 0;
  $urlProductsIdx = rd_url('products-index', 'sayfa-urunler');
?>
<div class="rd-page">

  <div class="rd-page-hero" style="background-image:url('<?= $productDataCheck['banner'] ?>')">
    <h1><?= $productDataCheck['title'] ?></h1>
    <div class="rd-crumbs">
      <a href="<?= $generalSettingCheck['site_url'] ?>"><?= $langArray['RD_HOME'] ?></a> /
      <a href="<?= $urlProductsIdx ?>"><?= $langArray['LINK_PRODUCT'] ?></a>
      <?php if ($prodCatCheck): ?> / <?= $prodCatCheck['title'] ?><?php endif; ?>
    </div>
  </div>

  <section class="rd-block rd-white">
    <div class="rd-container rd-product-detail">
      <div class="rd-product-detail-media">
        <?php if (!empty($productDataCheck['banner'])): ?>
          <img src="<?= $productDataCheck['banner'] ?>" alt="<?= $productDataCheck['title'] ?>">
        <?php else: ?>
          <div class="rd-product-placeholder rd-product-placeholder-lg">
            <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3">
              <path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5M12 13v8"/>
            </svg>
            <span><?= $langArray['RD_SOON'] ?></span>
          </div>
        <?php endif; ?>
      </div>

      <div class="rd-product-detail-info">
        <?php if ($prodCatCheck): ?><span class="rd-product-badge-static"><?= $prodCatCheck['title'] ?></span><?php endif; ?>
        <h2><?= $productDataCheck['title'] ?></h2>
        <?php if ($productDetailCheck && $productDetailCheck['product_code']): ?>
          <div class="rd-product-meta"><?= $langArray['RD_PRODUCT_CODE'] ?> <strong><?= $productDetailCheck['product_code'] ?></strong></div>
        <?php endif; ?>
        <div class="rd-product-price-lg">
          <?= $hasPrice ? number_format($productDetailCheck['price'], 2) . ' ' . $productDetailCheck['currency'] : $langArray['RD_QUOTE_FOR_PRICE'] ?>
        </div>
        <div class="rd-product-desc"><?= $productDataCheck['content'] ?></div>
        <a href="#" class="rd-btn-primary" data-rd-modal-open><?= $langArray['RD_GET_QUOTE'] ?></a>
      </div>
    </div>
  </section>

  <?php if ($relatedProducts): ?>
  <section class="rd-block rd-tinted">
    <div class="rd-container">
      <div class="rd-section-heading">
        <span class="rd-eyebrow" style="color:var(--rd-green-dark)"><?= $langArray['RD_SIMILAR_PRODUCTS'] ?></span>
        <h2><?= $langArray['RD_SIMILAR_PRODUCTS_SUB'] ?></h2>
      </div>
      <div class="rd-product-grid">
        <?php foreach ($relatedProducts as $rel): ?>
          <a class="rd-product-card-v2" href="<?= $rel['seo_url'] ?>">
            <div class="rd-product-thumb">
              <?php if (!empty($rel['banner'])): ?>
                <img src="<?= $rel['banner'] ?>" alt="<?= $rel['title'] ?>">
              <?php else: ?>
                <div class="rd-product-placeholder">
                  <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5M12 13v8"/>
                  </svg>
                </div>
              <?php endif; ?>
            </div>
            <div class="rd-product-body">
              <h3><?= $rel['title'] ?></h3>
              <div class="rd-product-footer">
                <span class="rd-product-arrow">→</span>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

</div>
