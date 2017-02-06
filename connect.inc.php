<?php
$db_username = 'root';
$db_password = 'yeshu_123';
$hostname = 'localhost';
$connection = mysqli_connect($hostname,$db_username,$db_password);
if($connection){
    if(!mysqli_select_db($connection,'registration')){
        echo 'could not connect to database';
    }
}
else {
    echo 'problem connecting';
}
