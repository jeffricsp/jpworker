<?php
/////////////////////////////////////////////
////// Created by: Jeffric S. Pisuena //////
////// https://jeffric.com            //////
////// jeffric.sp@gmail.com           //////
////////////////////////////////////////////
session_start();

require_once 'demo_config.php';
require_once 'jpworker.php';

$jpworker = new JPWorker();

$table_name = "jp_user";

$offset = 0;
$limit = 5;



$result = $jpworker->getMultipleData($conn, $table_name, $offset, $limit);


?>

<html>
    <head>
        <title>Demo Using JPWorker</title>
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th>User ID</th><th>Name</th><th>Username</th><th>Role</th>
                </tr>
            </thead>
            <tbody>
<?php
    if(isset($result)) {
       if(is_array($result)) {
            foreach($result as $r) {
                echo "<tr><td>$r[userid]</td><td>$r[name]</td><td>$r[uname]</td><td>$r[role]</td></tr>";  
            }
        }
    } else {
        echo "<tr><td colspan=4>No records found</td>";
    }
        
?>
            
            </tbody>
        
        </table>
        

    </body>

</html>