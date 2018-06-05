<?php
	include 'connect_db.php';
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	if(isset($_POST['new_id']))
	{
		$new_id = (int)$_POST['new_id'];
	}
	else
	{
		$new_id ="";
	}

	if(isset($_POST['fo']))
	{
		$fo = (int)$_POST['fo'];
	}
	else
	{
		$fo = "";
	}
?>

<tr fo="<?php echo $fo; ?>" id="<?php echo $new_id; ?>">
	<td>
		<select id="quotation_brand">
			<option value=""></option>
				<?php
					$get_brand_query = "SELECT brand FROM stock WHERE creator_branch_code ='$creator_branch_code' GROUP BY brand";
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
	</td>

	<td>
		<select id="quotation_model_name"></select>
	</td>

	<td>
		<select id="quotation_model_number"></select>
	</td>

	<td><input type="text" disabled="disabled" id="quotation_hsn_code"></td>
	<td><input type="text" disabled="disabled" id="quotation_description"></td>

	<td><input type="text" id="quotation_serial_num"></td>					
	<td><input type="text" id="quotation_service_id"></td>
	<td><input type="text" id="quotation_purchase_order"></td>
	<td><input type="number" value="0" id="quotation_part_quantity"></td>
	<td><input type="number" disabled="disabled" id="item_availability"></td>
	<td><input type="number" value="0" id="quotation_part_rate"></td>

	<td><input type="number" value="0" id="quotation_part_cgst"></td>
	<td><input type="number" disabled="disabled" value="0" id="quotation_cgst_amount"></td>

	<td><input type="number" value="0" id="quotation_part_sgst"></td>
	<td><input type="number" disabled="disabled" value="0" id="quotation_sgst_amount"></td>

	<td><input type="number" value="0" id="quotation_part_igst"></td>
	<td><input type="number" disabled="disabled" value="0" id="quotation_igst_amount"></td>

	<td><input type="button" style="background: #cc0000; color: white; margin: 2px; width: auto;" value="calculate" id="quotation_part_total_price"></td>
	<td>
		<img class="item_delete_icon" src="img/delete.png">
	</td>

<!------script----------->
	<script type="text/javascript">
		creator_branch_code = "<?php echo $creator_branch_code; ?>";

	//on selecting a brand
		$('.quotation_entry_table tr #quotation_brand').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			this_thing = $(this);
			brand = $(this).val();
			var query = "SELECT model_name FROM stock WHERE brand ='" + brand + "' AND creator_branch_code ='" + creator_branch_code + "' GROUP BY model_name";
			var to_get = "model_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				this_thing.parent().parent().find('#quotation_model_name').html(data);
			});
		});

	//on selecting a model_name
		$('.quotation_entry_table tr #quotation_model_name').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			this_thing = $(this);
			model_name = $(this).val();
			var query = "SELECT model_number FROM stock WHERE model_name ='" + model_name + "'AND brand = '" + brand + "' AND creator_branch_code ='" + creator_branch_code + "' GROUP BY model_number";
			var to_get = "model_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				this_thing.parent().parent().find('#quotation_model_number').html(data);
			});
		});

	//on selecting a model_number
		$('.quotation_entry_table tr #quotation_model_number').change(function()
		{
			$(this).attr('disabled', 'disabled');
			this_thing = $(this);
			model_number = $(this).val();

		//populating hsn code
			var query = "SELECT hsn_code FROM stock WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND creator_branch_code ='" + creator_branch_code + "'";
			var to_get = "hsn_code";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#quotation_hsn_code').val(data);
			});

		//populating description
			var query = "SELECT description FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "'";
			var to_get = "description";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#quotation_description').val(data);
			});

		//populating availability
			var query = "SELECT in_stock FROM stock WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND creator_branch_code ='" + creator_branch_code + "'";
			var to_get = "in_stock";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#item_availability').val(data);
			});
		});

	//on entering cgst rate
		$('.quotation_entry_table tr #quotation_part_cgst').keyup(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var cgst_rate = parseInt($(this).parent().parent().find('#quotation_part_cgst').val());

			var cgst_amount = (rate*cgst_rate/100)*quantity;

			$(this).parent().parent().find('#quotation_cgst_amount').val(cgst_amount);
		});

	//on entering sgst rate
		$('.quotation_entry_table tr #quotation_part_sgst').keyup(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var sgst_rate = parseInt($(this).parent().parent().find('#quotation_part_sgst').val());

			var sgst_amount = (rate*sgst_rate/100)*quantity;

			$(this).parent().parent().find('#quotation_sgst_amount').val(sgst_amount);
		});

	//on entering igst rate
		$('.quotation_entry_table tr #quotation_part_igst').keyup(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var igst_rate = parseInt($(this).parent().parent().find('#quotation_part_igst').val());

			var igst_amount = (rate*igst_rate/100)*quantity;

			$(this).parent().parent().find('#quotation_igst_amount').val(igst_amount);
		});

	//on clicking on calculate
		$('.quotation_entry_table tr #quotation_part_total_price').click(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var cgst = parseInt($(this).parent().parent().find('#quotation_part_cgst').val());
			var sgst = parseInt($(this).parent().parent().find('#quotation_part_sgst').val());
			var igst = parseInt($(this).parent().parent().find('#quotation_part_igst').val());

			var total_price = (rate + (rate * (cgst+sgst+igst)/100))*quantity;

			$(this).val(total_price);
			//alert(total_price);
		});

	//on change of quantity, rate or gst after calculation
		$('.quotation_entry_table tr #quotation_part_quantity').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');

		//updating gst amounts
			var quantity = $(this).val();
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());

			var cgst_rate = parseInt($(this).parent().parent().find('#quotation_part_cgst').val());
			var sgst_rate = parseInt($(this).parent().parent().find('#quotation_part_sgst').val());
			var igst_rate = parseInt($(this).parent().parent().find('#quotation_part_igst').val());

			var cgst_amount = (rate*cgst_rate/100)*quantity;
			var sgst_amount = (rate*sgst_rate/100)*quantity;
			var igst_amount = (rate*igst_rate/100)*quantity;

			$(this).parent().parent().find('#quotation_cgst_amount').val(cgst_amount);
			$(this).parent().parent().find('#quotation_sgst_amount').val(sgst_amount);
			$(this).parent().parent().find('#quotation_igst_amount').val(igst_amount);
		});

		$('.quotation_entry_table tr #quotation_part_rate').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');

		//updating gst amounts
			var rate = $(this).val();
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());

			var cgst_rate = parseInt($(this).parent().parent().find('#quotation_part_cgst').val());
			var sgst_rate = parseInt($(this).parent().parent().find('#quotation_part_sgst').val());
			var igst_rate = parseInt($(this).parent().parent().find('#quotation_part_igst').val());

			var cgst_amount = (rate*cgst_rate/100)*quantity;
			var sgst_amount = (rate*sgst_rate/100)*quantity;
			var igst_amount = (rate*igst_rate/100)*quantity;

			$(this).parent().parent().find('#quotation_cgst_amount').val(cgst_amount);
			$(this).parent().parent().find('#quotation_sgst_amount').val(sgst_amount);
			$(this).parent().parent().find('#quotation_igst_amount').val(igst_amount);
		});

		$('.quotation_entry_table tr #quotation_part_cgst').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

		$('.quotation_entry_table tr #quotation_part_sgst').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

		$('.quotation_entry_table tr #quotation_part_igst').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

	//checking the availability of that product or part in stock
		$('.quotation_entry_table tr #quotation_part_quantity').keyup(function()
		{
			$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");

			this_thing = $(this);			
			var quantity = parseInt($(this).val());
			var in_stock = parseInt(this_thing.parent().parent().find('#item_availability').val());

			var fo = this_thing.parent().parent().attr('fo');

			if(quantity > in_stock)
			{
				this_thing.css('border', 'red 1px solid');
				$('.gen_quotation_span').text("You have entered a quantity greater than its avavilability in stock. Please change the quantity otherwise you are not able to create invoice.").css('color', 'red');

				$('#quotation_gen_button').addClass(fo).fadeOut(0);
			}
			else
			{
				this_thing.css('border', 'red 0px solid');
				
				$('#quotation_gen_button').removeClass(fo);
				var class_length = parseInt($('#quotation_gen_button').attr('class').length);

				if(class_length > 0)
				{
					$('#quotation_gen_button').fadeOut(0);
					$('.gen_quotation_span').text("You have entered a quantity greater than its avavilability in stock. Please change the quantity otherwise you are not able to create invoice.").css('color', 'red');
				}
				else
				{
					$('#quotation_gen_button').fadeIn(0);
					$('.gen_quotation_span').text("").css('color', 'black');
				}
			}	
		});

	//on clicking on delete goods button
		$('.quotation_entry_table tr .item_delete_icon').click(function()
		{
			$(this).parent().parent().remove();
		});
	
	</script>
</tr>
