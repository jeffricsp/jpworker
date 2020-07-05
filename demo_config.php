<?php
/////////////////////////////////////////////
////// Created by: Jeffric S. Pisuena //////
////// https://jeffric.com            //////
////// jeffric.sp@gmail.com           //////
//////          CONFIG FILE           //////
////////////////////////////////////////////
$config = array(
    "host" => "localhost",
    "user" => "root",
    "pass" => "root",
    "db"   => "demodb"
);
require_once 'jpworker.php';
$jpworker = new JPWorker();
$conn = $jpworker->dbConn($config['host'], $config['user'], $config['pass'], $config['db']);