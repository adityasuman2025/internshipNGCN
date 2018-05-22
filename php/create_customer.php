<?php
	include('connect_db.php');
	$user_username = $_COOKIE['logged_username'];
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	$name = htmlentities(mysqli_real_escape_string($connect_link,$_POST['name']));
	$company_name = htmlentities(mysqli_real_escape_string($connect_link,$_POST['company_name']));
	$email = htmlentities(mysqli_real_escape_string($connect_link, $_POST['email']));
	$mobile = htmlentities(mysqli_real_escape_string($connect_link, $_POST['mobile']));
	$pan = htmlentities(mysqli_real_escape_string($connect_link, $_POST['pan']));
	$address = htmlentities(mysqli_real_escape_string($connect_link, $_POST['address']));
	$gst = htmlentities(mysqli_real_escape_string($connect_link, $_POST['gst']));
	$shipping_address = htmlentities(mysqli_real_escape_string($connect_link, $_POST['shipping_address']));

	$create_user_query = "INSERT INTO customers VALUES('', '$user_username', '$creator_branch_code', '$name', '$company_name', '$email', '$mobile', '$address', '$pan', '$gst', '$shipping_address', '', '', now())";
	if($create_user_query_run = mysqli_query($connect_link, $create_user_query))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}

?>