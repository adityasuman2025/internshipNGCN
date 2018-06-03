<?php
	include 'connect_db.php';

	$user_id = mysqli_real_escape_string($connect_link, $_POST['user_id']);

	$edit_user_query = "SELECT * FROM inventory WHERE id='$user_id'";
	$list_user_query_run = mysqli_query($connect_link, $edit_user_query);

	$list_user_assoc = mysqli_fetch_assoc($list_user_query_run);

	$user_id = $list_user_assoc['id'];
	$brand = $list_user_assoc['brand'];
	$model_name = $list_user_assoc['model_name'];
	$model_number = $list_user_assoc['model_number'];
	$hsn_code = $list_user_assoc['hsn_code'];
	$description = $list_user_assoc['description'];
	$type = $list_user_assoc['type'];
?>

<!-----edit user form------->
	<div class="user_entry_form" id="user_edit_form">
		Brand:
		<br>
		<input id="user_entry_brand" type="text" value="<?php echo $brand; ?>">
		<br><br>
		
		Product/Part:
		<br>
		<input type="text" value="<?php echo $model_name; ?>" id="user_entry_model_name">
		<br><br>

		Product/Part Code:
		<br>
		<input type="text" value="<?php echo $model_number; ?>" id="user_entry_model_number">
		<br><br>

		HSN Code:
		<br>
		<input type="text" value="<?php echo $hsn_code; ?>" id="user_entry_hsn_code">
		<br><br>

		Description:
		<br>
		<input type="text" value="<?php echo $description; ?>" id="user_entry_description">
		<br><br>
		
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
			
			var user_id = "<?php echo $user_id;?>";
			var brand = $('#user_edit_form #user_entry_brand').val();
			var model_name = $('#user_edit_form #user_entry_model_name').val();
			var model_number = $('#user_edit_form #user_entry_model_number').val();
			var hsn_code = $('#user_edit_form #user_entry_hsn_code').val();
			var description = $('#user_edit_form #user_entry_description').val();
								
			if(brand!= "" && model_name!= "" && model_number!= "")
			{
				var query_recieved = "UPDATE inventory SET brand ='" + brand + "', model_name ='" + model_name + "', model_number = '" + model_number + "', hsn_code = '" + hsn_code + "', description = '" + description + "' WHERE id = '" + user_id + "'";
			
				$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
				{
					if(e==1)
					{
						$('.user_edit_span').text('Successfully edited').css('color','green');
						$('#user_edit_form').fadeOut(0);

						$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/manage_inventory.php');
					}
					else
					{
						$('.user_edit_span').text('Something went wrong while editing the inventory').css('color','red');
					}
				});
			}
			else
			{
				$('.user_edit_span').text('Please fill all the details').css('color','red');
			}

		});

	</script>