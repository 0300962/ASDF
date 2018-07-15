<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:57
 */
include 'header.php';
?>
<script> //Sets the navbar link to show which page you're on
    document.getElementById("users").className += " active";
</script>

<div id="login_container">
    <div id="disclaimers">
    <?php
        if (isset($_GET['user'])) {
            $user = filter_var($_GET['user'], FILTER_SANITIZE_NUMBER_INT);

            $sql = "SELECT * FROM users WHERE userID = '{$user}';";
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_array($result);
            if (!$row) {
                echo "Error! Unable to retrieve user details from database.";
                exit;
            }
            echo "<h2>{$row['name']}</h2>";
            echo "<h4>{$row['initials']}<div id='colour_box' style='background-color: {$row['colour']}'></div></h4>";
            echo "<h3>User Details</h3>{$row['details']}";

            //Provides the Edit icon for Admin or account owners
            if (($_SESSION['status'] == '1') OR ($_SESSION['id'] == $user)) {
                echo "<br/><a class='profile_edit' href='edit.php?user={$user}'><i class='material-icons'>settings</i></a>";
            }
        } else {
            echo "Error: Invalid Link.";
            echo "<a href='task-board.php'>Back</a>";
        }
        ?>
    </div>
</div>