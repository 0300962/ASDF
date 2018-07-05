<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 30-Jun-18
 * Time: 3:08 PM
 */

//Increases version-number in database following a change
$sql = "UPDATE project SET revision = revision + 1;";
$result = mysqli_query($db, $sql);
if (!$result) {
    echo "Error updating project revision";
}
?>