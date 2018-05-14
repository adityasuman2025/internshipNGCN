<html>
<head>
	<?php
		include('php/header.php');
	?>

	<title>Inventory & Billing App</title>
</head>
<body>
	
<!-------company cover image---->
	<div class="index_cover">
		<img src="img/top.jpeg">
	</div>

<!--------log in portal------>
	<?php
	//if user is already logged in and the user is also an admin
		if(isset($_COOKIE['logged_username']) && $_COOKIE['isadmin'] ==1)
		{
			header("Location:admin");
		}
		else if(isset($_COOKIE['logged_user']) && $_COOKIE['isadmin'] ==0)//if user is already logged in and the user is not an admin
		{
			header("Location:user");
		}
		else //if the user is not already logged in
		{
			include('php/login_portal.php');
		}
	?>

</body>
</html>