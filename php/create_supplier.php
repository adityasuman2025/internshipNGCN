<?php
	include('connect_db.php');
	$user_username = $_COOKIE['logged_username'];
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	$name = htmlentities(mysqli_real_escape_string($connect_link,$_POST['name']));
	$email = htmlentities(mysqli_real_escape_string($connect_link, $_POST['email']));
	$mobile = htmlentities(mysqli_real_escape_string($connect_link, $_POST['mobile']));
	$address = htmlentities(mysqli_real_escape_string($connect_link, $_POST['address']));
	$details = htmlentities(mysqli_real_escape_string($connect_link, $_POST['details']));
	$type = htmlentities(mysqli_real_escape_string($connect_link, $_POST['type']));

	$create_user_query = "INSERT INTO supplier VALUES('', '$user_username', '$creator_branch_code', '$name', '$email', '$mobile', '$address', '$details', '$type')";
	if($create_user_query_run = mysqli_query($connect_link, $create_user_query))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}

?>