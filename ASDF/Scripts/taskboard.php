<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 02-Jul-18
 * Time: 11:44 AM
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'connection.php';

//Checks the database for the user's chosen colour
function getColour ($user, $db) {
    $sql2 = "SELECT colour FROM users WHERE userID = {$user}";
    $result2 = mysqli_query($db, $sql2);
    $row2 = mysqli_fetch_array($result2);
    return $row2['colour'];
}
?>

<div id="titles">
    <div><h3>Not Started</h3></div>
    <div><h3>In Progress</h3></div>
    <div><h3>Testing</h3></div>
    <div><h3>Done</h3></div>
</div>

<?php
//Retrieves all the SBIs, in order of PBI priority
$sql = "SELECT S.sbiNo, S.task, S.inProgress, S.testing, S.done, S.effort, S.pbiNo, P.userStory, P.acceptance 
            FROM sbis S, pbis P
            WHERE S.pbiNo = P.pbiNo
            ORDER BY P.priority ASC, S.sbiNo ASC";
$result = mysqli_query($db, $sql);

$currentPBI = 0; //Used to keep track of the PBIs

while ($row = mysqli_fetch_array($result)) {
    if ($currentPBI == 0) {                         //First PBI only
        echo "<div class='pbi'><div class='drop-down'><button class='menubutton'>PBI No {$row['pbiNo']}</button><div class='menu-options'>User 
                Story:<br/>{$row['userStory']}<br/><br/>Acceptance Criteria:<br/>{$row['acceptance']}</div></div>";
    } else if ($row['pbiNo'] != $currentPBI) {      //Checks for a new PBI
        echo "</div>";                              //Closes previous PBI
        echo "<div class='pbi'><div class='drop-down'><button class='menubutton'>PBI No {$row['pbiNo']}</button><div class='menu-options'>User 
                Story:<br/>{$row['userStory']}<br/><br/>Acceptance Criteria:<br/>{$row['acceptance']}</div></div>";
    }
    echo "<div class='sbi' id='sbi{$row['sbiNo']}'>"; //Starts the SBI row

    if ($_SESSION['status'] == '1') { //Displays the Edit icon for Admin users
        echo "<div class='edit'><a class='edit_button' href='edit.php?sbi={$row['sbiNo']}'><i class='material-icons'>settings</i></a></div>";
    }
    //Checks the status of the SBI to display in the right column
    if ($row['inProgress'] == NULL) {  //Default state - SBI not started yet
        echo "<div class='not_started'>{$row['task']}<br/><div class='centered'>Effort:{$row['effort']}<a href='Scripts/progress.php?progress={$row['sbiNo']}'>Progress ></a></div></div>";
    } else {
        if ($row['testing'] == NULL) { //SBI in-progress but not in Testing yet
            //Checks which user moved the task into In-progress
            $user = substr($row['inProgress'],0,2);
            $colour = getColour($user, $db);
            //Populates the SBI cell with the user's colour
            echo "<div class='in_progress' style='background-color: {$colour}'>{$row['task']}<br/><div class='centered'>
                    <a href='Scripts/progress.php?regress={$row['sbiNo']}'>< Revert</a>
                    Effort:{$row['effort']}
                    <a href='Scripts/progress.php?progress={$row['sbiNo']}'>Progress ></a></div></div>";
        } else {
            if ($row['done'] == NULL) { //SBI in Testing but not Done yet
                //Checks which user moved the task into Testing
                $user = substr($row['testing'],0,2);
                $colour = getColour($user, $db);
                //Populates the SBI cell with the user's colour
                echo "<div class='testing' style='background-color: {$colour}'>{$row['task']}<br/><div class='centered'>
                <a href='Scripts/progress.php?regress={$row['sbiNo']}'>< Revert</a>
                Effort:{$row['effort']}
                <a href='Scripts/progress.php?progress={$row['sbiNo']}'>Progress ></a></div></div>";
            } else { //SBI is Done
                //Checks which user moved the task into Done
                $user = substr($row['done'],0,2);
                $colour = getColour($user, $db);
                //Populates the SBI cell with the user's colour
                echo "<div class='done' style='background-color: {$colour}'>{$row['task']}<br/><div class='centered'>
                <a href='Scripts/progress.php?regress={$row['sbiNo']}'>< Revert</a>
                Effort:{$row['effort']}</div></div>";
            }
        }
    }
    echo "</div>";//Closes the SBI
    $currentPBI = $row['pbiNo'];
}