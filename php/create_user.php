<?php
	include('connect_db.php');

	$username = htmlentities(mysqli_real_escape_string($connect_link,$_POST['username']));
	$password = htmlentities(mysqli_real_escape_string($connect_link, md5($_POST['password'])));
	$email = htmlentities(mysqli_real_escape_string($connect_link, $_POST['email']));
	$service_tax_no = htmlentities(mysqli_real_escape_string($connect_link, $_POST['service_tax_no']));
	$isadmin = htmlentities(mysqli_real_escape_string($connect_link, $_POST['isadmin']));
	$address = htmlentities(mysqli_real_escape_string($connect_link, $_POST['address']));
	$branch_code = htmlentities(mysqli_real_escape_string($connect_link, $_POST['branch_code']));
	
	$create_user_query = "INSERT INTO users VALUES('', '$username', '$email', '$password', '$service_tax_no', '$address', '$branch_code', '$isadmin' )";
	if($create_user_query_run = mysqli_query($connect_link, $create_user_query))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}

?>