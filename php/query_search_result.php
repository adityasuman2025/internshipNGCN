<?php
	include('connect_db.php');

	$query = $_POST['query'];
	$to_get = $_POST['to_get'];

	$query_run = mysqli_query($connect_link, $query);

	while($query_assoc = mysqli_fetch_assoc($query_run))
	{
		$query_result = $query_assoc[$to_get];
	
		echo "<div id=\"search_result_span\">";
			echo $query_result;
		echo "</div>";
	}
?>