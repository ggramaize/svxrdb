<?php

date_default_timezone_set('Europe/Berlin'); // http://php.net/manual/de/timezones.europe.php

// set to SHOW and the last LOGLINECOUNT lines from logfile is showing in HTML
define("LOGTABLE", "SHOW" );

// ammount of line to show
define("LOGLINECOUNT", "25");

// full path to logfile with read access for http deamon, 'file1', 'file2', 'file3', 'file4' ....
$LOGFILES = array( 'svxlinkreflector.log', '', '' ); 

// set to SHOW and the IP address is showing in HTML
define("IPLIST", "SHOWNO");

// set to SHOW for see refreshtime statusline
define("REFRESHSTATUS", "SHOW"); 

// You own style
define("STYLECSS", "style_normal.css");

/* set to YES for save client data an recover it after logrotate for SDCard User not recommended
recover data stored in base directory from ...www/svxrdb/recover_data_xxxxxx */
define("RECOVER", "NO"); 

/* use "EAR" to mark with the icon of the last transmission.
use "TOP" as a keyword to see the last transmission in the first place in the list (EAR is no longer seen automatically) */
define("LASTHEARD", "TOP"); // EAR or TOP




//do not change this values
define("CLIENTLIST", "CALL");
define("DBVERSION", "20170913.1739" );
$lastheard_call = "CALL";
$clients[] = array();
// ----
?>
