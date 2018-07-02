<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:57
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_REQUEST['login'])) { //Checks that user was trying to login
    $login = filter_var($_POST['user'], FILTER_SANITIZE_STRING);
    $pw = filter_var($_POST['pw'], FILTER_SANITIZE_STRING);

    include_once 'connection.php';
    //Gets the encrypted password from the database
    $sql = "SELECT * FROM logins WHERE login = '{$login}'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);

    //Checks for new users or a cleared password
    if(!isset($row['pwhash'])) {
        //Sends user to the edit function
        header("Location: edit.php?account={$row['userID']}&profile={$row['userID']}");
        exit;
    }

    $pwhash = $row['pwhash'];
    if(password_verify($pw, $pwhash)) { //Checks if the password matches
        $_SESSION['logged-in'] = TRUE;
        $_SESSION['status'] = $row['status'];
        $_SESSION['id'] = $row['userID'];
        $id = $row['userID'];
        //Gets user details from database
        $sql = "SELECT name, initials, colour FROM users WHERE userID = '{$id}'";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_assoc($result);

        //Checks for a new User and directs them to fill in their details
        if ($row['name'] == NULL) {
            //Temp details
            $_SESSION['name'] = 'New User';
            $_SESSION['initials'] = 'ABCD';
            $_SESSION['colour'] = 'grey';
            $editpage = "Location: edit.php?profile={$id}";
            header($editpage);
        } else { //Existing user
            $_SESSION['name'] = $row['name'];
            $_SESSION['initials'] = $row['initials'];
            $_SESSION['colour'] = $row['colour'];
            header("Location: ../task-board.php");
        }
    } else {
        header('Location: ../index.php?error');
    }
} else { //Logout of everything
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit;
}