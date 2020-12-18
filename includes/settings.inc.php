<?php
/*

========================================================
Rotary Club Zoom Landing Page
Copyright 2020 Christian Punke, Rotary E-Club of D-1850
https://github.com/chrpun/rotary-zoom-landingpage
========================================================

includes/setting.inc.php (required)
>> wird von jeder einzelnen Seite eingebunden

>> Statische Einstellungen
>> Indivduelle Einstellungen



========================================================
LICENSE
========================================================
Copyright 2020 Christian Punke, Rotary E-Club of D-1850

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


/*
===================================================
SETTINGS
Individuell - dürfen/müssen angepasst werden
===================================================
*/

// API Daten für eine JWT-App im Zoom Marketspace
$api_key = '### HIER API-KEY EINFÜGEN ###';
$api_secret = '### HIER API-SECRET EINFÜGEN ###';

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

// Registrierung für Weitergabe an Zoom-Meeting App aktivieren (unabhängig ob man sich für das Meeting bei Zoom registrieren muss oder nicht):
$active_mandatory_registration = true;

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


##### VISUAL SETTINGS ####
#### wenn diese verändert werden sollen, aktivieren - ansonsten werden die Standard-Werte aus der CSS genommen.

$visual_bg_image = 'img/bg.jpg'; // Hintergrund-Bild für die linke Seite (Link von index.php aus gesehen)
$visual_bg_color = array(247,168,27); // Farben in RGB (je 0-255)
$visual_bg_opacity = 0.4; // Transparenz (0 = gar kein Overlay bis 1 = nur Farbe)

$visual_bg_right_image = 'img/bg.jpg'; // Hintergrund-Bild für die rechte Seite (Link von index.php aus gesehen)
$visual_bg_right_opacity = 0.9; // Transparenz (0 = gar kein Overlay bis 1 = nur Farbe)


######## Rotary.de SSO Daten
$oauth2_client_id = '### HIER RO.CAS SSO CLIENT ID EINFÜGEN ###';
$oauth2_client_secret = '### HIER RO.CAS SSO CLIENT SECRET EINFÜGEN ###';
$oauth2_token_endpoint = 'https://sso-server.rotary.de/oauth/token';
$introspection_endpoint = 'https://sso-server.rotary.de/introspect';

// eins von den beiden muss "true" sein (wenn nicht wird automatisch "guest" als true angenommen!)
$sso_login_active = true;
$allow_guest_participants = true;


// Bis Scope Auflösung geklärt ist: Hier aktuellen Scope des RCs eintragen:
$club_scope = '### HIER CLUB SCOPE EINFÜGEN ###';


// Wer darf Zoom-Meetings hosten?
// list: SSO-User die unten gelistet sind
// rocas-role: RC Admin und amtierender RC Präsident (Default)
$zoom_host_principle = 'rocas-role';

// Erlaubte Zoom-Hosts (SSO-Usernames) (nur wenn $zoom_host_principle = 'list' !!)
//$zoom_host['max@mustermann.de'] = true;
//$zoom_host['john@doe.com'] = true;
//...



if (!$sso_login_active && !$allow_guest_participants) $allow_guest_participants = true;


/*
==========================================
Meeting-Infos für Website Funktionen bereitstellen
Statisch - nicht verändern
==========================================
*/

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
