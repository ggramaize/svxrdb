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
        if(empty($LOGFILES[$i])) { } else {
            $lastdata=getdata($LOGFILES[$i]);
            if(count($lastdata) >0) {
                $logs=array_merge($logs, $lastdata);
                $logs[] = array ('CALL' => "NEWLOGFILEDATA");
            }
        }// END check filname size check
    }
} else { exit(0); }

/* loading userdb for mouse hover textinfo from userdb.php */
for ($i=0; $i<count($logs, 0); $i++) {
    if (isset($userdb_array[$logs[$i]['CALL']], $userdb_array)) {
       $logs[$i]['COMMENT'] = $userdb_array[$logs[$i]['CALL']];
    }
}

echo "<!DOCTYPE html>";
echo "<html lang=\"de\"><head>\r\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>";
echo '<link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
<link rel="manifest" href="/favicons/manifest.json">
<link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ffffff">';

echo "\r\n<title>SVXLINKREFLECTOR</title>";
echo "<script src=\"tablesort.js\"></script>\n\r";

$current_style = file_get_contents(STYLECSS);
echo "<style type=\"text/css\">".$current_style."</style></head>\n\r";

if (count($logs) >= 0){
    echo "<main><table id=\"logtable\" with:80%>\n\r<tr>\n\r";
    echo "<th onclick=tabSort(\"EAR\")>Callsign client</th>\n\r";
    echo "<th>Connection</th>\n\r";
    
    if( preg_match('/'.IPLIST.'/i', 'SHOW')) {
        echo "<th>Network address</th>\n\r";
    }
    
    echo '<th class="state">state</th>'."\n\r";
    echo "<th>Tx on</th>\n\r";
    echo "<th onclick=tabSort(\"TOP\")>Tx off</th>\n\r";
    
    for ($i=0; $i<count($logs, 0); $i++)
    {
        if( ($logs[$i]['CALL'] != "CALL") AND ($logs[$i]['CALL'] != '') ) {
            echo '<tr>'; 

            if($logs[$i]['CALL'] != 'NEWLOGFILEDATA') {

                if ( ($logs[$i]['STATUS'] === "ONLINE") OR ($logs[$i]['STATUS'] === "TX") ) {
                    echo '<td class="green"><div class="tooltip">'.$logs[$i]['CALL'].'<span class="tooltiptext">'.$logs[$i]['COMMENT'].'</span></div></td>';
                }
                if ($logs[$i]['STATUS'] === "OFFLINE") {
                    echo '<td class="darkgrey"><div class="tooltip">'.$logs[$i]['CALL'].'<span class="tooltiptext">'.$logs[$i]['COMMENT'].'</span></div></td>';
                }
                if ( ($logs[$i]['STATUS'] === "DOUBLE") OR ($logs[$i]['STATUS'] === "DENIED") ){
                    echo '<td class="red"><div class="tooltip">'.$logs[$i]['CALL'].'<span class="tooltiptext">'.$logs[$i]['COMMENT'].'</span></div></td>';
                }
                
                echo '<td class="grey">'.$logs[$i]['LOGINOUTTIME'].'</td>';
                
                if( preg_match('/'.IPLIST.'/i', 'SHOW')) {
                    echo '<td class="grey">'.$logs[$i]['IP'].'</td>';
                }
                if (preg_match('/TX/i',$logs[$i]['STATUS'])) {
                    echo '<td class=\'tx\'></td>';
                }
                if (preg_match('/OFFLINE/i',$logs[$i]['STATUS'])) {
                    echo '<td class="grey"></td>';
                }

                if (preg_match('/ONLINE/i',$logs[$i]['STATUS'])) {
                    if ((preg_match('/'.$logs[$i]['CALL'].'/i' , $lastheard_call)) AND (preg_match('/'.$LASTHEARD.'/i', 'EAR')) ) {
                        echo '<td class="ear"></td>';
                    } else { 
                        echo '<td class="grey"></td>';
                    }
                }

                if (preg_match('/DOUBLE/i',$logs[$i]['STATUS'])) {
                    echo '<td class=\'double\'></td>';
                }

                if (preg_match('/DENIED/i',$logs[$i]['STATUS'])) {
                    echo '<td class=\'denied\'></td>';
                }
      
                if(preg_match('/TX/i',$logs[$i]['STATUS'])) {
                    echo '<td class="yellow">'.$logs[$i]['TX_S'].'</td>';
                    echo '<td class="yellow">'.$logs[$i]['TX_E'].'</td>';
                } else {
                    echo '<td class="grey">'.$logs[$i]['TX_S'].'</td>';
                    echo '<td class="grey">'.$logs[$i]['TX_E'].'</td>';                
                }
                echo "</tr>\n\r";
            
            } // END NEWLOGFILEDATA FALSE
            // add marker for new logfiledata
            if (preg_match('/NEWLOGFILEDATA/i', $logs[$i]['CALL'])) {
                echo "<tr><th class='logline' colspan='6'></th></tr>\n\r";
            }
        }
    }

    if( preg_match('/'.REFRESHSTATUS.'/i', 'SHOW')) {
        echo "<tr><th colspan='6'>SxvlinkReflector-Dashboard-Refresh ".date("Y-m-d | H:i:s"."</th></tr>\n\r");
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
echo '<a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/"><img alt="Creative Commons Lizenzvertrag" style="border-width:0" src="https://i.creativecommons.org/l/by-nc/4.0/88x31.png" /></a><br /><a rel="github" href="https://github.com/SkyAndy/svxrdb/">DO7EN / DJ1JAY</a> v'.DBVERSION;
?>
