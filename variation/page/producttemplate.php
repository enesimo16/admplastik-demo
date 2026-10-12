<?php
  // Ürün kategorileri (filtre pilleri için)
  $prodCatFilterAsk = $db->prepare("SELECT * FROM categories WHERE categories_type='product_categories' AND language=:language AND status=1 ORDER BY order_sort");
  $prodCatFilterAsk->execute(array('language' => $getLangCheck['language_id']));
  $prodCategories = $prodCatFilterAsk->fetchAll(PDO::FETCH_ASSOC);

  $totalData = $db->query("SELECT COUNT(*) FROM posts WHERE post_type='post_products' AND status=1 AND language={$getLangCheck['language_id']}")->fetchColumn();
  $show = 12;
  $totalPage = ceil($totalData / $show);
  $page = @$_GET["p"];
  if ($page < 1) $page = 1;
  if ($totalPage > 0 && $page > $totalPage) { $page = (int)$totalPage; }
  $limit = ($page - 1) * $show;
  $data = $db->prepare("SELECT * FROM posts WHERE post_type='post_products' AND status=1 AND language={$getLangCheck['language_id']} ORDER BY order_sort LIMIT :basla, :bitir");
  $data->bindValue(":basla", $limit, PDO::PARAM_INT);
  $data->bindValue(":bitir", $show, PDO::PARAM_INT);
  $data->execute();
  $sequence = $data->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="rd-page">

  <section class="rd-page-hero" style="background-image:url('<?= $pageDataCheck['banner'] ?>')">
    <h1><?= $pageDataCheck['title'] ?></h1>
    <div class="rd-crumbs"><a href="<?= $generalSettingCheck['site_url'] ?>"><?= $langArray['RD_HOME'] ?></a> / <?= $langArray['LINK_PRODUCT'] ?></div>
  </section>

  <section class="rd-block rd-white rd-products-listing">
    <div class="rd-container">

      <div class="rd-filter-bar">
        <button class="rd-filter-pill rd-active" data-filter="all"><?= $langArray['RD_ALL'] ?></button>
        <?php foreach ($prodCategories as $cat): ?>
          <button class="rd-filter-pill" data-filter="<?= $cat['categories_id'] ?>"><?= $cat['title'] ?></button>
        <?php endforeach; ?>
      </div>

      <?php if (!$sequence): ?>
        <div class="rd-empty-state"><?= $langArray['NO_CONTENT'] ?></div>
      <?php else: ?>
      <div class="rd-product-grid" id="rdProductGrid">
        <?php foreach ($sequence as $item):
          $catTitle = '';
          foreach ($prodCategories as $c) { if ($c['categories_id'] == $item['categories']) { $catTitle = $c['title']; break; } }
          $prodDetailAsk = $db->prepare("SELECT * FROM products WHERE product_post_id=:id");
          $prodDetailAsk->execute(array('id' => $item['post_id']));
          $prodDetail = $prodDetailAsk->fetch(PDO::FETCH_ASSOC);
          $hasPrice = $prodDetail && (float)$prodDetail['price'] > 0;
        ?>
          <a class="rd-product-card-v2" href="<?= $item['seo_url'] ?>" data-cat="<?= $item['categories'] ?>">
            <div class="rd-product-thumb">
              <?php if (!empty($item['banner'])): ?>
                <img src="<?= $item['banner'] ?>" alt="<?= $item['title'] ?>" loading="lazy">
              <?php else: ?>
                <div class="rd-product-placeholder">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5M12 13v8"/>
                  </svg>
                  <span><?= $langArray['RD_SOON'] ?></span>
                </div>
              <?php endif; ?>
              <?php if ($catTitle): ?><span class="rd-product-badge"><?= $catTitle ?></span><?php endif; ?>
            </div>
            <div class="rd-product-body">
              <?php if ($prodDetail && $prodDetail['product_code']): ?>
                <span class="rd-product-code"><?= $prodDetail['product_code'] ?></span>
              <?php endif; ?>
              <h3><?= $item['title'] ?></h3>
              <p><?= mb_strimwidth(strip_tags($item['content']), 0, 90, '...') ?></p>
              <div class="rd-product-footer">
                <span class="rd-product-price"><?= $hasPrice ? number_format($prodDetail['price'], 2) . ' ' . $prodDetail['currency'] : $langArray['RD_QUOTE_FOR_PRICE_SHORT'] ?></span>
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
          <a class="<?= $i == $page ? 'rd-active' : '' ?>" href="<?= $pageDataCheck['seo_url'] ?>?p=<?= $i ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>

    </div>
  </section>

</div>

<script>
(function () {
  var pills = document.querySelectorAll('.rd-filter-pill');
  var cards = document.querySelectorAll('#rdProductGrid .rd-product-card-v2');

  pills.forEach(function (pill) {
    pill.addEventListener('click', function () {
      pills.forEach(function (p) { p.classList.remove('rd-active'); });
      pill.classList.add('rd-active');
      var f = pill.dataset.filter;
      cards.forEach(function (card) {
        var show = (f === 'all' || card.dataset.cat === f);
        card.style.display = show ? '' : 'none';
      });
    });
  });

  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined' && cards.length) {
    gsap.registerPlugin(ScrollTrigger);
    gsap.fromTo(cards, { opacity: 0, y: 36 }, {
      opacity: 1, y: 0, duration: .55, stagger: 0.08, ease: 'power2.out',
      scrollTrigger: { trigger: '#rdProductGrid', start: 'top 85%' }
    });
  } else {
    cards.forEach(function (c) { c.style.opacity = 1; });
  }
})();
</script>
