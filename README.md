# Rotary Zoom Landing Page Software
> by Rotary E-Club of D-1850

Dynamic Landing Page for Rotary and Rotaract Clubs which use Zoom Meetings.

## Installation

1. Paket auf Webserver entpacken
   - php Version 7 empfohlen (Achtung: Module "json" erforderlich - sollte in der Regel aber inkludiert sein)
   - Bootstrap, Query.js und Popper.js sind extern von CDNs eingebunden
   - Fontawesome ist im Paket enthalten (sonst sehr langsames Nachladen…)

2. Meeting (wiederkehrend) bei Zoom wie gewoht erstellen (wenn noch nicht vorhanden)
    - Registrierung wahlweise aktiv oder auch nicht (erkennt die Landing Page automatisch)
    - Im Falle der Registierung optimalerweise in den Einstellungen die Info-Mails an die Teilnehmer deaktivieren

3. Account ID + Client ID+Secret für eine Server-To-Server OAuth 2.0-App im Zoom Marketplace generieren
    - Vorher für den Admin Account die Berechtigung setzen (siehe auch https://developers.zoom.us/docs/internal-apps/#enable-permissions)
   
4. [includes/settings.inc.php](includes/settings.inc.php) modifizieren

5. Club-Logo, Datenschutzerklärung und Anleitung austauschen

6. Fertig! :)

Detaillierte Informationen (insbesondere zu Schritt 3) stehen in [dieser PDF](/landing-page_beschreibung_v02_oauth.pdf)


## Beispiel-Installation eines kompletten Webservers (CentOS 8 – Minimal Install)
> von Elmar Zoepf, Distrikt 1890

Betriebssystem aktualisieren:
```
yum update -y
```

mc und nano installieren:
```
yum install nano mc -y
```

Apache installieren:
```
yum install httpd -y
systemctl enable httpd --now
```

Firewall installieren:
```
yum install firewalld -y
systemctl enable firewalld --now
```

Firewall konfigurieren:
```
Firewall-cmd –-zone=public --permanent –-add-service=http
Firewall-cmd –-zone=public --permanent –-add-service=https
```

PHP installieren:
```
yum module enable php:7.3
yum install php php-cli php-common php-json
systemctl enable php-fpm --now
```

GIT installieren:
```
yum install git -y
```

GIT konfigurieren:
```
git config -–global user.name „xxxx”
git config –-global user.email “xxxx@xxxx.de”
```

Projektordner anlegen:
```
mkdir project
cd project
git init
mkdir landingpage
cd landingpage 
```

Repository klonen:
```
git clone https://github.com/chrpun/rotary-zoom-landingpage.git
```
