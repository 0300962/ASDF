<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:59
 */
include 'header.php';
if ($_SESSION['status'] != '1') { //Checks for Admin user
    header('Location: index.php');
    exit;
}
?>
<script> //Sets the navbar link and title to show which page you're on
    document.getElementById("adminLink").className += " active";
    document.title = "ASDF - Administration";
</script>
<div id='login_container'><div id='disclaimers'><h3>Administration Page</h3>
        This is the System Administration page; only Admin-level users can access these functions.<br/>
        <ul>
            <li>Sprint Management allows you to start/stop a Sprint or change the end-date of a Sprint that's in-progress.</li>
            <li>Project Management is where you can change the Project Details page.</li>
            <li>User Administration lets you add or remove Users, grant Admin privileges, or reset users passwords.</li>
            <li>Clear Chat Log will erase the chat history, if you need to.</li>
        </ul>
        <a href='sprint.php'>Sprint Management</a>
        <a href='edit.php?project'>Project Management</a>
        <a href='administration.php?func=1'>User Administration</a>
        <a href='administration.php?func=4'>Clear Chat Log</a><br/>
    </div><br/>
    <div id="disclaimers">
        <h3>System Functions</h3>
        Here you can go back to Setup Mode if you want to change the Database details, export the project
        data to disk, or delete all project and user data from the system.<br/>
        <a href='Scripts/setup.php?stage=1'>Setup Mode</a>
        <a href='administration.php?func=5'>Backup Project Data</a>
        <a href='administration.php?func=6'>Erase Everything</a><br/>
    </div>
</div>