# rotary-zoom-landingpage
Dynamic Landing Page for Rotary and Rotaract Clubs witch use Zoom Meetings.

## Installation

1. Paket auf Webserver entpacken
   - php Version 7 empfohlen
   - Bootstrap, Query.js und Popper.js sind extern von CDNs eingebunden
   - Fontawesome ist im Paket enthalten (sonst sehr langsames Nachladen…)

2. Meeting (wiederkehrend) bei Zoom wie gewoht erstellen (wenn noch nicht vorhanden)
    - Registrierung wahlweise aktiv oder auch nicht (erkennt die Landing Page automatisch)
    - Im Falle der Registierung in den Einstellen die Info-Mails an die Teilnehmer deaktivieren

3. API Key+Secret für eine JWT im Zoom Marketplace generieren

4. [includes/settings.inc.php](include/settings.inc.php) modifizieren

5. Club-Logo, Datenschutzerklärung und Anleitung austauschen

6. Fertig! :)

Detaillierte Informationen (insbesondere zu Schritt 3) stehen in [dieser PDF](landing-page_beschreibung_v01.pdf)
