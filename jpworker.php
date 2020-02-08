<?php
////////////////////////////////////////////
////// Created by: Jeffric S. Pisuena //////
////// https://jeffric.com            //////
////// jeffric.sp@gmail.com           //////
//////            JPWorker            //////
////// This class was created to make //////  
////// the basic CRUD task much more  //////
////// easier if you are building a   //////
////// PHP applicatin from scratch.   //////
////// Note: This is not yet complete!////// 
//////                                //////
////////////////////////////////////////////
class JPWorker {
    
    //Add data to database dynamically
    function addData($conn, $tblName, $data) {
        $fields = array_keys($data);
        $col = "";
        $q = "";
        $dt = "";
        $vals = "";
        $ctr = 0;
        $tblName = $this->sanitize($conn, $tblName);
        
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
                }
                else {
                    $col .= "$field";
                    $q .= "?";
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
        $tblName = $this->sanitize($conn, $tblName);
        
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
                }
                else {
                    $col .= "$field";
                    $q .= "?";
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
        $tblName = $this->sanitize($conn, $tblName);
        
        if(is_array($fields)) {
            foreach($fields as $field) {
                
                if(stristr($field,"user") || stristr($field,"uname")) {
                    $username = $this->sanitize($conn, $data[$field]);;
                    $u_field = $this->sanitize($conn, $field);
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
    
    //search data in the database with condition
    function getData($conn, $tblName, $data) {
        $fields = array_keys($data);
        $col = "";
        $dt = "";
        $ctr = 0;
        $data_arr = array();
        $tblName = $this->sanitize($conn, $tblName);
        
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
        $tblName = $this->sanitize($conn, $tblName);
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
    
    //Update data to database dynamically
    function updateData($conn, $tblName, $key, $data) {
        //$num_col = count($data);
        $fields = array_keys($data);
        $col = "";
        $q = "";
        $dt = "";
        $vals = "";
        $ctr = 0;
        $key_col = "";
        $key_data = "";
        $key_dt = "";
        $tblName = $this->sanitize($conn, $tblName);
        $key = $this->sanitize($conn, $key);
        
        $sql = "DESCRIBE $tblName";
        $result = $conn->query($sql);
        while($col_data = $result->fetch_assoc()) {
            $col_dt[$col_data['Field']] = $col_data['Type'];
        }
        
        if(is_array($fields)) {
            foreach($fields as $field) {
                if(strcasecmp($field, $key)==0) {
                    $key_col = $key."=?";
                    $key_data = $this->sanitize($conn, $data[$field]);
                    $key_dt = $this->getParamDataType($col_dt[$field]);
                } else {
                    if($ctr>0) {
                        $col .= ", $field=?";
                    }
                    else {
                        $col .= "$field=?";
                    }
                
                    $dt .= $this->getParamDataType($col_dt[$field]);
                    $ctr++;
                    $n_data[] = $this->sanitize($conn, $data[$field]);
                }
            }
            $dt .= $key_dt;
            $n_data[] = $this->sanitize($conn, $key_data);
        }
        
        $sql = "UPDATE $tblName SET $col WHERE $key_col";
        $qry = $conn->prepare($sql);
        $qry->bind_param($dt, ...$n_data);

        if($qry->execute())
            $status = 1;
        else
            $status = 0;
        return $status;
    }
    
    //function to delete data in DB
    function deleteData($conn, $tblName, $data) {
        $col = "";
        $dt = "";
        $ctr = 0;
        //$fields = array_keys($data);
        $key = key($data);
        $tblName = $this->sanitize($conn, $tblName);
        $key_data = $this->sanitize($conn, $data[$key]);
        
        $sql = "DESCRIBE $tblName";
        $result = $conn->query($sql);
        while($col_data = $result->fetch_assoc()) {
            $col_dt[$col_data['Field']] = $col_data['Type'];
        }
  
        $dt = $this->getParamDataType($col_dt[$key]);
        
        $sql = "DELETE FROM $tblName WHERE $key=?";
        $qry = $conn->prepare($sql);
        $qry->bind_param($dt, $key_data);

        if($qry->execute())
            $status = 1;
        else
            $status = 0;
        return $status;
    } 
    
    //Get Data Type of query parameters
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
    
    //Lets be safe, sanitize them!
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