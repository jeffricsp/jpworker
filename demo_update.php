<?php
/////////////////////////////////////////////
////// Created by: Jeffric S. Pisuena //////
////// https://jeffric.com            //////
////// jeffric.sp@gmail.com           //////
//////        DEMO UPDATE USER        //////
////////////////////////////////////////////
require_once 'demo_config.php';
require_once 'jpworker.php';

$jpworker = new JPWorker();

$table_name = "jp_user";
$key = "rid"; //your primary key

//fetch data from db
if(isset($_GET['rid'])) {
    $data = $jpworker->getData($conn, $table_name, $_GET);
}

//perform update
if(isset($_POST['rid'])) {
    $result = $jpworker->updateData($conn, $table_name, $key, $_POST);   
    //fetch updated data from db
    $data = $jpworker->getData($conn, $table_name, $_GET);      
}

?>

<html>
    <head>
        <title>Demo Using JPWorker</title>
    </head>
    <body>
        <?php
            $rid = "";
            $name = "";
            $uname = "";
            $role = "";
        
            //get data from the $data array and assign it to local variables
            if(isset($data)) {
                if(is_array($data)) {
                    foreach($data as $d) {
                        $rid = $d['rid']; // index same as with column name in table
                        $name = $d['name'];
                        $uname = $d['uname'];
                        $role = $d['role'];
                    }
                }
                $admin=""; $user="";
                if(stristr($role,"Admin")) {
                    $admin = "selected";
                }else {
                    $user = "selected";
                }
            }  
        
            //display result of updating
            if(isset($result)) {
                if($result==1)
                    echo "Updated!";
                else
                    echo "ERROR!";
            }
            
        
        ?>

        <form method="post">
            Enter Name: <input type="text" name="name" value="<?php echo $name; ?>"><br>
            Enter Username:<input type="text" name="uname" value="<?php echo $uname; ?>"><br>
            Role:
            <select name="role">
                <option value="User" <?php echo $user; ?>>User</option>
                <option value="Admin" <?php echo $admin; ?>>Admin</option>
            </select><br>
            <!-- your key is userid -->
            <input type="hidden" name="rid" value="<?php echo $rid; ?>">
            <input type="submit" value="Update Data">
        </form>
    </body>

</html>