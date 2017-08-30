<?php
require_once('logparse.php');
require_once('array_column.php');

$logs = getdata();
if( count($logs) < 0) {
    exit(0);
}

echo 'System Date/Time: '.date("Y-m-d | h:i:sa").'<br>';
echo "<html><head>";
echo "<title>svxrefektor Dashboard by (DO7EN)</title>";
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

td.logdatei {
font-family: Arial, Helvetica, sans-serif;
font-size: .8em;
background: #111111;
border: 1px solid #DDD;
text-align:center;
vertical-align:center;
}
</style>";

if (count($logs) > 0){
echo "<table with:80%>
<tr>
<th>Callsign client</th>
<th>Login / Logout - time</th>
<th>Network adress</th>
<th>state</th>
<th>QSO run</th>
<th>QSO stop</th>
</tr>";

for ($i=0; $i<count($logs, 0); $i++)
{
    echo '<tr>'; 
    echo '<td>'.$logs[$i]['CALL'].'</td>';
    echo '<td>'.$logs[$i]['LOGINOUTTIME'].'</td>';
    echo '<td>'.$logs[$i]['IP'].'</td>';
    if (preg_match('/TX/i',$logs[$i]['STATUS'])) {
            echo '<td class=\'tx\'>'.$logs[$i]['STATUS'].'</td>';
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
$lastlog=getlastlog();
echo "<table width='80%'>
<tr>
<th>Log svxreflektor</th>
</tr>
<td class=\'logdatei\'><pre>$lastlog</pre></td>
</tr>
</table>";
}
?>
