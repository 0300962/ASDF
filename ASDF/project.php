<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 28-Jun-18
 * Time: 3:20 PM
 */

include 'header.php';
?>
    <script> //Sets the navbar link and title to show which page you're on
        document.getElementById("guide").className += " active";
        document.title = "ASDF - Project Information";
    </script>

<div id="login_container">
    <div id="disclaimers">
    <?php
        $sql = "SELECT * FROM project;";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result);
        if (!$row) {
            echo "Error! Unable to retrieve project details from database.";
            exit;
        }
        echo "<h2>{$row['title']}</h2>";
        echo $row['details'];
        echo "<h3>Links and Reference</h3>";
        echo $row['links'];
        echo "<br/>";
        //Provides the Edit icon for Admin or account owners
        if ($_SESSION['status'] == '1') {
            echo "<a class='profile_edit' href='edit.php?project'><i class='material-icons'>settings</i></a>";
        }
        ?>
        <a href='task-board.php'>Back</a>
    </div>
</div>