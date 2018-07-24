<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 14-Jul-18
 * Time: 2:28 PM
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!$_SESSION['logged-in'] OR !isset($_COOKIE['Logged-in'])) {
    header('Location: index.php');
    exit;
}
if ($_SESSION['status'] != '1') { //Checks for Admin user
    header('Location: ../index.php');
    exit;
}

if (!isset($_POST['pbi'])) { //Checks for incoming form data from Sprint Admin page
    header('Location: ../index.php');
    exit;
} else {
    //Checks inputs from SBI form
    $pbi = filter_var($_POST['pbi'], FILTER_SANITIZE_NUMBER_INT);
    $task = filter_var($_POST['task'], FILTER_SANITIZE_STRING);
    $effort = filter_var($_POST['effort'], FILTER_SANITIZE_NUMBER_INT);
    //Writes new SBI into database
    include_once 'connection.php';
    $sql = "INSERT INTO sbis (pbiNo, task, effort) 
            VALUES ('{$pbi}','{$task}','{$effort}');";
    $result = mysqli_query($db, $sql);
    if (!$result) {
        echo "Error: unable to add SBI to database.";
        echo $sql;
        print_r($_POST);
    } else {
        $signal_mode = 'tb';
        include 'signaller.php'; //Updates the taskboard revision
        //Gets the existing SBIs for each PBI
        $sql = "SELECT sbiNo, task, effort FROM sbis WHERE pbiNo = '{$pbi}' ORDER BY sbiNo ASC;";
        $result2 = mysqli_query($db, $sql);
        //Populates the SBI list on the Sprint Admin page
        while ($row2 = mysqli_fetch_assoc($result2)) {
            echo "<div class='sbi'>SBI Number: {$row2['sbiNo']} Effort: <span class='effortTotal'>{$row2['effort']}</span><br/>{$row2['task']}</div>";
            echo "<div class='spacer'></div>";
        }
    }
}
?>