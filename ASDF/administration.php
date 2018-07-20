<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 20/07/2018
 * Time: 09:52
 */

include_once 'header.php';

if ($_SESSION['status'] != '1') { //Checks for Admin user
    header('Location: index.php');
    exit;
}
?>
<script> //Sets the navbar link and title to show which page you're on
    document.getElementById("adminLink").className += " active";
    document.title = "ASDF - Administration";
</script>
<link rel="stylesheet" href="CSS/tables.css">
<div id='login_container'><div id="disclaimers"><h3>Administration Page</h3>

<?php
if (isset($_GET['func'])) { //Checks what it's being asked to do
    $func = filter_var($_GET['func'], FILTER_SANITIZE_NUMBER_INT);
    switch ($func) {
        case 1: //User Admin
            echo "Login names must be unique, and are case-sensitive.<br/>";
            echo "<form method='post' action='administration.php?func=2'>
                    Login Name: <input type='text' name='name' maxlength='128' required>
                     <br/>
                     User Type: <input type='radio' name='type' value='0' checked> Standard User
                     <input type='radio' name='type' value='1'>Admin User 
                     <input type='submit' value='Add New User'></form>
                     <a href='admin.php'>Back</a></div>";

            $sql = "SELECT logins.login, logins.status, logins.userID, users.name 
                    FROM logins LEFT JOIN users ON logins.userID = users.userID;";
            $result = mysqli_query($db, $sql);
            $total = mysqli_num_rows($result);

            echo "<div class='table_cont'>";
            echo "<table id='backlog'><tr><th>Login Name</th><th>User Name</th><th>Type</th><th>Actions</th></tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['name'] != null) { //Gets names for existing users
                    $name = $row['name'];
                } else { //Sets default for new accounts
                    $name = "New User";
                }
                if ($row['status'] == 1){ //Used for make/remove admin buttons
                    $status = 'Admin';
                    $link = "<a href=administration.php?func=5&user={$row['userID']}&demote>Remove Admin</a>";
                } else {
                    $status = 'User';
                    $link = "<a href=administration.php?func=5&user={$row['userID']}&promote>Make Admin</a>";
                }
                echo "<tr><td>{$row['login']}</td><td>{$name}</td><td>{$status}</td>
                        <td><a href='administration.php?func=3&user={$row['userID']}'>Delete User</a>
                        {$link}</td></tr>";
            }
            echo "<tr><td>Total Users =</td><td colspan='3'>{$total}</td></tr></table></div>";
            break;
        case 2: //Add new user
            echo "Working...";
            if (isset($_POST['name'])) { //Receives input from stage 1
                $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
                $status = filter_var($_POST['type'], FILTER_SANITIZE_NUMBER_INT);

                $sql = "INSERT INTO logins (login, status) VALUES ('{$name}', '{$status}');";
                $result = mysqli_query($db, $sql);
                if (!$result) {
                    echo "Error: could not add user to database.";
                    echo "<a href='administration.php?func=1'>Back</a>";
                } else { //Redirects to the User List page
                    echo "<script>location='administration.php?func=1';</script>";
                }
            }
            break;
        case 3: //Delete user
            if(isset($_GET['user'])) {
                echo "Deleting a user account will remove all their previous chat messages, profile 
                information and login details from the system.<br/>This <b>cannot</b> be undone.<br/>";
                echo "<a href='administration.php?func=3&confirm={$_GET['user']}'>Confirm</a> ";
            } else {
                if (isset($_GET['confirm'])) { //User has confirmed deletion
                    $user = filter_var($_GET['confirm'], FILTER_SANITIZE_NUMBER_INT);
                    $sql = "DELETE FROM chat WHERE sender = '{$user}';";
                    $result = mysqli_query($db, $sql);
                    if (!$result) {
                        echo "Error: unable to delete user messages.";
                    } else {
                        echo "User messages deleted ok.<br/>";
                    }
                    $sql = "DELETE FROM users WHERE userID = '{$user}';";
                    $result = mysqli_query($db, $sql);
                    if (!$result) {
                        echo "Error: unable to delete user profile.";
                    } else {
                        echo "User profile deleted ok.<br/>";
                    }
                    $sql = "DELETE FROM logins WHERE userID = '{$user}';";
                    $result = mysqli_query($db, $sql);
                    if (!$result) {
                        echo "Error: unable to delete user account.";
                    } else {
                        echo "User account deleted ok.<br/>";
                    }
                }
            }
            echo "<a href='administration.php?func=1'>Back</a></div>";
            break;
        case 4: //Clear Chat log
            if (isset($_GET['confirm'])) {
                $sql = "DELETE FROM chat;";
                $result = mysqli_query($db, $sql);
                if (!$result) {
                    echo "Error: unable to delete chat messages.";
                } else {
                    echo "User messages deleted ok.<br/>";
                    //Sets mode for the signaller script
                    $signal_mode = 'chat';
                    //Updates the database revision
                    include_once 'Scripts/signaller.php';
                }
            } else {
                echo "Clearing the Chat Log will erase all messages from all Users. This <b>cannot</b> be undone.<br/>";
                echo "<a href='administration.php?func=4&confirm'>Confirm</a> ";
            }
            echo "<a href='admin.php'>Back</a></div>";
            break;
        case 5: //Change user status
            echo "Working...";
            if (isset($_GET['user'])) {
                $user = filter_var($_GET['user'], FILTER_SANITIZE_NUMBER_INT);
                if (isset($_GET['promote'])) {
                    $status = 1;
                } else {
                    $status = 0;
                }
                $sql = "UPDATE logins SET status = '{$status}' WHERE userID = '{$user}';";
                $result = mysqli_query($db, $sql);
                if (!$result) {
                    echo "Error: could not change user status.";
                    echo "<a href='admin.php'>Back</a></div>";
                } else {
                    echo "<script>location='administration.php?func=1';</script>";
                }
            }
            break;
        case 6: //Backup Project Data
            echo "This utility allows you to export all project data (Product Backlog, SBIs and Sprint History) to file;
            this does not delete anything from your system.  PBI, SBI and Sprint History will be stored into one .csv file,
            in that order.<br/>Press the button to proceed. ";
            echo "<a href='administration.php?func=6&proceed'>Continue</a> ";
            echo "<a href='administration.php?func=1'>Back</a><br/>";

            if(isset($_GET['proceed'])) {
                $sql = "SELECT * FROM pbis ORDER BY completed, priority;";
                $result = mysqli_query($db, $sql);

                //Creates a new file to store the data.
                $date = date("Y-m-d h-i-a");
                $filename = "backup-{$date}.csv";
                $backup = fopen($filename, "w") or die("Unable to create backup file!");

                //Writes the PBI data to disk (if there is any)
                if (mysqli_num_rows($result) > 0) {
                    $text = "pbiNo, userStory, acceptance, priority, completed, blank, blank \n";
                    fwrite($backup, $text);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $story = str_replace(',','', $row['userStory']); //Deletes commas from text fields
                        $criteria = str_replace(',', '', $row['acceptance']);
                        $text = "{$row['pbiNo']}, \"{$story}\", \"{$criteria}\", {$row['priority']}, {$row['completed']}, , \n";
                        fwrite($backup, $text);
                    }
                    echo "PBIs saved OK.<br/>";
                }

                //Gets the SBI data (if there is any)
                $text = "\n \n";
                fwrite($backup, $text);
                $sql = "SELECT * from sbis;";
                $result = mysqli_query($db, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $text = "sbiNo, pbiNo, task, inProgress, testing, done, effort\n";
                    fwrite($backup, $text);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $task = str_replace(',', '', $row['task']); //Deletes commas from text field
                        $text = "{$row['sbiNo']}, {$row['pbiNo']}, \"{$task}\", {$row['inProgress']}, {$row['testing']},
                         {$row['done']}, {$row['effort']}\n";
                        fwrite($backup, $text);
                    }
                    echo "SBIs saved OK.<br/>";
                } else {
                    $text = "0, 0, \"No Sprint in progress\", 0, 0, 0, 0\n";
                    fwrite($backup, $text);
                }

                //Gets the Previous Sprint data (if there is any)
                $text = "\n \n";
                fwrite($backup, $text);
                $sql = "SELECT * from sprints;";
                $result = mysqli_query($db, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $text = "sprintNo, startDate, endDate, startingBacklog, backlogTotal, blank, blank\n";
                    fwrite($backup, $text);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $text = "{$row['sprintNo']}, {$row['startDate']}, {$row['endDate']}, {$row['startingBacklog']},
                         {$row['backlogTotal']}, , \n";
                        fwrite($backup, $text);
                    }
                    echo "Project history saved OK.<br/>";
                }
                fclose($backup);
                echo "<a href='backup-{$date}.csv'>Download</a></div>";
            }
            break;
        case 7: // Erase Everything

            break;
        default: //Shouldn't be here
            echo "<script>location='administration.php?func=1';</script>";
    }
} else {//Shouldn't be here
    echo "<script>location='administration.php?func=1';</script>";
}
?>
    </div>
</div>
