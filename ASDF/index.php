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
</head>
<body>
<nav>
    <img src="Scripts/logo.png" alt="ASDF Logo">
    <div id="sprint_info">
        The Agile Software Development Framework
    </div>
</nav>

<div id="container">
    <div id="banner">
    <?php
    //Checks whether the database has been configured yet; if so, presents the Login screen
    if (file_exists('Scripts/connection.php')) {
        if (isset($_GET['error'])) {
                echo "Incorrect User Name or Password!<br/>";
            }
    ?>
        Please sign-in to ASDF:
    </div>
    <div id="login_panel">
        <form method="post" action="Scripts/login.php">
            <input type="text" name="user" placeholder="Username" required autofocus>
            <input type="password" name="pw" placeholder="Password">
            <input type="submit" name="login" value="Login">
        </form><br/>
    </div>
    <div id="disclaimers">
        This system uses cookies to control access and permissions.<br/><br/>
        This system uses elements from xxx.inc
    </div>
</div>
</body>
</html>

<?php
} else { //Enters setup mode
    echo "<script type='text/javascript'> location = 'Scripts/setup.php?stage=1'</script>";
}
?>


