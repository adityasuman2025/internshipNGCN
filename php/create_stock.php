<?php
	include('connect_db.php');

	$user_username = $_COOKIE['logged_username'];
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	$inv_type = htmlentities(mysqli_real_escape_string($connect_link,$_POST['inv_type']));
	$inv_brand = htmlentities(mysqli_real_escape_string($connect_link,$_POST['inv_brand']));
	$inv_model_name = htmlentities(mysqli_real_escape_string($connect_link, $_POST['inv_model_name']));
	$inv_model_num = htmlentities(mysqli_real_escape_string($connect_link, $_POST['inv_model_num']));
	$inv_part_name = htmlentities(mysqli_real_escape_string($connect_link, $_POST['inv_part_name']));
	$inv_part_number = htmlentities(mysqli_real_escape_string($connect_link, $_POST['inv_part_number']));
	
	$inv_quantity = htmlentities(mysqli_real_escape_string($connect_link,$_POST['inv_quantity']));
	$inv_sales_price = htmlentities(mysqli_real_escape_string($connect_link, $_POST['inv_sales_price']));
	$inv_supplier_price = htmlentities(mysqli_real_escape_string($connect_link, $_POST['inv_supplier_price']));
	$inv_hsn_code = htmlentities(mysqli_real_escape_string($connect_link, $_POST['inv_hsn_code']));
	
	$add_inventory_query = "INSERT INTO stock VALUES('', '$user_username', '$creator_branch_code',  '$inv_brand', '$inv_model_name', '$inv_model_num', '$inv_part_name', '$inv_part_number', '$inv_type', '$inv_quantity', '$inv_sales_price', '$inv_supplier_price', '$inv_hsn_code', '$inv_quantity', '0', now())";
	if($add_inventory_query_run = mysqli_query($connect_link, $add_inventory_query))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}

?>