<?php
/*
========================================================
Rotary Club Zoom Landing Page
Copyright 2020 Christian Punke, Rotary E-Club of D-1850
https://github.com/chrpun/rotary-zoom-landingpage
========================================================

host-login.php
>> Fragt Passwort für Host-Link Generierung ab.
>> Leitet im Erfolgsfall zu Zoom weiter (als Host!)

========================================================
*/

// Laden der Einstellungen und benötigen Funktionen
require __DIR__ . '/includes/settings.inc.php';


/*
==========================================
POST-Datenverarbeitung
==========================================
*/

# Alle Felder initialieseren um "not defined" Notices zu vermeiden
$error = false;
$show_error['input-password'] = false;

# Check ob Formulardaten vorhanden sind
if (!empty($_POST)) {
  
  //Variablen belegen
  $password = isset($_POST['input-password']) ? $_POST['input-password'] : '';

  
  // Validierung des Passwortes (siehe settings.inc.php)
  if ($password != $host_password) {
    $error = true;
    $show_error['input-password'] = true;
  }
  
  if(!$error){
    
    // Weiterleitung zum Meeting als Host.
    
    $url = $info['start_url']; # info wird in settings.inc.php belegt
  
    if (!$direct_header_redirect) {
      require __DIR__ . '/includes/meta-forward.inc.php'; // HTML-Seite mit Weiterleitungslink und Meta-Refresh...
    } else {
      header("Location: ".$url);
    }
    
    die(); // Hier gehts raus...
  }
  
}

?><!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Zoom Meeting Landing Page of <?php echo $club_name ?>">
  <meta name="author" content="Christian Punke, Rotary E-Club of D-1850">
  
  <!--
  Rotary Club Landing Page Software
    Copyright 2020 Christian Punke, Rotary E-Club of D-1850
    Please see license information on https://github.com/chrpun/rotary-zoom-landingpage
  -->

  <title><?php echo $club_name ?> // Host-Login</title>

  <!-- CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <?php if ($club_is_rotaract): ?>
    <link rel="stylesheet" href="css/main_rotaract version.css" >
  <?php else: ?>
    <link rel="stylesheet" href="css/main.css" >
  <?php endif ?>
  
  <!-- Fontawesome CSS-->
  <link href="vendor/fontawesome/css/fontawesome.min.css" rel="stylesheet">
  <link href="vendor/fontawesome/css/brands.min.css" rel="stylesheet">
  <link href="vendor/fontawesome/css/solid.min.css" rel="stylesheet">
  
</head>

<body>

<div class="container-fluid">
  <div class="row no-gutter">
    <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image">
      <div class="meeting-status">
        Zoom Meeting: <strong><?php echo $meeting_name; ?></strong><br>Aktueller Meeting Status: <span class="<?php echo $meeting_running ? 'green' : 'orange'; ?>"><i class="fas fa-comments"></i> <strong><?php echo $meeting_running ? 'aktiv' : 'noch nicht gestartet'; ?></strong></span>
      </div>
    </div>
    <div class="col-md-8 col-lg-6">
      <div class="login d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-md-11 col-lg-10 mx-auto">
              <div class="text-center mb-4">
                <img src="img/club-logo.png" class="logo img-fluid"/>
                <h3 class="login-heading mt-3">Host Login</h3>
              </div>
 
              <div class="mb-5 text-center">
                Bitte das Host-Passwort eintragen um das Meeting zu starten.
              </div>
              
              <form method="post" novalidate>
                <div class="form-label-group">
                  <input type="password" id="input-password" name="input-password" class="form-control<?php if ($show_error['input-password']) echo ' is-invalid'; ?>" required>
                  <label for="input-firstname">Host-Passwort</label>
                  <div class="invalid-feedback">Bitte geben Sie das korrekte Passwort ein!</div>
                </div>
                
                <button class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit">Meeting als Host starten</button>
            </form>
                
                <div class="small text-center text-muted mt-5">Landing Page Software by Rotary E-Club of D-1850<br><a href="impressum.php">Impressum</a> | <a href="index.php">Zurück zum Teilnehmer Login</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="social-icons">
  <ul class="list-unstyled text-center mb-0">
    <?php if ($homepage_link != ''): ?>
      <li class="list-unstyled-item">
      <a href="<?php echo $homepage_link ?>">
        <i class="fas fa-home"></i>
      </a>
    </li>
    <?php endif ?>
    
    <?php if ($facebook_link != ''): ?>
      <li class="list-unstyled-item">
      <a href="<?php echo $facebook_link ?>">
        <i class="fab fa-facebook-f"></i>
      </a>
    </li>
    <?php endif ?>
    
    <?php if ($twitter_link != ''): ?>
      <li class="list-unstyled-item">
      <a href="<?php echo $twitter_link ?>">
        <i class="fab fa-twitter"></i>
      </a>
    </li>
    <?php endif ?>
    
    <?php if ($instagram_link != ''): ?>
      <li class="list-unstyled-item">
      <a href="<?php echo $instagram_link ?>">
        <i class="fab fa-instagram"></i>
      </a>
    </li>
    <?php endif ?>
    
    <?php if ($youtube_link != ''): ?>
      <li class="list-unstyled-item">
      <a href="<?php echo $youtube_link ?>">
        <i class="fab fa-youtube"></i>
      </a>
    </li>
    <?php endif ?>
    
  </ul>
</div>


<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

  <!-- Font Awesome Kit -->
  <script src="https://kit.fontawesome.com/e166820a61.js" crossorigin="anonymous"></script>

</body>

</html>
