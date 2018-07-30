<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:54
 */

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/header.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="icon" type="image/png" href="CSS/asdf.png">
    <title>ASDF - Agile Software Development Framework</title>
</head>
<body>
<nav>
    <img src="Scripts/logo.png" alt="ASDF Logo">
    <div id="sprint_info">
        The Agile Software Development Framework
    </div>
</nav>

<div id="login_container">
    <?php
    //Checks whether the database has been configured yet; if so, presents the Login screen
    if (file_exists('Scripts/connection.php')) {
        if (isset($_GET['error'])) {
                echo "<div id='login_banner'>Incorrect User Name or Password!<br/></div>";
        }
    ?>
    <div id="login_panel">
        Please sign-in to ASDF:<br/>
        <form method="post" action="Scripts/login.php">
            <input type="text" name="user" placeholder="Username" required autofocus>
            <input type="password" name="pw" placeholder="Password">
            <input type="submit" name="login" value="Login">
        </form><br/>NOTE: This system uses cookies to control access and permissions.
    </div>
    <script>
        //Checks for HTML Server-Sent Events Support
        if(typeof(EventSource) === "undefined") {
           document.getElementById("login_panel").innerHTML += "<br/>Your browser does not support SSE; you can still use the" +
                " system but you will not receive live updates from your team.";
        }
        //Checks if Cookies are enabled
        if(!navigator.cookieEnabled) {
            document.getElementById("login_panel").innerHTML += "<br/>Error: Your browser does not have cookies enabled.";
        }
    </script>
    <div id="disclaimers">
        ASDF - the Agile Software Development Framework is a modern web application designed to help small development teams
        adopt an Agile methodology for their next project.  It provides the tools and guidance to help your team work in a
        Scrum-like process, even if you're spread around the world.  ASDF is self-hosted, so your data stays with you.<br/>
        <br/>
        More information, installation instructions and the source code for ASDF is available from <a href="https://github.com/0300962/ASDF">GitHub</a><br/>
        ASDF is free for all users and is offered under the <a href="https://github.com/0300962/ASDF/blob/master/LICENSE">GNU General Public License V3.0</a> without
        warranty or liability.  Some ASDF functions utilise the <a href="https://developers.google.com/chart/">Google Charts</a> API.<br/>
        System Font is Roboto Sans-Serif, courtesy of <a href="https://fonts.google.com/">Google Fonts</a>
        Icons provided by <a href="https://material.io/tools/icons/?style=outline">Google Icons</a><br/>
    </div>
</div>
</body>
</html>

<?php
} else { //Enters setup mode
    echo "<script type='text/javascript'> location = 'Scripts/setup.php?stage=1'</script>";
}
?>


