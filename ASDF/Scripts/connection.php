<?php  //Created by ASDF at 2018/06/24 
DEFINE ('DB_USER', 'root');
DEFINE ('DB_PSWD', '');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'asdf');
$db = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME); 
if (!$db) {die('Cannot connect to database');} 
?>