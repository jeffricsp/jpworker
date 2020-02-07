<?php
session_start();

require_once 'demo_config.php';
require_once 'jpworker.php';

$jpworker = new JPWorker();

$table_name = "jp_user";

if(isset($_POST['uname'])) {
    $status = $jpworker->login($conn, $table_name, $_POST); 
}
?>

<html>
    <head>
        <title>Demo Using JPWorker</title>
    </head>
    <body>
        <?php
            if(isset($status)) {
                if(is_array($status)) {
                    foreach($status as $s) {
                        echo "$s<br>";
                    }
                }
            }
        ?>
        <form method="post">
            Enter Username:<input type="text" name="uname"><br>
            Enter Password<input type="password" name="password"><br>
            <input type="submit" value="Login">
        </form>
    </body>

</html>