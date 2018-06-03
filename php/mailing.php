<?php
	$mail_email = htmlentities($_POST['mail_email']);
	$mail_subject= $_POST['mail_subject'];
	$mail_body= $_POST['mail_body'];
	$mail_header= $_POST['mail_header'];

	if(mail($mail_email, $mail_subject, $mail_body, $mail_header))
	{
		echo 1;
	}
	else 
	{
		echo 0;
	}
?>	