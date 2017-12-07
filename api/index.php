<?php
require 'database.php';
$query = "SELECT * FROM test";
$query_obj = mysqli_query($conn, $query);
while($row = mysqli_fetch_assoc($query_obj)){
	print_r($row);
	echo "<br>";
}
echo "I am in index";
?>