<h3>Add User</h3>
<?php
	include 'connect_db.php';
?>
<!----user entry form------>
	<div class="user_entry_form">
		<input id="user_entry_username" type="text" placeholder="Username">
		<br>
		<br>
		<input id="user_entry_password" type="password" placeholder="Password">
		<br>
		<br>
		<input type="password" id="user_entry_password_again" placeholder="Enter the password again">
		<br>
		<br>
		<input type="email" placeholder="Email ID" id="user_entry_email">
		<br>
		<br>

		Is Admin
		<br>
		<select id="user_entry_isadmin">
			<option value=""></option>
			<option value="1">Yes</option>
			<option value="0">No</option>
		</select>
		<br>
		<br>

		Branch Code:
		<br>
		<select id="user_entry_branch_code">
			<option value=""></option>
			<?php
				$get_brand_query = "SELECT * FROM branch";
				$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

				while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
				{
					$branch_code = $get_brand_result['branch_code'];

					echo "<option value=\"$branch_code\">";
						echo $get_brand_result['branch_code'];
					echo "</option>";
				}
			?>	
		</select>
		<br>
		<br>

		<input type="button" value="Create User" id="user_entry_create_button">
	</div>

	<span class="user_entry_span"></span>
	<br><br>
	<input type="submit" value="Create New User" id="create_new_user_button">
	<br><br>

<!---------script---------->
<script type="text/javascript">
//on clicking on create user button
	$('#user_entry_create_button').click(function()
	{
		$('.user_entry_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
		
		var username = $.trim($('#user_entry_username').val());
		var password = $.trim($('#user_entry_password').val());
		var password_again = $.trim($('#user_entry_password_again').val());
		var email = $.trim($('#user_entry_email').val());
		var isadmin = $.trim($('#user_entry_isadmin').val());
		var branch_code = $.trim($('#user_entry_branch_code').val());

		//alert(isadmin);
		var query_recieved ="SELECT id FROM users WHERE username='" + username + "'";
		$.post('php/query_result_counter.php', {query_recieved:query_recieved},function(e)
		{
			if(e>=1)
			{
				$('.user_entry_span').text('This username already exist').css('color','red');
			}
			else
			{
				if(password != password_again)
				{
					$('.user_entry_span').text('Password do not match').css('color','red');
				}
				else
				{
					if(username!= "" && password!= "" && email!= "" && isadmin!= "" && branch_code!= "" )
					{
						$.post('php/create_user.php', {username:username, password:password, email:email, isadmin:isadmin, branch_code:branch_code}, function(e)
						{
							if(e==1)
							{
								$('.user_entry_span').text('User has been successfully created').css('color','green');
								$('#create_new_user_button').fadeIn(100);

							//disappearing the user entry form
								$('.user_entry_form').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);
							}
							else
							{
								$('.user_entry_span').text('Something went wrong while creating the user').css('color','red');
							}
						});
					}
					else
					{
						$('.user_entry_span').text('Please fill all the details').css('color','red');
					}
				}
			}
		});

		
	});

//on clicking on create new user button
	$('#create_new_user_button').click(function()
	{
		$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_user.php');
	});
</script>
