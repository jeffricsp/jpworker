<?php
session_start();

require_once 'demo_config.php';
require_once 'jpworker.php';

$jpworker = new JPWorker();

$table_name = "jp_user";

if(isset($_POST['userid'])) {
    $result = $jpworker->getData($conn, $table_name, $_POST);
}

?>

<html>
    <head>
        <title>Demo Using JPWorker</title>
    </head>
    <body>
        <?php
            if(isset($result)) {
                if(is_array($result)) {
                
                    foreach($result as $r) {
                        echo "$r[userid] - $r[name] : username: $r[uname]<br>";
                        
                    }
                }
            }
        
        ?>
        <form method="post">
            Enter UserID:<input type="text" name="userid"><br>
            
            <input type="submit" value="Get User Data">
        </form>
    </body>

</html>