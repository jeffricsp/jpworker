<?php
/////////////////////////////////////////////
////// Created by: Jeffric S. Pisuena //////
////// https://jeffric.com            //////
////// jeffric.sp@gmail.com           //////
//////       DEMO DISPLAY USER        //////
////////////////////////////////////////////
session_start();

require_once 'demo_config.php';
require_once 'jpworker.php';

$jpworker = new JPWorker();

$table_name = "jp_user";

if(isset($_GET['userid'])) {
    $result = $jpworker->getData($conn, $table_name, $_GET);
}

?>

<html>
    <head>
        <title>Demo Using JPWorker</title>
    </head>
    <body>
        <a href="demo_index.php">View Users</a><br>
        <h3>
        <?php
            if(isset($result)) {
                if(is_array($result)) {
                
                    foreach($result as $r) {
                        echo "User ID: $r[userid]<br>
                              Name: $r[name]<br>
                              Username: $r[uname]<br>
                              Password:*********<br>
                              Role: $r[role]<br>";
                    }
                }
            }
        
        ?>
            </h3>

    </body>

</html>