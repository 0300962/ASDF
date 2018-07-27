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
//Sets page title
echo "<script>document.title = 'ASDF - Edit Details';</script>";

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
    echo "Your username was set by the system administrator.  Note that passwords are case-sensitive, and must be at
     least 8 characters long.<br/>";
    $profile = filter_var($_GET['profile'], FILTER_SANITIZE_NUMBER_INT);
    //Checks if a user is editing their own details or not
    if (($profile == $_SESSION['id']) OR $approved) {
        $sql = "SELECT * FROM logins WHERE userID = {$profile}";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 1) {
            echo "Error retrieving details from database.";
        }
        if (isset($_GET['new'])) { //Checks for a new user
            echo "Select a password to use to login to ASDF. The next time you login, you will be asked for some profile
             information too.<br/>";
            echo "<input type=hidden name='new' value='true'>";
        }
        echo "<input type='hidden' name='id' value='{$profile}'>";
        echo "<input type=hidden name='mode' value='update_profile'>";
        echo "Login: <input type='text' name='login' value='{$row['login']}' maxlength='128' required readonly><br/><br/>";
        echo "Password: <input type='password' name='password' placeholder='Enter new password' required minlength='8'><br/><br/>";
        echo "Confirm: <input type='password' name='password2' placeholder='Confirm new password' required minlength='8'><br/>";
        if ($row['status'] == '1') { //Checks the admin status of the account being edited
            if ($approved) { //Checks if it's an admin making the change
                echo "Administrator: <input type='radio' name='status' value='1' checked>Yes";
                echo "<input type='radio' name='status' value='0'>No<br/>";
            } else {
                echo "Administrator: <input type='radio' name='status' value='1' checked>Yes";
                echo "<input type='radio' name='status' value='0' disabled>No<br/>";
            }
        } else { //Normal user account being edited
            if ($approved) {
                echo "Administrator: <input type='radio' name='status' value='1'>Yes";
                echo "<input type='radio' name='status' value='0' checked>No";
            } else { //Normal users may not make themselves Admin
                echo "Administrator: <input type='radio' name='status' value='1' disabled>Yes";
                echo "<input type='radio' name='status' value='0' checked>No";
            }
        }
        echo "<br/><br/><input type='submit' name='update' value='Save'> ";
        if (isset($_GET['welcome'])) { //Directs new users to log out again
            echo "<a href='Scripts/login.php'>Let's login again...</a>";
        } else {
            echo "<a href='profile.php?user={$profile}'>Back</a>";
        }
        echo "</form>";
    } else {  //Unauthorised user
        echo "<script type='text/javascript'> location = 'edit.php?error=0'</script>";
    }
} elseif (isset($_GET['user'])) { //User Profile details
    echo "<h3>Edit User Profile</h3>";
    echo "Your name and details are used to help your colleagues know a little more about you; useful details might 
    include your timezone/location, area of expertise, and so on. Your initials and user colour are used to indicate
     what you've been working on with the Task Board.<br/><br/>";
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
        echo "Name: <input type='text' name='name' value='{$row['name']}' maxlength='50' required> ";
        echo "Initials: <input type='text' name='initials' value='{$row['initials']}' maxlength='4' required><br/>";
        echo "Details: <br/><textarea name='details' maxlength='300' rows='8' cols='70'>{$row['details']}</textarea><br/>";
        echo "Colour: <input type='color' name='colour' value='{$row['colour']}'><br/><br/>";
        echo "<input type='submit' name='update' value='Save'>  ";
        if (isset($_GET['welcome'])) { //Directs new users to the Help section
            echo "<a href='guide.php'>Let's get started...</a>";
        } else {
            echo "<a href='profile.php?user={$user}'>Back</a>";
        }
        echo "</form>";
    } else {  //Unauthorised user
        echo "<script type='text/javascript'> location = 'edit.php?error=0'</script>";
    }
} elseif (isset($_GET['pbi']) && $approved) { //PBI item
    echo "<h3>Edit Product Backlog Item</h3>";
    echo "The User Story is a brief description of what the product will do in a particular scenario (e.g. As a Manager, 
    I want to see a summary of sales for the day).  The Acceptance Criteria is what the team use to decide if a feature
    is completed or not (e.g. Manager able to view Sales totals, broken down into categories, graph of sales for the week).<br/><br/>";
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
    echo "<input type='submit' name='update' value='Save'>  <a href='pbl.php'>Back</a>";
    echo "</form>";
} elseif (isset($_GET['sbi']) && $approved) { //SBI items
    echo "<h3>Edit Sprint Backlog Item</h3>";
    echo "Each SBI should be a well-defined task, that shouldn't take more than one day to complete (e.g. Mock-up the User 
    Profile page).  If an SBI is too complex or non-specific, break it down into more manageable steps; there's no such
    thing as too many SBIs!  The Effort indicator is a realistic estimate of how much work is involved with the task; 
    this may be in hours, or more arbitrary steps (e.g. 1,2,4,8).<br/><br/>";
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
    echo "<br/><input type='submit' name='update' value='Save'>  <a href='task-board.php'>Back</a>";
    echo "</form>";
} elseif (isset($_GET['project']) && $approved) { //Overall Project details
    echo "<h3>Edit Project Details</h3>";
    echo "The Project Details page is a source of reference for the team members.  Feel free to add whatever information, links
    and so on that may be of use.  You can even embed other web content in the Links section if you want to.<br/><br/>";
    $sql = "SELECT * FROM project";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) != 1) {
        echo "Error retrieving details from database.";
    }
    echo "<input type=hidden name='mode' value='project'>";
    echo "Project Title: <input type='text' name='title' value='{$row['title']}' maxlength='30' required><br/>";
    echo "Project Details:<br/><textarea name='details' maxlength='500' rows='8' cols='70'>{$row['details']}</textarea><br/>";
    echo "Project Links:<br/><textarea name='links' maxlength='500' rows='8' cols='70'>{$row['links']}</textarea><br/>";
    echo "<input type='submit' name='update' value='Save'> <a href='project.php'>Back</a>";
    echo "</form>";
} else { //Shouldn't be here - redirects to Task Board
    echo "<script type='text/javascript'> location = 'task-board.php'</script>";
}