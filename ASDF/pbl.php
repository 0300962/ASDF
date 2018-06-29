<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:57
 */
include 'header.php';

//Checks for a new PBI, from an admin user
if (isset($_POST['story']) AND ($_SESSION['status'] == 1)) {
    $story = filter_var($_POST['story'], FILTER_SANITIZE_STRING);
    $criteria = filter_var($_POST['criteria'], FILTER_SANITIZE_STRING);
    if (isset($_POST['priority'])) {
        $p = filter_var($_POST['priority'], FILTER_SANITIZE_NUMBER_INT);
    } else {
        $p = 100;
    }
    //Adds the new PBI to the database
    $sql = "INSERT INTO pbis (userStory, acceptance, priority)
    VALUES ('{$story}', '{$criteria}', '{$p}' );";
    $result = mysqli_query($db, $sql);
    if(!$result) {
        echo "Error - could not add PBI to database.";
        print_r(mysqli_errno($db));
    }
}

?>
<link rel="stylesheet" href="CSS/tables.css">
<div class="table_cont">
    <table id="backlog">
        <tr>
            <th>User Story</th><th>Acceptance Criteria</th><th>Priority</th>
        </tr>
        <?php
        $sql = "SELECT * FROM pbis
                ORDER BY priority ASC";
        $result = mysqli_query($db, $sql);

        while($row = mysqli_fetch_array($result)) {
            echo "<tr><td>{$row['userStory']}</td><td>{$row['acceptance']}</td><td>Current Priority:{$row['priority']}\n\n
                    <a href='Scripts/priority.php?up={$row['pbiNo']}'>Move Up</a><a href='Scripts/priority.php?down={$row['pbiNo']}'>Move Down</a></td>";
        }
        ?>
    </table>
</div>
<?php
    if ($_SESSION['status'] == 1) {
        ?>
        <div>
            <form method="post" action="pbl.php">
                <fieldset>
                    <legend>Add a new PBI</legend>
                    <label for="story">User Story</label><br/>
                    <textarea name="story" placeholder="Enter User Story" required maxlength="300" rows="4"
                              cols="115"></textarea><br/>
                    <label for="criteria">Acceptance Criteria</label><br/>
                    <textarea name="criteria" placeholder="Enter acceptance criteria" required maxlength="300" rows="4"
                              cols="115"></textarea><br/>
                    <label for="priority">(Optional) Select what position to insert into the PBL</label>
                    <input type="number" name="priority" min="1" max="200">
                    <input type="submit" value="Add to Product Backlog">
                    Note:
                </fieldset>
            </form>
        </div>
        <?php
    }
?>


