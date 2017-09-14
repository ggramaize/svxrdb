<?php
include "config.php";
error_reporting(0);

function getdata($logfilename) {
    global $lastheard_call, $LASTHEARD;
    
    if(isset($_COOKIE["svxrdb"])) { 
        $LASTHEARD = $_COOKIE["svxrdb"];
    }
    
    $line_of_text = file_get_contents( $logfilename );
    $logline = explode("\n", $line_of_text);
    $member = array( CLIENTLIST );

    for($i=0;$i<count($member);$i++){
        $clients[$i] = array('CALL' => $member[$i], 'LOGINOUTTIME'=> "time", 'IP'=> "ip", 'STATUS'=> "OFFLINE", 'TX_S'=> "OFFLINE", 'TX_E'=> "OFFLINE");
    }

    // Recovering old data logrotate issues
    if ( preg_match('/'.RECOVER.'/i', 'YES') ) {
        $recoveredData = file_get_contents("recover_data_".$logfilename);
        $recoveredArray = unserialize($recoveredData);
        $clients=array_merge($clients, $recoveredArray);
    }

    foreach ($logline as $value) {
        $value = str_replace(" CEST:", "",$value);

        if(preg_match("/Login OK from/i", $value)) {
            $data = explode(" ",$value);
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
                $clients[$key]['TX_S']="ONLINE";
                $clients[$key]['TX_E']="ONLINE";
                $clients[$key]['SID']="$data[0] ".substr($data[1], 0, -1);
            } else {
                //member not found add im
                $clients[] = array( 'CALL'=> $data[2], 'LOGINOUTTIME'=> $data[0]." ".substr($data[1], 0, -1),
                'IP'=> substr($data[6], 0, 10), 'STATUS'=> 'ONLINE',
                'TX_S'=> "ONLINE", 'TX_E'=> "ONLINE");
            }
        } // END Login OK from

        if((preg_match("/disconnected: Connection closed/i", $value)) OR (preg_match("/disconnected: Locally ordered/i", $value))) {
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
                $clients[$key]['TX_S']="OFFLINE";
                $clients[$key]['TX_E']="OFFLINE";
                $clients[$key]['SID']="$data[0] ".substr($data[1], 0, -1); //: remove from timestring
            } else {
                // member not found add im
                // ### ReflectorClient::disconnect: Access denied Call "Client" not allowed in list :)
                if ($data[2] !== "Client")
                {
                    $clients[] = array( 'CALL'=> $data[2], 'LOGINOUTTIME'=> $data[0]." ".substr($data[1], 0, -1),
                    'IP'=> substr($data[4], 0, 10), 'STATUS'=> "OFFLINE",
                    'TX_S'=> "OFFLINE", 'TX_E'=> "OFFLINE", 'SID'=> $data[0]." ".substr($data[1], 0, -1) );
                }
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
                $clients[$key]['TX_S']=substr($data[1], 0, -1); //: remove from timestring
                $clients[$key]['TX_E']=substr($data[1], 0, -1); //: remove from timestring
                $clients[$key]['SID']="$data[0] ".substr($data[1], 0, -1);
                $lastheard_call = $data[3];
            } else {
                //member not found add im
                $clients[] = array( 'CALL'=> $data[3], 'STATUS'=> "TX",
                'TX_S'=> substr($data[1], 0, -1), 'TX_E'=> substr($data[1], 0, -1), 'SID'=> $data[0]." ".substr($data[1], 0, -1));
                $lastheard_call = $data[3];
            }
        }// END Talker start
        
        if(preg_match("/Talker stop/i", $value)) {
            $data = explode(" ",$value);
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
                $clients[$key]['TX_E']=substr($data[1], 0, -1); //: remove from timestring
                $clients[$key]['SID']="$data[0] ".substr($data[1], 0, -1);
                $lastheard_call = $data[3];
            } else {
                //member not found add im
                $clients[] = array( 'CALL'=> $data[3], 'STATUS'=> "ONLINE",
                'TX_E'=> substr($data[1], 0, -1), 'SID'=> $data[0]." ".substr($data[1], 0, -1) );
                $lastheard_call = $data[3];
            }
        }// END Talker stop

        if(preg_match("/is already talking.../i", $value)) {
            $data = explode(" ",$value);
            $data[3] = str_replace(":","",$data[3]);
            /*
            Array
    (
        [0] => 08.09.2017
        [1] => 18:30:01:
        [2] => ###
        [3] => DD6LK:
        [4] => DL7ATA
        [5] => is
        [6] => already
        [7] => talking...
    )           
            */  
            if (($key = array_search($data[3], array_column($clients, 'CALL'))) !==FALSE) {
                $clients[$key]['STATUS']="DOUBLE";
                $clients[$key]['TX_E']=substr($data[1], 0, -1); //: remoed from timestring
                $clients[$key]['SID']="$data[0] ".substr($data[1], 0, -1);

            } else {
                //member not found add im
                $clients[] = array( 'CALL'=> $data[3], 'STATUS'=> "DOUBLE",
                'TX_E'=> substr($data[1], 0, -1), 'SID'=> $data[0]." ".substr($data[1], 0, -1) );
            }
        }// END Talker double stop

        // Server login failure
        if((preg_match("/[Access denied]{6}/",  $value)) AND (strlen($value)<=57 )) {
            $data = explode(" ",$value);
            $data[2] = str_replace(":","",$data[2]);
            /*
        Array
    (
        [0] => 12.09.2017
        [1] => 16:05:10:
        [2] => DO0SE:
        [3] => Access
        [4] => denied
    )
            */  
            if (($key = array_search($data[2], array_column($clients, 'CALL'))) !==FALSE) {
                $clients[$key]['STATUS']="DENIED";
                $clients[$key]['LOGINOUTTIME']="ACCESS DENIED"; //: remoed from timestring
                $clients[$key]['IP']="ACCESS DENIED";
                $clients[$key]['TX_S']="ACCESS DENIED";
                $clients[$key]['TX_E']="ACCESS DENIED";
                $clients[$key]['SID']="$data[0] ".substr($data[1], 0, -1);
            } else {
                //member not found add im
                $clients[] = array( 'CALL'=> $data[2], 'STATUS'=> "DENIED",
                'TX_E'=> substr($data[1], 0, -1), 'SID'=> $data[0]." ".substr($data[1], 0, -1) );
            }
        }// END Server login failure

    } // END foreach ($logline as $value)

   // Recovering old data logrotate issues
    if ( preg_match('/'.RECOVER.'/i', 'YES') ) {
        // cleanup array from CALL
        for ($i=0; $i<count($clients, 0); $i++) {
            if ($clients[$i]['CALL'] == "CALL")
            {
                unset($clients[$i]);
            }
        }
        $serialized_data = serialize($clients);
        file_put_contents("recover_data_".$logfilename, $serialized_data);
    }

    $last_key = array_search($lastheard_call, array_column($clients, 'CALL'));
    $value = $clients[$last_key];
    unset($clients[$last_key]);
    $clients = array($value) + $clients;

    if (preg_match('/'.$LASTHEARD.'/i', 'TOP')) {
        $clients_sort = array();
        foreach ($clients as $key => $value) {
            $clients_sort[$key] = $value['SID'];
        } 
        array_multisort($clients_sort, SORT_DESC, $clients);
    }

    return $clients;
} // END function getdata() 

?>
