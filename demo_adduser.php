<?php
/////////////////////////////////////////////
////// Created by: Jeffric S. Pisuena //////
////// https://jeffric.com            //////
////// jeffric.sp@gmail.com           //////
////////////////////////////////////////////
require_once 'demo_config.php';
require_once 'jpworker.php';

$jpworker = new JPWorker();

$table_name = "jp_user";

if(isset($_POST['uname'])) {
    $status = $jpworker->addUser($conn, $table_name, $_POST); 
}

?>

<html>
    <head>
        <title>Demo Using JPWorker</title>
    </head>
    <body>
        <?php
            if(isset($status)) {
                echo $status;
            }
        ?>
        <form method="post">
            Enter Name: <input type="text" name="name"><br>
            Enter Username:<input type="text" name="uname"><br>
            Enter Password<input type="password" name="password"><br>
            <select name="role">
                <option value="User">User</option>
                <option value="Admin">Admin</option>
            </select>
            <input type="submit" value="Add New User">
        </form>
    </body>

</html>