<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:58
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Creates the connection.php file that the rest of the system shall use.
function databaseConnection($name, $pw, $dbhost, $status)
{
    //Takes the values from the Setup page form
    $username = $name;
    $password = $pw;
    $host = $dbhost;
    //Creates/overwrites any existing file
    $dbinfo = fopen("connection.php", "w") or die("Unable to create connection file!");

    $date = date("Y/m/d h:i:sa");
    $text = "<?php  //Created by ASDF at {$date} \n";
    fwrite($dbinfo, $text);
    $text = "DEFINE ('DB_USER', '{$username}'); \n";
    fwrite($dbinfo, $text);
    $text = "DEFINE ('DB_PSWD', '{$password}'); \n";
    fwrite($dbinfo, $text);
    $text = "DEFINE ('DB_HOST', '{$host}'); \n";
    fwrite($dbinfo, $text);
    //Checks whether to include the database name in the connection file - for Setup mode
    if ($status) {
        $text = "DEFINE ('DB_NAME', 'asdf'); \n";
        fwrite($dbinfo, $text);
        $text = "\$db = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME); \n";
        fwrite($dbinfo, $text);
        $text = "mysqli_select_db(\$db, DB_NAME); \n";
        fwrite($dbinfo, $text);
    } else {
        $text = "\$db = mysqli_connect(DB_HOST, DB_USER, DB_PSWD); \n";
        fwrite($dbinfo, $text);
    }

    $text = "if (!\$db) {die('Cannot connect to database');} \n";
    fwrite($dbinfo, $text);

    $text = "?>";
    fwrite($dbinfo, $text);
    fclose($dbinfo);
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/header.css">

</head>
<body>
<nav>
    <img src="logo.png" alt="ASDF Logo">
    <div id="sprint_info">
        The Agile Software Development Framework
    </div>
</nav>

<div id="container">
    <div id="banner">

<?php
switch ($_GET['stage']) {
    case 1: //Introduction
        echo "Welcome to the ASDF guided setup mode!  In a few steps we'll configure your 
            ASDF system and let you let you set up your team and the project they'll be working on.<br/>
            The first step is to connect to your MySQLi database; if you don't have a MySQLi database server 
            yet, then you need to set that up first- go do that and then come back here.  We recommend 
            something like WAMP or MAMP, but anything using <b>MySQLi</b> should be fine.<br/>
            Ensure that you have configured a user account within the database management software, this is the
            account that ASDF will use to access and store your data.";
        echo "<a href='setup.php?stage=2'>Let's get started...</a>";
        break;
    case 2: //Database Connection
        echo "Enter the details for your MySQLi server below:";
        ?>
        <form method="post" action="setup.php?stage=3">
            <input name="host" type="text" placeholder="Database Host" required autofocus>
            <input name="user" type="text" placeholder="Username for Database" required>
            <input name="pw" type="text" placeholder="Password for Database">
            <input type="submit" value="Check...">
        </form>
        Note: If the ASDF server and the database server are running on the same computer, then you probably
        want to use <i>localhost</i> for the Host.  If you don't have a password on your database (you really
        should!) then just enter a space for the password field.
        <?php
        break;
    case 3: //Checks and confirms database connection
        $host = $_POST['host'];
        $user = $_POST['user'];
        $pw =  $_POST['pw'];
        //Tries to connect using the details
        $database = mysqli_connect($host, $user, $pw);
        if ($database) { //Database connection worked
            //Writes connection details to 'connection.php' file
            databaseConnection($user, $pw, $host, FALSE);
            //Creates the database and its tables
            include_once 'dbcreation.php';
            //Updates the connection details to specify the ASDF database
            databaseConnection($user, $pw, $host, TRUE);
            echo "Database connection successful - saved to 'connection.php'";

            echo "<a href='setup.php?stage=4'>Next step...</a>";
        } else { //Database connection did not work
            echo "Database connection unsuccessful; please check and try again.";
            echo "<a href='setup.php?stage=2'>Try again...</a>";
        }
        break;
    case 4: //Creating Admin account
        echo "Now that you can connect to the database, we can start storing data.  First, enter a login name
        and password for yourself.  You will be a system Administrator!  The next time you log-in, you will
        add your name, initials and so on.";
        ?>
        <form method="post" action="setup.php?stage=5">
            <input name="login" type="text" placeholder="Username" required autofocus>
            <input name="pw" type="password" placeholder="Password" required>
            <input type="submit" value="Save">
        </form>
        <?php
        break;
    case 5: //Sanitizes inputs and hashes the password to keep it secure
        $name = filter_var($_POST['login'], FILTER_SANITIZE_STRING);
        $pw = filter_var($_POST['pw'], FILTER_SANITIZE_STRING);
        $hashedpw = password_hash($pw, PASSWORD_DEFAULT);
        //Adds admin user to the database
        include_once 'connection.php';
        $sql = "INSERT INTO logins (login, pwhash, status)
                VALUES ('{$name}', '{$hashedpw}', '1');";
        $result = mysqli_query($db, $sql);

        if (!$result) { //Checks that details were saved properly
            echo "There was a problem adding your login details to the database.  Please try again.";
            echo "<a href='setup.php?stage=4'>Try again...</a>";
        } else {
            echo "Details saved; you will use them whenever you login to ASDF. <br/>
                 How many people are on your development team (not including you)?";
            ?>
            <form method="post" action="setup.php?stage=6">
                <input name="team" type="number" required min="0" max="50" autofocus>
                <input type="submit" value="Save">
            </form>
        <?php
        }
        break;
    case 6:
        $team = filter_var($_POST['team'], FILTER_SANITIZE_NUMBER_INT);
        if ($team == 0) { //Skips this stage
            echo "<script type='text/javascript'> location = '../Scripts/setup.php?stage=7'</script>";
            break;
        }
        echo "Please enter login names for each team member below.  They will be prompted to enter
         a password and personal details when they first log-in.  If any other user is to be an Administrator,
          tick the box for that account.";
        echo "<form method='post' action='setup.php?stage=7'>";
        for ($i = 0; $i < $team; $i++) {
            echo "<input name='name{$i}' type='text' required placeholder='Login'>";
            echo "Make Admin y/n <input name='admin{$i}' type='checkbox' value='TRUE'>";
        }
        echo "<input type='hidden' name='team' value={$team}>";
        echo "<input type='submit' value='Save'></form>";
        echo "Note: Administrators will be able to change development priorities, reset user passwords, 
        and delete all system data if they want to.";
        break;
    case 7:
        if(isset($_POST['team'])) { //Checks if more team members were added
            include_once 'connection.php';
            for ($i = 0; $i < $_POST['team']; $i++) { //Adds each one as a login account
                $name = $_POST['name'.$i];
                if(isset($_POST['admin'.$i])) {
                    $admin = 1;
                } else {
                    $admin = 0;
                }
                $login = filter_var($name, FILTER_SANITIZE_STRING);

                $sql = "INSERT INTO logins (login, status)
                VALUES ('{$name}', '{$admin}');";
                $result = mysqli_query($db, $sql);
                if (!$result) {  //Could not add user account
                    echo "Error adding user: {$name} <br/> Possible duplicate username?";
                    echo $sql;
                    print_r(mysqli_errno($db));
                }
            }
        }
        echo "We're nearly done with the setup.  All that's left now is to enter some details about your project.
            This will be used as a reference for the team, to help keep them on-track.";
        echo "<a href='setup.php?stage=8'>Last step...</a>";
        break;
    case 8:
        //Gets project details to share with the team
        ?>
        Please provide some details about your project, to be shared amongst the team.  As an example you may wish to
        provide a mission statement, target users, links to design documents, and so on.
        <form method="post" action="setup.php?stage=9">
            <input type="text" name="projectTitle" placeholder="Project Title" required maxlength="30">
            <input type="text" name="details" placeholder="About the project" maxlength="500">
            <input type="url" name="links" placeholder="Web links" maxlength="300">
            <input type="submit" value="Save">
        </form>
        <?php
        break;
    case 9:
        //Checks user inputs
        $title = filter_var($_POST['projectTitle'], FILTER_SANITIZE_STRING);
        if (isset($_POST['details'])) {
            $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);
        } else {
            $details = '';
        }
        if (isset($_POST['links'])) {
            $url = filter_var($_POST['links'], FILTER_SANITIZE_URL);
        } else {
            $url = '';
        }
        include_once 'connection.php';
        $sql = "INSERT INTO project (title, details, links)
        VALUES ('{$title}', '{$details}', '{$url}');";
        $result = mysqli_query($db, $sql);
        if (!$result) {  //Could not add Project details
            echo "Error adding project details.<br/>";
            echo $sql;
            print_r(mysqli_errno($db));
        }

        echo "Setup mode is 99.5% complete!  You can now ask your users to login with the usernames
        you configured earlier.  Whenever a user (including you) logs in for the first time, ASDF 
        will ask them to complete their profile information- this includes setting a password for
        their accounts for your users.";
        echo "<a href='../index.php'>Let's get started...</a>";
        break;
    default:
        echo "<script type='text/javascript'> location = '../index.php'</script>";
}

?>




