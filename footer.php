  <!-- Redesign 2026 footer (Piedmont-inspired) -->
  <?php
    $ftUrlProducts = rd_url('products-index', 'sayfa-urunler');
    $ftUrlAuto     = rd_url('service-automotive', 'hizmet-otomotiv');
    $ftUrlAgri     = rd_url('service-agri', 'hizmet-is-ve-tarim-makinalari');
    $ftUrlRail     = rd_url('service-rail', 'hizmet-rayli-sistemler');
    $ftUrlAbout    = rd_url('about-main', 'sayfa-hakkimizda');
    $ftUrlPolicy   = rd_url('policy-quality', 'sayfa-kalite-politikasi');
    $ftUrlSkillArge = rd_url('skill-arge', 'sayfa-arge');
    $ftUrlSkillMuh  = rd_url('skill-muh', 'sayfa-muhendislik');
    $ftUrlSkillLab  = rd_url('skill-lab', 'sayfa-laboratuvar');
    $ftUrlSkillAdt  = rd_url('skill-adt', 'sayfa-adt-standartlari');
    $ftUrlContact  = rd_url('contact-index', 'sayfa-iletisim');

    // Sayfa başlığını seo_url ile bulur, arayüz diline göre çevirir (bkz. site-config.php)
    function ft_title($pageKey, $fallback) {
      global $db;
      static $cache = array();
      if (isset($cache[$pageKey])) return $cache[$pageKey];
      $ask = $db->prepare("SELECT title FROM posts WHERE seo_url=:slug LIMIT 1");
      $ask->execute(array('slug' => rd_slug($pageKey)));
      $title = $ask->fetchColumn() ?: $fallback;
      $cache[$pageKey] = rd_text($pageKey, 'TITLE', $title);
      return $cache[$pageKey];
    }
    $ftTitleAuto = ft_title('service-automotive', 'Otomotiv');
    $ftTitleAgri = ft_title('service-agri', 'İş ve Tarım Makinaları');
    $ftTitleRail = ft_title('service-rail', 'Raylı Sistemler');
    $ftTitleArge = ft_title('skill-arge', 'AR-GE');
    $ftTitleMuh  = ft_title('skill-muh', 'Mühendislik');
    $ftTitleLab  = ft_title('skill-lab', 'Laboratuvar');
    $ftTitleAdt  = ft_title('skill-adt', 'ADT Standartları');
  ?>
  <footer class="rd-footer-v2">
      <div class="rd-container rd-footer-main">
          <div class="rd-footer-links">
              <div class="rd-footer-col">
                  <h4><?= $langArray['RD_FOOTER_PRODUCTS'] ?></h4>
                  <ul>
                      <li><a href="<?= $ftUrlProducts ?>"><?= $langArray['RD_FOOTER_ALL_PRODUCTS'] ?></a></li>
                      <li><a href="<?= $ftUrlAuto ?>#sector-otomotiv"><?= $ftTitleAuto ?></a></li>
                      <li><a href="<?= $ftUrlAgri ?>#sector-is-ve-tarim-makinalari"><?= $ftTitleAgri ?></a></li>
                      <li><a href="<?= $ftUrlRail ?>#sector-rayli-sistemler"><?= $ftTitleRail ?></a></li>
                  </ul>
              </div>
              <div class="rd-footer-col">
                  <h4><?= $langArray['RD_FOOTER_CORPORATE'] ?></h4>
                  <ul>
                      <li><a href="<?= $ftUrlAbout ?>#hakkimizda"><?= $langArray['RD_TAB_ABOUT'] ?></a></li>
                      <li><a href="<?= $ftUrlAbout ?>#misyon-vizyon"><?= $langArray['RD_TAB_MV'] ?></a></li>
                      <li><a href="<?= $ftUrlAbout ?>#sertifikalar"><?= $langArray['RD_TAB_CERT'] ?></a></li>
                      <li><a href="<?= $ftUrlPolicy ?>"><?= $langArray['RD_FOOTER_QUALITY_POLICY'] ?></a></li>
                  </ul>
              </div>
              <div class="rd-footer-col">
                  <h4><?= $langArray['RD_FOOTER_SKILLS'] ?></h4>
                  <ul>
                      <li><a href="<?= $ftUrlSkillArge ?>"><?= $ftTitleArge ?></a></li>
                      <li><a href="<?= $ftUrlSkillMuh ?>"><?= $ftTitleMuh ?></a></li>
                      <li><a href="<?= $ftUrlSkillLab ?>"><?= $ftTitleLab ?></a></li>
                      <li><a href="<?= $ftUrlSkillAdt ?>"><?= $ftTitleAdt ?></a></li>
                  </ul>
              </div>
              <div class="rd-footer-col">
                  <h4><?= $langArray['RD_FOOTER_RESOURCES'] ?></h4>
                  <ul>
                      <?php
                         $footerMenuAsk=$db->prepare("SELECT * FROM menu WHERE  status=:status AND footer_menu=:footer_menu AND language=:language  ORDER BY order_sort");
                         $footerMenuAsk->execute(array(
                           'status'      => 1,
                           'footer_menu' => 1,
                           'language'    => $getLangCheck['language_id']
                         ));
                         // Bu dilde footer menüsü yoksa Türkçe'ye düş
                         if ($footerMenuAsk->rowCount() == 0) {
                           $trLangAsk2 = $db->prepare("SELECT language_id FROM language WHERE country_code='tr'");
                           $trLangAsk2->execute();
                           $footerMenuAsk->execute(array(
                             'status'      => 1,
                             'footer_menu' => 1,
                             'language'    => $trLangAsk2->fetchColumn()
                           ));
                         }
                         while($footerMenuCheck=$footerMenuAsk->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                           <li>
                             <?php if($footerMenuCheck['ready_link']=="manualLink"){ ?>
                               <a <?= $footerMenuCheck['new_tab'] == 1 ? 'target="_blank"' : ''; ?> href="<?= $footerMenuCheck['link']?>"><?= rd_menu_label($footerMenuCheck["title"]) ?></a>
                             <?php }else{
                               $menulinkid=$footerMenuCheck['ready_link'];
                               $pageLinkAsk=$db->prepare("SELECT * FROM posts WHERE post_id=$menulinkid");
                               $pageLinkAsk->execute();
                               $pageLinkCheck=$pageLinkAsk->fetch(PDO::FETCH_ASSOC);
                             ?>
                               <a <?= $footerMenuCheck['new_tab'] == 1 ? 'target="_blank"' : ''; ?>  href="<?= $pageLinkCheck['seo_url']; ?>"><?= rd_menu_label($footerMenuCheck["title"]) ?></a>
                             <?php } ?>
                           </li>
                         <?php } ?>
                      <li><a href="<?= $ftUrlContact ?>"><?= $langArray['RD_CONTACT_TITLE_SHORT'] ?? $langArray['RD_CONTACT_GET_IN_TOUCH'] ?></a></li>
                  </ul>
              </div>
          </div>

          <div class="rd-footer-social-col">
              <?php
                  $socialAsk->execute(array('status' => 1));
                  while ($socialCheck=$socialAsk->fetch(PDO::FETCH_ASSOC)){
                    // Gerçek hesap adresi girilene kadar ikon görünür kalsın
                    // ama hiçbir yere gitmesin (kurulumdan kalan yer tutucu
                    // adrese tıklanmasın diye).
                    $rdHasRealLink = rd_is_real_social_link($socialCheck['link']);
              ?>
                <a<?= $rdHasRealLink ? ' target="_blank" rel="noopener" href="' . htmlspecialchars($socialCheck['link']) . '"' : ' href="javascript:void(0)" aria-disabled="true" class="rd-social-empty"' ?> title="<?= htmlspecialchars($socialCheck['name']) ?>">
                  <i class="fa <?= htmlspecialchars($socialCheck['icon']) ?>"></i>
                </a>
              <?php } ?>
          </div>

          <div class="rd-footer-divider-v"></div>

          <div class="rd-footer-help">
              <h3><?= $langArray['RD_FOOTER_NEED_HELP'] ?></h3>
              <p><?= $langArray['RD_FOOTER_WORKHOURS_LABEL'] ?><br><?= $generalSettingCheck['work_time'] ?: $langArray['RD_FOOTER_WORKHOURS_DEFAULT'] ?></p>
              <?php $rdFooterTelHref = preg_replace('/[^0-9+]/', '', $generalSettingCheck['contact_land_phone'] ?? ''); ?>
              <div class="rd-help-actions">
                  <a class="rd-help-item" href="tel:<?= $rdFooterTelHref ?>">
                      <span class="rd-help-icon"><?= rd_icon('phone') ?></span>
                      <span><?= $langArray['RD_FOOTER_CALL_US'] ?></span>
                  </a>
                  <a class="rd-help-item" href="mailto:<?= $generalSettingCheck['site_email'] ?>">
                      <span class="rd-help-icon"><?= rd_icon('mail') ?></span>
                      <span><?= $langArray['RD_FOOTER_EMAIL_US'] ?></span>
                  </a>
              </div>
          </div>
      </div>

      <div class="rd-footer-bottom">
          <div class="rd-container rd-footer-bottom-inner">
              <div class="rd-copyright">&copy; <?php echo $generalSettingCheck['site_copyright']; ?></div>
              <div class="rd-footer-bottom-links">
                  <a href="<?php echo $generalSettingCheck['webmaster_link'] ?>"><?php echo $generalSettingCheck['webmaster_name'] ?></a>
                  <button type="button" class="rd-cookie-settings" data-cookie-settings>Çerez ayarları</button>
              </div>
          </div>
      </div>
  </footer>
  <!-- Redesign footer end -->

  <!-- Redesign 2026: floating action buttons -->
  <?php
    $rdBackToTopLabel = ($getLangCheck['country_code'] ?? 'tr') === 'en' ? 'Back to top' : 'Yukarı çık';
    $rdChatFabLabel = ($getLangCheck['country_code'] ?? 'tr') === 'en' ? 'Live support' : 'Canlı destek';
  ?>
  <button class="rd-back-to-top" id="rdBackToTop" type="button" aria-label="<?= $rdBackToTopLabel ?>">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 15l-6-6-6 6"/></svg>
  </button>
  <?php $rdWhatsappDigits = rd_whatsapp_number($generalSettingCheck['contact_whatsapp'] ?? ''); ?>
  <?php if ($rdWhatsappDigits): ?>
  <a class="rd-whatsapp-fab" href="https://wa.me/<?= $rdWhatsappDigits ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
      <path d="M3.5 20.5l1.3-4.2a8 8 0 111.9 2l-3.2 2.2z"/>
      <path d="M8.3 8.6c.2-.5.5-.5.7-.5h.5c.2 0 .4 0 .6.4.2.4.6 1.4.7 1.5.1.1.1.3 0 .5-.1.2-.2.3-.3.4-.2.2-.3.3-.1.6.2.4 1 1.3 2 2 .3.2.6.3.8.1.2-.1.5-.5.7-.7.2-.2.3-.2.6-.1.3.1 1.6.8 1.9.9.3.1.5.2.5.3 0 .2 0 .9-.4 1.3-.4.5-1.2.9-2 .9-.6 0-1.3-.1-3-.9-2.5-1.2-4-3.8-4.1-4a4 4 0 01-.8-2.3c0-.7.3-1.1.5-1.4z" fill="currentColor" stroke="none"/>
    </svg>
  </a>
  <?php endif; ?>
  <button class="rd-chat-fab" id="rdChatFab" type="button" aria-label="<?= $rdChatFabLabel ?>">
    <svg class="rd-chat-fab-icon" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
      <path d="M14 9a5 5 0 015 5c0 .9-.2 1.7-.6 2.4L19.5 19l-2.7-.9c-.8.6-1.8.9-2.8.9a5 5 0 01-5-5"/>
      <path d="M10 3a6 6 0 016 6c0 1.1-.3 2.2-.8 3.1L16.5 15l-3-1a6 6 0 01-9.3-5A6 6 0 0110 3z" fill="currentColor" stroke="none" opacity=".35"/>
      <path d="M10 3a6 6 0 016 6c0 1.1-.3 2.2-.8 3.1L16.5 15l-3-1a6 6 0 01-9.3-5A6 6 0 0110 3z"/>
    </svg>
    <svg class="rd-chat-fab-close" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
  </button>

  <!-- Chat panel -->
  <div class="rd-chat-panel" id="rdChatPanel" aria-hidden="true">
    <div class="rd-chat-panel-head">
      <div class="rd-chat-panel-avatar">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a7 7 0 017 7v1h1a2 2 0 012 2v3a2 2 0 01-2 2h-1.3A7 7 0 015 17H4a2 2 0 01-2-2v-3a2 2 0 012-2h1V9a7 7 0 017-7z"/><circle cx="9" cy="12" r="1" fill="currentColor"/><circle cx="15" cy="12" r="1" fill="currentColor"/></svg>
      </div>
      <div>
        <strong><?= $langArray['RD_CHAT_NAME'] ?></strong>
        <span class="rd-chat-status"><i></i> <?= $langArray['RD_CHAT_ONLINE'] ?></span>
      </div>
    </div>
    <div class="rd-chat-panel-body" id="rdChatBody" data-autoreply="<?= htmlspecialchars($langArray['RD_CHAT_AUTOREPLY']) ?>">
      <div class="rd-chat-msg rd-chat-msg-bot"><?= $langArray['RD_CHAT_WELCOME'] ?></div>
      <div class="rd-chat-quick">
        <a href="#" data-rd-modal-open><?= $langArray['RD_CHAT_QUICK_1'] ?></a>
        <a href="<?= $ftUrlProducts ?>"><?= $langArray['RD_CHAT_QUICK_2'] ?></a>
        <a href="<?= $ftUrlContact ?>"><?= $langArray['RD_CHAT_QUICK_3'] ?></a>
      </div>
    </div>
    <?php
      // WhatsApp'a doğrudan geçiş — numara Genel Ayarlar'da tanımlıysa görünür
      $rdChatWaDigits = rd_whatsapp_number($generalSettingCheck['contact_whatsapp'] ?? '');
    ?>
    <?php if ($rdChatWaDigits): ?>
    <a class="rd-chat-wa" href="https://wa.me/<?= $rdChatWaDigits ?>?text=<?= rawurlencode($langArray['RD_CHAT_WA_MESSAGE']) ?>"
       target="_blank" rel="noopener">
      <span class="rd-chat-wa-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3.5 20.5l1.3-4.2a8 8 0 111.9 2l-3.2 2.2z"/>
          <path d="M8.3 8.6c.2-.5.5-.5.7-.5h.5c.2 0 .4 0 .6.4.2.4.6 1.4.7 1.5.1.1.1.3 0 .5-.1.2-.2.3-.3.4-.2.2-.3.3-.1.6.2.4 1 1.3 2 2 .3.2.6.3.8.1.2-.1.5-.5.7-.7.2-.2.3-.2.6-.1.3.1 1.6.8 1.9.9.3.1.5.2.5.3 0 .2 0 .9-.4 1.3-.4.5-1.2.9-2 .9-.6 0-1.3-.1-3-.9-2.5-1.2-4-3.8-4.1-4a4 4 0 01-.8-2.3c0-.7.3-1.1.5-1.4z" fill="currentColor" stroke="none"/>
        </svg>
      </span>
      <span class="rd-chat-wa-text">
        <strong><?= $langArray['RD_CHAT_WA_TITLE'] ?></strong>
        <small><?= $langArray['RD_CHAT_WA_DESC'] ?></small>
      </span>
      <span class="rd-chat-wa-arrow" aria-hidden="true">→</span>
    </a>
    <?php endif; ?>
    <form class="rd-chat-panel-input" id="rdChatForm">
      <input type="text" id="rdChatInput" placeholder="<?= $langArray['RD_CHAT_PLACEHOLDER'] ?>" autocomplete="off">
      <button type="submit" aria-label="<?= $langArray['RD_CHAT_SEND'] ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
      </button>
    </form>
  </div>
  <!-- back to top area end -->
  <!-- jquery -->
  <script src="assets/js/jquery-3.4.1.min.js"></script>
  <!--migrate-->
  <script src="assets/js/jquery-migrate.min.js"></script>
  <!-- bootstrap -->
  <script src="assets/js/bootstrap.min.js"></script>
  <!-- waypoint -->
  <script src="assets/js/waypoints.min.js"></script>
  <!-- wow -->
  <script src="assets/js/wow.min.js"></script>
  <!--Slick Js-->
  <script src="assets/js/slick.min.js"></script>
  <!-- counterup -->
  <script src="assets/js/jQuery.rcounter.js"></script>
  <!--Custom Video Popup-->
  <script src="assets/js/jquery.rPopup.js"></script>
  <!--Nice Select-->
  <script src="assets/js/jquery.nice-select.min.js"></script>
  <!--Mean Menu-->
  <script src="assets/js/jquery.meanmenu.min.js"></script>
  <!-- imageloaded -->
  <script src="assets/js/imagesloaded.pkgd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" charset="utf-8"></script>
  <!--Google Map API-->
  <!-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_KEY&callback=initMap"
      async defer></script> -->
  <!--Google Map Active-->
  <!-- <script src="assets/js/goolg-map-activate.js"></script> -->
  <!-- main js -->
  <script src="assets/js/main.js"></script>
  <!-- Redesign 2026: GSAP for scroll animations — sitede gömülü (harici CDN'e
       bağımlı değil; CDN'e erişim engellenirse/başarısız olursa tüm kaydırma
       animasyonları sessizce hiç çalışmıyordu, bu yüzden diğer varlıklar gibi
       yerelden servis ediliyor). -->
  <script src="assets/js/gsap.min.js"></script>
  <script src="assets/js/ScrollTrigger.min.js"></script>
  <script src="assets/js/redesign.js?v=<?= @filemtime(__DIR__ . '/assets/js/redesign.js') ?: time() ?>"></script>
  <!-- Etkileşim istatistiği (tıklama, kaydırma, form, süre) -->
  <?php if (!empty($rdAnalyticsAllowed)): ?>
    <script src="assets/js/track.js?v=<?= @filemtime(__DIR__ . '/assets/js/track.js') ?: time() ?>" defer></script>
  <?php endif; ?>
  <div class="rd-cookie-consent" id="rdCookieConsent" role="dialog" aria-live="polite" aria-label="Çerez tercihleri" hidden>
    <div>
      <strong>Çerez tercihleri</strong>
      <p>Siteyi geliştirmek ve ziyaret istatistiklerini ölçmek için analitik çerezleri kullanıyoruz. Tercihinizi dilediğiniz zaman değiştirebilirsiniz.</p>
    </div>
    <div class="rd-cookie-consent-actions">
      <button type="button" class="rd-cookie-reject" data-cookie-choice="necessary">Sadece gerekli</button>
      <button type="button" class="rd-cookie-accept" data-cookie-choice="all">Tümünü kabul et</button>
    </div>
  </div>
  <script src="assets/js/cookie-consent.js?v=1" defer></script>
  <?= $generalSettingCheck['footer_code']; ?>
  <style media="screen">
    #reCaptchaCrush iframe{
      height: 50px !important;
      width: 200px !important;
    }
  </style>
</body>
</html>
