<option value=""></option>

<?php
	include('connect_db.php');
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	$model_name = $_POST['model_name'];
	$model_number = $_POST['model_number'];

	$list_item_query = "SELECT part_name FROM stock WHERE model_name = '$model_name' AND model_number = '$model_number' AND creator_branch_code = '$creator_branch_code' GROUP BY part_name";
	$list_item_query_run = mysqli_query($connect_link, $list_item_query);
	while($list_item_assoc = mysqli_fetch_assoc($list_item_query_run))
	{
		$part_name = $list_item_assoc['part_name'];
		
		echo "<option value=\"$part_name\">";
			echo $part_name;
		echo "</option>";
	
	}
?>