<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 30-Jun-18
 * Time: 11:17 AM
 */

/* This script is accessed by pages that require live updates (Project Backlog, Task Board, burndown charts).
It runs once, returning the revision number before exiting. */
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
include_once 'connection.php';
//Checks for the last update to the database
$sql = "SELECT pbl, taskboard, chat FROM project;";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);
//Creates a PHP object to hold the version numbers
$data;
$data->pbl = $row['pbl'];
$data->tb = $row['taskboard'];
$data->chat = $row['chat'];
//Converts PHP object to JSON object
$jsonData = json_encode($data);

//Returns the revision numbers as JSON to the requesting page.
echo "data: ".$jsonData." \n\n";
ob_end_flush();
flush();

exit;
?>