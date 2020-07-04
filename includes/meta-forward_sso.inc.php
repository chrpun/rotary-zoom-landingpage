<?php
/*
========================================================
Rotary Club Zoom Landing Page
Copyright 2020 Christian Punke, Rotary E-Club of D-1850
https://github.com/chrpun/rotary-zoom-landingpage
========================================================

meta-forward.inc.php
>> Alternative HTML Seite zu index.php und host-login.php welche den Benutzer über ein Meta-Refresh zu Zoom weiterleitet (wenn Einstellung in settings.php entsprechend gesetzt ist)
>> wird von index.php bzw. host-login.php eingebunden

========================================================
*/

// Sicherstellen, dass diese Seite nur von einer anderen Seite aufgerufen wird und nicht eigenständig
if (!isset($club_name) || !isset($url)) {
  header('Location: ../index.php');
  die();
}



/*
$response['result']:
{
    "active": true,
    "scope": "91f84099-bb56-4b29-bc8d-a3f9096c9ef9",
    "client_id": "rocas_rodas",
    "username": "chrpun@gmail.com",
    "token_type": "Bearer",
    "exp": 1593603396,
    "sub": "2eb1f193-6adc-429b-ad5a-d34390398af1",
    "iss": "rocas_rodas",
    "91f84099-bb56-4b29-bc8d-a3f9096c9ef9": "ROLE_ROWEB ROLE_EMGV ROLE_RC_ADMIN ROLE_RC_PRESIDENT_PAST ROLE_ROAPP",
    "salutation": "Herr",
    "prefix": "",
    "firstName": "Christian",
    "nobility": "",
    "lastName": "Punke",
    "rotaryInternationalNumber": "8625192"
}
*/
$data = $response['result'];
$scopes = explode(' ', $data['scope']);

$member_of_club = ( strstr($data['scope'], $club_scope) ) ? true : false;

if ($member_of_club) {
  $club_admin = ( strstr($data[$club_scope], 'ROLE_RC_ADMIN ') ) ? true : false;
  $club_president = ( strstr($data[$club_scope], 'ROLE_RC_PRESIDENT ') ) ? true : false; //Such-String mit Leerzeichen, damit zB ROLE_RC_PRESIDENT_PAST nicht auch gefunden wird
} else {
  $club_admin = false;
  $club_president = false;
}





// Mobile Detection um den richtigen Direkt-Zoom-Link zu generieren:
// https://marketplace.zoom.us/docs/guides/guides/client-url-schemes

require_once __DIR__ . '/../vendor/Mobile_Detect.php';
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

switch ($deviceType) {
  case 'tablet':
  case 'phone':
    $zoom_protokoll = 'zoomus';
    break;
  
  case 'computer':
  default:
    $zoom_protokoll = 'zoommtg';
    break;
}
$zoom_club_name = ($member_of_club) ? '('.$club_name.', SSO verified)' : '(SSO verified))';
$zoom_username = $data['firstName'].' '.$data['lastName'].' '.$zoom_club_name;

if (!$registration_enabled) $direct_zoom_link = $zoom_protokoll . '://zoom.us/join?confno='.$info['id'].'&pwd='.$info['encrypted_password'].'&uname='.urlencode($zoom_username);


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
  <?php require __DIR__ . '/../css/main_dynamic.php'; ?>
  
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
                <h3 class="login-heading mt-3">Vielen Dank!</h3>
              </div>
 
              <div class="text-center">
                
                <h5>Daten des SSO-Logins:</h5>
                
                <strong>Username: </strong><?php echo $data['username']; ?><br>
                <strong>Name: </strong><?php echo $data['salutation'].' '.$data['prefix'].' '.$data['firstName'].' '.$data['lastName']; ?><br>
                <br>
                <strong>Mitglied im <?php echo $club_name ?>: </strong><?php echo ($member_of_club) ? 'ja!' : 'nein' ?><br>
                <?php if ($member_of_club): ?>
                  <strong>Club-Admin: </strong><?php echo ($club_admin) ? 'ja!' : 'nein' ?><br>
                  <strong>Club-Präsident: </strong><?php echo ($club_president) ? 'ja!' : 'nein' ?><br>
                <?php endif ?>
                <br>
                
                  <a class="btn btn-secondary btn-sm" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Weitere Infos...
                  </a>

                <div class="collapse" id="collapseExample">
                  <div class="card card-body small">
                    <strong>RI #: </strong><?php echo $data['rotaryInternationalNumber']; ?><br>
                    <strong>Sub: </strong><?php echo $data['sub']; ?><br>
                    <strong>Scopes: </strong><br>
                    <?php foreach ($scopes as $key): ?>
                      <strong><?php echo $key ?>: </strong><?php echo $data[$key]; ?><br>
                    <?php endforeach ?>
                  </div>
                </div>
                
                <br><br>
                
                <?php if ($registration_enabled): ?>
                  
                  <a href="<?php echo $url ?>" class="btn btn-lg btn-block btn-forward text-uppercase font-weight-bold mt-4 mb-4 btn-success" role="button">Weiter zu Zoom als Teilnehmer</a>
                  <strong>Achtung:</strong> Dieser Link ist individuell nur für Sie und nur für dieses eine Meeting.<br>Bitte leiten Sie diesen nicht weiter.
                  
                <?php else: ?>
                  
                  <a href="<?php echo $direct_zoom_link ?>" class="btn btn-lg btn-block btn-forward text-uppercase font-weight-bold mt-4 mb-4 btn-success" role="button">Weiter zu Zoom als Teilnehmer</a>
                  Sie haben die Zoom Software noch nicht installiert und/oder der Button funktioniert nicht?<br><a href="<?php echo $url ?>">Hier klicken</a> um auf die Zoom-Homepage zu gehen und von dort die Software zu installieren und das Meeting zu starten.
                  
                <?php endif ?>
                
                <br><br>
                
                <?php 
                
                $show_host_login = false;
                
                switch ($zoom_host_principle) {
                  case 'list':
                    if (isset($zoom_host[$data['username']]) && $zoom_host[$data['username']] == true){
                      $show_host_login = true;
                    }
                    break;
                  
                  case 'rocas-role':
                  default:
                    if ($member_of_club && ($club_admin || $club_president)){
                      $show_host_login = true;
                    }
                    break;
                }
                  if ($show_host_login): 
                  ?>
                  <?php echo $data['username'] ?> hat die Berechtigung das Zoom-Meeting zu hosten!<br>
                  <a href="<?php echo $start_url ?>" class="btn btn-lg btn-block btn-forward text-uppercase font-weight-bold mb-4 btn-success" role="button">Weiter zu Zoom als Host</a>
                <?php endif ?>
                
                Viel Spaß bei unseren Meetings!<br><br>
                
              </div>
              
                
                <div class="small text-center text-muted mt-5">Landing Page Software by Rotary E-Club of D-1850<br><a href="impressum.php">Impressum</a> | <a href="host-login.php">Host Login</a></div>
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

</body>

</html>
