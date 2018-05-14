<?php
	$mysql_host = "localhost";
	$mysql_user = "root";
	$mysql_pass = "";
	$mysql_db = "may_18";

	if($connect_link = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db))
	{
		//echo "database connection succeed";
	}
	else
	{
		echo "database connection failed";
	}
?>