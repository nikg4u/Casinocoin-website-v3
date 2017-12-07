<?php
if(!isset($_POST) || !isset($_POST['email']) || !isset($_POST['cc_address'])){
	$response['success'] = 0;
	$response['error'] = "Please provide email and casino coin address";
	echo json_encode($response);
	exit();
}

$email = $_POST['email'];
$cc_address = $_POST['cc_address'];

$query = "SELECT * FROM users WHERE email = '" . mysqli_escape_string($conn, $email) . "' AND cc_address = '" . mysqli_escape_string($conn, $cc_address) . "'";
$query_obj = mysqli_query($conn, $query);
$result = mysqli_fetch_assoc($query_obj);

if(!$result){
	$response['success'] = 0;
	$response['error'] = "Invalid user";
	echo json_encode($response);
	exit();
}

$response['success'] = 1;
echo json_encode($response);
exit();
?>