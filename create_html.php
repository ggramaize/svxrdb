<?php
require_once('logparse.php');
require_once('array_column.php');
require_once('config.php');

$logs = getdata();
if( count($logs) < 0) {
    exit(0);
}

echo "<html><head>";
echo "<title>SVXLINKREFLECTOR</title>";
echo "<style type=\"text/css\">
table {
margin: 8px;
}

th {
    font-family: Arial, Helvetica, sans-serif;
    font-size: .8em;
    background: #666;
    color: #FFF;
    padding: 2px 6px;
    border-collapse: separate;
    border: 1px solid #000;
    text-align:center;
    vertical-align:center;
}

td.online {
    font-family: Arial, Helvetica, sans-serif;
    font-size: .8em;
    background: #58FA58;
    border: 1px solid #DDD;
    text-align:center;
    vertical-align:center;
}

td.offline {
    font-family: Arial, Helvetica, sans-serif;
    font-size: .8em;
    background: #BDBDBD;
    border: 1px solid #DDD;
    text-align:center;
    vertical-align:center;
}

td.tx {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 20px;
    background: #FA5858;
    background: url(tx.gif) center center no-repeat;
    border: 1px solid #DDD;
    text-align:center;
    vertical-align:center;
}

th.logdateikopf {
    font-family: Arial, Helvetica, sans-serif;
    font-size: .8em;
    background: #666;
    color: #FFF;
    padding: 2px 6px;
    border-collapse: separate;
    border: 1px solid #000;
    width: 80%;
}

tr.event {
    font-family: Arial, Helvetica, sans-serif;
    font-size: .8em;
    background: #ff00ff;
    color: #FFF;
    padding: 2px 6px;
    border-collapse: separate;
    border: 1px solid #000;
    width: 80%;
}

td {
    padding: 0.5em 0.5em;
    text-align: center;
    margin-left: 10px;
    background: linear-gradient(to left, transparent 50%, #1F7DE2 50%), linear-gradient(#F7f7f7, #EEE);
    background-size: 200% 100%;
    background-position: right bottom;
    transition: all 0.6s ease-in;
}

td.logshow {
    padding: 0.5em 0.5em;
    text-align: left;
    font-size: .8em;
    background: linear-gradient(to left, transparent 50%, #1F7DE2 50%), linear-gradient(#F7f7f7, #EEE);
    background-size: 200% 100%;
    background-position: right bottom;
    transition: all 0.6s ease-in;
}
</style></head><body>";

echo '<h2>SxvlinkReflector-Dashboard Logdata: '.date("Y-m-d | H:i:s").'</h2>';

if (count($logs) > 0){
    echo "<table with:80%><tr><th>Callsign client</th><th>Login / Logout - time</th>";
        if( preg_match('/'.IPLIST.'/i', 'SHOW')) {
            echo "<th>Network address</th>";
        }
    echo "<th>state</th><th>QSO run</th><th>QSO stop</th></tr>";

    for ($i=0; $i<count($logs, 0); $i++)
    {
        echo '<tr>'; 
        echo '<td>'.$logs[$i]['CALL'].'</td>';
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
            echo '<td class=\'online\'>'.$logs[$i]['STATUS'].'</td>';
        }

        echo '<td>'.$logs[$i]['TX_S'].'</td>';
        echo '<td>'.$logs[$i]['TX_E'].'</td>';
        echo '</tr>';
    }

    echo '</table>';
    
    if( preg_match('/'.LOGTABLE.'/i', 'SHOW')) {
        $lastlog=getlastlog();
        echo '<table width=\'80%\'>
            <tr>
            <th>Log svxreflektor</th>
            </tr>
            <td class=\'logshow\'><pre>'.$lastlog.'</pre></td>
            </tr>
            </table>';
    }
}
echo '<a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/"><img alt="Creative Commons Lizenzvertrag" style="border-width:0" src="https://i.creativecommons.org/l/by-nc/4.0/88x31.png" /></a><br /><a rel="github" href="https://github.com/SkyAndy/svxrdb/">DO7EN / DJ1JAY</a> v'.DBVERSION;
?>
