<?php
/*
========================================================
Rotary Club Zoom Landing Page
Copyright 2020 Christian Punke, Rotary E-Club of D-1850
https://github.com/chrpun/rotary-zoom-landingpage
========================================================

list-meeting-activity.inc.php
>> Bereitet eine Liste für den Aufruf von meta-forward_sso.inc.php vor.
>> wird von meta-forward_sso.inc.php eingebunden

========================================================
*/

// Sicherstellen, dass diese Seite nur von meta-forward_sso.inc.php aufgerufen wird und nicht eigenständig
if (!isset($show_activity_list) || !$show_activity_list) {
  header('Location: ../index.php');
  die();
}

// Anzahl der Meetings die ausgegeben werden
// wenn nicht gesetzt - Standard-Wert = 3;
if (!isset($number_of_meetings)) $number_of_meetings = 3;

// Min. Länge eines Meetings in Minuten, damit es angezeigt wird (um zB Test-Sessions nicht anzuzeigen)
// wenn nicht gesetzt - Standard-Wert = 30;
if (!isset($min_meeting_length)) $min_meeting_length = 30;

// Min. Länge die ein Teilnehmer im Meeting war um angezeigt zu werden (sonst werden zB alle Teilnehmer doppelt angezeigt wenn diese im Warteraum waren)
// wenn nicht gesetzt - Standard-Wert = 2;
if (!isset($min_participant_length)) $min_participant_length = 2;




$list = list_last_meetings($meeting_id); // Liste mit allen UUIDs


$array = array();
$i = 0;


foreach ($list as $v1) {
  
  $uuid = $v1['uuid'];
  
  $data2 = meeting_report($uuid);
  // echo $uuid.'<br>'; // Debug-Only
  
  $array[$i]['start_time'] = strtotime($data2['start_time']);
  $array[$i]['end_time'] = strtotime($data2['end_time']);
  $array[$i]['duration'] = $data2['duration']; // ungenau... nur ganze minuten...
  $array[$i]['duration'] = ($array[$i]['end_time'] - $array[$i]['start_time']) / 60; // besser :)
  
  // nur Meetings anzeigen, die min 20 Minuten gedauert haben (Rest weren vermutlich Tests etc...)
  if ($array[$i]['duration'] < $min_meeting_length) continue;
  
  
  $data3 = participant_meeting_report($uuid);
  
  $j = 0;
  foreach ($data3 as $v) {
    
    if (($v['duration'] / 60) < $min_participant_length) continue;
    
    $array[$i]['participant'][$j]['name'] = $v['name'];
    $array[$i]['participant'][$j]['user_email'] = $v['user_email'];
    $array[$i]['participant'][$j]['join_time'] = strtotime($v['join_time']);
    $array[$i]['participant'][$j]['leave_time'] = strtotime($v['leave_time']);
    $array[$i]['participant'][$j]['duration'] = $v['duration'] / 60;
    
    $j++;
  }
  
  $i++;
  
  // Nach x Meetings Schluss machen...
  if ($i >= $number_of_meetings) {
    break;
  }
}


#var_dump($array); die();



foreach ($array as $line): ?>
  <h5 class="mt-5"><?php echo date("D, d F Y H:i", $line['start_time']) ?> (Dauer: <?php echo round($line['duration'], 0) ?> Min.)</h5>
  
  <table class="table-sm table-striped table-hover table-bordered">
    <thead>
      <tr>
        <!-- <th>Teilnehmer</th><th>Mail</th><th>Teilnahmedauer</th><th>Gesamt-Meetingdauer</th><th>Teilnahmeanteil %</th><th>Präsenzkarte schicken</th> -->
        <th>Teilnehmer</th><th>Teilnahmedauer</th><th>Teilnahmeanteil %</th>
      </tr>
    </thead>
    
    <tbody>
    
    <?php foreach ($line['participant'] as $line2): ?>
      <tr>
        <td><?php echo $line2['name'] ?></td>
        <!-- <td><?php echo $line2['user_email'] ?></td> -->
        <td><?php echo round($line2['duration'], 0) ?> Min.</td>
        <!-- <td><?php echo round($line['duration'], 0) ?> Min.</td> -->
        <td><?php $prozent = $line2['duration'] / $line['duration']; echo round($prozent*100, 0) ?> % </td>
        <!-- <td><?php echo ($prozent > 0.6) ? '<a href="#" class="btn btn-sm btn-outline-success" role="button">Karte schicken</a>' : 'nein...' ?></td> -->
      </tr>
    <?php endforeach ?>
  </tbody></table>
  
<?php endforeach ?>