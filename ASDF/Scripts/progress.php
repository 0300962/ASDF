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

/*if (!$_SESSION['logged-in']) {
    header('Location: index.php');
    exit;
}*/

if (isset($_GET['sbi'])) { //Checks for incoming SBI number to be progressed
    $sbiNo = filter_var($_GET['sbi'], FILTER_SANITIZE_NUMBER_INT);
    include 'connection.php';
    $sql = "SELECT * FROM sbis WHERE sbiNo = {$sbiNo};";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);

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
    //Forms the timestamp of userID and date
    $timestamp = ''.$_SESSION['id'].' '.date('Y-M-D');
    //Adds stamp to SBI
    $sql = "UPDATE sbis SET {$stage} = '{$timestamp}' WHERE sbiNo = {$sbiNo};";
    $result = mysqli_query($db, $sql);

    //Updates the database revision
    include 'signaller.php';
    header('Location: ../task-board.php');
} else { //Shouldn't be here
    header('Location: index.php');
}
?>