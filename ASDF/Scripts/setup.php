<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:58
 */


//Creates the connection.php file that the rest of the system shall use.
function databaseConnection($name, $pw, $dbhost)
{
    //Takes the values from the Setup page form
    $username = $name;
    $password = $pw;
    $host = $dbhost;
    //Creates/overwrites any existing file
    $dbinfo = fopen("connection.php", "w") or die("Unable to create connection file!");

    $date = date("Y/m/d h:i:sa");
    $text = "<?php  //Created by ASDF at {$date} \n";
    fwrite($dbinfo, $text);
    $text = "DEFINE ('DB_USER', {$username}); \n";
    fwrite($dbinfo, $text);
    $text = "DEFINE ('DB_PSWD', {$password}); \n";
    fwrite($dbinfo, $text);
    $text = "DEFINE ('DB_HOST', {$host}); \n";
    fwrite($dbinfo, $text);
    $text = "DEFINE ('DB_NAME', 'ASDF'); \n";
    fwrite($dbinfo, $text);

    $text = "\$db = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME); \n";
    fwrite($dbinfo, $text);
    $text = "if (!\$db) {die('Cannot connect to database');} \n";
    fwrite($dbinfo, $text);

    $text = "?>";
    fwrite($dbinfo, $text);
}


//




?>

