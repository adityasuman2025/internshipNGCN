<?php
	include('connect_db.php');
?>

<!-------inventory area----->
	<!-- <div class="inventory_tab">
		<button class="whole_unit_button">Whole Unit</button>
		<button class="parts_only_button">Parts Only</button>
	</div>
	<br><br> -->

<!-----inventory form---->
	<div class="inventory_form">
		Brand:<br>
		<select id="inv_brand">
			<option value=""></option>

			<?php
				$get_brand_query = "SELECT brand FROM inventory GROUP BY brand";
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
		<select id="inv_model_name">
			<option value=""></option>
		</select>
		<br><br>

		Model Number:<br>
		<select id="inv_model_number">
			<option value=""></option>
		</select>
		<br><br>

		<div class="parts_only_input">	
			Part Name:<br>		
			<select id="inv_part_name">
				<option value=""></option>
			</select>
			<br><br>
			
			Part Number:<br>
			<select id="inv_part_number">
				<option value=""></option>
			</select>
			<br><br>
		</div>

		<input id="inv_quantity" type="number" placeholder="Quantity">
		<br><br>
		<input id="inv_sales_price" type="number" placeholder="Sales Price">
		<br><br>
		<input id="inv_supplier_price" type="number" placeholder="Supplier Price">
		<br><br>
		<input id="inv_hsn_code" type="text" placeholder="HSN Code">
		<br><br>

		<input type="submit" id="inv_add_button" value="Add">
	</div>

	<span class="inventory_feed"></span>
	<br><br>
	<input type="submit" id="inv_add_new_button" value="Add New">
	<br><br>

<!-----------script------------>
	<script type="text/javascript">
		
	// //switching tab b/w whole unit and parts only
	// 	$('.whole_unit_button').click(function()
	// 	{
	// 		$('.parts_only_input').fadeOut(0);
	// 	});

	// 	$('.parts_only_button').click(function()
	// 	{
	// 		$('.parts_only_input').fadeIn(0);
	// 	});

	//on selecting a brand
		$('#inv_brand').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			var brand = $(this).val();
			var query = "SELECT model_name FROM inventory WHERE brand ='" + brand + "' GROUP BY model_name";
			var to_get = "model_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#inv_model_name').html(data);
			});
		});

	//on selecting a model_name
		$('#inv_model_name').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			model_name = $(this).val();
			var query = "SELECT model_number FROM inventory WHERE model_name ='" + model_name + "' GROUP BY model_number";
			var to_get = "model_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#inv_model_number').html(data);
			});
		});

	//on selecting a model_number
		$('#inv_model_number').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			model_number = $(this).val(); //making it universal variable to use in, on selecting a part_name
			var query = "SELECT part_name FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' GROUP BY part_name";
			var to_get = "part_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#inv_part_name').html(data);
			});
		});

	//on selecting a part_name
		$('#inv_part_name').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			part_name = $(this).val(); //making it universal variable to use in, on selecting a part_name
			var query = "SELECT part_number FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND part_name = '" + part_name + "'";
			var to_get = "part_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#inv_part_number').html(data);
			});
		});

	//on selecting a part_number
		$('#inv_part_number').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');
		});

	//on clicking on inventory add button
		$('#inv_add_button').click(function()
		{
			$('.inventory_feed').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			var inv_brand = $.trim($('#inv_brand').val());
			var inv_model_name = $.trim($('#inv_model_name').val());
			var inv_model_num = $.trim($('#inv_model_number').val());

			var inv_part_name = $.trim($('#inv_part_name').val());
			var inv_part_number = $.trim($('#inv_part_number').val());

			var inv_quantity = $.trim($('#inv_quantity').val());
			var inv_sales_price = $.trim($('#inv_sales_price').val());
			var inv_supplier_price = $.trim($('#inv_supplier_price').val());
			var inv_hsn_code = $.trim($('#inv_hsn_code').val());
			
			var inv_type = "";

		//for choosing b/w whole unit and part only
			if(inv_part_name =="" && inv_part_number =="")
			{
				var inv_type = "whole";
			}
			else //for parts only
			{
				var inv_type = "part";
			}

			if(inv_brand !="" && inv_model_name !="" && inv_model_num !="" && inv_quantity !="")
			{			
				$.post('php/create_stock.php', {inv_brand:inv_brand, inv_model_name:inv_model_name, inv_model_num:inv_model_num, inv_part_name:inv_part_name, inv_part_number:inv_part_number, inv_quantity:inv_quantity, inv_type:inv_type, inv_sales_price:inv_sales_price, inv_supplier_price:inv_supplier_price, inv_hsn_code:inv_hsn_code}, function(e)
				{
					if(e == 1)
					{
						$('.inventory_feed').text('Stock has been successfully added').css('color', 'green');
						$('.inventory_form').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);
						$('#inv_add_new_button').fadeIn(0);
					}
					else
					{
						$('.inventory_feed').text('Something went wrong while adding stock').css('color', 'red');
					}
				});
			}
			else
			{
				$('.inventory_feed').text('Please fill all the details').css('color', 'red');
			}		
		});

	//on clicking on add new inventory button
		$('#inv_add_new_button').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_stock.php');
		});

	</script>