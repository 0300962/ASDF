<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 30-Jun-18
 * Time: 10:19 AM
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!$_SESSION['logged-in'] OR !isset($_COOKIE['Logged-in'])) {
    header('Location: index.php');
    exit;
}
    include_once 'connection.php';

    //Checks for a Sprint in-progress
    $sql = "SELECT * FROM sprints
                    WHERE backlogTotal IS NULL";
    $result = mysqli_query($db, $sql);
    if ((mysqli_num_rows($result) > 0) OR ($_SESSION['status'] != '1')) {  // Checks for live Sprint or Non-admin user
        $liveSprint = TRUE;
    } else {
        $liveSprint = FALSE;
    }

    //Checks whether to hide completed PBIs
    if (isset($_GET['false'])) {
        $sql = "SELECT * FROM pbis
            ORDER BY completed, priority ASC";
        $button = true;
        $buttonlabel = "Hide completed";
    } else { //Default option - hides completed PBIs
        $sql = "SELECT * FROM pbis
            WHERE completed IS NULL
            ORDER BY priority ASC";
        $button = false;
        $buttonlabel = "Show completed";
    }
    $result = mysqli_query($db, $sql);

    //Table heading with show/hide button
    echo "<tr>
                <th>User Story</th><th>Acceptance Criteria</th><th>Priority <button id='pbl_button' type='button' onclick='update({$button})'>{$buttonlabel}</button></th>
          </tr>";

    while($row = mysqli_fetch_array($result)) {
        //Checks if the PBI is completed or not
        if ($row{'completed'} != NULL) {
            $done = "class='completed'";
        } else {
            $done = '';
        }
        //Populates the table row for this PBI
        echo "<tr {$done}><td>{$row['userStory']}</td><td>{$row['acceptance']}</td><td class='controls'>Current Priority:{$row['priority']}  ";
        //Adds Up and Down buttons if there's not a Sprint in progress
        if (!$liveSprint && ($_SESSION['status'] == '1')){
            echo "<a class='edit_button' href='edit.php?pbi={$row['pbiNo']}'><i class='material-icons'>settings</i></a><br/>";
            echo "<a href='Scripts/priority.php?up={$row['pbiNo']}'>Move Up</a><a href='Scripts/priority.php?down={$row['pbiNo']}'>Move Down</a></td>";
        } else {
            echo "</td>";
        }
    }
?>