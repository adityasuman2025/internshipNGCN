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
	<td><input type="text" id="quotation_description"></td>

	<td>
		<select id="quotation_brand">
			<option value=""></option>
				<?php
					$get_brand_query = "SELECT brand FROM stock WHERE creator_branch_code = '$creator_branch_code' GROUP BY brand";
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

	<td>
		<input type="text" id="quotation_serial_num">
	</td>

	<td>
		<select id="quotation_part_name">
			<option value=""></option>					
		</select>
	</td>

	<td><input type="text" id="quotation_part_serial_num"></td>

	<td><input type="number" disabled="disabled" id="quotation_part_in_stock"></td>

	<td><input type="number" value="0" id="quotation_part_quantity"></td>
	<td><input type="number" value="0" id="quotation_part_rate"></td>

	<td><input type="number" value="0" id="quotation_part_cgst"></td>
	<td><input type="number" value="0" id="quotation_part_sgst"></td>
	<td><input type="number" value="0" id="quotation_part_igst"></td>
					
	<td><input type="text" id="quotation_part_hsn_code"></td>
	<td><input type="button" style="background: #cc0000; color: white; margin: 2px; width: auto;" value="calculate" id="quotation_part_total_price"></td>
	<td>
		<img class="item_delete_icon" src="img/delete.png">
	</td>

<!------script----------->
	<script type="text/javascript">
		creator_branch_code = "<?php echo $creator_branch_code; ?>";

	//on selecting a brand
		$('tr:last #quotation_brand').change(function()
		{
			$(this).attr('disabled', 'disabled');
			this_thing = $(this); 

			brand = $(this).val();
			var query = "SELECT model_name FROM stock WHERE brand ='" + brand + "' AND creator_branch_code = '" + creator_branch_code + "' GROUP BY model_name";
			var to_get = "model_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				this_thing.parent().parent().find('#quotation_model_name').html(data);
			});
		});

	//on selecting a model_name
		$('tr:last #quotation_model_name').change(function()
		{
			$(this).attr('disabled', 'disabled');
			this_thing = $(this); 

			model_name = $(this).val();
			//alert(model_name);
			var query = "SELECT model_number FROM stock WHERE model_name ='" + model_name + "' AND creator_branch_code = '" + creator_branch_code + "' GROUP BY model_number";
			var to_get = "model_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				this_thing.parent().parent().find('#quotation_model_number').html(data);
			});
		});

	//on selecting a model_number
		$('tr:last #quotation_model_number').change(function()
		{
			$(this).attr('disabled', 'disabled');
			this_thing = $(this); 

			model_number = $(this).val(); //making it universal variable to use in, on selecting a part_name

		//giving options to choose from part_name			
			var query = "SELECT part_name FROM stock WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND creator_branch_code = '" + creator_branch_code + "' GROUP BY model_number";
			var to_get = "part_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				this_thing.parent().parent().find('#quotation_part_name').html(data);
			});

		//checking the availability of that item in stock
			var query = "SELECT in_stock FROM stock WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND part_name = '' AND creator_branch_code = '" + creator_branch_code + "'";
			var to_get = "in_stock";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				if(data == '0')
				{
					$('.gen_quotation_span').html('The item you have selected is not available at your branch. Click on See Availability button to see the availability of that item in different branches').css('color', 'red');
					
				//for generating see availability button
					this_thing.parent().parent().find('#quotation_part_in_stock').attr('type', 'button').attr('value', 'See Availability').attr('disabled', false).addClass('change_branch_button').css('background', '#cc0000').css('color', 'white');

					this_thing.parent().parent().find('.change_branch_button').click(function()
					{
						$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/search_item_in_branch.php');
					});
				}
				else
				{
					this_thing.parent().parent().find('#quotation_part_in_stock').val(data);
					$('.gen_quotation_span').html('');
				}
			});
		});

	//on selecting a part_name
		$('tr:last #quotation_part_name').change(function()
		{
			$(this).attr('disabled', 'disabled');
			part_name = $(this).val();
			this_thing = $(this); 

		//checking the availability of that item in stock
			var query = "SELECT in_stock FROM stock WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND part_name = '" + part_name + "' AND creator_branch_code = '" + creator_branch_code + "'";
			var to_get = "in_stock";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				if(data == '0') //if the item is not available in stock
				{
					$('.gen_quotation_span').html('The item you have selected is not available at your branch. Click on See Availability button to see the availability of that item in different branches').css('color', 'red');
					
				//for generating see availability button
					this_thing.parent().parent().find('#quotation_part_in_stock').attr('type', 'button').attr('value', 'See Availability').attr('disabled', false).addClass('change_branch_button').css('background', '#cc0000').css('color', 'white');

					this_thing.parent().parent().find('.change_branch_button').click(function()
					{
						$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/search_item_in_branch.php');
					});
				}
				else
				{
					this_thing.parent().parent().find('#quotation_part_in_stock').val(data);
					$('.gen_quotation_span').html('');
				}
			});
		});

	//on clicking on calculate
		$('tr:last #quotation_part_total_price').click(function()
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
		$('tr:last #quotation_part_quantity').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

		$('tr:last #quotation_part_rate').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

		$('tr:last #quotation_part_cgst').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

		$('tr:last #quotation_part_sgst').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

		$('tr:last #quotation_part_igst').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

	//on clicking on delete item button
		$('.item_delete_icon').click(function()
		{
			$(this).parent().parent().remove();
		});

	</script>
</tr>
