<?php  //Created by ASDF at 2018/07/15 02:27:09pm 
DEFINE ('DB_USER', '0300962'); 
DEFINE ('DB_PSWD', '0300962'); 
DEFINE ('DB_HOST', 'localhost'); 
DEFINE ('DB_NAME', 'asdf'); 
$db = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME); 
mysqli_select_db($db, DB_NAME); 
if (!$db) {die('Cannot connect to database');} 
?>