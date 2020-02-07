<?php

$config = array(
    "host" => "localhost",
    "user" => "root",
    "pass" => "root",
    "db"   => "demodb"
);

$conn = new mysqli($config['host'], $config['user'], $config['pass'], $config['db']);