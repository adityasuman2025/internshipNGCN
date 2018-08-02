<?php
	$mysql_host = "localhost";
	$mysql_pass = "may_18";

//for operating for different domains
	//session_start();
	$website = $_SERVER['HTTP_HOST'];

	if($website == "localhost" OR $website == "volta.pnds.in")
	{
		$_SESSION["comp_code"] = "VOLTA/";

		$_SESSION["mail_subject_quot"] = "Quotation from Voltatech";
		$_SESSION["mail_subject_invoice"] = "Invoice from Voltatech";
		$_SESSION["headers"] = "From: voltatech@voltatech.in";

		$_SESSION["mainMessage_quot"] = "Dear Customer, \nQuotation generated from our online resource is attached with this mail. Please find your attached Quotation pdf file. \n \nRegards \nVoltatech \nhttp://" . $website;
		$_SESSION["mainMessage_invoice"] = "Dear Customer, \nInvoice generated from our online resource is attached with this mail. Please find your attached Invoice pdf file. \n \nRegards \nVoltatech \nhttp://" . $website;

		$mysql_db = "pnds_may_18";
		$mysql_user = "pnds_may_18";
	}
	else if($website == "erp.voltatech.in")
	{
		$_SESSION["comp_code"] = "VOLTA/";

		$_SESSION["mail_subject_quot"] = "Quotation from Voltatech";
		$_SESSION["mail_subject_invoice"] = "Invoice from Voltatech";
		$_SESSION["headers"] = "From: voltatech@voltatech.in";

		$_SESSION["mainMessage_quot"] = "Dear Customer, \nQuotation generated from our online resource is attached with this mail. Please find your attached Quotation pdf file. \n \nRegards \nVoltatech \nhttp://" . $website;
		$_SESSION["mainMessage_invoice"] = "Dear Customer, \nInvoice generated from our online resource is attached with this mail. Please find your attached Invoice pdf file. \n \nRegards \nVoltatech \nhttp://" . $website;

		$mysql_db = "voltatech_may_18";
		$mysql_user = "voltatech_may_18";		
	}
	else if($website == "oxy.pnds.in")
	{
		$_SESSION["comp_code"] = "OXY/";

		$_SESSION["mail_subject_quot"] = "Quotation from OxyVin";
		$_SESSION["mail_subject_invoice"] = "Invoice from OxyVin";
		$_SESSION["headers"] = "From: oxyvin@pnds.in";

		$_SESSION["mainMessage_quot"] = "Dear Customer, \nQuotation generated from our online resource is attached with this mail. Please find your attached Quotation pdf file. \n \nRegards \nOxyVin \nhttp://" . $website;
		$_SESSION["mainMessage_invoice"] = "Dear Customer, \nInvoice generated from our online resource is attached with this mail. Please find your attached Invoice pdf file. \n \nRegards \nOxyVin \nhttp://" . $website;

		$mysql_db = "pnds_may_18_oxy";
		$mysql_user = "pnds_may_18";
	}
	else if($website == "powernet.pnds.in")
	{
		$_SESSION["comp_code"] = "POWER/";

		$_SESSION["mail_subject_quot"] = "Quotation from Powernet";
		$_SESSION["mail_subject_invoice"] = "Invoice from Powernet";
		$_SESSION["headers"] = "From: powernet@pnds.in";

		$_SESSION["mainMessage_quot"] = "Dear Customer, \nQuotation generated from our online resource is attached with this mail. Please find your attached Quotation pdf file. \n \nRegards \nPowernet \nhttp://" . $website;
		$_SESSION["mainMessage_invoice"] = "Dear Customer, \nInvoice generated from our online resource is attached with this mail. Please find your attached Invoice pdf file. \n \nRegards \nPowernet \nhttp://" . $website;

		$mysql_db = "pnds_may_18_power";
		$mysql_user = "pnds_may_18";
	}
	else
	{
		$_SESSION["comp_code"] = "DEMO/";

		$_SESSION["mail_subject_quot"] = "Quotation from Pnds Demo";
		$_SESSION["mail_subject_invoice"] = "Invoice from Pnds Demo";
		$_SESSION["headers"] = "From: demo@pnds.in";

		$_SESSION["mainMessage_quot"] = "Dear Customer, \nQuotation generated from our online resource is attached with this mail. Please find your attached Quotation pdf file. \n \nRegards \nPnds Demo \nhttp://" . $website;
		$_SESSION["mainMessage_invoice"] = "Dear Customer, \nInvoice generated from our online resource is attached with this mail. Please find your attached Invoice pdf file. \n \nRegards \nPnds Demo \nhttp://" . $website;

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