<?php
	include('connect_db.php');
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];
?>

<!----add customer form------>
	<div class="user_entry_form">

		Customer:<br>
		<select id="return_customer">
			<option value=""></option>
				<?php
					$get_brand_query = "SELECT name FROM customers WHERE creator_branch_code ='$creator_branch_code'";
					$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

					while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
					{
						$brand = $get_brand_result['name'];
						echo "<option value=\"$brand\">";
							echo $brand;
						echo "</option>";
					}
				?>	
		</select>
		<br><br>

		<input type="text" placeholder="Invoice Number" id="return_invoice_num">
		<br>
		<br>

		Brand:<br>
		<select id="return_brand">
			<option value=""></option>
				<?php
					$get_brand_query = "SELECT brand FROM stock";
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
		<select id="return_model_name">

		</select>
		<br>
		<br>

		Model Number:<br>
		<select id="return_model_number">

		</select>
		<br>
		<br>

		<div class="inventory_tab">
			<button class="whole_unit_button">Whole Unit</button>
			<button class="parts_only_button">Parts Only</button>
		</div>
		<br><br>

		<div class="parts_only_input">
			Part Name:<br>
			<select id="return_part_name">

			</select>
			<br>
			<br>

			Part Number:<br>
			<select id="return_part_number">

			</select>
			<br>
			<br>
		</div>

		<textarea placeholder="Return Note" id="return_note"></textarea>
		<br>
		<br>

		<input type="button" value="Create Return" id="user_entry_create_button">
	</div>
	
	<span class="user_entry_span"></span>
	<br><br>
	<input type="submit" value="Create New Return" id="create_new_user_button">
	<br><br>

<!--------script-------->
	<script type="text/javascript">
	//on selecting a brand
		$('#return_brand').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			var brand = $(this).val();
			var query = "SELECT model_name FROM stock WHERE brand ='" + brand + "'";
			var to_get = "model_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#return_model_name').html(data);
			});
		});

	//on selecting a model_name
		$('#return_model_name').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			model_name = $(this).val();
			var query = "SELECT model_number FROM stock WHERE model_name ='" + model_name + "'";
			var to_get = "model_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#return_model_number').html(data);
			});
		});

	//on selecting a model_number
		$('#return_model_number').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			model_number = $(this).val(); //making it universal variable to use in, on selecting a part_name
			var query = "SELECT part_name FROM stock WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "'";
			var to_get = "part_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#return_part_name').html(data);
			});
		});

	//on selecting a part_name
		$('#return_part_name').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			var part_name = $(this).val();
			var query = "SELECT part_number FROM stock WHERE part_name ='" + part_name + "' AND model_number = '" + model_number + "' AND model_name = '" + model_name + "'";
			var to_get = "part_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#return_part_number').html(data);
			});
		});

	//switching tab b/w whole unit and parts only
		$('.whole_unit_button').click(function()
		{
			$('.parts_only_input').fadeOut(0);
		});

		$('.parts_only_button').click(function()
		{
			$('.parts_only_input').fadeIn(0);
		});

	//on clicking on create return button
		$('#user_entry_create_button').click(function()
		{
			$('.user_entry_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			var customer = $.trim($('#return_customer').val());
			var invoice_num = $.trim($('#return_invoice_num').val());
			var return_brand = $.trim($('#return_brand').val());
			var return_model_name = $.trim($('#return_model_name').val());
			var return_model_number = $.trim($('#return_model_number').val());
			var return_part_name = $.trim($('#return_part_name').val());
			var return_part_number = $.trim($('#return_part_number').val());
			var return_note = $.trim($('#return_note').val());
			var return_type = "";

		//for choosing b/w whole unit and part only
			if(return_part_name =="" && return_part_number =="")
			{
				var return_type = "whole";
			}
			else //for parts only
			{
				var return_type = "part";
			}

			if(customer!= "" && invoice_num!= "" && return_brand!= "" && return_model_name!= "" & return_model_number!= "" && return_note!= "")
			{
				$.post('php/create_return.php', {customer:customer, invoice_num:invoice_num, return_brand:return_brand, return_model_name:return_model_name, return_model_number:return_model_number, return_part_name:return_part_name, return_part_number:return_part_number, return_type:return_type, return_note:return_note}, function(e)
				{
					if(e==1)
					{
					//disappearing the user entry form
						$('.user_entry_form').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);

						$('.user_entry_span').text('Return has been successfully created').css('color','green');
						$('#create_new_user_button').fadeIn(100);

					}
					else
					{
						$('.user_entry_span').text('Something went wrong while creating return').css('color','red');
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
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_return.php');
		});

	</script>