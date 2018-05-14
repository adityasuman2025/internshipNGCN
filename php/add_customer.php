<!----add customer form------>
	<div class="user_entry_form">
		<input id="user_entry_username" type="text" placeholder="Customer Name">
		<br>
		<br>
		<input type="email" placeholder="Customer Email" id="user_entry_email">
		<br>
		<br>
		<input type="number" placeholder="Mobile Number" id="user_entry_mob">
		<br>
		<br>
		<textarea placeholder="Address" id="user_entry_address"></textarea>
		<br>
		<br>
		<input id="user_entry_pan" type="text" placeholder="Pan Number">
		<br>
		<br>
		<input id="user_entry_gst" type="text" placeholder="GST Number">
		<br>
		<br>
		<textarea placeholder="Shipping Address" id="user_entry_shipping_address"></textarea>
		<br>
		<br>
		<input type="button" value="Create Customer" id="user_entry_create_button">
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
			var pan = $('#user_entry_pan').val();
			var gst = $('#user_entry_gst').val();
			var shipping_address = $('#user_entry_shipping_address').val();

			if(name!= "" && email!= "" && mobile!= "" && address!= "" & pan!= "" && gst!="" && shipping_address!= "")
			{
				$.post('php/create_customer.php', {name:name, email:email, mobile:mobile, address:address, pan:pan, gst:gst, shipping_address:shipping_address}, function(e)
				{
					if(e==1)
					{
						$('.user_entry_span').text('Customer has been successfully created').css('color','green');
						$('#create_new_user_button').fadeIn(100);

					//disappearing the user entry form
						$('.user_entry_form').fadeOut(0);
					}
					else
					{
						$('.user_entry_span').text('Something went wrong while creating customer').css('color','red');
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
			$('.user_module_content').load('php/add_customer.php');
		});

	</script>