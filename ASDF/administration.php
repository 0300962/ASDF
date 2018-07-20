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
            echo "<form method='post' action='administration.php?func=2'>
                    Login Name: <input type='text' name='name' maxlength='128' required>
                     <input type='submit' value='Add New User'><br/>
                     User Type: <input type='radio' name='type' value='0' checked> Standard User
                     <input type='radio' name='type' value='1'>Admin User 
                     </form>
                     <a href='admin.php'>Back</a></div>";

            $sql = "SELECT * FROM logins;";
            $result = mysqli_query($db, $sql);
            $total = mysqli_num_rows($result);

            echo "<div class='table_cont'>";
            echo "<table id='backlog'><tr><th>Login Name</th><th>User Name</th><th>Type</th><th></th></tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['pwhash'] != null) { //Gets names for existing users
                    $sql2 = "SELECT name FROM users WHERE userID = '{$row['userID']}'";
                    $result2 = mysqli_query($db, $sql2);
                    $row2 = mysqli_fetch_assoc($result2);
                    $name = $row2['name'];
                } else { //Sets default for new accounts
                    $name = "New User";
                }
                if ($row['status'] == 1){
                    $status = 'Admin';
                } else {
                    $status = 'User';
                }
                echo "<tr><td>{$row['login']}</td><td>{$name}</td><td>{$status}</td>
                        <td><a href='administration.php?func=3&user={$row['userID']}'>Delete</a></td></tr>";
            }
            echo "<tr><td>Total Users =</td><td colspan='3'>{$total}</td></tr></table></div>";
            break;
        case 2: //Add new user
            if (isset($_POST['name'])) {
                $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
                $status = filter_var($_POST['type'], FILTER_SANITIZE_NUMBER_INT);

                $sql = "INSERT INTO logins (login, status) VALUES ('{$name}', '{$status}');";
                $result = mysqli_query($db, $sql);
                if (!$result) {
                    echo "Error: could not add user to database.";
                    echo "<a href='administration.php?func=1'>Back</a>";
                } else {
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
                if (isset($_GET['confirm'])) {
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
            echo "<a href='admin.php'>Back</a></div>";
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
        case 5: //Backup Project Data

            break;
        case 6: // Erase Everything

            break;
        default: //Shouldn't be here

    }
} else {

}

?>
    </div>
</div>
