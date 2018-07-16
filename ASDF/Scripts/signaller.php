<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 30-Jun-18
 * Time: 3:08 PM
 */
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
}
//Increases version-number in database following a change
$result = mysqli_query($db, $sql);
if (!$result) {
    echo "Error updating project revision";
}
