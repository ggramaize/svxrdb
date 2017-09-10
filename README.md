- INFO
  - Das svxrdb ist ein einfaches Dashboard für den Service svxreflektor(Server Seite).

- INSTALLATION:
  - Den Ordner in das Wurzelverzeichniss vom Webserverdeamon speichern
  - z.B. /var/www/html oder /var/www/html/httpdocs verschieben.

FUNKTIONEN:
  - beliebig viele Logdateien als Quelle nutzen.
    - Logauszüge ein/ausblenden
  - Zeit wann der letzte Client seinen Login hatte.
    - Disconnect wird mit Zeitstempel und OFFLINE angezeigt
  - Clients aus dem Verbund werden automatisch im Log gefunden
  - Anzeige des Aktuellen Status vom Client
    - Online / Offline / Icon->"spricht" / Icon->"Kanal belget"
  - Client Sprechzeiten
    - Anfang / Endzeit
  - Spalte IP-Adresse des Clients
    - zu / abschaltbar
  - Statusleiste mit der Zeit des letzten Aktualisierung
    - ein/ausblenden
  - Tabelle kann durch anklicken sortiert werden
    - oben das letzte Gespräch

## HINWEIS:
  - Der Zeitstempel in der svxreflector.conf MUSS so eingestellt sein.
  - [GLOBAL]
  - TIMESTAMP_FORMAT="%d.%m.%Y %H:%M:%S"
