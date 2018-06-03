<?php
	$mail_email = "adityasuman2025@gmail.com";

	$mail_subject= "Hello world";
	$mail_body= "how r u doing";
	$mail_header= "FROM: mngoscience@gmail.com";

	if(mail($mail_email, $mail_subject, $mail_body, $mail_header))
	{
		echo 1;
	}
	else 
	{
		echo 0;
	}
?>	