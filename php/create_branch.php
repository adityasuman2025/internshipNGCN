<?php
	include('connect_db.php');
	$user_username = $_COOKIE['logged_username'];
	
	$company_name = htmlentities(mysqli_real_escape_string($connect_link,$_POST['company_name']));
	$branch_name = htmlentities(mysqli_real_escape_string($connect_link, $_POST['branch_name']));
	$branch_code = htmlentities(mysqli_real_escape_string($connect_link, $_POST['branch_code']));
	$city = htmlentities(mysqli_real_escape_string($connect_link, $_POST['city']));
	$address = htmlentities(mysqli_real_escape_string($connect_link, $_POST['address']));
	$email = htmlentities(mysqli_real_escape_string($connect_link, $_POST['email']));
	$phone_number = htmlentities(mysqli_real_escape_string($connect_link, $_POST['phone_number']));
	$registration_number = htmlentities(mysqli_real_escape_string($connect_link, $_POST['registration_number']));
	$gst_number = htmlentities(mysqli_real_escape_string($connect_link, $_POST['gst_number']));
	
	$bank_accnt_name = htmlentities(mysqli_real_escape_string($connect_link, $_POST['bank_accnt_name']));
	$bank_accnt_no = htmlentities(mysqli_real_escape_string($connect_link, $_POST['bank_accnt_no']));
	$bank_name = htmlentities(mysqli_real_escape_string($connect_link, $_POST['bank_name']));
	$bank_ifsc = htmlentities(mysqli_real_escape_string($connect_link, $_POST['bank_ifsc']));

	$create_user_query = "INSERT INTO branch VALUES('', '$user_username', '$company_name', '$branch_name', '$branch_code', '$city', '$address', '$email', '$phone_number', '$registration_number', '$gst_number', '$bank_accnt_name', '$bank_accnt_no', '$bank_name', '$bank_ifsc')";
	if($create_user_query_run = mysqli_query($connect_link, $create_user_query))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}

?>