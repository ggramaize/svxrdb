<?php

date_default_timezone_set('Europe/Berlin'); // http://php.net/manual/de/timezones.europe.php
define("LOGLINECOUNT", "25"); // ammount of line to show
$LOGFILES = array( 'svxlinkreflector.log', '', '' ); // path to logfile, 'file1', 'file2', 'file3', 'file4' ....
$clients[] = array();
define("CLIENTLIST", "CALL"); // do not change this value
define("LOGTABLE", "SHOW" ); // set to SHOW and the last LOGLINECOUNT lines from logfile is showing in HTML
define("DBVERSION", "20170910.1135" );
define("IPLIST", "SHOWNO"); // set to SHOW and the IP address is showing in HTML
define("REFRESHSTATUS", "SHOW"); // set to SHOW for see refreshtime
$lastheard_call = "CALL"; //do not change this value
?>
