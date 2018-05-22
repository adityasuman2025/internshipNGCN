<?php
	include 'connect_db.php';

	$user_id = mysqli_real_escape_string($connect_link, $_POST['user_id']);

	$edit_user_query = "SELECT * FROM users WHERE id='$user_id'";
	$list_user_query_run = mysqli_query($connect_link, $edit_user_query);

	$list_user_assoc = mysqli_fetch_assoc($list_user_query_run);

	$user_id = $list_user_assoc['id'];
	$username = $list_user_assoc['username'];
	$password = $list_user_assoc['password'];
	$isadmin = $list_user_assoc['isadmin'];
	$email = $list_user_assoc['email'];
	$branch_code = $list_user_assoc['branch_code'];

?>

<!-----edit user form------->
	<div class="user_entry_form" id="user_edit_form">
		Username:
		<br>
		<input id="user_entry_username" type="text" value="<?php echo $username; ?>">

		<br><br>
		Password:
		<button id="change_password_button">Change</button>
		
		<br>
		Email:
		<br>
		<input type="email" value="<?php echo $email; ?>" id="user_entry_email">
		<br><br>

		Is Admin
		<br>
		<select id="user_entry_isadmin">
			<?php
				if($isadmin == '0')
				{
					echo "<option value=\"0\">No</option>";
					echo "<option value=\"1\">Yes</option>";
				}
				else
				{
					echo "<option value=\"1\">Yes</option>";
					echo "<option value=\"0\">No</option>";
				}
			?>
		</select>
		<br><br>

		Branch Code:
		<br>
		<select id="user_entry_branch_code">
			<option value="<?php echo $branch_code; ?>"><?php echo $branch_code; ?></option>
			<?php
				$get_brand_query = "SELECT * FROM branch WHERE branch_code != '$branch_code'";
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
		<br><br>
		
		<input type="button" value="Save" id="user_save_edit_button">
		<br>
	</div>

	<div class="user_entry_form change_password_div">
		<input type="password" id="user_entry_password" placeholder="Enter the new password">
		<br>
		<br>
		<input type="password" id="user_entry_password_again" placeholder="Enter the password again">
		<br>
		<br>
		<input type="submit" value="change" id="save_password_button">
		
	</div> 
		
	<span class="user_edit_span"></span>

<!----script----->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/md5.js"></script>

	<script type="text/javascript">
	//on clicking on edit button
		$('#user_save_edit_button').click(function()
		{
			$('.user_edit_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			var user_id = "<?php echo $user_id;?>";
			var username = $('#user_edit_form #user_entry_username').val();
			var email = $('#user_edit_form #user_entry_email').val();
			var isadmin = $('#user_edit_form #user_entry_isadmin').val();
			var branch_code = $('#user_edit_form #user_entry_branch_code').val();

			var query_recieved ="SELECT id FROM users WHERE username='" + username + "' AND id !='" + user_id + "'";
			$.post('php/query_result_counter.php', {query_recieved:query_recieved},function(e)
			{
				if(e>=1)
				{
					$('.user_edit_span').text('This username already exist').css('color','red');
				}
				else
				{							
					if(username!= "" && email!= "" && isadmin!= "" && branch_code!= "" )
					{
						var query_recieved = "UPDATE users SET username ='" + username + "', email ='" + email + "', isadmin = '" + isadmin + "', branch_code = '" + branch_code + "' WHERE id = '" + user_id + "'";
						// alert(query_recieved);

						$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
						{
							if(e==1)
							{
								$('.user_edit_span').text('Successfully edited').css('color','green');
								$('#user_edit_form').fadeOut(0);

								$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/manage_user.php');
							}
							else
							{
								$('.user_edit_span').text('Something went wrong while editing the user').css('color','red');
							}
						});
					}
					else
					{
						$('.user_edit_span').text('Please fill all the details').css('color','red');
					}
				}
			});
		});

	//on clicking on change password button
		$('#change_password_button').click(function()
		{
			$('#user_edit_form').fadeOut(0);
			$('.change_password_div').fadeIn(100);
		});

	//on clicking on save password button
		$('#save_password_button').click(function()
		{
			$('.user_edit_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");

			var user_id = "<?php echo $user_id;?>";
			var password = $('.change_password_div #user_entry_password').val();
			var password_again = $('.change_password_div #user_entry_password_again').val();
			if(password == password_again && password_again !="" && password !="")
			{
				$('.user_edit_span').text('');

				var password = CryptoJS.MD5(password); //generating md5 hash of the password
				var query_recieved = "UPDATE users SET password = '" + password + "' WHERE id ='" + user_id + "'";
				
				$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
				{
					if(e==1)
					{
						$('.user_edit_span').text('Password successfully changed').css('color','green');
						$('.change_password_div').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);
					}
					else
					{
						$('.user_edit_span').text('Something went wrong while changing the password').css('color','red');
					}
				});
			}
			else
			{
				$('.user_edit_span').text('Password do not match').css('color','red');
			}
		});

	</script>