<?php
/////////////////////////////////////////////
////// Created by: Jeffric S. Pisuena //////
////// https://jeffric.com            //////
////// jeffric.sp@gmail.com           //////
////////////////////////////////////////////
class JPWorker {
    
    //Add data to database dynamically
    function addData($conn, $tblName, $data) {
        //$num_col = count($data);
        $fields = array_keys($data);
        $col = "";
        $q = "";
        $dt = "";
        $vals = "";
        $ctr = 0;
        
        $sql = "DESCRIBE $tblName";
        $result = $conn->query($sql);
        while($col_data = $result->fetch_assoc()) {
            $col_dt[$col_data['Field']] = $col_data['Type'];
        }
        
        if(is_array($fields)) {
            foreach($fields as $field) {
                if($ctr>0) {
                    $col .= ", $field";
                    $q .= ",?";
                    //$vals .= ',$fields['. $field .']';
                }
                else {
                    $col .= "$field";
                    $q .= "?";
                    //$vals .= '$fields['. $field .']';
                }
                
                $dt .= $this->getParamDataType($col_dt[$field]);
                
                $ctr++;
                $n_data[] = $this->sanitize($conn, $data[$field], "");
            }
        }
        
        $sql = "INSERT INTO $tblName ($col) values($q)";
        $qry = $conn->prepare($sql);
        $qry->bind_param($dt, ...$n_data);

        if($qry->execute())
            $status = 1;
        else
            $status = 0;
        return $status;
    }
    
    //Add new user (will hash password)
    function addUser($conn, $tblName, $data) {
        //$num_col = count($data);
        $fields = array_keys($data);
        $col = "";
        $q = "";
        $dt = "";
        $vals = "";
        $ctr = 0;
        $n_data = array();
        $err = array();
   
        $sql = "DESCRIBE $tblName";
        $result = $conn->query($sql);
        while($col_data = $result->fetch_assoc()) {
            $col_dt[$col_data['Field']] = $col_data['Type'];
        }
        
        if(is_array($fields)) {
            foreach($fields as $field) {
                if(strcasecmp($field, "password")==0) {
                    $data[$field] = password_hash($data[$field], PASSWORD_DEFAULT);
                }
                  
                if($ctr>0) {
                    $col .= ", $field";
                    $q .= ",?";
                    //$vals .= ',$fields['. $field .']';
                }
                else {
                    $col .= "$field";
                    $q .= "?";
                    //$vals .= '$fields['. $field .']';
                }
                
                $dt .= $this->getParamDataType($col_dt[$field]);
                $ctr++;
                $n_data[] = $this->sanitize($conn, $data[$field]);
            }
        }
        
        $sql = "INSERT INTO $tblName ($col) values($q)";
        $qry = $conn->prepare($sql);
        $qry->bind_param($dt, ...$n_data);

        if($qry->execute())
            $status = "New User Added!";
        else {
            if(stristr($qry->error,"Duplicate"))
                $status = "Username already in use!";
            else 
                $status = "Failed to add new user!";
        }
        
        return $status;
        
    }
    
    //Login function
    function login($conn, $tblName, $data) {
        $num_col = count($data);
        $fields = array_keys($data);
        $result = "";
       
        $u_field = "";
        $p_field = "";
        if(is_array($fields)) {
            foreach($fields as $field) {
                
                if(stristr($field,"user") || stristr($field,"uname")) {
                    $username = $this->sanitize($conn, $data[$field]);;
                    $u_field = $field;
                }
                
                if(stristr($field,"pass") || stristr($field,"pw")) {
                    $password = $data[$field];
                    $p_field = $field;
                }
                
            }
        }
        
        $sql = "SELECT * FROM $tblName WHERE $u_field=?";
        
        $qry = $conn->prepare($sql);
        $qry->bind_param("s", $username);
    
        if($qry->execute()) {
            $meta = $qry->result_metadata();
            while ($field = $meta->fetch_field())
            {
                $params[] = &$data[$field->name];
            }

            call_user_func_array(array($qry, 'bind_result'), $params);
    
            $qry->fetch();
    
            $hpass = $data[$p_field];
    
            if(password_verify($password, $hpass)) {
                foreach($data as $key => $val){
                    if(!stristr($key,"pass")) {
                        $_SESSION[$key] = $val;
                        
                        //setcookie($key, $val, time()+28800, "/", "domain.com");
                    }
                }
                $result = "OK";
                
            } else {
                $result = "ERROR"; 
            }
        }
        else {
            $result = "ERROR"; 
        }
        return $result;
    }  
    
    //search individual data in the database
    function getData($conn, $tblName, $data) {
        //$num_col = count($data);
        $fields = array_keys($data);
        $col = "";
        $dt = "";
        $ctr = 0;
        $data_arr = array();
        
        $sql = "DESCRIBE $tblName";
        $result = $conn->query($sql);
        while($col_data = $result->fetch_assoc()) {
            $col_dt[$col_data['Field']] = $col_data['Type'];
        }
        
        if(is_array($fields)) {
            foreach($fields as $field) {                
                $dt .= $this->getParamDataType($col_dt[$field]);
                $col = $field;
                $ctr++;
                $n_data[] = $this->sanitize($conn, $data[$field]);
            }
        }
        
        $sql = "SELECT * FROM $tblName WHERE $col=?";
        $qry = $conn->prepare($sql);
        $qry->bind_param($dt, ...$n_data);

        if($qry->execute()) {
            $meta = $qry->result_metadata();
            while ($field = $meta->fetch_field())
            {
                $params[] = &$data[$field->name];
            }

            call_user_func_array(array($qry, 'bind_result'), $params);
            $ctr=0;
            while($qry->fetch()) {
                foreach($data as $key => $val){
                    if(!stristr($key,"password")) {
                        $data_arr[$ctr][$key] = $val;
                    }
                }
                $ctr++;
            }
            
        }
            
        return $data_arr;
    }    
    
    function getMultipleData($conn, $tblName, $offset, $limit) {
        
        $col = "";
        $dt = "";
        $ctr = 0;
        $data_arr = array();
        $offset = empty($offset)?0:$offset;
        $limit = empty($limit)?30:$limit;

        $offset = $this->sanitize($conn, $offset,"int");
        $limit = $this->sanitize($conn, $limit, "int");
        
        $sql = "SELECT * FROM $tblName LIMIT $offset,$limit";
        $qry = $conn->prepare($sql);
        if($qry->execute()) {
            $meta = $qry->result_metadata();
            while ($field = $meta->fetch_field())
            {
                $params[] = &$data[$field->name];
            }
            call_user_func_array(array($qry, 'bind_result'), $params);
            $ctr=0;
            while($qry->fetch()) {
                foreach($data as $key => $val){
                    if(!stristr($key,"password")) {
                        $data_arr[$ctr][$key] = $val;
                    }
                }
                $ctr++;
            }
            
        }
            
        return $data_arr;
    }     
    
    function getParamDataType($datatype) {
        if(stristr($datatype, "int"))
            $dt = "i";
        elseif(stristr($datatype, "double") || stristr($datatype, "float"))
            $dt = "d";
        elseif(stristr($datatype, "blob") || stristr($datatype, "binary"))
            $dt = "b";
        else 
            $dt = "s";
        
        return $dt;
    }
    
    function sanitize($conn, $data, $type=NULL) {
        $data = trim($data);
        if(strcasecmp($type, "int")==0)
            $filter = FILTER_SANITIZE_NUMBER_INT;
        elseif(strcasecmp($type, "float")==0)
            $filter = FILTER_SANITIZE_NUMBER_FLOAT;
        elseif(strcasecmp($type, "email")==0)
            $filter = FILTER_SANITIZE_EMAIL;
        elseif(strcasecmp($type, "url")==0)
            $filter = FILTER_SANITIZE_URL;
        else
            $filter = FILTER_SANITIZE_STRING;
        
        $data = filter_var($data, $filter);
        $data = $conn->real_escape_string($data);
        return $data;
    }

}

?>