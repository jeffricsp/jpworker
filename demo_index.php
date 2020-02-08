<?php
/////////////////////////////////////////////
////// Created by: Jeffric S. Pisuena //////
////// https://jeffric.com            //////
////// jeffric.sp@gmail.com           //////
//////      DEMO DISPLAY USERS        //////
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
        <a href="demo_adduser.php">Add New User</a>
        <table>
            <thead>
                <tr>
                    <th>User ID</th><th>Name</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
<?php
    if(isset($result)) {
       if(is_array($result)) {
            foreach($result as $r) {
                echo "<tr><td><a href='demo_displayuser.php?userid=$r[userid]'>$r[userid]</a></td><td>$r[name]</td><td><a href='demo_update.php?userid=$r[userid]'>Edit</a> | <a href='demo_delete.php?userid=$r[userid]' onClick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></td></tr>";  
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