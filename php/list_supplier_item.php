<option value=""></option>

<?php
	include('connect_db.php');

	$supplier = $_POST['supplier'];

	$list_item_query = "SELECT name_of_product FROM supplier WHERE name = '$supplier'";
	$list_item_query_run = mysqli_query($connect_link, $list_item_query);
	while($list_item_assoc = mysqli_fetch_assoc($list_item_query_run))
	{
		$list_item_result = $list_item_assoc['name_of_product'];
		$explode = explode(",", $list_item_result);
		$count_element = count($explode);

		for($i = 0; $i < $count_element; $i++ )
		{
			$item = $explode[$i];
			echo "<option value=\"$item\">";
				echo $item;
			echo "</option>";
		}
		//print_r($test);


		//echo "<option value=\"$item\">";
		//	echo $item;
		//echo "</option>";
	}
?>