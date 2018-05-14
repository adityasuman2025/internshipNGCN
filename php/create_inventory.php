<?php
	include('connect_db.php');

	$inv_brand = htmlentities(mysqli_real_escape_string($connect_link,$_POST['inv_brand']));
	$inv_model_name = htmlentities(mysqli_real_escape_string($connect_link, $_POST['inv_model_name']));
	$inv_model_num = htmlentities(mysqli_real_escape_string($connect_link, $_POST['inv_model_num']));
	$inv_part_name = htmlentities(mysqli_real_escape_string($connect_link, $_POST['inv_part_name']));
	$inv_part_number = htmlentities(mysqli_real_escape_string($connect_link, $_POST['inv_part_number']));
	$inv_part_desc = htmlentities(mysqli_real_escape_string($connect_link, $_POST['inv_part_desc']));
	$inv_type = htmlentities(mysqli_real_escape_string($connect_link, $_POST['inv_type']));

	$add_inventory_query = "INSERT INTO inventory VALUES('', '$inv_brand', '$inv_model_name', '$inv_model_num', '$inv_part_name', '$inv_part_number', '$inv_part_desc', '$inv_type', now())";
	if($add_inventory_query_run = mysqli_query($connect_link, $add_inventory_query))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}

?>