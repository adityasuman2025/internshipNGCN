<?php
	include 'connect_db.php';

	$return_id = mysqli_real_escape_string($connect_link, $_POST['return_id']);
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	$edit_user_query = "SELECT * FROM returns WHERE id='$return_id'";
	$list_user_query_run = mysqli_query($connect_link, $edit_user_query);

	$list_user_assoc = mysqli_fetch_assoc($list_user_query_run);

	$return_id = $list_user_assoc['id'];
	$customer = $list_user_assoc['customer'];
	$invoice_num = $list_user_assoc['invoice_num'];
	$brand = $list_user_assoc['brand'];
	$model_name = $list_user_assoc['model_name'];
	$model_number = $list_user_assoc['model_number'];
	$part_name = $list_user_assoc['part_name'];
	$part_number = $list_user_assoc['part_number'];
	$return_note = $list_user_assoc['return_note'];

	$type = $list_user_assoc['type'];
?>

<!-----edit customer form------->
	<div class="user_entry_form" id="user_edit_form">

		Customer:<br>
		<select id="return_customer">
			<option value="<?php echo $customer; ?>"><?php echo $customer; ?></option>
				<?php
					$get_brand_query = "SELECT name FROM customers WHERE creator_branch_code ='$creator_branch_code' AND name !='$customer'";
					$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

					while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
					{
						$customer_1 = $get_brand_result['name'];
						echo "<option value=\"$customer_1\">";
							echo $customer_1;
						echo "</option>";
					}
				?>	
		</select>
		<br><br>

		Invoice Number:
		<br>
		<input type="text" value="<?php echo $invoice_num; ?>" id="return_invoice_num">
		<br>
		<br>

		Brand:<br>
		<select id="return_brand">
			<option value="<?php echo $brand; ?>"><?php echo $brand; ?></option>
				<?php
					$get_brand_query = "SELECT brand FROM inventory WHERE brand != '$brand'";
					$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

					while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
					{
						$brand = $get_brand_result['brand'];
						echo "<option value=\"$brand\">";
							echo $brand;
						echo "</option>";
					}
				?>	
		</select>
		<br><br>

		Model Name:<br>
		<select id="return_model_name" disabled="disabled" style="border: 1px lightgrey solid">
			<option value="<?php echo $model_name; ?>"><?php echo $model_name; ?></option>
		</select>
		<br>
		<br>

		Model Number:<br>
		<select id="return_model_number" disabled="disabled" style="border: 1px lightgrey solid">
			<option value="<?php echo $model_number; ?>"><?php echo $model_number; ?></option>
		</select>
		<br>
		<br>

		<?php
			if( $type == "part")
			{
				echo "Part Name:<br>
						<select id=\"return_part_name\" disabled=\"disabled\" style=\"border: 1px lightgrey solid\">
							<option value=\"$part_name\">$part_name</option>
						</select>
						<br><br>

						Part Number:<br>
						<select id=\"return_part_number\" disabled=\"disabled\" style=\"border: 1px lightgrey solid\">
							<option value=\"$part_number\">$part_number</option>
						</select>
						<br><br>";
			}
		?>

		Return Note:
		<br>
		<textarea id="return_note"><?php echo $return_note; ?></textarea>
		<br>
		<br>

		<input type="button" value="Save" id="user_save_edit_button">
		<br>
	</div>
		
	<span class="user_edit_span"></span>

<!----script----->
	<script type="text/javascript">
	//on selecting a brand
		$('#return_brand').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			var brand = $(this).val();
			var query = "SELECT model_name FROM inventory WHERE brand ='" + brand + "'";
			var to_get = "model_name";
			//alert('data');
			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#return_model_name').html(data).attr('disabled', false).css('border', '1px grey solid');
			});
		});

	//on selecting a model_name
		$('#return_model_name').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			var model_name = $(this).val();
			var query = "SELECT model_number FROM inventory WHERE model_name ='" + model_name + "'";
			var to_get = "model_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#return_model_number').html(data).attr('disabled', false).css('border', '1px grey solid');
			});
		});

	//on selecting a model_number
		$('#return_model_number').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			model_number = $(this).val(); //making it universal variable to use in, on selecting a part_name
			var query = "SELECT part_name FROM inventory WHERE model_number ='" + model_number + "'";
			var to_get = "part_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#return_part_name').html(data).attr('disabled', false).css('border', '1px grey solid');
			});
		});

	//on selecting a part_name
		$('#return_part_name').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			var part_name = $(this).val();
			var query = "SELECT part_number FROM inventory WHERE part_name ='" + part_name + "' AND model_number = '" + model_number + "'";
			var to_get = "part_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#return_part_number').html(data).attr('disabled', false).css('border', '1px grey solid');
			});
		});

	//on clicking on edit button
		$('#user_save_edit_button').click(function()
		{
			$('.user_edit_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			var return_id = "<?php echo $return_id ?>";

			var customer = $('#return_customer').val();
			var invoice_num = $('#return_invoice_num').val();
			var return_brand = $('#return_brand').val();
			var return_model_name = $('#return_model_name').val();
			var return_model_number = $('#return_model_number').val();
			var return_part_name = $('#return_part_name').val();
			var return_part_number = $('#return_part_number').val();
			var return_note = $('#return_note').val();
			var return_type = "<?php echo $type; ?>";

			if(customer!= "" && invoice_num!= "" && return_brand!= "" && return_model_name!= "" & return_model_number!= "" && return_note!= "")
			{
				var query_recieved = "UPDATE returns SET customer ='" + customer + "', invoice_num ='" + invoice_num + "', brand = '" + return_brand + "', model_name = '" + return_model_name + "', model_number = '" + return_model_number + "', part_name = '" + return_part_name + "', part_number = '" + return_part_number + "', return_note = '" + return_note + "' WHERE id = '" + return_id + "'";
				
				$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
				{
					if(e==1)
					{
						$('.user_edit_span').text('Successfully edited').css('color','green');
						$('#user_edit_form').fadeOut(0);
						$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/admin_manage_return.php');
					}
					else
					{
						$('.user_edit_span').text('Something went wrong while editing the return').css('color','red');
					}
				});
			}
			else
			{
				$('.user_edit_span').text('Please fill all the details').css('color','red');
			}
			
		});

	</script>