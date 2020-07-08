<?php
// Debug-Modus!
ini_set('display_errors', true);
ini_set('error_reporting', E_ALL);

/*
========================================================
Rotary Club Zoom Landing Page
Copyright 2020 Christian Punke, Rotary E-Club of D-1850
https://github.com/chrpun/rotary-zoom-landingpage
========================================================

list-participants.php
>> Listet die aktuell angemeldeten Teilnehmern für die nächste Meeting-Instanz auf

noch rudimentär und in Entwicklung!

========================================================
*/

// Laden der Einstellungen und benötigen Funktionen
require __DIR__ . '/includes/settings.inc.php';


if (!$registration_enabled) {
  ###### HINWEIS: FUNKTIONIERT NUR MIT "?register-test=1"
  die('Die hinterlegte Meeting-ID ('.$meeting_id.', '.$meeting_name.') hat keine Registrierung aktiviert... Somit gibt es hier nichts zu sehen...');
}

$list = list_all_participants($meeting_id);

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

  <title><?php echo $club_name ?> // Teilnehmer-Liste</title>

  <!-- CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="css/main.css" >
  
</head>

<body>


<h3>Teilnehmerliste</h3>

<table class="table-sm table-striped table-hover table-bordered">
  <thead>
    <tr>
      <th>Vorname</th><th>Nachname</th><th>E-Mail</th><th>Status</th><th>Reg.-Zeit</th><th>Join URL</th>
    </tr>
  </thead>
  
  <tbody>
<?php  

foreach($list['registrants'] as $value) {
    
    echo "<tr>";
	  echo '<td>'.$value['first_name'].'</td><td>'.$value['last_name'].'</td><td>'.$value['email'].'</td><td>'.$value['status'].'</td><td>'.$value['create_time'].'<td style="word-break: break-all; word-break: break-word;"><a href="' . $value['join_url'] . '">Link</a></td>';
	  echo "</tr>";
}
?>
</tbody></table>


<div class="mt-4"><a href="list-meeting-activity.php?register-test=1">Alle Teilnehmer der letzten Meetings anzeigen</a></div>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>