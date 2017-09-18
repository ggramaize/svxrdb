# svxrdb
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
    - zu / abschaltbar (IPLIST) SHOWSHORT or SHOW
    - auf 10 Zeichen gekürzt
  - Statusleiste mit einem Zeitstempel
    - ein/ausblenden (REFRESHSTATUS)
  - Tabelle kann durch das anklicken im Titel der Spalte sortiert werden
    - als erstes wird der aktuellste Gesprächsanfang gezeigt
  - Logrotate Puffer (RECOVER)
    - wenn logrotate eine neue Datei anlegt werden die alten Client-Daten mit angezeigt
  - Darstellunglayout der Seite via css
    - (STYLECSS)
  - Beispielseite http://datenport.net/svxrdb/example.html

## HINWEIS:
  - Der Zeitstempel in der svxreflector.conf MUSS so eingestellt sein.
  - [GLOBAL]
  - TIMESTAMP_FORMAT="%d.%m.%Y %H:%M:%S"
  - Reload Time value(ms) defined in index.html at line 14
  
# English
- INFO
  -  svxrdb is a simple server-dashboard for svxreflektor.
- INSTALLATION:
  - Store the files to the root directory of the webserver-damon.
  - for instance move/store to /var/www/html or /var/www/html/httpdocs.

FUNCTIONS:
  - Use as many logfiles as source as you like.  
    - fade-in, fade-out of log content (LOGTABLE)
  - Specify the number of rows from the log
    - (LOGLINECOUNT)
  - Timestamp when the client connects to the server.
    - A client-disconnect is shown with timestamp and/or OFFLINE.
  - Clients from the netlink were picked automatically from log.
  - Current client-status
    - Online / Offline / Icon->"speaking" / Icon->"busy"
  - Client tx-on and tx-off timestamp
  - Column IP of Clients
    - turn on/off (IPLIST)
    - up to 10 characters
  - Status with timestamp
    - turn on/off (REFRESHSTATUS)
  - Sort table by click on certain column
    - Show tx-on at first
  - Logrotate buffer (RECOVER)
    - After logrotate there is a new file with historic client-dats
  - Layout of page with css
    - (STYLECSS)

## REMARK:
  - To use the dashboard you MUST define the timestamp in  svxreflector.conf as:
  - [GLOBAL]
  - TIMESTAMP_FORMAT="%d.%m.%Y %H:%M:%S"
  - Reload Time value(ms) defined in index.html at line 14

Danke für die Ideen und Anregungen an DL7ATA / DJ1JAY 

73 Andy
