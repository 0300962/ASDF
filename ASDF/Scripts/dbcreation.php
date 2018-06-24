<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22-Jun-18
 * Time: 10:30 PM
 */


include_once 'connection.php';
echo "Beginning step 1\n\n";

$sql = "CREATE DATABASE IF NOT EXISTS ASDF; 
        USE ASDF;
        
        CREATE TABLE Users (
            userID int NOT NULL,
            name varchar(50) NULL,
            initials varchar(4) NULL,
            blurb varchar (300) NULL,
            colour varchar (7) NULL,
            
            CONSTRAINT PK_Users PRIMARY KEY (userID)
            );           
        ";
$result = mysqli_query($db, $sql);
if (!$result) {  //Could not create Database or Users Table
    echo "Step 1 Failed";
}

echo "Beginning step 2\n\n";
$sql = "CREATE TABLE Logins (
            login varchar(128) NOT NULL,
            userID int NOT NULL AUTO_INCREMENT UNIQUE,            
            pwhash varchar(256) NULL,
            status int(1) NOT NULL,
            
            CONSTRAINT PK_Logins PRIMARY KEY (login),
            CONSTRAINT FK_Logins_Users FOREIGN KEY (userID) REFERENCES users(userID)
            );";
$result = mysqli_query($db, $sql);
if (!$result) {  //Could not create Logins table
    echo "Step 2 Failed";
}

echo "Beginning step 3\n\n";
$sql = "CREATE TABLE PBIs (
            pbiNo int NOT NULL AUTO_INCREMENT,
            userStory varchar(300) NOT NULL,
            acceptance varchar(300) NOT NULL,
            priority int(3) DEFAULT 50,
            completed date NULL,
            
            CONSTRAINT PK_PBIs PRIMARY KEY (pbiNo)
        );";
$result = mysqli_query($db, $sql);
if (!$result) {  //Could not create Project Backlog Items table
    echo "Step 3 Failed";
}

echo "Beginning step 4\n\n";
$sql = "CREATE TABLE SBIs (
            sbiNo int NOT NULL AUTO_INCREMENT,
            pbiNo int NOT NULL,
            task varchar(300) NOT NULL,
            inProgress varchar(16) NULL,
            testing varchar(16) NULL,
            done varchar(16) NULL,
            
            CONSTRAINT PK_SBIs PRIMARY KEY (sbiNo),
            CONSTRAINT FK_SBIs_PBIs FOREIGN KEY (pbiNo) REFERENCES pbis(pbiNo) ON DELETE CASCADE
        );";
$result = mysqli_query($db, $sql);
if (!$result) {  //Could not create Sprint Backlog Items Table
    echo "Step 4 Failed";
}

echo "Beginning step 5\n\n";
$sql = "CREATE TABLE Sprints (
            sprintNo int NOT NULL AUTO_INCREMENT,
            startDate date NOT NULL,
            endDate date NULL,
            backlogTotal int(4) NULL,
            
            CONSTRAINT PK_Sprints PRIMARY KEY (sprintNo)
        );";
$result = mysqli_query($db, $sql);
if (!$result) {  //Could not create Sprint History table
    echo "Step 5 Failed";
}

echo "Beginning step 6\n\n";
$sql = "CREATE TABLE Chat (
            msgNo int NOT NULL AUTO_INCREMENT,
            sent datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            msg varchar (180) NOT NULL,
            sender int NOT NULL,
            
            CONSTRAINT PK_Chat PRIMARY KEY (msgNo),
            CONSTRAINT FK_Chat_Users FOREIGN KEY (sender) REFERENCES users(userID)
        );";
$result = mysqli_query($db, $sql);
if (!$result) {  //Could not create Chat history Table
    echo "Step 6 Failed";
}
            
    
    
            
