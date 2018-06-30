<?php
	$mysql_host = "localhost";
	$mysql_pass = "may_18";
	//$mysql_db = "pnds_may_18_oxy";
	
	$website = $_SERVER['HTTP_HOST'];

	//setting database name according to the server name
	if($website == "localhost" OR $website == "volta.pnds.in")
	{
		$mysql_db = "pnds_may_18";
		$mysql_user = "pnds_may_18";		
	}
	else if($website == "oxy.pnds.in")
	{		
		$mysql_db = "pnds_may_18_oxy";
		$mysql_user = "pnds_may_18";
	}
	else if($website == "erp.voltatech.in")
	{
		$mysql_db = "voltatech_may_18";
		$mysql_user = "voltatech_may_18";		
	}
	else
	{
		$mysql_db = "pnds_may_18";	
		$mysql_user = "pnds_may_18";
	}

//connecting to the database
	if($connect_link = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db))
	{
		//echo "database connection succeed";
	}
	else
	{
		echo "database connection failed";
	}
?>