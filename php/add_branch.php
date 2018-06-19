<!----add customer form------>
	<h3>Add Branch</h3>

	<div class="user_entry_form">
		<input id="branch_entry_company_name" type="text" placeholder="Company Name">
		<br>
		<br>
		<input type="text" placeholder="Branch Name" id="branch_entry_branch_name">
		<br>
		<br>
		<input type="text" placeholder="Branch Code" id="branch_entry_branch_code">
		<br>
		<br>
		<input type="text" placeholder="City" id="branch_entry_branch_city">
		<br>
		<br>
		<textarea placeholder="Address" id="branch_entry_branch_address"></textarea>
		<br>
		<br>
		<input id="branch_entry_branch_email" type="email" placeholder="Email ID">
		<br>
		<br>
		<input id="branch_entry_branch_phone" type="number" placeholder="Phone Number">
		<br>
		<br>
		<input id="branch_entry_branch_registration" type="text" placeholder="Registration Number">
		<br>
		<br>
		<input id="branch_entry_branch_gst" type="text" placeholder="GST Number">
		<br>
		<br>

		<input id="branch_entry_bank_accnt_name" type="text" placeholder="Bank Account Name">
		<br>
		<br>

		<input id="branch_entry_bank_accnt_no" type="text" placeholder="Bank Account Number">
		<br>
		<br>

		<input id="branch_entry_bank_name" type="text" placeholder="Bank Name">
		<br>
		<br>

		<input id="branch_entry_bank_ifsc" type="text" placeholder="Bank IFS Code">
		<br>
		<br>
		<input type="button" value="Create Branch" id="user_entry_create_button">
	</div>
	
	<span class="user_entry_span"></span>
	<br><br>
	<input type="submit" value="Create New Branch" id="create_new_user_button">
	<br><br>

<!--------script-------->
	<script type="text/javascript">
	//on clicking on create user button
		$('#user_entry_create_button').click(function()
		{
			$('.user_entry_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			var company_name = $.trim($('#branch_entry_company_name').val());
			var branch_name = $.trim($('#branch_entry_branch_name').val());
			var branch_code = $.trim($('#branch_entry_branch_code').val());
			var city = $.trim($('#branch_entry_branch_city').val());
			var address = $.trim($('#branch_entry_branch_address').val());
			var email = $.trim($('#branch_entry_branch_email').val());
			var phone_number = $.trim($('#branch_entry_branch_phone').val());
			var registration_number = $.trim($('#branch_entry_branch_registration').val());
			var gst_number = $.trim($('#branch_entry_branch_gst').val());

			var bank_accnt_name = $.trim($('#branch_entry_bank_accnt_name').val());
			var bank_accnt_no = $.trim($('#branch_entry_bank_accnt_no').val());
			var bank_name = $.trim($('#branch_entry_bank_name').val());
			var bank_ifsc = $.trim($('#branch_entry_bank_ifsc').val());

			if(company_name!= "" && branch_name!= "" && branch_code!= "" && city!= "" & address!= "" && phone_number!= "" && bank_accnt_no!= "")
			{
				$.post('php/create_branch.php', {company_name:company_name, branch_name:branch_name, branch_code:branch_code, city:city, address:address, email:email, phone_number:phone_number, registration_number:registration_number, gst_number:gst_number, bank_accnt_name:bank_accnt_name, bank_accnt_no:bank_accnt_no, bank_name:bank_name, bank_ifsc:bank_ifsc}, function(e)
				{
					if(e==1)
					{
					//disappearing the user entry form
						$('.user_entry_form').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);
						
						$('.user_entry_span').text('Branch has been successfully created').css('color','green');
						$('#create_new_user_button').fadeIn(100);
					}
					else
					{
						$('.user_entry_span').text('Something went wrong while creating the branch').css('color','red');
					}
				});
			}
			else
			{
				$('.user_entry_span').text('Please fill all the details').css('color','red');
			}
			
		});

	//on clicking on create new user button
		$('#create_new_user_button').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_branch.php');
		});

	</script>