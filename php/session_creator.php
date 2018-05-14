<?php
	include('connect_db.php');

	$session_of = htmlentities(mysqli_real_escape_string($connect_link,$_POST['session_of']));
	$session_name = htmlentities(mysqli_real_escape_string($connect_link,$_POST['session_name']));

	session_start();

	if($_SESSION[$session_name] = $session_of)
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
?>