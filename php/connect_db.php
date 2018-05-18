<?php
	$mysql_host = "localhost";
	$mysql_user = "pnds_may_18";
	$mysql_pass = "may_18";
	$mysql_db = "pnds_may_18";

	if($connect_link = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db))
	{
		//echo "database connection succeed";
	}
	else
	{
		echo "database connection failed";
	}
?>