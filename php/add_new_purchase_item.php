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

?>

<tr id="<?php echo $new_id; ?>">
	<td>
		<select id="purchase_type">
			<option value=""></option>
			<option value="product">Product</option>
			<option value="part">Part</option>
			<option value="service">Service</option>		
		</select>
	</td>

	<td>
		<select id="purchase_brand">
			<option value=""></option>
		</select>
	</td>

	<td>
		<select id="purchase_model_name">
			<option value=""></option>
		</select>
	</td>

	<td>
		<select id="purchase_model_num">
			<option value=""></option>
		</select>
	</td>

	<td><input type="text" disabled="disabled" id="purchase_hsn_code"></td>
	<td><input type="text" id="purchase_description"></td>

	<td><input type="number" value="0" placeholder="Quantity" id="purchase_quantity"></td>
	<td><input type="number" value="0" placeholder="Rate per unit" id="purchase_rate"></td>
	<td><input type="number" value="0" placeholder="CGST Rate" id="purchase_cgst_rate"></td>
	<td><input type="number" disabled="disabled" value="0" placeholder="CGST Amount" id="purchase_cgst_amount"></td>
	<td><input type="number" value="0" placeholder="SGST Rate" id="purchase_sgst_rate"></td>
	<td><input type="number" disabled="disabled" value="0" placeholder="SGST Amount" id="purchase_sgst_amount"></td>
	<td><input type="number" value="0" placeholder="IGST Rate" id="purchase_igst_rate"></td>
	<td><input type="number" value="0" disabled="disabled" placeholder="IGST Amount" id="purchase_igst_amount"></td>

	<td><input type="button" style="background: #cc0000; color: white; margin: 2px; width: auto;" value="calculate" id="purchase_total_amount"></td>

	<td>
		<img class="item_delete_icon" src="img/delete.png">
	</td>

<!------script----------->
	<script type="text/javascript">
	//on selecting a type
		$('.purchase_entry_table tr #purchase_type').change(function()
		{
			type = $(this).val();
			this_thing = $(this);
			$(this).attr('disabled', true);

		//populating product/part according to the selected brand
			var query = "SELECT brand FROM inventory WHERE type ='" + type + "' GROUP BY brand";
			var to_get = "brand";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#purchase_brand').html(data);
			});
		});

	//on selecting a brand
		$('.purchase_entry_table tr #purchase_brand').change(function()
		{
			brand = $(this).val();
			this_thing = $(this);
			$(this).attr('disabled', true);

		//populating product/part according to the selected brand
			var query = "SELECT model_name FROM inventory WHERE brand ='" + brand + "' AND type = '" + type + "' GROUP BY model_name";
			var to_get = "model_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#purchase_model_name').html(data);
			});

		});
	
	//on selecting a model_name
		$('.purchase_entry_table tr #purchase_model_name').change(function()
		{
			$(this).attr('disabled', 'disabled');
			this_thing = $(this);
			model_name = $(this).val();

		//populating product/part code according to the selected brand	
			var query = "SELECT model_number FROM inventory WHERE model_name ='" + model_name + "' AND brand ='" + brand + "' AND type = '" + type + "' GROUP BY model_number";
			var to_get = "model_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#purchase_model_num').html(data);
			});
		});

	//on selecting a model_number
		$('.purchase_entry_table tr #purchase_model_num').change(function()
		{
			$(this).attr('disabled', 'disabled');
			this_thing = $(this);
			model_number = $(this).val();

		//populating hsn code
			var query = "SELECT hsn_code FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND type = '" + type + "'";
			var to_get = "hsn_code";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#purchase_hsn_code').val(data);
			});

		//populating description
			var query = "SELECT description FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND type = '" + type + "'";
			var to_get = "description";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#purchase_description').val(data);
			});
		});

	//on entering cgst rate
		$('.purchase_entry_table tr #purchase_cgst_rate').keyup(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#purchase_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#purchase_rate').val());
			var cgst_rate = parseInt($(this).parent().parent().find('#purchase_cgst_rate').val());

			var cgst_amount = (rate*cgst_rate/100)*quantity;

			$(this).parent().parent().find('#purchase_cgst_amount').val(cgst_amount);
		});

	//on entering sgst rate
		$('.purchase_entry_table tr #purchase_sgst_rate').keyup(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#purchase_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#purchase_rate').val());
			var sgst_rate = parseInt($(this).parent().parent().find('#purchase_sgst_rate').val());

			var sgst_amount = (rate*sgst_rate/100)*quantity;

			$(this).parent().parent().find('#purchase_sgst_amount').val(sgst_amount);
		});

	//on entering igst rate
		$('.purchase_entry_table tr #purchase_igst_rate').keyup(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#purchase_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#purchase_rate').val());
			var igst_rate = parseInt($(this).parent().parent().find('#purchase_igst_rate').val());

			var igst_amount = (rate*igst_rate/100)*quantity;

			$(this).parent().parent().find('#purchase_igst_amount').val(igst_amount);
		});

	//on clicking on calculate
		$('.purchase_entry_table tr #purchase_total_amount').click(function()
		{
			//alert('hello');
			var quantity = parseInt($(this).parent().parent().find('#purchase_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#purchase_rate').val());

			var cgst = parseInt($(this).parent().parent().find('#purchase_cgst_rate').val());
			var sgst = parseInt($(this).parent().parent().find('#purchase_sgst_rate').val());
			var igst = parseInt($(this).parent().parent().find('#purchase_igst_rate').val());

			var total_price = (rate + (rate * (cgst+sgst+igst)/100))*quantity;

			$(this).val(total_price);
			//alert(total_price);
		});

	//on change of quantity, rate or gst after calculation
		$('.purchase_entry_table tr #purchase_quantity').keyup(function()
		{
			$(this).parent().parent().find('#purchase_total_amount').val('calculate');
		});

		$('.purchase_entry_table tr #purchase_rate').keyup(function()
		{
			$(this).parent().parent().find('#purchase_total_amount').val('calculate');
		});

		$('.purchase_entry_table tr #purchase_cgst_rate').keyup(function()
		{
			$(this).parent().parent().find('#purchase_total_amount').val('calculate');
		});

		$('.purchase_entry_table tr #purchase_sgst_rate').keyup(function()
		{
			$(this).parent().parent().find('#purchase_total_amount').val('calculate');
		});

		$('.purchase_entry_table tr #purchase_igst_rate').keyup(function()
		{
			$(this).parent().parent().find('#purchase_total_amount').val('calculate');
		});

	//on clicking on delete item button
		$('.purchase_entry_table tr .item_delete_icon').click(function()
		{
			$(this).parent().parent().remove();
		});

	</script>
</tr>
