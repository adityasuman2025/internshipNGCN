<?php
	include('connect_db.php');

	$query_recieved = $_POST['query_recieved'];

	$query_run = mysqli_query($connect_link, $query_recieved);
	$num = mysqli_num_rows($query_run);

	$query_assoc = mysqli_fetch_assoc($query_run);
	$quantity = $query_assoc['in_stock'];

	$result = array("count"=>$num, "quantity"=>$quantity);

	//print_r($result);

	echo $quantity;
?>