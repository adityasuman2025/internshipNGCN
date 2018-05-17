<?php
	include('connect_db.php');

	$query = $_POST['query'];
	$query_run = mysqli_query($connect_link, $query);

	while($query_assoc = mysqli_fetch_assoc($query_run))
	{
		$part_name= $query_assoc['part_name'];
		$creator_branch_code= $query_assoc['creator_branch_code'];
		$in_stock= $query_assoc['in_stock'];

		echo "<tr>";
			echo "<td>$part_name</td>";
			echo "<td>$creator_branch_code</td>";
			echo "<td>$in_stock</td>";
		echo "</tr>";
	}
?>