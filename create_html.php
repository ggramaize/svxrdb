<?php

require_once('config.php');
require_once('function.php');
require_once('logparse.php');
require_once('array_column.php');
require_once('userdb.php');

if(isset($_COOKIE["svxrdb"])) {
    $LASTHEARD = $_COOKIE["svxrdb"];
}

$logs = array();
if(count($LOGFILES,0) >0) {
    for($i=0; $i<count($LOGFILES,0); $i++) {
        // check if filename size greater as zero
        if( !empty($LOGFILES[$i])) {
            $lastdata=getdata($LOGFILES[$i]);
            if(count($lastdata) >0) {
                $logs=array_merge($logs, $lastdata);
                $logs[] = array ('CALL' => "NEWLOGFILEDATA");
            }
        }// END check filname size check
    }
} else { exit(0); }

/* loading userdb for mouse hover textinfo from userdb.php */
foreach ( $logs as $key => $log) {
    if (isset($userdb_array[$log['CALL']], $userdb_array)) {
       $logs[$key]['COMMENT'] = $userdb_array[$log['CALL']];
    }
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
<link rel="manifest" href="/favicons/manifest.json">
<link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ffffff">

<title>SVXLINKREFLECTOR</title>
<script src="tablesort.js"></script>

<style type="text/css"><?php echo(file_get_contents(STYLECSS)); ?></style>
</head>
<body>
<?php 


if (count($logs) >= 0){
?>
<main>
<table id="logtable" with:80%>
<tr>
	<th onclick="javascript:tabSort('EAR');">Callsign client</th>
	<th>Connected since</th>
	<?php if( (IPLIST == "SHOW") OR (IPLIST == "SHOWLONG")) { ?><th>Network address</th><?php } ?> 
	<th class="state">state</th>
	<th>TX on</th>
	<th onclick="javascript:tabSort('TOP');">TX off</th>
</tr>
<?php
    foreach ( $logs as $log )
    {
        if( ($log['CALL'] != "CALL") && ($log['CALL'] != '') ) {
            echo '<tr>';

            if($log['CALL'] != 'NEWLOGFILEDATA') {

		    if ( ($log['STATUS'] === "ONLINE") OR ($log['STATUS'] === "TX") ) {
                    echo '<td class="green"><div class="tooltip">'.$log['CALL'].'<span class="tooltiptext">'.$log['COMMENT'].'</span></div></td>';
                }
                if ($log['STATUS'] === "OFFLINE") {
                    echo '<td class="darkgrey"><div class="tooltip">'.$log['CALL'].'<span class="tooltiptext">'.$log['COMMENT'].'</span></div></td>';
                }
                if ( ($log['STATUS'] === "DOUBLE") OR ($log['STATUS'] === "DENIED") ){
                    echo '<td class="red"><div class="tooltip">'.$log['CALL'].'<span class="tooltiptext">'.$log['COMMENT'].'</span></div></td>';
                }
                if ($log['STATUS'] === "ALREADY") {
                    echo '<td class="yellow"><div class="tooltip">'.$log['CALL'].'<span class="tooltiptext">'.$log['COMMENT'].'</span></div></td>';
                }

                echo '<td class="grey">'.$log['LOGINOUTTIME'].'</td>';

                if( IPLIST == "SHOW") {
                    echo '<td class="grey">'.explode(":",$log['IP'])[0].'</td>';
                }
                if( IPLIST == "SHOWSHORT") {
                    echo '<td class="grey">'.substr($log['IP'], 0, 10).'</td>';
                }

                if (preg_match('/TX/i',$log['STATUS'])) {
                    echo '<td class=\'tx\'></td>';
                }
                if (preg_match('/OFFLINE/i',$log['STATUS'])) {
                    echo '<td class="grey"></td>';
                }

                if (preg_match('/ONLINE/i',$log['STATUS'])) {
                    if ((preg_match('/'.$log['CALL'].'/i' , $lastheard_call)) AND (preg_match('/'.$LASTHEARD.'/i', 'EAR')) ) {
                        echo '<td class="ear"></td>';
                    } else {
                        echo '<td class="grey"></td>';
                    }
                }

                if (preg_match('/DOUBLE/i',$log['STATUS'])) {
                    echo '<td class=\'double\'></td>';
                }

                if (preg_match('/DENIED/i',$log['STATUS'])) {
                    echo '<td class=\'denied\'></td>';
                }

                if (preg_match('/ALREADY/i',$log['STATUS'])) {
                    echo '<td class=\'grey\'></td>';
                }

                if(preg_match('/TX/i',$log['STATUS'])) {
                    echo '<td class="yellow">'.$log['TX_S'].'</td>';
                    echo '<td class="yellow">'.$log['TX_E'].'</td>';
                } else {
                    echo '<td class="grey">'.$log['TX_S'].'</td>';
                    echo '<td class="grey">'.$log['TX_E'].'</td>';
                }
                echo "</tr>\n\r";
            } // END NEWLOGFILEDATA FALSE
            // add marker for new logfiledata
            if (preg_match('/NEWLOGFILEDATA/i', $log['CALL'])) {
                echo "<tr><th class='logline' colspan='6'></th></tr>\n\r";
            }
        }
    }

    if( preg_match('/'.REFRESHSTATUS.'/i', 'SHOW')) {
        $date_now = date_timezone_set( date_create('now'), new DateTimeZone(TIMEZONE));
        ?>
<tr><th colspan='6'>SVXReflector-Dashboard -=[ <?php echo(date_format( $date_now, 'Y-m-d | H:i:s')); ?> ]=-</th></tr><?php
    }

    if( preg_match('/'.LOGFILETABLE.'/i', 'SHOW')) {
        $all_logs = array();
        if(count($LOGFILES,0) >=0) {
            for($i=0; $i<count($LOGFILES); $i++) {
                $lastlog=getlastlog($LOGFILES[$i], LOGLINECOUNT);
                $all_logs=array_merge($all_logs, $lastlog);
            }
        }
        echo "<tr><th colspan='6'>Logfile</th></tr>\n\r
        <td class='logshow'; colspan='6'><pre>".implode("",$all_logs)."</pre></td></tr>";
    }
    echo "</table>\n\r";
}

if( LEGEND == "EN") {
?>
<table><tr><td><center><img src="./assets/tx.gif"></center></td><td>OM talking on this repeater</td></tr>
<tr><td><center><img src="./assets/accden.png"></center></td><td>Wrong credentials! contact sysop</td></tr>
<tr><td><center><img src="./assets/double.png"></center></td><td>Another station is already talking</td></tr>
<tr><td><center><img src="./assets/ear.png"></center></td><td>Last heard station, at last heard sorting</td></tr>
<tr><td><center></center></td><td>Switch sorting with click on Callsign client / TX off head</td></tr></table>
<?php
}

if( LEGEND == "DE") {
?>
<table><tr><td><center><img src="./assets/tx.gif"></center></td><td>OM spricht über dieses Relais</td></tr>
<tr><td><center><img src="./assets/accden.png"></center></td><td>Falsche Zugangsdaten?? Bitte Sysop kontaktieren</td></tr>
<tr><td><center><img src="./assets/double.png"></center></td><td>Eine andere Station spricht schon</td></tr>
<tr><td><center><img src="./assets/ear.png"></center></td><td>Zuletzt gehörte Station, bei Last Heard Sortierung </td></tr>
<tr><td><center></center></td><td>Sortierung Umschalten mit Klick auf Callsign client / TX off Tabellenkopf</td></tr>
</table>
<?php
}

?>
<a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/"><img alt="Creative Commons Lizenzvertrag" style="border-width:0" src="./assets/license.png" /></a>&nbsp;
<a style="font-size: 12px; text-decoration: none" rel="github" href="https://github.com/SkyAndy/svxrdb/">get your own Dashboard v<?php echo(DBVERSION); ?></a>
</body>
</html>
