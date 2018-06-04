<?php
	include('connect_db.php');
	$user_username = $_COOKIE['logged_username'];
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	$invoice_num = $_POST['invoice_num'];
	$supplier = htmlentities(mysqli_real_escape_string($connect_link, $_POST['supplier']));
	$date = htmlentities(mysqli_real_escape_string($connect_link, $_POST['date']));

	$create_user_query = "INSERT INTO purchases VALUES('', '$user_username', '$creator_branch_code', '$supplier', '$date', '$invoice_num', '', '', '', '', '', '', '', '', '', '', '')";
	if($create_user_query_run = mysqli_query($connect_link, $create_user_query))
	{
		$get_new_id_query = "SELECT id FROM purchases WHERE invoice_num = '$invoice_num' ORDER BY id DESC LIMIT 1";
		$get_new_id_query_run = mysqli_query($connect_link, $get_new_id_query);

		$get_new_id_assoc = mysqli_fetch_assoc($get_new_id_query_run);
		echo $new_id = $get_new_id_assoc['id'];
	}
	else
	{
		echo 0;
	}

?>