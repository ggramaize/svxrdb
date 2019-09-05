<?php
require_once "config.php";

function add_upd_client( &$clients, $call, $loginouttime, $ip, $status, $tx_s, $tx_e, $sid, $comment)
{
    if( !isset( $clients[$call] ) )
    {
        // Create array entry if it doesn't exists
	$clients[$call] = array( 'CALL' => $call );
    }

    if( $loginouttime !== null )
        $clients[$call]['LOGINOUTTIME'] = $loginouttime;

    if( $ip !== null )
        $clients[$call]['IP'] = $ip;

    if( $status !== null )
        $clients[$call]['STATUS'] = $status;

    if( $tx_s !== null )
        $clients[$call]['TX_S'] = $tx_s;

    if( $tx_e !== null )
        $clients[$call]['TX_E'] = $tx_e;

    if( $sid !== null )
        $clients[$call]['SID'] = $sid;

    if( $comment !== null )
        $clients[$call]['COMMENT'] = $comment;
}

function getdata($logfilename) {
    global $lastheard_call, $LASTHEARD;

    if(isset($_COOKIE["svxrdb"])) {
        $LASTHEARD = $_COOKIE["svxrdb"];
    }

    $logfile = file_get_contents( $logfilename );
    $logline = explode("\n", $logfile);
    $members = array( CLIENTLIST );

    $logline_regex = '/^(\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}:\d{2}|\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}:\d{2}(?:[-+]\d{2}[:]?\d{2}|Z)): ([^:]+): (.+)$/i';
    $logline2_regex = '/^(\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}:\d{2}|\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}:\d{2}(?:[-+]\d{2}[:]?\d{2}|Z)): ([^"]+)"([^"]+)"$/i';

    // Build client list.
    $clients = array();

    foreach ( $members as $member){
        if( preg_match( '/^CALL$/i', $member) !== false )
            continue;
        add_upd_client( $clients, $member, '-', '-', 'OFFLINE', 'OFFLINE', 'OFFLINE', null, null);
    }

    // Recovering old data logrotate issues
    if ( preg_match('/'.RECOVER.'/i', 'YES') ) {
        $recoveredData = file_get_contents("recover_data_".$logfilename);
        $recoveredArray = unserialize($recoveredData);
        $clients=array_merge($clients, $recoveredArray);
    }

    $timezone = new DateTimeZone( TIMEZONE );
    foreach ($logline as $value) {

        // Parse a log line, then put the parsed result in parsed_line
        //
        // $parsed_line content:
        // Index   Data type
        // 1       Timestamp
        // 2       Username
        // 3       Event string
        $valid_line = false;
	
        if( preg_match( $logline_regex, $value, $parsed_line) === 1 )
            $valid_line = true;

	elseif( preg_match( $logline2_regex, $value, $parsed_line) === 1 )
	{
            $argbuf = $parsed_line[2];
            $parsed_line[2] = $parsed_line[3];
            $parsed_line[3] = $argbuf;
	    unset($argbuf);
            $valid_line = true;
        }

	if( $valid_line !== true )
            continue;

	// Tokenize event string
        $ev_args = explode( ' ', $parsed_line[3]);

	// Read event time
	$ev_time = date_timezone_set( date_create( $parsed_line[1]), $timezone);
	$ev_date = date_format( $ev_time, 'd.m.Y H:i:s');
	$ev_hour = date_format( $ev_time, 'H:i:s');
	$ev_timestamp = strtotime( $parsed_line[1]);

	// Repeater login event
        if( preg_match('/Login OK from/i', $parsed_line[3]) )
	{
            add_upd_client( $clients, $parsed_line[2], $ev_date, $ev_args[3], 'ONLINE', 'ONLINE', 'ONLINE', $ev_timestamp, 'new client '.$ev_date);
            $lastheard_call = $parsed_line[2];
	}

	if( preg_match("/disconnected: Connection closed/i", $parsed_line[3]) ||
            preg_match("/disconnected: Locally ordered/i", $parsed_line[3])
        )
	{
            add_upd_client( $clients, $parsed_line[2], $ev_date, null, 'OFFLINE', 'OFFLINE', 'OFFLINE', $ev_timestamp, '');
            $lastheard_call = $parsed_line[2];
        }

        if( preg_match("/Already connected/i", $parsed_line[3]) )
        {
            add_upd_client( $clients, $parsed_line[2], $ev_date, null, 'ALREADY', 'ALREADY', 'CONNECTED', $ev_timestamp, null);
            $lastheard_call = $parsed_line[2];
        }

        if( preg_match("/Talker start/i", $parsed_line[3]) )
        {
            add_upd_client( $clients, $parsed_line[2], $ev_date, null, 'TX', $ev_hour, $ev_hour, $ev_timestamp, null);
            $lastheard_call = $parsed_line[2];
        }

        if( preg_match("/Talker stop/i", $parsed_line[3]) ||
            preg_match("/Talker audio timeout/i", $parsed_line[3])
	)
	{
            add_upd_client( $clients, $parsed_line[2], null, null, 'ONLINE', null, $ev_hour, $ev_timestamp, null);
            $lastheard_call = $parsed_line[2];
	}

        if( preg_match("/is already talking.../i", $parsed_line[3]) )
	{
            add_upd_client( $clients, $parsed_line[2], null, null, 'DOUBLE', null, $ev_hour, $ev_timestamp, null);
	}

	if( preg_match("/Authentication failed/i", $parsed_line[3]) ||
            preg_match("/Access denied/", $parsed_line[3])
	) 
	{
            add_upd_client( $clients, $parsed_line[2], '-', null, 'DENIED', '-', '-', $ev_timestamp, '');
	}

    } // END foreach ($logline as $value)

    // Recovering old data logrotate issues
    if ( preg_match('/'.RECOVER.'/i', 'YES') ) {
        $serialized_data = serialize($clients);
        file_put_contents("recover_data_".$logfilename, $serialized_data);
    }

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
