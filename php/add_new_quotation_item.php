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

	if(isset($_POST['way']))
	{
		$way = $_POST['way'];
	}
	else
	{
		$way ="";
	}
?>

<tr id="<?php echo $new_id; ?>">
	<td>
		<select id="quotation_item_type">
			<option value=""></option>
			<option value="product">Product</option>
			<option value="part">Part</option>
			<option value="service">Service</option>
		</select>
	</td>

	<td>
		<select id="quotation_brand">
			<option value=""></option>
		</select>	
	</td>

	<td>
		<select id="quotation_model_name"></select>
	</td>

	<td>
		<select id="quotation_model_number"></select>
	</td>

	<td><input type="text" disabled="disabled" id="quotation_hsn_code"></td>
	<td><input type="text" id="quotation_description"></td>

	<?php
		if($way == "edit")
		{
			echo "<td><input type=\"text\" id=\"quotation_serial_num\"></td>	";
		}
	?>

	<td><input type="text" id="quotation_service_id"></td>

	<?php
		if($way == "edit")
		{
			echo "<td><input type=\"text\" id=\"quotation_purchase_order\"></td>	";
		}
	?>

	<td><input type="number" value="0" id="quotation_part_quantity"></td>

	<?php
		if($way == "edit")
		{
			echo "<td><input type=\"number\" disabled=\"disabled\" id=\"item_availability\"></td>";
		}
	?>

	<td><input type="number" value="0" id="quotation_part_rate"></td>
	<td><input type="number" style="width: 70px;" value="0" id="quotation_discount"></td>

	<td><input type="number" style="width: 70px;" value="0" id="quotation_part_cgst"></td>
	<td><input type="number" disabled="disabled" value="0" id="quotation_cgst_amount"></td>

	<td><input type="number" style="width: 70px;" value="0" id="quotation_part_sgst"></td>
	<td><input type="number" disabled="disabled" value="0" id="quotation_sgst_amount"></td>

	<td><input type="number" style="width: 70px;" value="0" id="quotation_part_igst"></td>
	<td><input type="number" disabled="disabled" value="0" id="quotation_igst_amount"></td>

	<td><input type="button" style="background: #cc0000; color: white; margin: 2px; width: auto;" value="calculate" id="quotation_part_total_price"></td>
	<td>
		<img class="item_delete_icon" src="img/delete.png">
	</td>

<!------script----------->
	<script type="text/javascript">
		creator_branch_code = "<?php echo $creator_branch_code; ?>";

	//on selecting a item type
		$('.quotation_entry_table tr #quotation_item_type').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			this_thing = $(this);
			type = $(this).val();
			var query = "SELECT brand FROM inventory WHERE type= '" + type + "' GROUP BY brand";
			var to_get = "brand";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#quotation_brand').html(data);
			});
		});

	//on selecting a brand
		$('.quotation_entry_table tr #quotation_brand').change(function()
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
		$('.quotation_entry_table tr #quotation_model_name').change(function()
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
		$('.quotation_entry_table tr #quotation_model_number').change(function()
		{
			$(this).attr('disabled', 'disabled');
			this_thing = $(this);
			model_number = $(this).val();

		//populating hsn code
			var query = "SELECT hsn_code FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND type= '" + type + "' ";
			var to_get = "hsn_code";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#quotation_hsn_code').val(data);
			});

		//populating description
			var query = "SELECT description FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND type= '" + type + "' ";
			var to_get = "description";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#quotation_description').val(data);
			});

		//populating availability
			var query = "SELECT in_stock FROM stock WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND type ='" + type + "' AND creator_branch_code ='" + creator_branch_code + "'";
			var to_get = "in_stock";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				if(data == '')
				{
					data = 0;
				}
				
				this_thing.parent().parent().find('#item_availability').val(data);
			});
		});

	//on entering cgst rate
		$('.quotation_entry_table tr #quotation_part_cgst').keyup(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var discount = parseInt($(this).parent().parent().find('#quotation_discount').val());

			var cgst_rate = parseInt($(this).parent().parent().find('#quotation_part_cgst').val());
			var discount_amount = discount*quantity*rate/100;

			var cgst_amount = (rate*quantity - discount_amount)*cgst_rate/100;

			$(this).parent().parent().find('#quotation_cgst_amount').val(cgst_amount);
		});

	//on entering sgst rate
		$('.quotation_entry_table tr #quotation_part_sgst').keyup(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var discount = parseInt($(this).parent().parent().find('#quotation_discount').val());

			var sgst_rate = parseInt($(this).parent().parent().find('#quotation_part_sgst').val());
			var discount_amount = discount*quantity*rate/100;

			var sgst_amount = (rate*quantity - discount_amount)*sgst_rate/100;

			$(this).parent().parent().find('#quotation_sgst_amount').val(sgst_amount);
		});

	//on entering igst rate
		$('.quotation_entry_table tr #quotation_part_igst').keyup(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var discount = parseInt($(this).parent().parent().find('#quotation_discount').val());

			var igst_rate = parseInt($(this).parent().parent().find('#quotation_part_igst').val());
			var discount_amount = discount*quantity*rate/100;

			var igst_amount = (rate*quantity - discount_amount)*igst_rate/100;

			$(this).parent().parent().find('#quotation_igst_amount').val(igst_amount);
		});

	//on clicking on calculate
		$('.quotation_entry_table tr #quotation_part_total_price').click(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var discount = parseInt($(this).parent().parent().find('#quotation_discount').val());

			var cgst_amount = parseFloat($(this).parent().parent().find('#quotation_cgst_amount').val());
			var sgst_amount = parseFloat($(this).parent().parent().find('#quotation_sgst_amount').val());
			var igst_amount =parseFloat( $(this).parent().parent().find('#quotation_igst_amount').val());

			var discount_amount = discount*quantity*rate/100;
			var price = quantity*rate;
			var total_price = quantity*rate - discount_amount +  cgst_amount + sgst_amount + igst_amount;

			$(this).val(total_price);
			//alert(total_price);
		});

	//on change of quantity, rate or gst after calculation
		$('.quotation_entry_table tr #quotation_discount').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');

		//updating gst amounts
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
			var discount = $(this).val();

			var cgst_rate = parseInt($(this).parent().parent().find('#quotation_part_cgst').val());
			var sgst_rate = parseInt($(this).parent().parent().find('#quotation_part_sgst').val());
			var igst_rate = parseInt($(this).parent().parent().find('#quotation_part_igst').val());

			var discount_amount = discount*quantity*rate/100;

			var cgst_amount = (rate*quantity - discount_amount)*cgst_rate/100;
			var sgst_amount = (rate*quantity - discount_amount)*sgst_rate/100;
			var igst_amount = (rate*quantity - discount_amount)*igst_rate/100;

			$(this).parent().parent().find('#quotation_cgst_amount').val(cgst_amount);
			$(this).parent().parent().find('#quotation_sgst_amount').val(sgst_amount);
			$(this).parent().parent().find('#quotation_igst_amount').val(igst_amount);
		});
		
		$('.quotation_entry_table tr #quotation_part_quantity').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');

		//updating gst amounts
			var quantity = $(this).val();
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var discount = parseInt($(this).parent().parent().find('#quotation_discount').val());

			var cgst_rate = parseInt($(this).parent().parent().find('#quotation_part_cgst').val());
			var sgst_rate = parseInt($(this).parent().parent().find('#quotation_part_sgst').val());
			var igst_rate = parseInt($(this).parent().parent().find('#quotation_part_igst').val());

			var discount_amount = discount*quantity*rate/100;

			var cgst_amount = (rate*quantity - discount_amount)*cgst_rate/100;
			var sgst_amount = (rate*quantity - discount_amount)*sgst_rate/100;
			var igst_amount = (rate*quantity - discount_amount)*igst_rate/100;

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
			var discount = parseInt($(this).parent().parent().find('#quotation_discount').val());

			var cgst_rate = parseInt($(this).parent().parent().find('#quotation_part_cgst').val());
			var sgst_rate = parseInt($(this).parent().parent().find('#quotation_part_sgst').val());
			var igst_rate = parseInt($(this).parent().parent().find('#quotation_part_igst').val());

			var discount_amount = discount*quantity*rate/100;

			var cgst_amount = (rate*quantity - discount_amount)*cgst_rate/100;
			var sgst_amount = (rate*quantity - discount_amount)*sgst_rate/100;
			var igst_amount = (rate*quantity - discount_amount)*igst_rate/100;

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
			var type = this_thing.parent().parent().find('#quotation_item_type').val();
			
			if(type == 'service')
			{
				$('.gen_quotation_span').text("").css('color', 'black');
			}
			else
			{
				if(quantity > in_stock)
				{
					this_thing.css('border', 'red 1px solid');
					$('.gen_quotation_span').text("You have entered a quantity greater than its avavilability in stock. You are not able to generate invoice.").css('color', 'red');
				}
				else
				{
					this_thing.css('border', 'red 0px solid');
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
