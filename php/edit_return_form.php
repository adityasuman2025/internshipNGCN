<?php
	include 'connect_db.php';

	//checking if user is admin or not
	$isadmin = $_COOKIE['isadmin'];
	if($isadmin == 1)//is admin
	{
		$redirect_page = "php/admin_manage_return.php";
	}
	else if($isadmin == 0) //not admin
	{
		$redirect_page = "php/manage_return.php";
	}

	$return_id = mysqli_real_escape_string($connect_link, $_POST['return_id']);
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	$edit_user_query = "SELECT * FROM returns WHERE id='$return_id'";
	$list_user_query_run = mysqli_query($connect_link, $edit_user_query);

	$list_user_assoc = mysqli_fetch_assoc($list_user_query_run);

	$return_id = $list_user_assoc['id'];
	$customer = $list_user_assoc['customer'];
	echo $customer_company = $list_user_assoc['customer_company'];

	$invoice_num = $list_user_assoc['invoice_num'];

	$type = $list_user_assoc['type'];
	$brand = $list_user_assoc['brand'];
	$model_name = $list_user_assoc['model_name'];
	$model_number = $list_user_assoc['model_number'];
	$hsn_code = $list_user_assoc['hsn_code'];

	$return_note = $list_user_assoc['return_note'];

?>

<!-----edit customer form------->
	<div class="user_entry_form" id="user_edit_form">

		Customer:<br>
		<select id="return_customer">
			<option customer_company= "<?php echo $customer_company; ?>" value = "<?php echo $customer; ?>"><?php echo $customer . ", " . $customer_company; ?></option>
				<?php
					$get_brand_query = "SELECT * FROM customers WHERE creator_branch_code = '$creator_branch_code' AND name !='$customer' AND company_name !='$customer_company' ORDER BY id DESC";
					$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

					while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
					{
						$company_name = $get_brand_result['company_name'];
						$name = $get_brand_result['name'];
						$email = $get_brand_result['email'];

						echo "<option value=\"$name\" email=\"$email\" customer_company=\"$company_name\">";
							echo $name . ", $company_name";
						echo "</option>";
					}
				?>	
		</select>
		<br><br>

		Invoice Number:<br>
		<input type="text" value="<?php echo $invoice_num; ?>" id="return_invoice_num">
		<br><br>

		Type:<br>
		<select id="quotation_item_type" >
			<option value="<?php echo $type; ?>"><?php echo $type; ?></option>
				<?php
					if($type == "product")
					{
						echo "<option value=\"part\">Part</option>";
						echo "<option value=\"service\">Service</option>";
					}
					else if($type == "part")
					{
						echo "<option value=\"product\">Product</option>";
						echo "<option value=\"service\">Service</option>";
					}
					else if($type == "service")
					{
						echo "<option value=\"product\">Product</option>";
						echo "<option value=\"part\">Part</option>";
					}
					else
					{
						echo "<option value=\"product\">Product</option>";
						echo "<option value=\"part\">Part</option>";
						echo "<option value=\"service\">Service</option>";
					}
				?>
		</select>
		<br><br>

		Brand:<br>
		<select id="quotation_brand">
			<option value="<?php echo $brand; ?>"><?php echo $brand; ?></option>
		</select>
		<br><br>

		Product/Part Name:<br>
		<select id="quotation_model_name">
			<option value="<?php echo $model_name; ?>"><?php echo $model_name; ?></option>
		</select>
		<br><br>

		Product/Part Code:<br>
		<select id="quotation_model_number">
			<option value="<?php echo $model_number; ?>"><?php echo $model_number; ?></option>
		</select>
		<br><br>

		HSN Code:<br>
		<input id="quotation_hsn_code" disabled="disabled" type="text" value="<?php echo $hsn_code; ?>">
		<br><br>

		Return Note:
		<br>
		<textarea id="return_note"><?php echo $return_note; ?></textarea>
		<br><br>

		<input type="button" value="Save" id="user_save_edit_button">
		<br>
	</div>
		
	<span class="user_edit_span"></span>

<!----script----->
	<script type="text/javascript">
	//on selecting a item type
		$('.user_entry_form #quotation_item_type').change(function()
		{
		//emptying all the preceeding fields
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');
			$('#quotation_brand').html("");
			$('#quotation_model_name').html("");
			$('#quotation_model_number').html("");
			$('#quotation_hsn_code').val("");	

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
				this_thing.parent().parent().find('#quotation_model_name').html(data);
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
				this_thing.parent().parent().find('#quotation_model_number').html(data);
			});
		});

	//on selecting a model_number
		$('.user_entry_form #quotation_model_number').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');
			this_thing = $(this);
			model_number = $(this).val();

		//populating hsn code
			var query = "SELECT hsn_code FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND type= '" + type + "' ";
			var to_get = "hsn_code";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#quotation_hsn_code').val(data).css('border', '1px solid lightgrey');
			});
		});

	//on clicking on edit button
		$('#user_save_edit_button').click(function()
		{
			$('.user_edit_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			var redirect_page = $.trim("<?php echo $redirect_page; ?>");
			
			var return_id = "<?php echo $return_id ?>";

			var customer = $.trim($('#return_customer').val());
			var customer_company = $.trim($('#return_customer option:selected').attr('customer_company'));

			var invoice_num = $.trim($('#return_invoice_num').val());

			var return_type = $.trim($('#quotation_item_type').val());
			var return_brand = $.trim($('#quotation_brand').val());
			var return_model_name = $.trim($('#quotation_model_name').val());
			var return_model_number = $.trim($('#quotation_model_number').val());
			var return_hsn_code = $.trim($('#quotation_hsn_code').val());

			var return_note = $.trim($('#return_note').val());

			if(customer!= "" && invoice_num!= "" && return_brand!= "")
			{
				var query_recieved = "UPDATE returns SET customer ='" + customer + "', customer_company = '" + customer_company + "', invoice_num ='" + invoice_num + "', brand = '" + return_brand + "', model_name = '" + return_model_name + "', model_number = '" + return_model_number + "', hsn_code = '" + return_hsn_code + "', type = '" + return_type + "', return_note = '" + return_note + "' WHERE id = '" + return_id + "'";
				
				$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
				{
					if(e==1)
					{
						$('.user_edit_span').text('Successfully edited').css('color','green');
						$('#user_edit_form').fadeOut(0);
						$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load(redirect_page);
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