<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 26-Jun-18
 * Time: 1:30 PM
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!$_SESSION['logged-in'] OR !isset($_COOKIE['Logged-in'])) {
    header('Location: ..\index.php');
    exit;
}

if (isset($_GET['up'])) {
    $pbi = filter_var($_GET['up'], FILTER_SANITIZE_NUMBER_INT);
    $mode = TRUE;
} else if (isset($_GET['down'])) {
    $pbi = filter_var($_GET['down'], FILTER_SANITIZE_NUMBER_INT);
    $mode = FALSE;
} else {
    header('Location: ../index.php');
    exit;
}

include 'connection.php';

//Gets the total number of unfinished PBIs in the system
$sql = "SELECT COUNT(pbiNo) FROM pbis WHERE completed IS NULL";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);
$total = $row['COUNT(pbiNo)'];

//Gets the new priority for the updated task
$sql = "SELECT priority FROM pbis WHERE pbiNo = {$pbi}";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);
$priority = $row['priority'];

if ($mode) { //Increasing priority
    //Moves PBI up the list
    $priority--;
    //Gets unfinished PBIs, higher-priority than the affected one
    $list = array();
    $i = 1;
    $sql = "SELECT pbiNo FROM pbis WHERE completed IS NULL 
              AND priority < {$priority}
              AND pbiNo != {$pbi} 
              ORDER BY priority ASC";
    $result = mysqli_query($db, $sql);
    //Compiles higher-priority PBIs into an array
    while ($row = mysqli_fetch_array($result)) {
        $list[$row['pbiNo']] = $i++;
    }
    //Inserts the affected PBI into the array
    $list[$pbi] = $i++;
    //Adds the rest of the PBIs to the array
    $sql = "SELECT pbiNo FROM pbis WHERE completed IS NULL 
              AND priority >= {$priority} 
              AND pbiNo != {$pbi} 
              ORDER BY priority ASC";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $list[$row['pbiNo']] = $i++;
    }
} else {  //Decreasing priority
    $priority++;
    //Gets unfinished PBIs, higher-priority than the affected one
    $list = array();
    $i = 1;
    $sql = "SELECT pbiNo FROM pbis WHERE completed IS NULL 
              AND priority <= {$priority}
              AND pbiNo != {$pbi} 
              ORDER BY priority ASC";
    $result = mysqli_query($db, $sql);
    //Compiles higher-priority PBIs into an array
    while ($row = mysqli_fetch_array($result)) {
        $list[$row['pbiNo']] = $i++;
    }
    //Inserts the affected PBI into the array
    $list[$pbi] = $i++;
    //Adds the rest of the PBIs to the array
    $sql = "SELECT pbiNo FROM pbis WHERE completed IS NULL 
              AND priority > {$priority} 
              AND pbiNo != {$pbi} 
              ORDER BY priority ASC";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $list[$row['pbiNo']] = $i++;
    }
}

//Updates the database to give sequential numbers from 1-total pbis.
foreach ($list as $pbi_no => $new_priority) {
    $sql = "UPDATE pbis SET priority = $new_priority WHERE pbiNo = $pbi_no";
    $result = mysqli_query($db, $sql);
}
include 'signaller.php';
header('Location: ../pbl.php');
?>