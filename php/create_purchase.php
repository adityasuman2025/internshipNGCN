<?php
	include('connect_db.php');
	$user_username = $_COOKIE['logged_username'];
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	$purchase_supplier = htmlentities(mysqli_real_escape_string($connect_link,$_POST['purchase_supplier']));
	$purchase_date = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_date']));
	$purchase_inv_num = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_inv_num']));

	$purchase_type = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_type']));
	$purchase_brand = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_brand']));
	$purchase_model_name = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_model_name']));
	$purchase_model_num = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_model_num']));
	$purchase_hsn_code = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_hsn_code']));
	$purchase_description = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_description']));

	$purchase_quantity = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_quantity']));
	$purchase_rate = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_rate']));
	$purchase_cgst_rate = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_cgst_rate']));
	$purchase_sgst_rate = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_sgst_rate']));
	$purchase_igst_rate = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_igst_rate']));
	$purchase_total_amount = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_total_amount']));

	$create_user_query = "INSERT INTO purchases VALUES('', '$user_username', '$creator_branch_code', '$purchase_supplier', '$purchase_date', '$purchase_inv_num', '$purchase_brand','$purchase_model_name', '$purchase_model_num', '$purchase_hsn_code', '$purchase_description', '$purchase_type', '$purchase_quantity', '$purchase_rate', '$purchase_cgst_rate', '$purchase_sgst_rate', '$purchase_igst_rate', '$purchase_total_amount')";
	if($create_user_query_run = mysqli_query($connect_link, $create_user_query))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}

?>