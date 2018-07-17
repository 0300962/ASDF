<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:59
 */
include 'header.php';

if (isset($_POST['msg'])) { //Checks for an incoming message
    $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);
    $sql = "INSERT INTO chat (msg, sender) VALUES ('{$msg}','{$_SESSION['id']}');";
    $result = mysqli_query($db, $sql);
    if (!$result) {
        echo "Error: Could not post message to chat log.<br/>";
        echo $sql;
    }
    //Sets mode for the signaller script
    $signal_mode = 'chat';
    //Updates the database revision
    include_once 'Scripts/signaller.php';
}

//Checks the last database revision to pre-load the messaging function
$sql = "SELECT chat FROM project";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);
$version = $row['chat'];
//Sets JavaScript variable to be used by Listener
echo "<script>var version = {$version}</script>";
?>

<script> //Sets the navbar link and title to show which page you're on
    document.getElementById("chatLink").className += " active";
    document.title = "ASDF - Chat";

    //Listener for message updates
    if(!!window.EventSource) {
        //Opens the version-tracking script
        var msgSource = new EventSource('Scripts/version.php');
        msgSource.onopen = function() {
            console.log("Connected");
        };

        msgSource.onmessage = function(event) {
            var newversion = JSON.parse(event.data);
            if (newversion.chat != version) { //Checks whether page displayed is the latest version
                console.log('Update received');
                location = location; //Refreshes the page
            }
        };
    }

</script>
<div id="login_container">
    <div id="disclaimers">
        <h3>Add a new message</h3>
        <form method='post' action='chat.php'>
            Add a short message for your colleagues (max. 180 characters)<br/>
            <textarea name='msg' rows='3' cols='85' maxlength='180' required></textarea><br/>
            <input type='submit' value='Post'>
        </form>
<?php
    echo "<div id='chatlog'>";
    //Checks which page of messages to display, 25 at a time
    if(isset($_GET['start'])) { //Next or previous button has been pressed
        $start = filter_var($_GET['start'], FILTER_SANITIZE_NUMBER_INT);
        $end = $start + 25;
        $range = ''.$start.','.$end;
    } else { //Front page - default values
        $range = '25';
        $end = 25;
    }

    //Gets the next 25 most recent chat messages, along with the User initials and colour
    $sql = "SELECT sent, msg, initials, colour 
            FROM chat, users 
            WHERE sender = userID  
            ORDER BY sent DESC
            LIMIT {$range};";
    $result = mysqli_query($db, $sql);
    //Prints each message to a div of the user's background colour
    while ($row = mysqli_fetch_array($result)) {
        echo "<div class='msg' style='background-color: {$row['colour']}'>{$row['sent']} ({$row['initials']}) : {$row['msg']}</div>";
    }
    echo "End of messages!<br/>";
    echo "<a href='chat.php?start={$end}'>Next page</a> ";

    if ($end != 25) {  //Checks whether user is on front-page or not
        $start -= 25;
        if ($start == 0) {
            echo "<a href='chat.php'>First page</a>";
        } else {
            echo "<a href='chat.php?start={$start}'>Previous page</a>";
        }
    }
?>
        </div>
    </div>
</div>
