<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 30-Jun-18
 * Time: 10:19 AM
 */
?>

<?php
    include_once 'connection.php';
    if (isset($_GET['false'])) {  //Checks whether to hide completed PBIs
        $sql = "SELECT * FROM pbis
            ORDER BY completed, priority ASC";
        $button = true;
        $buttonlabel = "Hide completed";
    } else { //Default option - hides completed PBIs
        $sql = "SELECT * FROM pbis
            WHERE completed IS NULL
            ORDER BY priority ASC";
        $button = false;
        $buttonlabel = "Show completed";
    }
    $result = mysqli_query($db, $sql);

    //Table heading with show/hide button
    echo "<tr>
                <th>User Story</th><th>Acceptance Criteria</th><th>Priority <button type='button' onclick='update({$button})'>{$buttonlabel}</button></th>
          </tr>";

    while($row = mysqli_fetch_array($result)) {
        //Checks if the PBI is completed or not
        if ($row{'completed'} != NULL) {
            $done = "class='completed'";
        } else {
            $done = '';
        }
        //Populates the table row for this PBI
        echo "<tr {$done}><td>{$row['userStory']}</td><td>{$row['acceptance']}</td><td class='controls'>Current Priority:{$row['priority']}<br/>
                <a href='Scripts/priority.php?up={$row['pbiNo']}'>Move Up</a><a href='Scripts/priority.php?down={$row['pbiNo']}'>Move Down</a></td>";
    }
?>