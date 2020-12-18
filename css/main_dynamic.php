  <?php
  // Sicherstellen, dass diese Seite nur von einer anderen Seite aufgerufen wird und nicht eigenständig
  if (!isset($club_name)) {
    header('Location: ../index.php');
    die();
  }
  
  
  
  
    //Override für ein paar der CSS Settings (in settings.inc.php einstellbar)
    
    // BG-Picture Overlay:
    if ($club_is_rotaract) {
      $bg_overlay = array(217,27,92); // Rotaract Pink
    } else {
      $bg_overlay = array(247,168,27); // Rotary Gelb/Gold
    }
    if (isset($visual_bg_color)) $bg_overlay = $visual_bg_color;
    
    $bg_overlay[] = isset($visual_bg_opacity) ? $visual_bg_opacity : 0.4;
    $bg_overlay = implode(',',$bg_overlay);
    
    $bg_r_opacity = isset($visual_bg_right_opacity) ? $visual_bg_right_opacity : 0.9;
  ?>
  <style type="text/css">
    .bg-image {
      <?php if (isset($visual_bg_image)): ?>background-image: url('<?php echo $visual_bg_image ?>');<?php endif ?> 
      box-shadow: inset 0 0 0 100vw rgba(<?php echo $bg_overlay ?>); 
    }

    @media (max-width: 768px) {
      .bg-image-right {
        <?php if (isset($visual_bg_right_image)): ?>background-image: url('<?php echo $visual_bg_right_image ?>');<?php endif ?> 
        box-shadow: inset 0 0 0 100vw rgba(255,255,255,<?php echo $bg_r_opacity ?>); 
      }
    }
    
    <?php if (!($sso_login_active && $allow_guest_participants)): ?>
    
    @media (max-width: 768px) {
      .tab-content {
        border-top: 1px solid #dee2e6;
        border-top-right-radius: .25rem;
        border-top-left-radius: .25rem;
      }
    }
    <?php endif ?> 
  </style>