<?php
error_reporting(0);
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

function getlastlog() {
    $data = array_slice(file(SVXLRLOGFILE),SVXRLOGFILECOUNT);
    $size = count($data);
    $logline = array();
    for($x=0; $x < SVXRLOGFILECOUNT; $x++) 
    {
        array_push($logline,$data[$size-$x]);
    }
    return $logline;
}

?>
