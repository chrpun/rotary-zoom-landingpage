<?php
/*
========================================================
Rotary Club Zoom Landing Page
Copyright 2020 Christian Punke, Rotary E-Club of D-1850
https://github.com/chrpun/rotary-zoom-landingpage
========================================================

list-meeting-activity.inc.php
>> Listet die aktuell angemeldeten Teilnehmern für die nächste Meeting-Instanz auf
>> wird von meta-forward_sso.inc.php eingebunden

========================================================
*/

// Sicherstellen, dass diese Seite nur von meta-forward_sso.inc.php aufgerufen wird und nicht eigenständig
if (!isset($show_participant_list) || !$show_participant_list) {
  header('Location: ../index.php');
  die();
}

$list = list_all_participants($meeting_id);

?>
<h5>Teilnehmerliste</h5>

<?php if (!$registration_enabled): ?>
  Die hinterlegte Meeting-ID (<?php echo $meeting_id ?>, <?php echo $meeting_name ?>) hat keine Registrierung aktiviert... Somit gibt es hier nichts zu sehen...

<?php else: ?>

<table class="table-sm table-striped table-hover table-bordered">
  <thead>
    <tr>
      <!-- <th>Vorname</th><th>Nachname</th><th>E-Mail</th><th>Status</th><th>Reg.-Zeit</th><th>Join URL</th> -->
      <th>Vorname</th><th>Nachname</th><th>E-Mail</th><th>Status</th><th>Reg.-Zeit</th>
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
<?php endif ?>