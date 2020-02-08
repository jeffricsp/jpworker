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

$conn = new mysqli($config['host'], $config['user'], $config['pass'], $config['db']);