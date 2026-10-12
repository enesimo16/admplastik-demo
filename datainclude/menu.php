<?php
   // Main menu query
   $mainMenuAsk=$db->prepare("SELECT * FROM menu WHERE menu_top_id=:menu_top_id AND status=:status AND language=:language  ORDER BY order_sort");
   $mainMenuAsk->execute(array(
     'menu_top_id' => 0,
     'status'      => 1,
     'language'    => $getLangCheck['language_id']
   ));
   // Bu dilde menü girilmemişse (örn. henüz çevrilmemiş EN), Türkçe menüye düş
   // — böylece site tamamen menüsüz kalmaz.
   if ($mainMenuAsk->rowCount() == 0) {
     $trLangAsk = $db->prepare("SELECT language_id FROM language WHERE country_code='tr'");
     $trLangAsk->execute();
     $mainMenuAsk->execute(array(
       'menu_top_id' => 0,
       'status'      => 1,
       'language'    => $trLangAsk->fetchColumn()
     ));
   }
   while($mainMenuCheck=$mainMenuAsk->fetch(PDO::FETCH_ASSOC)){
     // Does the main menu have a submenu?
     $countSubmenu=$db->prepare("SELECT * FROM menu WHERE menu_top_id=:menu_top_id AND status=:status ORDER BY order_sort");
     $countSubmenu->execute(array(
       'menu_top_id' => $mainMenuCheck['menu_id'],
       'status'      => 1
     ));
     $resultSubMenu=$countSubmenu->rowCount();
   ?>
<li  <?php echo $resultSubMenu > 0  ? 'class="menu-item-has-children"' : ''; ?>>
   <?php if ($mainMenuCheck['ready_link'] == "manualLink") {
     // Alt menüsü olan ama gerçek bir hedefi olmayan üst öge (link boş/# ) — <base href> yüzünden
     // yanlışlıkla anasayfaya atmasın diye tıklanabilir ama yönlendirmeyen bir href veriyoruz.
     $rdTopLink = trim($mainMenuCheck['link']);
     if ($resultSubMenu > 0 && ($rdTopLink === '' || $rdTopLink === '#')) {
       $rdTopLink = 'javascript:void(0)';
     }
   ?>
   <a <?php echo $mainMenuCheck['new_tab'] == 1 ? 'target="_blank"' : ''; ?> href="<?php echo $rdTopLink; ?>"  ><?php echo rd_menu_label($mainMenuCheck['title']); ?></a>
   <?php }else{
      // if the ready link is a page link
      $pageLinkId = $mainMenuCheck['ready_link'];
      $pageLinkAsk=$db->prepare("SELECT * FROM posts WHERE post_id=$pageLinkId");
      $pageLinkAsk->execute();
      $pageLinkCheck=$pageLinkAsk->fetch(PDO::FETCH_ASSOC);
      ?>
   <a <?php echo $mainMenuCheck['new_tab'] == 1 ? 'target="_blank"' : ''; ?> href="<?php echo $pageLinkCheck['seo_url']; ?>" ><?php echo rd_menu_label($mainMenuCheck['title']); ?></a>
   <?php }
      $topMenuId = $mainMenuCheck['menu_id'];
      $subMenuAsk=$db->prepare("SELECT * FROM menu WHERE menu_top_id=$topMenuId  AND status=1 ORDER BY order_sort");
      $subMenuAsk->execute();
      // if the main menu has a submenu
      if ($resultSubMenu > 0) {
        // Mega menü tanıtım metni (dil dosyasındaki RD_MEGA_MAP'ten, Türkçe başlığa göre)
        $rdMegaMap  = $langArray['RD_MEGA_MAP'] ?? array();
        $rdMegaInfo = $rdMegaMap[mb_strtolower(trim($mainMenuCheck['title']), 'UTF-8')] ?? null;
      ?>
   <div class="rd-mega">
     <div class="rd-mega-inner">
       <?php if ($rdMegaInfo): ?>
       <div class="rd-mega-intro">
         <h4><?php echo rd_menu_label($mainMenuCheck['title']); ?></h4>
         <p><?php echo $rdMegaInfo['desc']; ?></p>
         <a class="rd-mega-intro-link" href="<?php echo rd_slug('contact-index', 'sayfa-iletisim'); ?>">
           <?php echo $langArray['RD_MEGA_CONTACT_LINK']; ?> <span aria-hidden="true">→</span>
         </a>
       </div>
       <?php endif; ?>
   <ul class="sub-menu">
      <?php
         while($subMenuCheck=$subMenuAsk->fetch(PDO::FETCH_ASSOC)){
           // if the submenu has a submenu
           $countSubmenu=$db->prepare("SELECT * FROM menu WHERE menu_top_id=:menu_top_id AND status=:status ORDER BY order_sort");
           $countSubmenu->execute(array(
             'menu_top_id' => $subMenuCheck['menu_id'],
             'status'      => 1
           ));
           $resultSubSubMenu=$countSubmenu->rowCount();
         ?>
      <li <?php echo $resultSubSubMenu > 0  ? 'class="menu-item-has-children"' : ''; ?>>
         <?php if ($subMenuCheck['ready_link'] == "manualLink") { ?>
         <a <?php echo $subMenuCheck['new_tab'] == 1 ? 'target="_blank"' : ''; ?> href="<?php echo $subMenuCheck['link']; ?>" ><?php echo rd_menu_label($subMenuCheck['title']); ?></a>
         <?php }else{
            // if the ready link is a page link
            $subPageLinkId = $subMenuCheck['ready_link'];
            $subPageLinkAsk=$db->prepare("SELECT * FROM posts WHERE post_id=$subPageLinkId");
            $subPageLinkAsk->execute();
            $subPageLinkCheck=$subPageLinkAsk->fetch(PDO::FETCH_ASSOC);
            ?>
         <a <?php echo $mainMenuCheck['new_tab'] == 1 ? 'target="_blank"' : ''; ?> href="<?php echo $subPageLinkCheck['seo_url']; ?>"><?php echo rd_menu_label($subMenuCheck['title']); ?></a>
         <?php }
            if ($resultSubSubMenu > 0) {
            ?>
         <ul class="sub-menu">
            <?php
               $subMenuId = $subMenuCheck['menu_id'];
               $subSubMenuAsk=$db->prepare("SELECT * FROM menu WHERE menu_top_id=$subMenuId  AND status=1 ORDER BY order_sort");
               $subSubMenuAsk->execute();
               while($subSubMenuCheck=$subSubMenuAsk->fetch(PDO::FETCH_ASSOC)) {
               ?>
            <li>
               <?php if ($subSubMenuCheck['ready_link'] == "manualLink") { ?>
               <a <?php echo $subSubMenuCheck['new_tab'] == 1 ? 'target="_blank"' : ''; ?> href="<?php echo $subSubMenuCheck['link']; ?>"><?php echo rd_menu_label($subSubMenuCheck['title']); ?></a>
               <?php }else{
                  // if the ready link is a page link
                  $subSubPageLinkId = $subSubMenuCheck['ready_link'];
                  $subSubPageLinkAsk=$db->prepare("SELECT * FROM posts WHERE post_id=$subSubPageLinkId");
                  $subSubPageLinkAsk->execute();
                  $subSubPageLinkCheck=$subSubPageLinkAsk->fetch(PDO::FETCH_ASSOC);
                  ?>
               <a <?php echo $subSubMenuCheck['new_tab'] == 1 ? 'target="_blank"' : ''; ?> href="<?php echo $subSubPageLinkCheck['seo_url']; ?>"><?php echo rd_menu_label($subSubMenuCheck['title']); ?></a>
               <?php } ?>
            </li>
            <?php } ?>
         </ul>
         <?php } ?>
      </li>
      <?php } ?>
   </ul>
       <div class="rd-mega-card">
         <strong><?php echo $langArray['RD_MEGA_CTA_TITLE']; ?></strong>
         <p><?php echo $langArray['RD_MEGA_CTA_DESC']; ?></p>
         <a href="#" class="rd-mega-card-btn" data-rd-modal-open><?php echo $langArray['RD_GET_QUOTE']; ?></a>
       </div>
     </div>
   </div>
   <?php } ?>
</li>
<?php } ?>
