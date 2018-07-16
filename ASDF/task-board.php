<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:56
 */
include 'header.php';

//Checks the last database revision to pre-load the messaging function
$sql = "SELECT taskboard FROM project";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);
$version = $row['taskboard'];
//Sets JavaScript variable to be used by Listener
echo "<script> var version = {$version}; </script>";

?>
<link rel="stylesheet" href="CSS/taskboard.css">
<script>
    function update(sbi) {  //AJAX function to update the task board
        var ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("task_board").innerHTML = this.responseText;
            }
        };
        ajax.open("GET", "Scripts/taskboard.php", true);
        ajax.send();
    }
</script>

<div id="SBI_container">
    <div id="user_list">
        <h4>User List</h4>
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
        <?php
            include 'Scripts/taskboard.php';
        ?>
    </div>


</div>
<script type="text/javascript">
    //Listener for messaging function
   if(!!window.EventSource) {
        //Opens the version-tracking script
        var msgSource = new EventSource('Scripts/version.php');
        msgSource.onopen = function() {
            console.log("Connected");
        };
        //Runs when a message is received from version.php
        msgSource.onmessage = function(event) {
            var newversion = JSON.parse(event.data);
            console.log("Message received");
            if (newversion.tb != version) { //Checks whether page displayed is the latest version
                version = newversion.tb;
                console.log('Update received');
                update();  //Updates the backlog if there's a change of version detected
            }
        };
    }
</script>