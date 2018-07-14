<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 14-Jul-18
 * Time: 9:16 AM
 */
include 'header.php';

if ($_SESSION['status'] != '1') { //Checks for Admin user
    header('Location: index.php');
    exit;
}

function status($db) {
    $sql = "SELECT * FROM sprints WHERE backlogTotal IS NULL";
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0) { //Sprint is in-progress
        $row = mysqli_fetch_assoc($result);
        return $row['endDate'];
    } else {
        return 'No Sprint in Progress';
    }
}
?>

<script> //Sets the navbar link to show which page you're on
    document.getElementById("adminLink").className += " active";
</script>

<div id='login_container'><span id='disclaimers'><h3>Sprint Management</h3>

<?php
if (isset($_GET['func'])) {
    $stage = filter_var($_GET['func'], FILTER_SANITIZE_NUMBER_INT);
    switch ($stage) {
        case 1: //Start a new Sprint
            echo "<link rel='stylesheet' href='CSS/tables.css'>";
            echo "Select PBIs to tackle in this Sprint. <b>Start at the top of the list.</b>  It's a good idea to take 
                on one or two more than you expect to complete, just in case things go better than expected.<br/>
                <input form='pbi' type='submit' name='pickPBIs' value='Next'>";
            echo "<form id='pbi' method='post' action='sprint.php?func=2'>
                    <table><tr><th>PBI User Story (ordered by priority)</th><th>Select?</th></tr>";
            //Gets list of open PBIs
            $sql = "SELECT pbiNo, userStory FROM pbis 
                    WHERE completed IS NULL
                    ORDER BY priority ASC;";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>{$row['userStory']}</td><td><input type='checkbox' name='pbi{$row['pbiNo']}'></td></tr>";
            }
            echo "</table></form>";
            echo "<a href='sprint.php'>Back</a>";
            break;
        case 2: //Add SBIs to PBIs
            ?>
            <link rel='stylesheet' href='CSS/taskboard.css'>
            Now you need to add sub-tasks for each PBI, in discussion with the rest of the team.  These are the Sprint
            Backlog Items (or SBIs), and should be small, independent tasks, not normally taking more than one day to
            complete.  Give an indication of the effort required to complete the task- consider using something like
            1/2/4/8 rather than absolute hours. Keep an eye on the total amount of effort you've estimated for this
            Sprint.  Your current total is <span id="totalEffort">0</span> units of effort. <a href='task-board.php'>Go to Task Board</a>
            </div>
            <script>
                function checkTotal() { //Counts the total Effort assigned so far and updates information at top of page
                    var numbers = document.getElementsByClassName("effortTotal");
                    var total = 0;
                    for (var i = 0; i < numbers.length; i++) {
                        total += Number(numbers[i].innerHTML);
                    }
                    document.getElementById("totalEffort").innerHTML = total;
                }

                function update(pbi) {  //AJAX function to update the SBI lists
                    var ajax = new XMLHttpRequest();
                    //Get field names and form values
                    var parent = pbi;
                    var divName = "pbi"+parent;
                    var taskName = "task"+parent;
                    var task = document.getElementById(taskName).value;
                    var effortName = "effort"+parent;
                    var effort = document.getElementById(effortName).value;
                    //Update only the PBI that's receiving the new sub-task
                    ajax.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById(divName).innerHTML = this.responseText;
                        }
                    };
                    ajax.open("POST", "Scripts/addSBI.php", true);
                    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    var request = "pbi="+parent+"&task="+task+"&effort="+effort;
                    ajax.send(request);
                    //Clears the form once the table is updated
                    document.getElementById("form"+parent).reset();
                    //Updates the effort total after a short delay
                    setTimeout(checkTotal(), 1000);
                }
            </script>
            <?php
            $pbis = array();
            foreach ($_POST as $key => $value) { //Compiles selected PBIs into an array from POST
                $pbis[] = substr($key, 3);
            }
            //Trims the header off the POST array
            array_shift($pbis);

            for ($i = 0; $i < count($pbis); $i++) {
                //Gets the selected PBIs
                $sql = "SELECT userStory FROM pbis WHERE pbiNo = '{$pbis[$i]}';";
                $result = mysqli_query($db, $sql);
                while ($row = mysqli_fetch_assoc($result)) { //Puts each PBI into a section
                    echo "<div class='pbi'>";
                    echo "<div class='drop-down'><button class='menubutton'>PBI</button>
                            <div class='menu-options'>{$row['userStory']}</div></div> ";

                    //Gives each PBI a pop-up form to add a new SBI
                    echo "<div class='drop-down'><button class='menubutton'>Add a new SBI</button>
                            <div class='menu-options'>";
                    echo "<form id='form{$pbis[$i]}'>
                            Sub-task Description:<br/>
                            <textarea id='task{$pbis[$i]}' maxlength='300' rows='3' cols='30' placeholder='Enter sub-task details' required></textarea>
                            Effort: <input type='number' id='effort{$pbis[$i]}' min='1' max='32' required> 
                            <button onclick='update({$pbis[$i]})'>Save</button>
                    </form>";
                    echo "</div></div>";

                    //Gets the existing SBIs for each PBI
                    $sql = "SELECT sbiNo, task, effort FROM sbis WHERE pbiNo = '{$pbis[$i]}';";
                    $result2 = mysqli_query($db, $sql);
                    echo "<div id='pbi{$pbis[$i]}'>";
                    while ($row2 = mysqli_fetch_assoc($result2)) {
                        echo "<div class='sbi'>SBI Number: {$row2['sbiNo']} Effort: <span class='effortTotal'>{$row2['effort']}</span><br/>{$row2['task']}</div>";
                        echo "<div class='spacer'></div>";
                    }
                    echo "</div></div>";
                }
            }
            break;
        case 3: //Confirm to stop a Sprint
            echo "Are you sure you want to stop the current Sprint? <b>This cannot be undone.</b>  The completed PBIs will be
            archived along with the Sprint statistics, but the existing SBIs will be deleted.  The Project Burndown graph
            will be updated.<br/>";
            echo "<a href='sprint.php?func4'>Confirm</a><br/>";
            echo "<a href='admin.php'>Back</a>";
            break;
        case 4: //Stop a Sprint in progress
            //Gets all PBIs in the Sprint into an array
            $sql = "SELECT DISTINCT pbiNo FROM sbis;";
            $result = mysqli_query($db, $sql);
            $pbis = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $pbis[] = $row['pbiNo'];
            }
            //Gets all un-finished PBIs into an array
            $sql = "SELECT DISTINCT pbiNo FROM sbis WHERE done IS NULL;";
            $result = mysqli_query($db, $sql);
            $openPbis = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $openPbis[] = $row['pbiNo'];
            }
            //Subtracts the open PBIs from the list of Sprint PBIs, renumber the array from Zero-up
            $completedPbis = array_merge(array_diff($pbis, $openPbis));
            $today = date('Y-m-d');
            //Loops through list of completed PBIs to update today's date as their Completed date
            for ($i = 0; $i < count($completedPbis); $i++) {
                $sql = "UPDATE pbis SET completed = '{$today}'
                        WHERE pbiNo = '{$completedPbis[$i]}';";
                $result = mysqli_query($db, $sql);
                if (!$result) {
                    echo "Error: could not close PBI <br/>";
                    echo $sql;
                } else {
                    echo "PBI No {$completedPbis[$i]} closed OK<br/>";
                }
            }
            //Counts remaining PBIs on the backlog, after this Sprint
            $sql = "SELECT COUNT(pbiNo) FROM pbis WHERE completed IS NOT NULL;";
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_array($result);
            $remaining = $row['COUNT(pbiNo)'];
            //Sets the number of outstanding PBIs when the Sprint closed
            $sql = "UPDATE sprints SET backlogTotal = '{$remaining}' WHERE backlogTotal IS NULL;";
            $result = mysqli_query($db, $sql);
            if (!$result) {
                echo "Error: Could not close Sprint.";
                echo $sql;
            } else {
                echo "Sprint performance added to Project history.<br/>";
            }
            //Deletes the Sprint details
            $sql = "DELETE FROM sbis WHERE *;";
            $result = mysqli_query($db, $sql);
            if (!$result) {
                echo "Error: Could not delete SBI details.";
            } else {
                echo "Sprint SBIs deleted successfully.<br/>";
            }
            echo "Your team should now have a quick discussion about how the Sprint went (a Sprint Retrospective Meeting) 
            before going through the Product Backlog to reconsider development priorities, new PBIs that may have arisen etc.<br/>";
            echo "<a href='pbl.php'>Product Backlog</a>";
            break;
        case 5: //Change end-date
            if (isset($_POST['date'])) { //Checks for incoming new date
                $newdate = filter_var($_POST['date'], FILTER_SANITIZE_EMAIL);
                //Updates the end date of the current Sprint
                $sql = "UPDATE sprints SET endDate = '{$newdate}'
                WHERE backlogTotal IS NULL;";
                $result = mysqli_query($db, $sql);
                if (!$result) {
                    echo "Error: Unable to update Sprint End-Date.<br/>";
                } else {
                    echo "Date updated; navigation banner will change on next refresh.<br/>";
                }
            }
            //Date-picker form to change end date of current Sprint
            $endDate = status($db);
            echo "Current End-Date: {$endDate}<br/><br/>";
            echo "<form method='post' action='sprint.php?func=4'>
                Select New End-Date:<br/>
                    <input type='date' name='date'>
                     <input type='submit' name='newdate'>
                 </form>";
            echo "<a href='sprint.php'>Back</a>";
            break;
        default:
            echo "Sorry, something went wrong.<br/><a href='sprint.php'>Back</a>";
            break;
    }
} else {
    echo "Welcome to the Sprint Management Page.  Here you can start or stop a Sprint, including 
    setting the sub-tasks the team will tackle to meet the objectives.<br/>";
    if (status($db) == 'No Sprint in Progress') {
        echo "<a href='sprint.php?func=1'>Start New Sprint</a>";
    } else {
        echo "<a href='sprint.php?func=3'>Stop Current Sprint</a><br/>";
        echo "<a href='sprint.php?func=5'>Change end-date</a><br/>";
    }
    echo "<a href='admin.php'>Back</a>";
}
?>
    </div>
</div>