<?php
	include('connect_db.php');

	$quotation_num = $_POST['quotation_num'];

	$get_item_info_query = "SELECT * FROM quotation WHERE quotation_num = '$quotation_num' AND payment_method !=''";
	$get_item_info_query_run = mysqli_query($connect_link, $get_item_info_query);

	while($get_item_info_assoc = mysqli_fetch_assoc($get_item_info_query_run))
	{
		$brand = $get_item_info_assoc['brand'];
		$model_name = $get_item_info_assoc['model_name'];
		$model_number = $get_item_info_assoc['model_number'];
		$part_name = $get_item_info_assoc['part_name'];

		$quantity = $get_item_info_assoc['quantity'];

	//get stock of that particular item
		$get_stock_query = "SELECT * FROM stock WHERE brand = '$brand' AND model_name = '$model_name' AND model_number = '$model_number' AND part_name = '$part_name'";
		$get_stock_query_run = mysqli_query($connect_link, $get_stock_query);
		
		while($get_stock_assoc = mysqli_fetch_assoc($get_stock_query_run))
		{
			$item_in_stock = $get_stock_assoc['in_stock'];
			$item_sold = $get_stock_assoc['sold'];

			$new_item_in_stock = $item_in_stock - $quantity;
			$new_item_sold = $item_sold + $quantity;

		//update stock of that particular item
			$update_item_stock_query = "UPDATE stock SET sold ='$new_item_sold', in_stock = '$new_item_in_stock' WHERE brand = '$brand' AND model_name = '$model_name' AND model_number = '$model_number' AND part_name = '$part_name'";
			
			if($update_item_stock_query_run = mysqli_query($connect_link, $update_item_stock_query))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
	}	
?>