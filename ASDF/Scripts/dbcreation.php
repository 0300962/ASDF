<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22-Jun-18
 * Time: 10:30 PM
 */


include_once 'connection.php';

if (isset($userDB)) { //Checks for a custom database name
    //Creates the database
    $sql = "CREATE DATABASE IF NOT EXISTS {$userDB};";
    $result = mysqli_query($db, $sql);
    if (!$result) {  //Could not create Database
        echo "Create DB Failed<br/>";
        echo $sql;
        print_r(mysqli_errno($db));
    }
    //Selects the database
    $sql =  "USE {$userDB};";
    $result = mysqli_query($db, $sql);
    if (!$result) {  //Could not create Database
        echo "Could not set DB to use<br/>";
        echo $sql;
        print_r(mysqli_errno($db));
    }
} else {
    //Creates the database
    $sql = "CREATE DATABASE IF NOT EXISTS asdf;";
    $result = mysqli_query($db, $sql);
    //Selects the database
    $sql =  "USE asdf;";
    $result = mysqli_query($db, $sql);
}

$sql = "CREATE TABLE logins (
            login varchar(128) NOT NULL,
            userID int NOT NULL AUTO_INCREMENT UNIQUE,            
            pwhash varchar(256) NULL,
            status int(1) NOT NULL,
            
            CONSTRAINT PK_Logins PRIMARY KEY (login)
            );";

$result = mysqli_query($db, $sql);
if (!$result) {  //Could not create Database or Logins Table
    echo "Step 1 Failed";
    print_r(mysqli_errno($db));
}
//Creates the Users table
$sql = "CREATE TABLE users (
            userID int NOT NULL,
            name varchar(50) NULL,
            initials varchar(4) NULL,
            details varchar (300) NULL,
            colour varchar (7) NULL,
            lastSeen timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            CONSTRAINT PK_Users PRIMARY KEY (userID),
            CONSTRAINT FK_Users_Logins FOREIGN KEY (userID) REFERENCES logins(userID)
            );           
        ";

$result = mysqli_query($db, $sql);
if (!$result) {  //Could not create Users table
    echo "Step 2 Failed";
    print_r(mysqli_errno($db));
}

$sql = "CREATE TABLE pbis (
            pbiNo int NOT NULL AUTO_INCREMENT,
            userStory varchar(300) NOT NULL,
            acceptance varchar(300) NOT NULL,
            priority int(3) DEFAULT 50,
            completed date NULL,
            
            CONSTRAINT PK_PBIs PRIMARY KEY (pbiNo)
        );";
$result = mysqli_multi_query($db, $sql);
if (!$result) {  //Could not create Project Backlog Items table
    echo "Step 3 Failed";
    print_r(mysqli_errno($db));
}

$sql = "CREATE TABLE sbis (
            sbiNo int NOT NULL AUTO_INCREMENT,
            pbiNo int NOT NULL,
            task varchar(300) NOT NULL,
            inProgress varchar(16) NULL,
            testing varchar(16) NULL,
            done varchar(16) NULL,
            effort int(2) NULL,
            
            CONSTRAINT PK_SBIs PRIMARY KEY (sbiNo),
            CONSTRAINT FK_SBIs_PBIs FOREIGN KEY (pbiNo) REFERENCES pbis(pbiNo) ON DELETE CASCADE
        );";
$result = mysqli_multi_query($db, $sql);
if (!$result) {  //Could not create Sprint Backlog Items Table
    echo "Step 4 Failed";
    print_r(mysqli_errno($db));
}

$sql = "CREATE TABLE sprints (
            sprintNo int NOT NULL AUTO_INCREMENT,
            startDate date NOT NULL,
            endDate date NULL,
            startingBacklog int(4) NULL,
            backlogTotal int(4) NULL,
            
            CONSTRAINT PK_Sprints PRIMARY KEY (sprintNo)
        );";
$result = mysqli_query($db, $sql);
if (!$result) {  //Could not create Sprint History table
    echo "Step 5 Failed";
    print_r(mysqli_errno($db));
}

$sql = "CREATE TABLE chat (
            msgNo int NOT NULL AUTO_INCREMENT,
            sent timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            msg varchar (180) NOT NULL,
            sender int NOT NULL,
            
            CONSTRAINT PK_Chat PRIMARY KEY (msgNo),
            CONSTRAINT FK_Chat_Users FOREIGN KEY (sender) REFERENCES users(userID)
        );";
$result = mysqli_query($db, $sql);
if (!$result) {  //Could not create Chat history Table
    echo "Step 6 Failed";
    print_r(mysqli_errno($db));
}

$sql = "CREATE TABLE project (
            title varchar(30) NOT NULL,
            details varchar(500) NULL,
            links varchar(500) NULL,
            pbl int(8) DEFAULT 1,
            taskboard int(8) DEFAULT 1,
            chat int(8) DEFAULT 1,            
            
            CONSTRAINT PK_Project PRIMARY KEY (title)
        );";
$result = mysqli_query($db, $sql);
if (!$result) {  //Could not create Project details Table
    echo "Step 7 Failed";
    print_r(mysqli_errno($db));
}
            
    
    
            
