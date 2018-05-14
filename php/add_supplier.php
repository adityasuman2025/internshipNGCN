<!----add customer form------>
	<div class="user_entry_form">
		<input id="user_entry_username" type="text" placeholder="Supplier Name">
		<br>
		<br>
		<input type="email" placeholder="Supplier Email" id="user_entry_email">
		<br>
		<br>
		<input type="number" placeholder="Supplier Mobile" id="user_entry_mob">
		<br>
		<br>
		<textarea placeholder="Supplier Address" id="user_entry_address"></textarea>
		<br>
		<br>
		<textarea placeholder="Supplier Details" id="user_entry_details"></textarea>
		<br><br>
		write name of all the products seperated by comma
		<br>
		<input id="user_entry_type" type="text" placeholder="Name of Product">
		<br><br>
		
		<input type="button" value="Add Supplier" id="user_entry_create_button">
	</div>
	
	<span class="user_entry_span"></span>
	<br><br>
	<input type="submit" value="Create New Customer" id="create_new_user_button">
	<br><br>

<!--------script-------->
	<script type="text/javascript">
	//on clicking on create user button
		$('#user_entry_create_button').click(function()
		{
			var name = $('#user_entry_username').val();
			var email = $('#user_entry_email').val();
			var mobile = $('#user_entry_mob').val();
			var address = $('#user_entry_address').val();
			var details = $('#user_entry_details').val();
			var type = $('#user_entry_type').val();

			if(name!= "" && email!= "" && mobile!= "" && address!= "" & details!= "" && type!="")
			{
				$.post('php/create_supplier.php', {name:name, email:email, mobile:mobile, address:address, details:details, type:type}, function(e)
				{
					if(e==1)
					{
						$('.user_entry_span').text('Supplier has been successfully added').css('color','green');
						$('#create_new_user_button').fadeIn(100);

					//disappearing the user entry form
						$('.user_entry_form').fadeOut(0);
					}
					else
					{
						$('.user_entry_span').text('Something went wrong while adding supplier').css('color','red');
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
			$('.user_module_content').load('php/add_supplier.php');
		});

	</script>