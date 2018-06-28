<?php  //Created by ASDF at 2018/06/28 01:40:11pm 
DEFINE ('DB_USER', 'root'); 
DEFINE ('DB_PSWD', ''); 
DEFINE ('DB_HOST', 'localhost'); 
DEFINE ('DB_NAME', 'asdf'); 
$db = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME); 
mysqli_select_db($db, DB_NAME); 
if (!$db) {die('Cannot connect to database');} 
?>