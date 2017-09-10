<?php
require_once('config.php');
require_once('function.php');
require_once('logparse.php');
require_once('array_column.php');

$logs = array();
if(count($LOGFILES,0) >0) {
    for($i=0; $i<count($LOGFILES,0); $i++) {
            $lastdata=getdata($LOGFILES[$i]);
            if(count($lastdata) >1) {
                $logs=array_merge($logs, $lastdata);
            }
    }
} else { exit(0); }

echo "<html><head>";
echo "<link rel=\"stylesheet\" href=\"style_normal.css\">";
echo "<title>SVXLINKREFLECTOR</title>";
echo "<script src=\"tablesort.js\"></script>\n\r";

if( preg_match('/'.IPLIST.'/i', 'SHOW')) {
    echo "</head><body onload=\"sortTable(4)\">\n\r";
} else {
    echo "</head><body onload=\"sortTable(3)\">\n\r";
}

if (count($logs) > 0){
    echo "<main><table id=\"logtable\" with:80%>\n\r<tr>\n\r";
    echo "<th onclick=\"sortTable(0)\">Callsign client</th>\n\r";
    echo "<th onclick=\"sortTable(1)\">Login / Logout - time</th>\n\r";
        if( preg_match('/'.IPLIST.'/i', 'SHOW')) {
            echo "<th onclick=\"sortTable(2)\">Network address</th>\n\r";
            echo "<th onclick=\"sortTable(3)\">state</th>\n\r";
            echo "<th onclick=\"sortTable(4)\">QSO run</th>\n\r";
            echo "<th onclick=\"sortTable(5)\">QSO stop</th>\n\r</tr>\n\r";
        } else {
            echo "<th onclick=\"sortTable(2)\">state</th>\n\r";
            echo "<th onclick=\"sortTable(3)\">QSO run</th>\n\r";
            echo "<th onclick=\"sortTable(4)\">QSO stop</th>\n\r</tr>\n\r";
        }

    for ($i=0; $i<count($logs, 0); $i++)
    {
        if( $logs[$i]['CALL'] != "CALL") {
            echo '<tr>'; 

            if (preg_match('/'.$logs[$i]['CALL'].'/i' , $lastheard_call)) {
                echo '<td class=\'lastheard\'>'.$logs[$i]['CALL'].'</td>';
            } else {
                echo '<td>'.$logs[$i]['CALL'].'</td>';
            }

            echo '<td>'.$logs[$i]['LOGINOUTTIME'].'</td>';
            
            if( preg_match('/'.IPLIST.'/i', 'SHOW')) {
                echo '<td>'.$logs[$i]['IP'].'</td>';
            }
            if (preg_match('/TX/i',$logs[$i]['STATUS'])) {
                echo '<td class=\'tx\'></td>';
            }
            if (preg_match('/OFFLINE/i',$logs[$i]['STATUS'])) {
                echo '<td class=\'offline\'>'.$logs[$i]['STATUS'].'</td>';
            }
            if (preg_match('/ONLINE/i',$logs[$i]['STATUS'])) {
                echo '<td class=\'ONLINE\'>'.$logs[$i]['STATUS'].'</td>';
            }
            if (preg_match('/DOUBLE/i',$logs[$i]['STATUS'])) {
                echo '<td class=\'double\'></td>';
            }

            echo '<td>'.$logs[$i]['TX_S'].'</td>';
            echo '<td>'.$logs[$i]['TX_E'].'</td>';
            echo "</tr>\n\r";
        }
    }
    if( preg_match('/'.REFRESHSTATUS.'/i', 'SHOW')) {
        echo "<tr><th colspan='6'>SxvlinkReflector-Dashboard-Refresh ".date("Y-m-d | H:i:s"."</th></tr>\n\r");
    }
    if( preg_match('/'.LOGTABLE.'/i', 'SHOW')) {
        $all_logs = array();
        if(count($LOGFILES,0) >0) {
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
echo '<a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/"><img alt="Creative Commons Lizenzvertrag" style="border-width:0" src="https://i.creativecommons.org/l/by-nc/4.0/88x31.png" /></a><br /><a rel="github" href="https://github.com/SkyAndy/svxrdb/">DO7EN / DJ1JAY</a> v'.DBVERSION;
?>
