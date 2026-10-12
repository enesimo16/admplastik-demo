<div class="rd-page">

  <section class="rd-contact-map-section">
    <iframe class="rd-contact-map" src="<?php echo $generalSettingCheck['site_maps']; ?>" style="border:0;" allowfullscreen loading="lazy"></iframe>
    <div class="rd-contact-map-overlay"></div>

    <div class="rd-contact-form-wrap">
      <div class="rd-contact-form-card">
        <span class="rd-eyebrow" style="color:var(--rd-green-dark)"><?= $langArray['FRONT_CON_INFO'] ?></span>
        <h1><?= $langArray['RD_CONTACT_TITLE'] ?></h1>
        <p class="rd-contact-sub"><?= $langArray['RD_CONTACT_SUB'] ?></p>

        <div class="rd-contact-info-row">
          <div class="rd-contact-info-item">
            <span class="rd-contact-info-icon"><?= rd_icon('map-marker') ?></span>
            <span><?= $generalSettingCheck['contact_adress'] ?></span>
          </div>
          <div class="rd-contact-info-item">
            <span class="rd-contact-info-icon"><?= rd_icon('phone') ?></span>
            <a href="tel:<?= $generalSettingCheck['contact_land_phone'] ?>"><?= $generalSettingCheck['contact_land_phone'] ?></a>
          </div>
          <div class="rd-contact-info-item">
            <span class="rd-contact-info-icon"><?= rd_icon('mail') ?></span>
            <a href="mailto:<?= $generalSettingCheck['site_email'] ?>"><?= $generalSettingCheck['site_email'] ?></a>
          </div>
        </div>

        <form action="form-submit.php" method="post" class="rd-contact-form rd-ajax-form"
              data-track="iletisim-formu"
              data-sending="<?= htmlspecialchars($langArray['RD_FORM_SENDING']) ?>"
              data-sent="<?= htmlspecialchars($langArray['RD_FORM_SENT']) ?>">
          <input type="hidden" name="type" value="contact">
          <div class="rd-form-row">
            <div class="rd-form-group">
              <label for="rdContactName"><?php echo $langArray['NAME_SURNAME']; ?></label>
              <input type="text" id="rdContactName" name="namesurname" autocomplete="name" required>
            </div>
            <div class="rd-form-group">
              <label for="rdContactEmail"><?php echo $langArray['EMAIL']; ?></label>
              <input type="email" id="rdContactEmail" name="email" inputmode="email" autocomplete="email" required>
            </div>
          </div>
          <div class="rd-form-row">
            <div class="rd-form-group">
              <label for="rdContactPhone"><?php echo $langArray['PHONE']; ?></label>
              <input type="tel" id="rdContactPhone" name="phone" inputmode="tel" autocomplete="tel" required>
            </div>
            <div class="rd-form-group">
              <label for="rdContactSubject"><?php echo $langArray['SUBJECT']; ?></label>
              <input type="text" id="rdContactSubject" name="subject" required>
            </div>
          </div>
          <div class="rd-form-group">
            <label for="rdContactMessage"><?php echo $langArray['DESCRIPTION']; ?></label>
            <textarea id="rdContactMessage" name="message" rows="4"></textarea>
          </div>
          <?php if (!empty($generalSettingCheck['site_recap_sitekey'])): ?>
          <div class="rd-form-group">
            <div class="g-recaptcha" data-sitekey="<?php echo $generalSettingCheck['site_recap_sitekey']; ?>"></div>
          </div>
          <?php endif; ?>
          <button type="submit" class="rd-btn-primary rd-submit-btn">
            <span class="rd-submit-label"><?php echo $langArray['CONTACT_BTN']; ?></span>
            <span class="rd-submit-spinner" aria-hidden="true"></span>
            <svg class="rd-submit-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
          </button>
          <p class="rd-form-feedback" role="status" aria-live="polite"></p>
        </form>

        <?php
          // JavaScript kapalıyken form-submit.php buraya ?sent=1 ile geri döner
          if (isset($_GET['sent'])) {
            echo '<div class="rd-form-alert rd-alert-success">'.$langArray['ACCEPT_CONTACT'].'</div>';
          } elseif (isset($_GET['formerror'])) {
            echo '<div class="rd-form-alert rd-alert-error">'.htmlspecialchars($_GET['formerror']).'</div>';
          }
        ?>
      </div>
    </div>
  </section>

</div>
