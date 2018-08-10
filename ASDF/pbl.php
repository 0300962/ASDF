<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:57
 */
include 'header.php';

//Checks the last database revision to pre-load the messaging function
$sql = "SELECT pbl FROM project";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);
$version = $row['pbl'];
//Sets JavaScript variable to be used by Listener
echo "<script> var version = {$version} </script>";
?>
<script> //Sets the navbar link and title to show which page you're on
    document.getElementById("pblLink").className += " active";
    document.title = "ASDF - Project Backlog";
</script>
<?php

//Checks for a new PBI, from an admin user
if (isset($_POST['story']) AND ($_SESSION['status'] == 1)) {
    $story = filter_var($_POST['story'], FILTER_SANITIZE_STRING);
    $criteria = filter_var($_POST['criteria'], FILTER_SANITIZE_STRING);

    //Checks for a selected priority
    if ($_POST['priority'] > 0) {
        $p = filter_var($_POST['priority'], FILTER_SANITIZE_NUMBER_INT);
        $sql = "INSERT INTO pbis (userStory, acceptance, priority)
    VALUES ('{$story}', '{$criteria}', '{$p}' );";
    } else {
        $sql = "INSERT INTO pbis (userStory, acceptance)
    VALUES ('{$story}', '{$criteria}');";
    }
    //Adds the new PBI to the database
    $result = mysqli_query($db, $sql);
    if(!$result) {
        echo "Error - could not add PBI to database.";
        print_r(mysqli_errno($db));
        echo $sql;
    }
    //Sets mode for the signaller script
    $signal_mode = 'pbl';
    //Updates database revision
    include 'Scripts/signaller.php';
}
?>
<script> //AJAX function to refresh the Backlog table, optionally showing completed PBIs
    function update(hidecompleted) {
        var ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("backlog").innerHTML = this.responseText;
            }
        };
        //Checks whether to show or hide completed tasks
        if (hidecompleted) { //Default is to hide completed PBIs
            ajax.open("GET", "Scripts/backlogtable.php", true);
        } else {
            ajax.open("GET", "Scripts/backlogtable.php?false", true);
        }
        ajax.send();
    }
</script>

<link rel="stylesheet" href="CSS/tables.css">
<div class="table_cont">
    <table id="backlog">
        <?php include 'Scripts/backlogtable.php';?>
    </table>
</div>
<?php
    if (($_SESSION['status'] == 1) && (!$liveSprint)) {
        ?>
        <div id="add_pbi">Add a new PBI - Note: new PBI's will often have duplicated priorities, this will resolve when the priorities are next adjusted.<br/>
            <form method="post" action="pbl.php">
                <br/><label for="story">User Story</label><br/>
                <textarea name="story" placeholder="Enter User Story" required maxlength="300" rows="4"
                          cols="60"></textarea><br/><br/>
                <label for="criteria">Acceptance Criteria</label><br/>
                <textarea name="criteria" placeholder="Enter acceptance criteria" required maxlength="300" rows="4"
                          cols="60"></textarea><br/>
                <label for="priority">(Optional) Select what position to insert into the PBL</label>
                <input type="number" name="priority" min="1" max="200">
                <input type="submit" value="Add to Backlog">
            </form>
        </div>
        <?php
    } else {
        ?>
        <div id="disclaimers">
            Only Admin users can add or reorder PBIs, and only when there's not a Sprint in progress.
        </div>
    <?php
    }
?>
<script type="text/javascript">
    if(!!window.EventSource) {
        //Opens the version-tracking script
        var msgSource = new EventSource('Scripts/version.php');
        msgSource.onopen = function() {
            console.log("Connected");
        };

        msgSource.onmessage = function(event) {
            var newversion = JSON.parse(event.data);
            if (newversion.pbl != version) { //Checks whether page displayed is the latest version
                version = newversion.pbl;
                console.log('Update received');
                update(true);  //Updates the backlog if there's a change of version detected
            }
        };
    }
</script>