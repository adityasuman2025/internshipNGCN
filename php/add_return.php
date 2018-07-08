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
					$get_brand_query = "SELECT * FROM customers WHERE creator_branch_code = '$creator_branch_code' ORDER BY id DESC";
					$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

					while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
					{
						$company_name = $get_brand_result['company_name'];
						$name = $get_brand_result['name'];

						echo "<option value=\"$name\" customer_company=\"$company_name\">";
							echo $name . ", $company_name";
						echo "</option>";
					}
				?>	
		</select>
		<br><br>

		<input type="text" placeholder="Invoice Number" id="return_invoice_num">
		<br><br>

		Type<br>
		<select id="quotation_item_type">
			<option value=""></option>
			<option value="product">Product</option>
			<option value="part">Part</option>
			<option value="service">Service</option>
		</select>
		<br><br>

		Brand:<br>
		<select id="quotation_brand">
			<option value=""></option>
		</select>	
		<br><br>

		Product/Part Name:<br>
		<select id="quotation_model_name"></select>
		<br><br>

		Product/Part Code:<br>
		<select id="quotation_model_number"></select>
		<br><br>

		HSN Code:<br>
		<input type="text" disabled="disabled" id="quotation_hsn_code">
		<br><br>

		<textarea placeholder="Return Note" id="return_note"></textarea>
		<br><br>

		<input type="button" value="Create Return" id="user_entry_create_button">
	</div>
	
	<span class="user_entry_span"></span>
	<br><br>
	<input type="submit" value="Create New Return" id="create_new_user_button">
	<br><br>

<!--------script-------->
	<script type="text/javascript">
	//on selecting a item type
		$('.user_entry_form #quotation_item_type').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			this_thing = $(this);
			type = $(this).val();
			var query = "SELECT brand FROM inventory WHERE type= '" + type + "' GROUP BY brand";
			var to_get = "brand";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				this_thing.parent().find('#quotation_brand').html(data);
			});
		});

	//on selecting a brand
		$('.user_entry_form #quotation_brand').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			this_thing = $(this);
			brand = $(this).val();
			var query = "SELECT model_name FROM inventory WHERE brand ='" + brand + "' AND type= '" + type + "' GROUP BY model_name";
			var to_get = "model_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				this_thing.parent().find('#quotation_model_name').html(data);
			});
		});

	//on selecting a model_name
		$('.user_entry_form #quotation_model_name').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			this_thing = $(this);
			model_name = $(this).val();
			var query = "SELECT model_number FROM inventory WHERE model_name ='" + model_name + "' AND brand ='" + brand + "' AND type= '" + type + "'  GROUP BY model_number";
			var to_get = "model_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				this_thing.parent().find('#quotation_model_number').html(data);
			});
		});

	//on selecting a model_number
		$('.user_entry_form #quotation_model_number').change(function()
		{
			$(this).attr('disabled', 'disabled');
			this_thing = $(this);
			model_number = $(this).val();

		//populating hsn code
			var query = "SELECT hsn_code FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND type= '" + type + "' ";
			var to_get = "hsn_code";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().find('#quotation_hsn_code').val(data);
			});
		});

	//on clicking on create return button
		$('#user_entry_create_button').click(function()
		{
			$('.user_entry_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			var customer = $.trim($('#return_customer').val());
			var customer_company = $.trim($('#return_customer option:selected').attr('customer_company'));

			var invoice_num = $.trim($('#return_invoice_num').val());

			var return_type = $.trim($('#quotation_item_type').val());
			var return_brand = $.trim($('#quotation_brand').val());
			var return_model_name = $.trim($('#quotation_model_name').val());
			var return_model_number = $.trim($('#quotation_model_number').val());
			var return_hsn_code = $.trim($('#quotation_hsn_code').val());

			var return_note = $.trim($('#return_note').val());

			if(customer!= "" && invoice_num!= "" && return_brand!= "" && quotation_model_name!= "")
			{
				$.post('php/create_return.php', {customer:customer, customer_company: customer_company, invoice_num: invoice_num, return_brand: return_brand, return_model_name: return_model_name, return_model_number: return_model_number, return_hsn_code: return_hsn_code, return_type: return_type, return_note: return_note}, function(e)
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