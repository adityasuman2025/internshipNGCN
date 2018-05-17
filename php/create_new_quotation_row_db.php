<?php
	include('connect_db.php');
	$user_username = $_COOKIE['logged_username'];
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	$quotation_customer = htmlentities(mysqli_real_escape_string($connect_link,$_POST['quotation_customer']));
	$quotation_date = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_date']));
	$quotation_num = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_num']));
	$quotation_brand = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_brand']));
	$quotation_model_name = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_model_name']));
	$quotation_model_number = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_model_number']));
	$quotation_serial_num = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_serial_num']));
	$quotation_service_id = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_service_id']));
	
	$type = htmlentities(mysqli_real_escape_string($connect_link, $_POST['type']));
	

	$create_user_query = "INSERT INTO quotation VALUES('', '$user_username', '$creator_branch_code', '$quotation_num', '$quotation_customer', '$quotation_date', '$quotation_brand', '$quotation_model_name', '$quotation_model_number', '$quotation_serial_num', '$quotation_service_id', '', '', '', '', '', '', '', '$type', '', '')";
	if($create_user_query_run = mysqli_query($connect_link, $create_user_query))
	{
		$get_new_id_query = "SELECT id FROM quotation ORDER BY id DESC LIMIT 1";
		$get_new_id_query_run = mysqli_query($connect_link, $get_new_id_query);

		$get_new_id_assoc = mysqli_fetch_assoc($get_new_id_query_run);
		echo $new_id = $get_new_id_assoc['id'];
	}
	else
	{
		echo 0;
	}

?>