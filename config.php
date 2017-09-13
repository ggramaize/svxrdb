<?php

date_default_timezone_set('Europe/Berlin'); // http://php.net/manual/de/timezones.europe.php
define("LOGLINECOUNT", "25"); // ammount of line to show

// full path to logfile with read access for http deamon, 'file1', 'file2', 'file3', 'file4' ....
$LOGFILES = array( 'svxlinkreflector.log', '', '' ); 

$clients[] = array();
define("CLIENTLIST", "CALL"); // do not change this value
define("LOGTABLE", "SHOW" ); // set to SHOW and the last LOGLINECOUNT lines from logfile is showing in HTML
define("DBVERSION", "20170913.1115" );
define("IPLIST", "SHOWNO"); // set to SHOW and the IP address is showing in HTML
define("REFRESHSTATUS", "SHOW"); // set to SHOW for see refreshtime
$lastheard_call = "CALL"; //do not change this value
define("STYLECSS", "style_normal.css");

// set to YES for save client data an recover it after logrotate for SDCard User not recommended
// recover data stored in base directory from ...www/svxrdb/recover_data_xxxxxx
define("RECOVER", "NO"); 

?>
