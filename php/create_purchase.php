<?php
	include('connect_db.php');
	$user_username = $_COOKIE['logged_username'];
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	$purchase_supplier = htmlentities(mysqli_real_escape_string($connect_link,$_POST['purchase_supplier']));
	$purchase_date = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_date']));
	$purchase_inv_num = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_inv_num']));
	$purchase_item = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_item']));
	$purchase_quantity = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_quantity']));
	$purchase_rate = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_rate']));
	$purchase_total_price = htmlentities(mysqli_real_escape_string($connect_link, $_POST['purchase_total_price']));

	$create_user_query = "INSERT INTO purchases VALUES('', '$user_username', '$creator_branch_code', '$purchase_supplier', '$purchase_date', '$purchase_inv_num', '$purchase_item', '$purchase_quantity', '$purchase_rate', '$purchase_total_price')";
	if($create_user_query_run = mysqli_query($connect_link, $create_user_query))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}

?>