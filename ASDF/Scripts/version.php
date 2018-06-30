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
$sql = "SELECT revision FROM project";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);

$last_update = $row['revision'];
//Returns the revision number to the requesting page.

echo "data: ".$last_update." \n\n";
ob_end_flush();
flush();

exit;
?>