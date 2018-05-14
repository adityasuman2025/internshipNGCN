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
	$part_name = $list_user_assoc['part_name'];
	$part_number = $list_user_assoc['part_number'];
	$part_desc = $list_user_assoc['part_desc'];
	$type = $list_user_assoc['type'];
	
?>

<!-----edit user form------->
	<div class="user_entry_form" id="user_edit_form">
		Brand:
		<br>
		<input id="user_entry_brand" type="text" value="<?php echo $brand; ?>">
		<br><br>
		
		Model Name:
		<br>
		<input type="text" value="<?php echo $model_name; ?>" id="user_entry_model_name">
		<br><br>

		Model Number:
		<br>
		<input type="text" value="<?php echo $model_number; ?>" id="user_entry_model_number">
		<br><br>

		<?php
			if($type =="whole")
			{

			}
			else if($type = "part")
			{
				echo "	Part Name:
						<br>
						<input id=\"user_entry_part_name\" type=\"text\" value=\"$part_name\">
						<br><br>
						
						Part Number:
						<br>
						<input type=\"text\" value=\"$part_number\" id=\"user_entry_part_number\">
						<br><br>

						Part Description:
						<br>
						<input type=\"text\" value=\"$part_desc\" id=\"user_entry_part_desc\">
						<br><br>";
			}

		?>
		
		<input type="button" value="Save" id="user_save_edit_button">
		<br>
	</div>
		
	<span class="user_edit_span"></span>

<!----script----->
	<script type="text/javascript">
	//on clicking on edit button
		$('#user_save_edit_button').click(function()
		{
			var user_id = "<?php echo $user_id;?>";
			var brand = $('#user_edit_form #user_entry_brand').val();
			var model_name = $('#user_edit_form #user_entry_model_name').val();
			var model_number = $('#user_edit_form #user_entry_model_number').val();
			var part_name = $('#user_edit_form #user_entry_part_name').val();
			var part_number = $('#user_edit_form #user_entry_part_number').val();
			var part_desc = $('#user_edit_form #user_entry_part_desc').val();
								
			if(brand!= "" && model_name!= "" && model_number!= "")
			{
				var query_recieved = "UPDATE inventory SET brand ='" + brand + "', model_name ='" + model_name + "', model_number = '" + model_number + "', part_name = '" + part_name + "', part_number = '" + part_number + "', part_desc = '" + part_desc + "' WHERE id = '" + user_id + "'";
			
				$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
				{
					if(e==1)
					{
						$('.user_edit_span').text('Successfully edited').css('color','green');
						$('#user_edit_form').fadeOut(0);

						$('.user_module_content').load('php/manage_inventory.php');
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