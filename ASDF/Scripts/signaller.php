<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 30-Jun-18
 * Time: 3:08 PM
 */
if (!$_SESSION['logged-in'] OR !isset($_COOKIE['Logged-in'])) {
    header('Location: ..\index.php');
    exit;
}

//Checks which page included this script
switch ($signal_mode) {
    case 'pbl':
        $sql = "UPDATE project SET pbl = pbl + 1;";
        break;
    case 'tb':
        $sql = "UPDATE project SET taskboard = taskboard + 1;";
        break;
    case 'chat':
        $sql = "UPDATE project SET chat = chat + 1;";
        break;
    case 'login':
        $sql = "UPDATE users SET lastSeen = CURRENT_TIMESTAMP WHERE userID = {$_SESSION['id']};";
        break;
    default:
        echo "Error: ensure $signal_mode is set prior to inclusion";
}

//Increases version-number in database following a change
$result = mysqli_query($db, $sql);
if (!$result) {
    echo "Error updating project revision";
}
