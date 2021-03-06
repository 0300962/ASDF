<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 04-Jul-18
 * Time: 9:50 AM
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!$_SESSION['logged-in'] OR !isset($_COOKIE['Logged-in'])) {
    header('Location: ..\index.php');
    exit;
}

//Returns the user to the Edit page with an error code
function error($errNo, $src){
    $header = "Location: ../edit.php?error={$errNo}&{$src}";
    header($header);
}
//Sends the update to the database and checks for a problem
function send($sql, $db, $src) {
    $result = mysqli_query($db, $sql);
    if (!$result) {
        error(2, $src);
    } else {
        header("Location: ../edit.php?{$src}&ok");
    }
}

include_once 'connection.php';

switch ($_POST['mode']) {
    case 'update_profile':  //Password and status changes
        $userID = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $pw = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        $pw2 = filter_var($_POST['password2'], FILTER_SANITIZE_STRING);
        $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        //Checks that both passwords match
        if ($pw != $pw2) {
            error(1, $src);
        }
        //Securely encrypts the password
        $pwhash = password_hash($pw, PASSWORD_DEFAULT);

        //Checks for a new or existing user to point them to the user guide
        if (isset($_POST['new'])) {
            $src = "profile={$userID}&welcome";
        } else {
            $src = "profile={$userID}";
        }

        $sql = "UPDATE logins SET pwhash = '{$pwhash}', status = '{$status}'
                WHERE userID = '{$userID}';";
        //Submits any changes and returns to Edit page
        send($sql, $db, $src);
        break;
    case 'user':  //User details changes
        $userID = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $initials = filter_var($_POST['initials'], FILTER_SANITIZE_STRING);
        $details = filter_var($_POST ['details'], FILTER_SANITIZE_STRING);
        $colour = filter_var($_POST['colour'], FILTER_SANITIZE_EMAIL);

        //Checks what colour the users have chosen
        $red = hexdec(substr($colour, 1,2));
        $green = hexdec(substr($colour, 3, 2));
        $blue = hexdec(substr($colour, 5, 2));
        //Prevent any users from setting their colour to (effectively) black
        if (($red <= 33) AND ($blue <= 33) AND ($green <= 33) OR (($red + $blue + $green) <= 150)) {
            $colour = '#333333'; //Dark grey
        }
        //Checks for a new or existing user to point them to the user guide
        if (isset($_POST['new'])) {
            $newUser = TRUE;
            $src = "user={$_POST['id']}&welcome";
        } else {
            $newUser = FALSE;
            $src = "user={$_POST['id']}";
        }
        //Checks data isn't too long for the database; shouldn't be possible
        if ((strlen($name) > 50) OR (strlen($initials) > 4) OR (strlen($details) > 300)) {
            error(3, $src);
        }
        if ($newUser) { //Creates a new User record for the user during registration
            $sql = "INSERT INTO users (userID, name, initials, details, colour) 
                    VALUES ('{$userID}', '{$name}', '{$initials}', '{$details}', '{$colour}')";
        } else {
            $sql = "UPDATE users SET name='{$name}', initials='{$initials}', details='{$details}', colour='{$colour}'
                WHERE userID = '{$userID}';";
        }
        //Submits any changes
        send($sql, $db, $src);
        break;
    case 'pbi':  //User Story and Acceptance Criteria changes
        $src = "pbi={$_POST['id']}";
        $pbi = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $userStory = filter_var($_POST['story'], FILTER_SANITIZE_STRING);
        $criteria = filter_var($_POST['criteria'], FILTER_SANITIZE_STRING);
        //Checks data isn't too long for the database; shouldn't be possible
        if ((strlen($userStory) > 300) OR (strlen($criteria) > 300)) {
            error(3, $src);
        }

        $sql = "UPDATE pbis SET userStory = '{$userStory}', acceptance = '{$criteria}'
                WHERE pbiNo = '{$pbi}';";
        //Submits any changes
        send($sql, $db, $src);
        break;
    case 'sbi':  //Sub-task and Effort changes
        $src = "sbi={$_POST['id']}";
        $sbi = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $task = filter_var($_POST['task'], FILTER_SANITIZE_STRING);
        $effort = filter_var($_POST['effort'], FILTER_SANITIZE_NUMBER_INT);
        //Checks data isn't too long for the database; shouldn't be possible
        if ((strlen($task) > 300) OR (strlen($effort) > 2)) {
            error(3, $src);
        }

        $sql = "UPDATE sbis SET task = '{$task}', effort = '{$effort}'
                WHERE sbiNo = '{$sbi}';";
        //Submits any changes
        send($sql, $db, $src);
        break;
    case 'project':  //General Project Information changes
        $src = "project";
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);
        $links = addslashes($_POST['links']);
        //$links = filter_var($_POST['links'], FILTER_SANITIZE_URL);
        //Checks data isn't too long for the database; shouldn't be possible
        if ((strlen($title) > 30) OR (strlen($details) > 500) OR (strlen($links) > 300)) {
            error(3, $src);
        }

        $sql = "UPDATE project SET title = '{$title}', details = '{$details}', links = '{$links}';";
        //Submits any changes
        send($sql, $db, $src);
        break;
    case 'newproject': //New project information
        $src = "project";
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);
        $links = addslashes($_POST['links']);
        if ((strlen($title) > 30) OR (strlen($details) > 500) OR (strlen($links) > 300)) {
            error(3, $src);
        }
        $sql = "INSERT INTO project (title, details, links) 
                VALUES ('{$title}', '{$details}', '{$links}');";
        //Submits any changes
        send($sql, $db, $src);
        break;
    default:
        error(4, '');
}