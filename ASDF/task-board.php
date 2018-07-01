<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:56
 */
include 'header.php';

?>
<link rel="stylesheet" href="CSS/taskboard.css">
<div id="SBI_container">
    <div id="user_list">
        <?php  //Retrieves list of User accounts from the database
        $sql = "SELECT initials, colour
                FROM users";
        $result = mysqli_query($db, $sql);
        while($row = mysqli_fetch_array($result)) {
            //Adds a button for each user to the drop-down menu
            echo "<div class='user_box' style='background-color:{$row['colour']}'>{$row['initials']}</div>";
        }
        ?>
    </div>
    <div id="task_board">
        <div class="pbi" id="123">
            <div>PBI Title</div>
            <div class="sbi">
                <div class="not_started"></div>
            </div>
            <div class="sbi">
                <div class="in_progress"></div>
            </div>
            <div class="sbi">
                <div class="testing"></div>
            </div>
            <div class="sbi">
                <div class="done"></div>
            </div>
        </div>
        <div class="pbi" id="456">
            <div>PBI Title</div>
            <div class="sbi">
                <div class="not_started"></div>
            </div>
            <div class="sbi">
                <div class="in_progress"></div>
            </div>
            <div class="sbi">
                <div class="testing"></div>
            </div>
            <div class="sbi">
                <div class="done"></div>
            </div>
        </div>

    </div>
</div>
