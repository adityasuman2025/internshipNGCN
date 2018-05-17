<?php
	include 'connect_db.php';

	$customer_id = mysqli_real_escape_string($connect_link, $_POST['customer_id']);

	$edit_user_query = "SELECT * FROM customers WHERE id='$customer_id'";
	$list_user_query_run = mysqli_query($connect_link, $edit_user_query);

	$list_user_assoc = mysqli_fetch_assoc($list_user_query_run);

	$customer_id = $list_user_assoc['id'];
	$name = $list_user_assoc['name'];
	$email = $list_user_assoc['email'];
	$mobile = $list_user_assoc['mobile'];
	$address = $list_user_assoc['address'];
	$pan = $list_user_assoc['pan'];
	$gst = $list_user_assoc['gst'];
	$shipping_address = $list_user_assoc['shipping_address'];

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
		
		Pan Number:
		<br>
		<input id="user_entry_pan" type="text" value="<?php echo $pan; ?>">
		<br>

		GST Number:
		<br>
		<input id="user_entry_gst" type="text" value="<?php echo $gst; ?>">
		<br>

		Shipping Address:
		<br>
		<input id="user_entry_shipping_address" type="text" value="<?php echo $shipping_address; ?>">
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
			$('.user_edit_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			var customer_id = "<?php echo $customer_id;?>";
			var name = $('#user_edit_form #user_entry_username').val();
			var email = $('#user_edit_form #user_entry_email').val();
			var mobile = $('#user_edit_form #user_entry_mobile').val();
			var address = $('#user_edit_form #user_entry_address').val();
			var pan = $('#user_edit_form #user_entry_pan').val();
			var gst = $('#user_edit_form #user_entry_gst').val();
			var shipping_address = $('#user_edit_form #user_entry_shipping_address').val();

			if(name!= "" && email!= "" && mobile!= "" & pan!= "" && address!="" && gst!= "" && shipping_address!= "" )
			{
				var query_recieved = "UPDATE customers SET name ='" + name + "', email ='" + email + "', mobile = '" + mobile + "', pan = '" + pan + "', address = '" + address + "', gst = '" + gst + "', shipping_address = '" + shipping_address + "' WHERE id = '" + customer_id + "'";
				// alert(query_recieved);

				$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
				{
					if(e==1)
					{
						$('.user_edit_span').text('Successfully edited').css('color','green');
						$('#user_edit_form').fadeOut(0);
						$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/manage_customer.php');
					}
					else
					{
						$('.user_edit_span').text('Something went wrong while editing the customer').css('color','red');
					}
				});
			}
			else
			{
				$('.user_edit_span').text('Please fill all the details').css('color','red');
			}
		});

	</script>