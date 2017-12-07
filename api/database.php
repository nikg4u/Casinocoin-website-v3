<?php
$host = "localhost";
$username = "root";
$password = "root123";
$db_name = "casino";

$conn = mysqli_connect($host, $username, $password, $db_name);
if(!$conn){
	die("Error in connecting to db " . mysqli_connect_error());
}
echo "Connected";
?>