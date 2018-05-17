<br>
<div class="login_portal">
	<br>
	<input class="input_username" type="text" name="" placeholder="Username">
	<br>
	<br>
	<input class="input_password" type="password" name="" placeholder="Password">
	<br>
	<span class="login_feed"></span>
	<br><br>
	<button class="login_button">Login</button>
	<br>
	<br>
	<a class="forget_pass_button" href="">Forgot password</a>
</div>

<script type="text/javascript">
	$('.login_button').click(function()
	{
		$('.login_feed').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
		
		var input_username = $('.input_username').val();
		var input_password = $('.input_password').val();

		if(input_username != "" && input_password != "")
		{
			//looking for the user credentials in the database
			$.post('php/login.php', {input_username: input_username, input_password: input_password}, function(e)
			{
				if(e==0)
				{
					$('.login_feed').text("Your username and password combination is not correct.").css('color','black').css('color','red');
				}
				else if(e==1)
				{
					location.href = "admin";
				}
				else if(e==2)
				{
					location.href = "user";
				}

			});
		}
		else
		{
			$('.login_feed').text('please fill all the login details.').css('color','red');
		}
	});
</script>