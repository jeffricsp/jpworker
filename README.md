# jpworker
My Worker Class - to make DB actions much simpler if not using frameworks 

Still working on it...

Currently:
- add data to mysql db - function addData($conn, $tblName, $data)
- add user data to mysql db - function addUser($conn, $tblName, $data)
- login function - function login($conn, $tblName, $data)
- search data from mysql db - function getData($conn, $tblName, $data) & function getMultipleData($conn, $tblName, $offset, $limit)
- update data - function updateData($conn, $tblName, $key, $data)
- delete data - function deleteData($conn, $tblName, $data)
- create table if table does not exist (use add data/user data function first)

Limitations:
- input names should be exactly the same as your field names or you have to create the array using your field names as index
- for login and add user to work, username and password field should be named "uname" and "password"

How to use:
- see demo


