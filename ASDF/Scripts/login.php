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
    if (isset($_POST['pw'])) {
        $pw = filter_var($_POST['pw'], FILTER_SANITIZE_STRING);
    } else {
        $pw = 'new';
    }

    include_once 'connection.php';
    //Gets the encrypted password from the database
    $sql = "SELECT * FROM logins WHERE login = '{$login}'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) != 1) { //Wrong username means user is booted out
        session_unset();
        session_destroy();
        header('Location: ../index.php?error');
        exit;
    }

    //Checks for new users or a cleared password
    if(!isset($row['pwhash'])) {
        //User is allowed to log-in without a password, if the username was correct
        $_SESSION['logged-in'] = TRUE;
        $_SESSION['status'] = '0'; //Forces normal user at this stage
        $_SESSION['id'] = $row['userID'];
        //Temp details
        $_SESSION['name'] = 'Set Password';
        $_SESSION['initials'] = 'ABCD';
        //Sends user to the edit function
        header("Location: ../edit.php?profile={$row['userID']}&user={$row['userID']}&new");
        exit;
    }

    $pwhash = $row['pwhash'];
    if(password_verify($pw, $pwhash)) { //Checks if the password matches
        //Sets a cookie on the user's system to log-out after 24hrs
        setcookie("Logged-in", time(), time()+86400, "/");
        //Manually forces the cookie into the superglobal  (otherwise not updated until next page refresh)
        $_COOKIE['Logged-in'] = time();
        $_SESSION['logged-in'] = TRUE;
        $_SESSION['status'] = $row['status'];
        $_SESSION['id'] = $row['userID'];
        $id = $row['userID'];
        //Gets user details from database
        $sql = "SELECT name, initials, colour FROM users WHERE userID = '{$id}';";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_assoc($result);

        //Checks for a new User and directs them to fill in their details
        if ($row['name'] == NULL) {
            //Temp details
            $_SESSION['name'] = 'New User';
            $_SESSION['initials'] = 'ABCD';
            $editpage = "Location: ../edit.php?user={$id}&new";
            header($editpage);
        } else { //Existing user
            $_SESSION['name'] = $row['name'];
            $_SESSION['initials'] = $row['initials'];
            //Updates the last-logged-in variable
            $signal_mode = 'login';
            include_once 'signaller.php';
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