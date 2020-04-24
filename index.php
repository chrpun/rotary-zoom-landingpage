<?php
/*
========================================================
Rotary Club Zoom Landing Page
Copyright 2020 Christian Punke, Rotary E-Club of D-1850
https://github.com/chrpun/rotary-zoom-landingpage
========================================================

index.php
>> Hauptseite
>> Formular für Beitritt zu Zoom Meetings (mit und ohne Registrierung)

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
$show_error['input-firstname'] = false;
$show_error['input-lastname'] = false;
$show_error['input-rc'] = false;
$show_error['input-email'] = false;
$show_error['check-data'] = false;
$show_error['check-recording'] = false;

# Check ob Formulardaten vorhanden sind
if (!empty($_POST)) {
  
  // Variablen belegen
  // Die Validierungen erfolgen zusätzlich zu der Client-seitigen Validierung im Browser!
  // Namen und RC werden erstmal nicht weiter inhaltlich validiert. Nur leer dürfen sie nicht sein.
  
  if ($registration_enabled) {
    $firstname = isset($_POST['input-firstname']) ? htmlspecialchars($_POST['input-firstname']) : '';
    $lastname = isset($_POST['input-lastname']) ? htmlspecialchars($_POST['input-lastname']) : '';
    $email = isset($_POST['input-email']) ? htmlspecialchars($_POST['input-email']) : '';
  
    $rc = isset($_POST['input-rc']) ? htmlspecialchars($_POST['input-rc']) : '';
    $rc = (isset($_POST['check-guest']) && $_POST['check-guest'] == 'on') ? 'Gast' : $rc; // Überschreiben falls "Gast"-Haken gesetzt ist
    
    if ($firstname == '') {
      $error = true;
      $show_error['input-firstname'] = true;
    }
    if ($lastname == '') {
      $error = true;
      $show_error['input-lastname'] = true;
    }
    if ($rc == '') {
      $error = true;
      $show_error['input-rc'] = true;
    }
  
    // E-Mail Adresse wird per Filter-Funktion gecheckt:
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = true;
      $show_error['input-email'] = true;
    }
  
  }
  
  $check_data = (isset($_POST['check-data']) && $_POST['check-data'] == 'on') ? true : false;
  $check_recording = (isset($_POST['check-recording']) && $_POST['check-recording'] == 'on') ? true : false;
  
  // Check der beiden Checkboxen:
  if (!$check_data) {
    $error = true;
    $show_error['check-data'] = true;
  }
  if (!$check_recording) {
    $error = true;
    $show_error['check-recording'] = true;
  }
  
  
  if(!$error){
    
    if ($registration_enabled) {
      
      // Hier erfolgt die Registrierung beim Meeting und dann die Weiterleitung and den individuellen Link.
      
      $reg['email'] = $email;
      $reg['first_name'] = $firstname;
      $reg['last_name'] = $lastname. ' ('.$rc.')';
      
      // Hier wird der Teilnehmer registiert und die Rückmeldung in das Info Array geschrieben:
      $info = register_participant($meeting_id, $reg);
      
      $url = $info['join_url']; # URL ist spezifisch für jeden Teilnehmer!
      
    } else {
      
      // keine Registrierung notwendig: Weiterleitung zum Meeting
      
      $url = $info['join_url']; # info wurde schon in settings.inc.php belegt
      
    }
    
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
    
  <title><?php echo $club_name ?> // Meeting</title>

  <!-- CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="css/main.css" >
  
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
                <h3 class="login-heading mt-3">Herzlich Willkommen!</h3>
              </div>
 
              <div class="mb-5 text-center">
                Bevor es losgehen kann,<?php if ($registration_enabled): ?> tragen Sie bitte Ihren Namen, Rotary Club und Ihre E-Mail Adresse ein und<?php endif ?> lesen und akzeptieren Sie die Bedingungen.<br>
                Viel Spaß bei unseren Meetings!
              </div>
              
              <form method="post" novalidate>
                <?php if ($registration_enabled): ?>
                <div class="row">
                    <div class="col">
                <div class="form-label-group">
                  <input type="text" id="input-firstname" name="input-firstname" class="form-control<?php if ($show_error['input-firstname']) echo ' is-invalid'; ?>" placeholder="Vorname" <?php echo (isset($firstname)) ? 'value="'.$firstname.'"' : ''; ?> required>
                  <label for="input-firstname">Vorname</label>
                  <div class="invalid-feedback">Bitte geben Sie einen Vornamen ein!</div>
                </div>
              </div>
                <div class="col">
                <div class="form-label-group">
                  <input type="text" id="input-lastname" name="input-lastname" class="form-control<?php if ($show_error['input-lastname']) echo ' is-invalid'; ?>" placeholder="Nachname" <?php echo (isset($lastname)) ? 'value="'.$lastname.'"' : ''; ?> required>
                  <label for="input-lastname">Nachname</label>
                  <div class="invalid-feedback">Bitte geben Sie einen Nachnamen ein!</div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-label-group">
                  <input type="text" id="input-rc" name="input-rc" class="form-control<?php if ($show_error['input-rc']) echo ' is-invalid'; ?>" placeholder="Rotary Club" <?php echo (isset($rc) && $rc != 'Gast') ? 'value="'.$rc.'"' : ''; echo (isset($rc) && $rc == 'Gast') ? 'value="Gast" disabled' : '' ;?> required>
                  <label for="input-rc">Rotary Club</label>
                  <div class="invalid-feedback">Bitte geben Sie einen Rotary Club ein oder wählen Sie das "Gast"-Feld aus!</div>
                </div>
              </div>
              <div class="col-auto">
                <div class="custom-control custom-checkbox mt-2">
                  <input type="checkbox" class="custom-control-input" id="check-guest" name="check-guest" <?php echo (isset($rc) && $rc == 'Gast') ? 'checked' : ''; ?>>
                  <label class="custom-control-label" for="check-guest">Gast</label>
                </div>
              </div>
            </div>
            
                <div class="form-label-group">
                  <input type="email" id="input-email" name="input-email" class="form-control<?php if ($show_error['input-email']) echo ' is-invalid'; ?>" placeholder="E-Mail Adresse" <?php echo (isset($email)) ? 'value="'.$email.'"' : ''; ?> required>
                  <label for="input-email">E-Mail Adresse</label>
                  <div class="invalid-feedback">Bitte geben Sie eine gültige E-Mail Adresse ein!</div>
                </div>
                  
                <?php endif ?>
                <div class="custom-control custom-checkbox mb-3">
                  <input type="checkbox" class="custom-control-input<?php if ($show_error['check-data']) echo ' is-invalid'; ?>" id="check-data" name="check-data" <?php echo (isset($check_data) && $check_data) ? 'checked' : ''; ?> required>
                  <label class="custom-control-label" for="check-data">Ich akzeptiere die <a href="files/<?php echo $dataprotection_filename ?>">Datenschutzbestimmungen</a>.</label>
                  <div class="invalid-feedback">Bitte akzeptieren Sie unsere Datenschutzbestimmungen!</div>
                </div>
                
                <div class="custom-control custom-checkbox mb-3">
                  <input type="checkbox" class="custom-control-input<?php if ($show_error['check-recording']) echo ' is-invalid'; ?>" id="check-recording" name="check-recording" <?php echo (isset($check_recording) && $check_recording) ? 'checked' : ''; ?> required>
                  <label class="custom-control-label" for="check-recording">Ich akzeptiere, dass dieses Meeting aufgezeichnet werden kann und den Mitgliedern des <?php echo $club_name ?> in einem passwortgeschützten Bereich zur Verfügung gestellt wird.</label>
                  <div class="invalid-feedback">Bitte akzeptieren Sie die Möglichkeit der Aufzeichnung!</div>
                </div>
                <input type="hidden" id="go" name="go" value="1">
                <button class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit">Los geht`s!</button>
            </form>
            
                <div class="mt-5 mb-5 text-center">Sie sind das erste Mal bei uns?<br><a href="files/<?php echo $help_filename; ?>" target="_blank">Hier</a> gibt es Tipps und Tricks für eine erfolgreiche Meetingteilnahme.</div>
                
                <div class="small text-center text-muted">Landing Page Software by Rotary E-Club of D-1850<br><a href="impressum.php">Impressum</a> | <a href="host-login.php">Host Login</a></div>
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


<script>
  $(function() {
      $('#check-guest').click(function() {
          if ($('#check-guest').is(':checked')) {
            $('#input-rc').prop('disabled', true);
            $('#input-rc').prop('value', 'Gast');
          } else {
            $('#input-rc').prop('disabled', false);
            $('#input-rc').prop('value', '');
          }
      });
  });
</script>

</body>

</html>
