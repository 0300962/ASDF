<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:58
 */

include 'header.php';

if ($_SESSION['status'] == '1') { //Sets a flag for an admin user
    $approved = TRUE;
} else {
    $approved = FALSE;
}

echo "<div id='login_container'><div id='login_panel'>";  //Opens the editing boxes
echo "<form method='post' action='Scripts/editor.php'>";
if (isset($_GET['error'])) { //Checks for an incoming Error code from a failed edit
    echo "Error: ";
    $error = filter_var($_GET['error'], FILTER_SANITIZE_NUMBER_INT);
    switch ($error) {
        case 0:
            echo "Unauthorised user!";
            break;
        case 1:
            echo "Passwords do not match!";
            break;
        case 2:
            echo "Could not save changes.";
            break;
        case 3:
            echo "Data exceeds maximum field length.";
            break;
        case 4:
            echo "Unknown editing mode. Please check and try again.";
            break;
        default:
            echo "Unknown error.";
            break;
    }
    echo "<br/>";
} elseif (isset($_GET['ok'])) {
    echo "Update saved to Database.<br/>";
    if ((isset($_GET['profile'])) OR (isset($_GET['user']))) {
        echo "Logout using the pop-up button in the upper-right, and log back in to use the new details.";
    }
}

//Checks what is to be edited
if (isset($_GET['profile'])) { //User Login details
    echo "<h3>Edit Login Details</h3>";
    $profile = filter_var($_GET['profile'], FILTER_SANITIZE_NUMBER_INT);
    //Checks if a user is editing their own details or not
    if (($profile == $_SESSION['id']) OR $approved) {
        $sql = "SELECT * FROM logins WHERE userID = {$profile}";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 1) {
            echo "Error retrieving details from database.";
        }
        echo "<input type='hidden' name='id' value='{$profile}'>";
        echo "<input type=hidden name='mode' value='update_profile'>";
        echo "Login: <input type='text' name='login' value='{$row['login']}' maxlength='128' required readonly><br/><br/>";
        echo "Password: <input type='password' name='password' placeholder='Enter new password' required><br/><br/>";
        echo "Confirm: <input type='password' name='password2' placeholder='Confirm new password' required><br/>";
        if ($row['status'] == '1') { //Checks the admin status of the account being edited
            if ($approved) { //Checks if it's an admin making the change
                echo "Administrator: <input type='radio' name='status' value='1' checked>Yes";
                echo "<input type='radio' name='status' value='0'>No<br/>";
            } else {
                echo "Administrator: <input type='radio' name='status' value='1' checked readonly>Yes";
                echo "<input type='radio' name='status' value='0' readonly>No<br/>";
            }
        } else { //Normal user account being edited
            if ($approved) {
                echo "Administrator: <input type='radio' name='status' value='1'>Yes";
                echo "<input type='radio' name='status' value='0' checked>No";
            } else { //Normal users may not make themselves Admin
                echo "Administrator: <input type='radio' name='status' value='1' readonly>Yes";
                echo "<input type='radio' name='status' value='0' checked readonly>No";
            }
        }
        echo "<input type='submit' name='update' value='Save'>";
        echo "</form>";
    } else {  //Unauthorised user
        echo "<script type='text/javascript'> location = 'edit.php?error=0'</script>";
    }
} elseif (isset($_GET['user'])) { //User Profile details
    echo "<h3>Edit User Profile</h3>";
    $user = filter_var($_GET['user'], FILTER_SANITIZE_NUMBER_INT);
    //Checks if a user is editing their own details or not
    if (($user == $_SESSION['id']) OR $approved) {
        $sql = "SELECT * FROM users WHERE userID = {$user}";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 1 && (!isset($_GET['new']))) {
            echo "Error retrieving details from database.";
        }
        if (isset($_GET['new'])) { //Checks for a new user
            echo "<input type=hidden name='new' value='true'>";
        }
        echo "<input type='hidden' name='id' value='{$user}'>";
        echo "<input type=hidden name='mode' value='user'>";
        echo "Name: <input type='text' name='name' value='{$row['name']}' maxlength='50' required><br/>";
        echo "Initials: <input type='text' name='initials' value='{$row['initials']}' maxlength='4' required><br/>";
        echo "Details: <br/><textarea name='details' maxlength='300' rows='8' cols='70'>{$row['details']}</textarea><br/>";
        echo "Colour: <input type='color' name='colour' value='{$row['colour']}'><br/>";
        echo "<input type='submit' name='update' value='Save'>";
        echo "</form>";
    } else {  //Unauthorised user
        echo "<script type='text/javascript'> location = 'edit.php?error=0'</script>";
    }
} elseif (isset($_GET['pbi']) && $approved) { //PBI item
    echo "<h3>Edit Product Backlog Item</h3>";
    $pbi = filter_var($_GET['pbi'], FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT * FROM pbis WHERE pbiNo = {$pbi}";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) != 1) {
        echo "Error retrieving details from database.";
    }
    echo "<input type='hidden' name='id' value='{$pbi}'>";
    echo "<input type=hidden name='mode' value='pbi'>";
    echo "User Story:<br/><textarea name='story' maxlength='300' rows='8' cols='70' required>{$row['userStory']}</textarea><br/>";
    echo "Acceptance Criteria:<br/><textarea name='criteria' maxlength='300' rows='8' cols='70' required>{$row['acceptance']}</textarea><br/>";
    echo "<input type='submit' name='update' value='Save'>";
    echo "</form>";
} elseif (isset($_GET['sbi']) && $approved) { //SBI items
    echo "<h3>Edit Sprint Backlog Item</h3>";
    $sbi = filter_var($_GET['sbi'], FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT * FROM sbis WHERE sbiNo = {$sbi}";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) != 1) {
        echo "Error retrieving details from database.";
    }
    echo "<input type='hidden' name='id' value='{$sbi}'>";
    echo "<input type=hidden name='mode' value='sbi'>";
    echo "Parent PBI Number: {$row['pbiNo']}<br/>";
    echo "Task Description:<br/><textarea name='task' maxlength='300' rows='8' cols='70' required>{$row['task']}</textarea><br/>";
    echo "Task Effort:<input type='number' name='effort' value='{$row['effort']}' required min='1' max='32'><br/>";
    echo "<input type='submit' name='update' value='Save'>";
    echo "</form>";
} elseif (isset($_GET['project']) && $approved) { //Overall Project details
    echo "<h3>Edit Project Details</h3>";
    $sql = "SELECT * FROM project";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) != 1) {
        echo "Error retrieving details from database.";
    }
    echo "<input type=hidden name='mode' value='project'>";
    echo "Project Title:<input type='text' name='title' value='title' maxlength='30' required><br/>";
    echo "Project Details:<br/><textarea name='details' maxlength='500' rows='8' cols='70'>{$row['details']}</textarea><br/>";
    echo "Project Links:<br/><textarea name='links' maxlength='300' rows='8' cols='70'>{$row['links']}</textarea><br/>";
    echo "<input type='submit' name='update' value='Save'>";
    echo "</form>";
} else { //Shouldn't be here - redirects to Task Board
    echo "<script type='text/javascript'> location = 'task-board.php'</script>";
}