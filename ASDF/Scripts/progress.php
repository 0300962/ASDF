<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 02-Jul-18
 * Time: 11:54 AM
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!$_SESSION['logged-in'] OR !isset($_COOKIE['Logged-in'])) {
    header('Location: ..\index.php');
    exit;
}
//Gets the current SBI from the database
function check($sbiNo, $db) {
    $sql = "SELECT * FROM sbis WHERE sbiNo = {$sbiNo};";
    $result = mysqli_query($db, $sql);
    return mysqli_fetch_array($result);
}

//Submits change to database and updates database revision
function update($sql, $db){
    $result = mysqli_query($db, $sql);
    if (!$result) {
        echo "Error: unable to update SBI!";
    }
    //Sets mode for the signaller script
    $signal_mode = 'tb';
    //Updates the database revision
    include 'signaller.php';
    header('Location: ../task-board.php');
}

include_once 'connection.php';

if (isset($_GET['progress'])) { //Checks for incoming SBI number to be progressed
    $sbiNo = filter_var($_GET['progress'], FILTER_SANITIZE_NUMBER_INT);
    $row = check($sbiNo, $db);

    //Checks what status to move the SBI to
    if ($row['inProgress'] == NULL) {
        $stage = 'inProgress';
    } elseif ($row['testing'] == NULL) {
        $stage = 'testing';
    } elseif ($row['done'] == NULL) {
        $stage = 'done';
    } else { //Something has gone wrong
        echo $sql."<br/>";
        print_r($row);
        exit;
    }
    //Forms the timestamp of userID and date as a string
    $timestamp = ''.$_SESSION['id'].' '.date('Y-m-d');
    //Adds stamp to SBI
    $sql = "UPDATE sbis SET {$stage} = '{$timestamp}' WHERE sbiNo = {$sbiNo};";
    //Commits the change to the database
    update($sql, $db);
} elseif (isset($_GET['regress'])){  //Checks for incoming SBI number to be pushed back a stage
    $sbiNo = filter_var($_GET['regress'], FILTER_SANITIZE_NUMBER_INT);
    $row = check($sbiNo, $db);

    //Checks the current status of the SBI, to erase the last update
    if ($row['done'] != NULL) {
        $stage = 'done';
    } elseif ($row['testing'] != NULL) {
        $stage = 'testing';
    } elseif ($row['inProgress'] != NULL) {
        $stage = 'inProgress';
    } else { //Something has gone wrong
        echo $sql."<br/>";
        print_r($row);
        exit;
    }
    $sql = "UPDATE sbis SET {$stage} = NULL WHERE sbiNo = {$sbiNo}";
    //Commits the change to the database
    update($sql, $db);
} else { //Shouldn't be here
    header('Location: index.php');
}
?>