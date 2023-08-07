<?php
/*

========================================================
Rotary Club Zoom Landing Page
Copyright 2023 Christian Punke, Rotary E-Club of D-1850
https://github.com/chrpun/rotary-zoom-landingpage

Update 2023: Wechsel von JWT auf OAuth 2.0 (Server-To-Server Zoom App)
========================================================

includes/setting.inc.php (required)
>> wird von jeder einzelnen Seite eingebunden

>> Statische Einstellungen
>> Indivduelle Einstellungen



========================================================
LICENSE
========================================================
Copyright 2023 Christian Punke, Rotary E-Club of D-1850

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
========================================================
*/

// Laden der benötigten Zoom API-Funktionen
require __DIR__ . '/api-functions.inc.php'; # Zoom API Funktionen

/*
===================================================
SETTINGS
Statisch - nicht verändern
===================================================
*/
$api_server = 'https://api.zoom.us/v2/';
$tokenUrl = 'https://zoom.us/oauth/token';

/*
===================================================
SETTINGS
Individuell - dürfen/müssen angepasst werden
===================================================
*/

// API Daten für die Server-To-Server OAuth-App im Zoom Marketspace

$accountId = '### HIER ACCOUNT ID EINFÜGEN ###';
$clientId = '### HIER CLIENT ID EINFÜGEN ###';
$clientSecret = '### HIER CLIENT SECRET EINFÜGEN ###';

// Selbstgewähltes Passwort für die Host-Login-Funktion
$host_password = '123456789';

// Zoom-Meeting ID die mit dieser Landing-Page verwaltet werden soll
$meeting_id = 000000000;

// RC/RAC Club Name (zur Anzeige auf der Website)
$club_name ='Rotary Club Bröckede';

// Ist es ein Rotaract Club (=true)? Wenn ja, welcher Rotary Club sponsort diese Landing Page/die Zoom Lizenz (kann auch leer bleiben)?
$club_is_rotaract = false;
$rotaract_sponsor_club = '';

// Direkte Weiterleitung zu Zoom (= true) oder erst Anzeige einer Erfolgsnachricht und dann Weiterleitung nach 5 Sekunden (= false)
$direct_header_redirect = false;

//Diese Dateien müssen im Unterordner "files" liegen
$dataprotection_filename = 'data-protection-example.pdf';
$help_filename = 'help-example.pdf';

// Für Impressum:
$praesident_mail = 'max@mustermann.de';

//Social Buttons (wenn nicht vorhanden - leer (also '') lassen, dann wird das Icon nicht angezeigt.)
$homepage_link = 'http://www.rotary.de';
$facebook_link = '';
$instagram_link = '';
$youtube_link = '';
$twitter_link = '';



/*
==========================================
Meeting-Infos für Website Funktionen bereitstellen
Statisch - nicht verändern
==========================================
*/

$access_token = get_oauth_token(); // Access token is retreived for making API requests

$info = get_meeting_info($meeting_id);

$meeting_name = $info['topic']; // Meeting Name zur Info...

# Registrierung ja/nein
switch ($info['settings']['approval_type']) {
  case 0:   # automatically approve
  case 1:   # manual approve
    $registration_enabled = true;
    break;
  
  case 2:   # no registratation
  default:
    $registration_enabled = false;
    break;
}

# Meeting läuft/läuft nicht
switch ($info['status']) {
  case 'started':
    $meeting_running = true;
    break;
  
  case 'waiting':
  case 'finished':
  default:
    $meeting_running = false;
    break;
}

?>
