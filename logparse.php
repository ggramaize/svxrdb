<?php
include "config.php";
error_reporting(0);

function getlastlog() {
    $logline = shell_exec("tac ".SVXLRLOGFILE." | head -n 25");
    return $logline;
}

function getdata() {
    $line_of_text = file_get_contents( SVXLRLOGFILE );
    $logline = explode("\n", $line_of_text);

    $member = array( CLIENTLIST );
    $clients[] = array();
    for($i=0;$i<count($member);$i++){
        $clients[$i] = array('CALL' => $member[$i], 'LOGINOUTTIME'=> "time", 'IP'=> "ip", 'STATUS'=> "offline", 'TX_S'=> "offline", 'TX_E'=> "offline");
    }

    foreach ($logline as $value) {
        $value = str_replace(" CEST:", "",$value);
        if(preg_match("/Login OK from/i", $value)) {
            $data = explode(" ",$value); //im 5 Call
    /*
Array
(
    [0] => 01.09.2017
    [1] => 18:02:47:
    [2] => DO0SE:
    [3] => Login
    [4] => OK
    [5] => from
    [6] => 79.240.57.65:50468
)
    */
        $data[2] = str_replace(":","",$data[2]);
        if (($key = array_search($data[2], array_column($clients, 'CALL'))) !==FALSE) {
            //member found
            $clients[$key]['LOGINOUTTIME']="$data[0] ".substr($data[1], 0, -1); //: remoed from timestring
            $clients[$key]['IP']=substr($data[6], 0, 10);
            $clients[$key]['STATUS']="ONLINE";
            $clients[$key]['TX_S']="online";
            $clients[$key]['TX_E']="online";
        } else {
            //member not found add im
            $clients[] = array( 'CALL'=> $data[2], 'LOGINOUTTIME'=> $data[0]." ".substr($data[1], 0, -1),
            'IP'=> substr($data[6], 0, 10), 'STATUS'=> "ONLINE",
            'TX_S'=> "online", 'TX_E'=> "online");
        }
    } // END Login OK from

    if(preg_match("/disconnected: Connection closed/i", $value)) {
        $data = explode(" ",$value);
        $data[2] = str_replace(":","",$data[2]);
        /*
Array
(
    [0] => 01.09.2017
    [1] => 18:18:31:
    [2] => DB0MGN-2m:
    [3] => Client
    [4] => 87.166.35.47:33692
    [5] => disconnected
    [6] => Connection
    [7] => closed
    [8] => by
    [9] => remote
    [10] => peer
)
        */
        if (($key = array_search($data[2], array_column($clients, 'CALL'))) !==FALSE) {
            //member found
            $clients[$key]['LOGINOUTTIME']="$data[0] ".substr($data[1], 0, -1); //: remoed from timestring
            $clients[$key]['IP']=substr($data[4], 0, 10);
            $clients[$key]['STATUS']="OFFLINE";
            $clients[$key]['TX_S']="offline";
            $clients[$key]['TX_E']="offline";
        } else {
            //member not found add im
            $clients[] = array( 'CALL'=> $data[2], 'LOGINOUTTIME'=> $data[0]." ".substr($data[1], 0, -1),
            'IP'=> substr($data[4], 0, 10), 'STATUS'=> "OFFLINE",
            'TX_S'=> "offline", 'TX_E'=> "offline");
        }
    }// END disconnected: Connection closed

    if(preg_match("/Talker start/i", $value)) {
        $data = explode(" ",$value);
        $data[3] = str_replace(":","",$data[3]);
        /*
        Array
(
    [0] => 01.09.2017
    [1] => 18:13:13:
    [2] => ###
    [3] => DO0SE:
    [4] => Talker
    [5] => start
    [6] => 
)
        */
        if (($key = array_search($data[3], array_column($clients, 'CALL'))) !==FALSE) {
            $clients[$key]['STATUS']="TX";
            $clients[$key]['TX_S']="$data[0] ".substr($data[1], 0, -1); //: remoed from timestring
            $clients[$key]['TX_E']="$data[0] ".substr($data[1], 0, -1); //: remoed from timestring
        } else {
            //member not found add im
            $clients[] = array( 'CALL'=> $data[3], 'STATUS'=> "TX",
            'TX_S'=> $data[0]." ".substr($data[1], 0, -1), 'TX_E'=> $data[0]." ".substr($data[1], 0, -1));
        }
    }// END Talker start
    
    if(preg_match("/Talker stop/i", $value)) {
        $data = explode(" ",$value); //@7 Call
        $data[3] = str_replace(":","",$data[3]);
        /*
        Array
(
    [0] => 01.09.2017
    [1] => 18:13:17:
    [2] => ###
    [3] => DO0SE:
    [4] => Talker
    [5] => stop
    [6] => 
)
        */
        if (($key = array_search($data[3], array_column($clients, 'CALL'))) !==FALSE) {
            $clients[$key]['STATUS']="ONLINE";
            $clients[$key]['TX_E']="$data[0] ".substr($data[1], 0, -1); //: remoed from timestring
        } else {
            //member not found add im
            $clients[] = array( 'CALL'=> $data[3], 'STATUS'=> "ONLINE",
            'TX_E'=> $data[0]." ".substr($data[1], 0, -1));
        }
    }// END Talker stop

    } // END foreach ($logline as $value)
    return $clients;
} // END unction getdata() 
?>
