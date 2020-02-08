<?php
/////////////////////////////////////////////
////// Created by: Jeffric S. Pisuena //////
////// https://jeffric.com            //////
////// jeffric.sp@gmail.com           //////
//////       DEMO DELETE USER        //////
////////////////////////////////////////////
require_once 'demo_config.php';
require_once 'jpworker.php';

$jpworker = new JPWorker();

$table_name = "jp_user";

if(isset($_GET['userid'])) {
    $status = $jpworker->deleteData($conn, $table_name, $_GET);
    if($status==1) {
        header("location: demo_index.php");
    } else {
        echo "ERROR!";
    }
}



?>