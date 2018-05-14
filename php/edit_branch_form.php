<?php
	include 'connect_db.php';

	$branch_id = mysqli_real_escape_string($connect_link, $_POST['branch_id']);

	$edit_user_query = "SELECT * FROM branch WHERE id='$branch_id'";
	$list_user_query_run = mysqli_query($connect_link, $edit_user_query);

	$list_user_assoc = mysqli_fetch_assoc($list_user_query_run);

	$branch_id = $list_user_assoc['id'];

	$company_name = $list_user_assoc['company_name'];
	$branch_name = $list_user_assoc['branch_name'];
	$branch_code = $list_user_assoc['branch_code'];
	$city = $list_user_assoc['city'];
	$address = $list_user_assoc['address'];
	$email = $list_user_assoc['email'];
	$phone_number = $list_user_assoc['phone_number'];
	$registration_number = $list_user_assoc['registration_number'];
	$gst_number = $list_user_assoc['gst_number'];
	$bank = $list_user_assoc['bank'];

?>

<!-----edit customer form------->
	<div class="user_entry_form" id="user_edit_form">
		Company Name:
		<br>
		<input id="branch_entry_company_name" type="text" value="<?php echo $company_name; ?>">
		<br>

		Branch Name:
		<br>
		<input type="text" value="<?php echo $branch_name; ?>" id="branch_entry_branch_name">
		<br>

		Branch Code:
		<br>
		<input type="text" value="<?php echo $branch_code; ?>" id="branch_entry_branch_code">
		<br>

		City:
		<br>
		<input type="text" value="<?php echo $city; ?>" id="branch_entry_branch_city">
		<br>

		Address:
		<br>
		<textarea id="branch_entry_branch_address"><?php echo $address; ?></textarea>
		<br>
		
		Email ID:
		<br>
		<input id="branch_entry_branch_email" type="email" value="<?php echo $email; ?>">
		<br>

		Phone Number:
		<br>
		<input id="branch_entry_branch_phone" type="number" value="<?php echo $phone_number; ?>">
		<br>

		Registration Number:
		<br>
		<input id="branch_entry_branch_registration" type="text" value="<?php echo $registration_number; ?>">
		<br>
		
		GST Number:
		<br>
		<input type="text" value="<?php echo $gst_number; ?>" id="branch_entry_branch_gst">
		<br>

		Bank Account:
		<br>
		<input type="text" value="<?php echo $city; ?>" id="branch_entry_branch_bank">
		<br>

		<br>
		<input type="button" value="Save" id="user_save_edit_button">
		<br>
	</div>
		
	<span class="user_edit_span"></span>

<!----script----->
	<script type="text/javascript">
	//on clicking on edit button
		$('#user_save_edit_button').click(function()
		{
			var branch_id = "<?php echo $branch_id; ?>";
			
			var company_name = $('#branch_entry_company_name').val();
			var branch_name = $('#branch_entry_branch_name').val();
			var branch_code = $('#branch_entry_branch_code').val();
			var city = $('#branch_entry_branch_city').val();
			var address = $('#branch_entry_branch_address').val();
			var email = $('#branch_entry_branch_email').val();
			var phone_number = $('#branch_entry_branch_phone').val();
			var registration_number = $('#branch_entry_branch_registration').val();
			var gst_number = $('#branch_entry_branch_gst').val();
			var bank = $('#branch_entry_branch_bank').val();

			if(company_name!= "" && branch_name!= "" && branch_code!= "" & city!= "" && address!="" && email!= "" && phone_number!= "" && registration_number!="" && gst_number!= "" && bank!= "")
			{
				var query_recieved = "UPDATE branch SET company_name ='" + company_name + "', branch_name ='" + branch_name + "', branch_code = '" + branch_code + "', city = '" + city + "', address = '" + address + "', email = '" + email + "', phone_number = '" + phone_number + "', registration_number = '" + registration_number + "', gst_number = '" + gst_number + "', bank = '" + bank + "' WHERE id = '" + branch_id + "'";
				// alert(query_recieved);

				$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
				{
					if(e==1)
					{
						$('.user_edit_span').text('Successfully edited').css('color','green');
						$('#user_edit_form').fadeOut(0);
						$('.user_module_content').load('php/manage_branch.php');
					}
					else
					{
						$('.user_edit_span').text('Something went wrong while editing the branch').css('color','red');
					}
				});
			}
			else
			{
				$('.user_edit_span').text('Please fill all the details').css('color','red');
			}
		});

	</script>