<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:56
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*if (!$_SESSION['logged-in']) {
    header('Location: index.php');
    exit;
}*/

include_once "Scripts/connection.php";

/* Debug variables! */
$_SESSION['status'] = 1;
$_SESSION['name'] = 'tester';
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/header.css">
    <!-- Find a font -->
</head>

<body>
    <header>

    </header>
    <nav>
        <a href="task-board.php"><img src="Scripts/logo.png" alt="ASDF Logo"></a>

        <div class="drop-down" id="users">
            <button class="menubutton">Users</button>
            <div class="menu-options">
                <?php  //Retrieves list of User accounts from the database
                    $sql = "SELECT userID, name
                            FROM users";
                    $result = mysqli_query($db, $sql);
                    while($row = mysqli_fetch_array($result)) {
                        //Adds a button for each user to the drop-down menu
                        echo "<a href=profile.php?user={$row['userID']}>{$row['name']}</a>";
                    }
                ?>
            </div>
        </div>

        <div class="link"><a href="pbl.php">Backlog</a></div>

        <div class="drop-down" id="performance">
            <button class="menubutton">Performance</button>
            <div class="menu-options">
                <a href="pburndown.php">Project Burndown</a>
                <a href="sburndown.php">Sprint Burndown</a>
            </div>
        </div>

        <div class="drop-down" id="guide">
            <button class="menubutton">Guide</button>
            <div class="menu-options">
                <a href="project.php">Project Details</a>
                <a href="guide.php">User Guide</a>
                <a href="help.php">Help Page</a>
            </div>
        </div>

        <?php
        if ($_SESSION['status'] == '1') {  // Only shown to Admin users
            echo "<div class='link'><a href='admin.php'>Admin</a></div>";
        }

        //Checks for a Sprint in-progress
        $sql = "SELECT * FROM sprints
                WHERE backlogTotal IS NULL";
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) > 0) {  // Only shown when a Sprint is in progress
            $row = mysqli_fetch_assoc($result);
            $endDate = $row['endDate'];
            $now = date("Y/m/d");
            //Some way to figure out days left?
            $days = 0;
            echo "<div id='sprint_info'>Sprint in progress: {$days} days left!</div>";
        }

        echo "<div id='greeting'>Welcome {$_SESSION['name']}</div>";
        ?>
    </nav>




