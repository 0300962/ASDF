<?php  //Created by ASDF at 2018/06/29 11:15:01am 
DEFINE ('DB_USER', 'root'); 
DEFINE ('DB_PSWD', ''); 
DEFINE ('DB_HOST', 'localhost'); 
DEFINE ('DB_NAME', 'asdf'); 
$db = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME); 
mysqli_select_db($db, DB_NAME); 
if (!$db) {die('Cannot connect to database');}
?>