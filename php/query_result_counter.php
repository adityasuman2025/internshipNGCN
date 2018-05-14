<?php
	include('connect_db.php');

	$query_recieved = $_POST['query_recieved'];

	$query_run = mysqli_query($connect_link, $query_recieved);
	echo $num = mysqli_num_rows($query_run);
?>