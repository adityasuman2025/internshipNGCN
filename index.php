<?php

//if user is already logged in
	if(isset($_COOKIE['logged_username']) && $_COOKIE['isadmin'] ==1)
	{
		header("Location:admin.php");
	}
	else if(isset($_COOKIE['logged_username']) && $_COOKIE['isadmin'] ==0)//if user is already logged in and the user is not an admin
	{
		header("Location:user.php");
	}
?>

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
		<img src="img/top.jpg">
	</div>

<!--------log in portal------>
	<?php	
		include('php/login_portal.php');
	?>

</body>
</html>