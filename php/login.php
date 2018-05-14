<?php
	include('connect_db.php');

	$input_username = strtolower(htmlentities(mysqli_real_escape_string($connect_link,$_POST['input_username'])));
	$input_password = htmlentities(mysqli_real_escape_string($connect_link, md5($_POST['input_password'])));

	$login_query = "SELECT * FROM users WHERE username = '$input_username' AND password = '$input_password'";
	$login_query_run = mysqli_query($connect_link, $login_query);
	if($login_query_assoc = mysqli_fetch_assoc($login_query_run))
	{
		$isadmin = $login_query_assoc['isadmin'];
		setcookie('logged_username', $login_query_assoc['username'], time() + 2592000, "/");
		setcookie('isadmin', $login_query_assoc['isadmin'], time() + 2592000, "/");
		setcookie('logged_username_branch_code', $login_query_assoc['branch_code'], time() + 2592000, "/");

		if($isadmin =='1')
		{
			echo 1;
		}
		else if($isadmin =='0')
		{
			echo 2;
		}
	}
	else
	{
		echo 0;
	}
?>