<option value=""></option>

<?php
	include('connect_db.php');

	$model_name = $_POST['model_name'];
	$model_number = $_POST['model_number'];

	$list_item_query = "SELECT part_name FROM stock WHERE model_name = '$model_name' AND model_number = '$model_number'";
	$list_item_query_run = mysqli_query($connect_link, $list_item_query);
	while($list_item_assoc = mysqli_fetch_assoc($list_item_query_run))
	{
		$part_name = $list_item_assoc['part_name'];
		
		echo "<option value=\"$part_name\">";
			echo $part_name;
		echo "</option>";
	
	}
?>