<?php
error_reporting(-1);
date_default_timezone_set('UTC');

function getlastlog() {
    $line_of_text = file_get_contents("svxlinkreflector.log");
    $logline = explode("\n", $line_of_text);
    return $logline;
}

function getdata() {
$line_of_text = file_get_contents("svxlinkreflector.log");
$logline = explode("\n", $line_of_text);

$member = array( "DL7ATO","DO7EN","DL7ATA","DO0SE","DO8DH");
$clients[] = array();
for($i=0;$i<count($member);$i++){
  $clients[$i] = array('CALL' => $member[$i], 'LOGINOUTTIME'=> "time", 'IP'=> "ip", 'STATUS'=> "offline", 'TX_S'=> "offline", 'TX_E'=> "offline");
}      

foreach ($logline as $value) {
    $value = str_replace(" CEST:", "",$value);
    if(preg_match("/Login OK from/i", $value)) {
        $data = explode(" ",$value); //im 5 Call
        $data[5] = str_replace(":","",$data[5]);
        if (($key = array_search($data[5], array_column($clients, 'CALL'))) !==FALSE) {
            //member found
            $clients[$key]['LOGINOUTTIME']="$data[0] $data[1] $data[2] $data[3] $data[4]";
            $clients[$key]['IP']=substr($data[9], 0, 10);
            $clients[$key]['STATUS']="ONLINE";
            $clients[$key]['TX_S']="online";
            $clients[$key]['TX_E']="online";
        }
        else {
            //member not found add im
            $clients[] = array('CALL' => $data[5], 'LOGINOUTTIME'=> "time", 'IP'=> "ip", 'STATUS'=> "offline", 'TX_S'=> "t_start", 'TX_E'=> "t_stop");
            $clients[$key]['LOGINOUTTIME']="$data[0] $data[1] $data[2] $data[3] $data[4]";
            $clients[$key]['IP']=substr($data[9], 0, 10);
            $clients[$key]['STATUS']="ONLINE";
            $clients[$key]['TX_S']="online";
            $clients[$key]['TX_E']="online";
        }
    } // END Login OK from

    if(preg_match("/disconnected: Connection closed/i", $value)) {
        $data = explode(" ",$value); //@6 Call @8 IP
        $data[5] = str_replace(":","",$data[5]);
        if (($key = array_search($data[5], array_column($clients, 'CALL'))) !==FALSE) {
            //member found
            $clients[$key]['LOGINOUTTIME']="$data[0] $data[1] $data[2] $data[3] $data[4]";
            $clients[$key]['IP']=substr($data[7], 0, 10);
            $clients[$key]['STATUS']="OFFLINE";
            $clients[$key]['TX_S']="offline";
            $clients[$key]['TX_E']="offline";
        } else {
            //member not found add im
            $clients[] = array('CALL' => $data[5], 'LOGINOUTTIME'=> "time", 'IP'=> "ip", 'STATUS'=> "offline", 'TX_S'=> "t_start", 'TX_E'=> "t_stop");
            $clients[$key]['LOGINOUTTIME']="$data[0] $data[1] $data[2] $data[3] $data[4]";
            $clients[$key]['IP']=substr($data[7], 0, 10);
            $clients[$key]['STATUS']="OFFLINE";
            $clients[$key]['TX_S']="offline";
            $clients[$key]['TX_E']="offline";
        }
    }// END disconnected: Connection closed

    if(preg_match("/Talker start/i", $value)) {
        $data = explode(" ",$value); //@7 Call
        $data[6] = str_replace(":","",$data[6]);
        if (($key = array_search($data[6], array_column($clients, 'CALL'))) !==FALSE) {
            $clients[$key]['STATUS']="TX";
            $clients[$key]['TX_S']="$data[0] $data[1] $data[2] $data[3] $data[4]";
            $clients[$key]['TX_E']="$data[0] $data[1] $data[2] $data[3] $data[4]";
        } else {
            //member not found add im
            $clients[] = array('CALL' => $data[6], 'LOGINOUTTIME'=> "time", 'IP'=> "ip", 'STATUS'=> "offline", 'TX_S'=> "t_start", 'TX_E'=> "t_stop");
            $clients[$key]['STATUS']="TX";
            $clients[$key]['TX_S']="$data[0] $data[1] $data[2] $data[3] $data[4]";
            $clients[$key]['TX_E']="$data[0] $data[1] $data[2] $data[3] $data[4]";
        }
    }// END Talker start
    
    if(preg_match("/Talker stop/i", $value)) {
        $data = explode(" ",$value); //@7 Call
        $data[6] = str_replace(":","",$data[6]);
        if (($key = array_search($data[6], array_column($clients, 'CALL'))) !==FALSE) {
            $clients[$key]['STATUS']="ONLINE";
            $clients[$key]['TX_E']="$data[0] $data[1] $data[2] $data[3] $data[4]";
        } else {
            //member not found add im
            $clients[] = array('CALL' => $data[6], 'LOGINOUTTIME'=> "time", 'IP'=> "ip", 'STATUS'=> "offline", 'TX_S'=> "t_start", 'TX_E'=> "t_stop");
            $clients[$key]['STATUS']="ONLINE";
            $clients[$key]['TX_E']="$data[0] $data[1] $data[2] $data[3] $data[4]";
        }
    }// END Talker stop
}
    return $clients;
}

?>
