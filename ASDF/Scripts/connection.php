<?php  //Created by ASDF at 2018/07/13 12:12:49pm 
DEFINE ('DB_USER', '0300962'); 
DEFINE ('DB_PSWD', '0300962'); 
DEFINE ('DB_HOST', 'localhost'); 
DEFINE ('DB_NAME', 'asdf'); 
$db = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME); 
mysqli_select_db($db, DB_NAME); 
if (!$db) {die('Cannot connect to database');} 
?>