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

list-meeting-activity.php
>> Listet die 5 letzten Meetings mit den Teilnehmern auf

noch rudimentär und in Entwicklung!

========================================================
*/

// Laden der Einstellungen und benötigen Funktionen
require __DIR__ . '/includes/settings.inc.php';




if (!$registration_enabled) {
  ###### HINWEIS: FUNKTIONIERT NUR MIT "?register-test=1"
  #die('Die hinterlegte Meeting-ID ('.$meeting_id.', '.$meeting_name.') hat keine Registrierung aktiviert... Somit gibt es hier nichts zu sehen...');
}

$list = list_last_meetings($meeting_id); // Liste mit allen UUIDs


$array = array();
$i = 0;


foreach ($list as $v1) {
  
  $uuid = $v1['uuid'];
  
  $data = meeting_report($uuid);
  
  $array[$i]['start_time'] = strtotime($data['start_time']);
  $array[$i]['end_time'] = strtotime($data['end_time']);
  $array[$i]['duration'] = $data['duration']; // ungenau... nur ganze minuten...
  $array[$i]['duration'] = ($array[$i]['end_time'] - $array[$i]['start_time']) / 60; // besser :)
  
  
  $data2 = participant_meeting_report($uuid);
  
  $j = 0;
  foreach ($data2 as $v) {
    $array[$i]['participant'][$j]['name'] = $v['name'];
    $array[$i]['participant'][$j]['user_email'] = $v['user_email'];
    $array[$i]['participant'][$j]['join_time'] = strtotime($v['join_time']);
    $array[$i]['participant'][$j]['leave_time'] = strtotime($v['leave_time']);
    $array[$i]['participant'][$j]['duration'] = $v['duration'] / 60;
    
    $j++;
  }
  
  $i++;
  
  // Nach 5 Meetings Schluss machen...
  if ($i>4) {
    break;
  }
}


#var_dump($array); die();

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

  <title><?php echo $club_name ?> // Liste Meeting Aktivität</title>

  <!-- CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="css/main.css" >
  
</head>

<body>


<h3>Letzte Meetings und entsprechende Aktivität</h3>

<?php foreach ($array as $line): ?>
  
  <h5 class="mt-5">Meeting (Start: <?php echo date("d.F Y H:i", $line['start_time']) ?>)</h5>
  
  <table class="table-sm table-striped table-hover table-bordered">
    <thead>
      <tr>
        <th>Teilehmer</th><th>Mail</th><th>Teilnahmedauer</th><th>Gesamt-Meetingdauer</th><th>Teilnahmeanteil %</th><th>Präsenzkarte schicken</th>
      </tr>
    </thead>
    
    <tbody>
    
    <?php foreach ($line['participant'] as $line2): ?>
      <tr>
        <td><?php echo $line2['name'] ?></td>
        <td><?php echo $line2['user_email'] ?></td>
        <td><?php echo round($line2['duration'], 0) ?> Min.</td>
        <td><?php echo round($line['duration'], 0) ?> Min.</td>
        <td><?php $prozent = $line2['duration'] / $line['duration']; echo round($prozent*100, 0) ?> % </td>
        <td><?php echo ($prozent > 0.6) ? '<a href="#" class="btn btn-sm btn-outline-success" role="button">Karte schicken</a>' : 'nein...' ?></td>
      </tr>
    <?php endforeach ?>
  </tbody></table>
  
<?php endforeach ?>

<div class="mt-4"><a href="list-participants.php?register-test=1">Alle aktuell registrierten Teilnehmer anzeigen</a></div>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>