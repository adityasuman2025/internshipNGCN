<?php
	include 'connect_db.php';

	$supplier_id = mysqli_real_escape_string($connect_link, $_POST['supplier_id']);

	$edit_user_query = "SELECT * FROM supplier WHERE id='$supplier_id'";
	$list_user_query_run = mysqli_query($connect_link, $edit_user_query);

	$list_user_assoc = mysqli_fetch_assoc($list_user_query_run);

	$supplier_id = $list_user_assoc['id'];
	$name = $list_user_assoc['name'];
	$email = $list_user_assoc['email'];
	$mobile = $list_user_assoc['mobile'];
	$address = $list_user_assoc['address'];
	$details = $list_user_assoc['details'];
	$type_of_product = $list_user_assoc['name_of_product'];

?>

<!-----edit customer form------->
	<div class="user_entry_form" id="user_edit_form">
		Name:
		<br>
		<input id="user_entry_username" type="text" value="<?php echo $name; ?>">
		<br>

		Email:
		<br>
		<input type="email" value="<?php echo $email; ?>" id="user_entry_email">
		<br>

		Mobile:
		<br>
		<input type="number" value="<?php echo $mobile; ?>" id="user_entry_mobile">
		<br>

		Address:
		<br>
		<textarea id="user_entry_address"><?php echo $address; ?></textarea>
		<br>
		
		Details:
		<br>
		<input id="user_entry_details" type="text" value="<?php echo $details; ?>">
		<br>

		Types of Product:
		<br>
		<input id="user_entry_type" type="text" value="<?php echo $type_of_product; ?>">
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
			var supplier_id = "<?php echo $supplier_id;?>";
			var name = $('#user_edit_form #user_entry_username').val();
			var email = $('#user_edit_form #user_entry_email').val();
			var mobile = $('#user_edit_form #user_entry_mobile').val();
			var address = $('#user_edit_form #user_entry_address').val();
			var details = $('#user_edit_form #user_entry_details').val();
			var type_of_product = $('#user_edit_form #user_entry_type').val();
			

			if(name!= "" && email!= "" && mobile!= "" & details!= "" && address!="" && type_of_product!= "")
			{
				var query_recieved = "UPDATE supplier SET name ='" + name + "', email ='" + email + "', mobile = '" + mobile + "', details = '" + details + "', address = '" + address + "', name_of_product = '" + type_of_product + "' WHERE id = '" + supplier_id + "'";
				// alert(query_recieved);

				$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
				{
					if(e==1)
					{
						$('.user_edit_span').text('Successfully edited').css('color','green');
						$('#user_edit_form').fadeOut(0);
						$('.user_module_content').load('php/manage_supplier.php');
					}
					else
					{
						$('.user_edit_span').text('Something went wrong while editing the supplier').css('color','red');
					}
				});
			}
			else
			{
				$('.user_edit_span').text('Please fill all the details').css('color','red');
			}
		});

	</script>