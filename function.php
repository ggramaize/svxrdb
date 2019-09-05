<?php
#error_reporting(0);
date_default_timezone_set('Europe/Berlin');

# dateDifference
/*
	$qso_time = dateDifference("01.09.2017 19:00:10","01.09.2017 18:00:00");
	print $qso_time->total_sec;
*/
function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' ) {
	$datetime1 = date_create($date_1);
	$datetime2 = date_create($date_2);
	$interval = date_diff($datetime1, $datetime2);
    $t1 = strtotime($date_1);
    $t2 = strtotime($date_2);
    $dtd = new stdClass();
    $dtd->interval = $t2 - $t1;
    $dtd->total_sec = abs($t2-$t1);

	return $dtd;
}

function getlastlog($logfile, $logcount) {
    // Give up if file name is empty, or file doesn't exists
    if( empty($logfile) || $logfile === null || !file_exists($logfile) )
        return array();

    $data = array_slice(file($logfile),$logcount);
    $size = count($data);
    $logline = array();

    // Give up if file is empty
    if ($size==0)
        return $logline;

    for($x=0; $x<$logcount; $x++)
        array_push($logline,$data[$size-$x]);

    return $logline;
}

# 01.09.2017-18:02:47 FORMAT
function logtounixtime($timestring) {
    $to=$timestring;
    list($part1,$part2) = explode('-', $to);
    list($day, $month, $year) = explode('.', $part1);
    list($hours, $minutes,$seconds) = explode(':', $part2);
    $timeto =  mktime($hours, $minutes, $seconds, $month, $day, $year);
    return $timeto;
}

?>
