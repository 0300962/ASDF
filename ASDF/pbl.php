<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:57
 */
include 'header.php';

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

