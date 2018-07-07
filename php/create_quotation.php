<?php
	include('connect_db.php');
	$user_username = $_COOKIE['logged_username'];
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	$quotation_customer = htmlentities(mysqli_real_escape_string($connect_link,$_POST['quotation_customer']));
	$quotation_customer_company = htmlentities(mysqli_real_escape_string($connect_link,$_POST['quotation_customer_company']));

	$quotation_date = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_date']));
	$quotation_num = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_num']));
	$quotation_serial = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_serial']));
	
	$quotation_item_type = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_item_type']));
	$quotation_brand = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_brand']));
	$quotation_model_name = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_model_name']));
	$quotation_model_number = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_model_number']));
	$quotation_serial_num = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_serial_num']));

	$quotation_service_id = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_service_id']));
	$quotation_description = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_description']));

	$quotation_part_name = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_part_name']));
	$quotation_purchase_order = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_purchase_order']));

	$quotation_part_quantity = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_part_quantity']));
	$quotation_part_rate = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_part_rate']));
	$quotation_part_cgst = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_part_cgst']));
	$quotation_part_sgst = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_part_sgst']));
	$quotation_part_igst = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_part_igst']));
	$quotation_hsn_code = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_hsn_code']));
	$quotation_part_total_price = htmlentities(mysqli_real_escape_string($connect_link, $_POST['quotation_part_total_price']));
	
	if(isset($_POST['quotation_discount']))
	{
		$quotation_discount = htmlentities(mysqli_real_escape_string($connect_link,$_POST['quotation_discount']));
	}
	else
	{
		$quotation_discount = "";
	}

	$create_user_query = "INSERT INTO quotation VALUES('', '$user_username', '$creator_branch_code', '$quotation_num', '$quotation_serial', '$quotation_description', '$quotation_customer', '$quotation_customer_company', '$quotation_date', '$quotation_brand', '$quotation_model_name', '$quotation_model_number', '$quotation_serial_num', '$quotation_service_id', '$quotation_part_name', '$quotation_purchase_order', '$quotation_part_quantity', '$quotation_part_rate', '$quotation_discount', '$quotation_part_cgst', '$quotation_part_sgst', '$quotation_part_igst', '$quotation_hsn_code', '$quotation_part_total_price', '$quotation_item_type', '', '')";
	if($create_user_query_run = mysqli_query($connect_link, $create_user_query))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
?>