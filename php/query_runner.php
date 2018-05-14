<?php
	include('connect_db.php');
	
	$query_recieved = $_POST['query_recieved'];
	if($query_run = mysqli_query($connect_link, $query_recieved))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
?>