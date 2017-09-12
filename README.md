- INFO
  - Das svxrdb ist ein einfaches Dashboard für den Service svxreflektor(Server Seite).

- INSTALLATION:
  - Den Ordner in das Wurzelverzeichniss vom Webserverdeamon speichern
  - z.B. /var/www/html oder /var/www/html/httpdocs verschieben.

FUNKTIONEN:
  - beliebig viele Logdateien als Quelle nutzen.
    - Logauszüge ein/ausblenden (LOGTABLE)
  - Anzahl der Zeilen aus dem Log festlegen
    - (LOGLINECOUNT)
  - Zeitstempel wann der Client sich mit dem Server verbunden hat.
    - Disconnect wird mit Zeitstempel und OFFLINE angezeigt
  - Clients aus dem Verbund werden automatisch im Log gefunden
  - zeigt den Aktuellen Status vom Client
    - Online / Offline / Icon->"spricht" / Icon->"Kanal belegt"
  - Client Sprechzeiten
    - Anfang / Endzeit
  - Spalte IP-Adresse des Clients
    - zu / abschaltbar (IPLIST)
    - auf 10 Zeichen gekürzt
  - Statusleiste mit einem Zeitstempel
    - ein/ausblenden (REFRESHSTATUS)
  - Tabelle kann durch das anklicken im Titel der Spalte sortiert werden
    - als erstes wird der aktuellste Gesprächsanfang gezeigt
  - Logrotate Puffer (RECOVER)
    - wenn logrotate eine neue Datei anlegt werden die alten Client-Daten mit angezeigt
  - Darstellunglayout der Seite via css
    - (STYLECSS)

## HINWEIS:
  - Der Zeitstempel in der svxreflector.conf MUSS so eingestellt sein.
  - [GLOBAL]
  - TIMESTAMP_FORMAT="%d.%m.%Y %H:%M:%S"
  - Reload Time value(ms) defined in index.html at line 14

73 Andy