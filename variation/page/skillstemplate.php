<?php
  // Sayfalar seo_url ile bulunur (site-config.php); veritabanında ek kolon gerekmez.
  $skillKeys  = array('skill-arge', 'skill-muh', 'skill-lab', 'skill-adt');
  $skillSlugs = rd_slugs($skillKeys);
  $skillsAsk = $db->prepare("SELECT * FROM posts WHERE seo_url IN ('" . implode("','", $skillSlugs) . "')");
  $skillsAsk->execute();
  $skillRows = $skillsAsk->fetchAll(PDO::FETCH_ASSOC);

  // site-config.php'deki sırayı koru + dile göre başlık/içerik uygula
  $skills = array();
  foreach ($skillKeys as $k) {
    $slug = rd_slug($k);
    foreach ($skillRows as $row) {
      if ($row['seo_url'] === $slug) {
        $row['page_key'] = $k;
        $row['title']    = rd_text($k, 'TITLE', $row['title']);
        $row['content']  = rd_text($k, 'CONTENT', $row['content']);
        $skills[] = $row;
        break;
      }
    }
  }

  $icons = array(
    'skill-arge' => '<path d="M9 18h6M10 21h4M12 3a6 6 0 00-4 10.5c.6.6 1 1.3 1 2.1V16h6v-.4c0-.8.4-1.5 1-2.1A6 6 0 0012 3z"/>', // AR-GE: ampul
    'skill-muh'  => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.9-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.7 1.7 0 00-1-1.6 1.7 1.7 0 00-1.9.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.9 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1a1.7 1.7 0 001.5-1 1.7 1.7 0 00-.3-1.9l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.9.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.9-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.9V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z"/>', // Mühendislik: dişli
    'skill-lab'  => '<path d="M9 2v6.5L4.5 17a2 2 0 001.7 3h11.6a2 2 0 001.7-3L15 8.5V2M9 2h6M9 14h6"/>', // Laboratuvar: deney tüpü
    'skill-adt'  => '<path d="M12 2l8 3v6c0 5-3.5 8.5-8 11-4.5-2.5-8-6-8-11V5l8-3z"/><path d="M9 12l2 2 4-4"/>', // ADT: kalkan-onay
  );

  $activeId = $servicesDataCheck['post_id'] ?? ($pageDataCheck['post_id'] ?? ($skills[0]['post_id'] ?? 0));
  if (!in_array($activeId, array_column($skills, 'post_id'))) $activeId = $skills[0]['post_id'] ?? 0;
?>
<div class="rd-page">

  <section class="rd-skills-hero" id="rdSkillsHero">
    <div class="rd-skills-media">
      <img id="rdSkillsImg" src="" alt="" data-current="">
    </div>
    <div class="rd-skills-panel">
      <span class="rd-eyebrow" style="color:var(--rd-green-light)"><?= $langArray['RD_SKILLS_EYEBROW'] ?></span>
      <h1 id="rdSkillsTitle"></h1>
      <div id="rdSkillsDesc" class="rd-skills-desc"></div>
    </div>

    <div class="rd-skills-cards">
      <?php foreach ($skills as $s): ?>
        <button class="rd-skill-card <?= $s['post_id'] == $activeId ? 'rd-active' : '' ?>"
                data-id="<?= $s['post_id'] ?>"
                data-title="<?= htmlspecialchars($s['title']) ?>"
                data-text="<?= htmlspecialchars(strip_tags($s['content'])) ?>"
                data-html="<?= htmlspecialchars($s['content']) ?>"
                data-img="<?= htmlspecialchars(rd_img_url($s['banner'])) ?>"
                style="background-image:url('<?= rd_img_url($s['banner']) ?>')">
          <span class="rd-skill-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?= $icons[$s['page_key']] ?? '' ?></svg></span>
          <span class="rd-skill-title"><?= $s['title'] ?></span>
        </button>
      <?php endforeach; ?>
    </div>
  </section>

</div>

<script>
(function () {
  var cards = document.querySelectorAll('.rd-skill-card');
  var imgEl = document.getElementById('rdSkillsImg');
  var titleEl = document.getElementById('rdSkillsTitle');
  var descEl = document.getElementById('rdSkillsDesc');
  var typing = false;

  function typeTitle(text, cb) {
    titleEl.textContent = '';
    var i = 0;
    typing = true;
    (function step() {
      if (!typing) return;
      titleEl.textContent = text.substring(0, i);
      i++;
      if (i <= text.length) {
        setTimeout(step, 16);
      } else {
        cb && cb();
      }
    })();
  }

  function activate(card, instant) {
    cards.forEach(function (c) { c.classList.remove('rd-active'); });
    card.classList.add('rd-active');
    typing = false;
    descEl.classList.remove('rd-in');
    imgEl.style.opacity = 0;
    setTimeout(function () {
      imgEl.src = card.dataset.img;
      imgEl.alt = card.dataset.title;
      imgEl.style.opacity = 1;
    }, instant ? 0 : 150);

    var title = card.dataset.title;
    if (instant) {
      titleEl.textContent = title;
      descEl.innerHTML = card.dataset.html;
      descEl.classList.add('rd-in');
    } else {
      typeTitle(title, function () {
        descEl.innerHTML = card.dataset.html;
        requestAnimationFrame(function () { descEl.classList.add('rd-in'); });
      });
    }
  }

  cards.forEach(function (card) {
    card.addEventListener('click', function () { activate(card, false); });
  });

  var initial = document.querySelector('.rd-skill-card.rd-active') || cards[0];
  if (initial) activate(initial, true);
})();
</script>
