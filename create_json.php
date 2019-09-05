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
foreach ($logs as $key => $log) {
    if (isset($userdb_array[$log['CALL']], $userdb_array)) {
       $logs[$key]['COMMENT'] = $userdb_array[$log['CALL']];
    }
}
header("Content-type: application/json");
echo json_encode($logs);

?>
