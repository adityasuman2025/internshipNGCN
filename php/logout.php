<?php
//deleting cookie for logging out
	if(setcookie('logged_username', '' , time() - 2592000, "/") && setcookie('isadmin', '' , time() - 2592000, "/") && setcookie('logged_username_branch_code', '' , time() - 2592000, "/"))
	{
		echo 1;	
	}
	else
	{
		echo 0;
	}

 ?>