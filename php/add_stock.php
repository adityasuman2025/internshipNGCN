<?php
	include('connect_db.php');
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];
?>

<!-----inventory form---->
	<div class="inventory_form">
		Is a Product or Part or Service
		<br>
		<select id="inv_type">
			<option value=""></option>
			<option value="product">Product</option>
			<option value="part">Part</option>
			<option value="service">Service</option>
		</select>
		<br><br>

		Brand:<br>
		<select id="inv_brand">
			<option value=""></option>
		</select>
		<br><br>

		Product/Part:<br>
		<select id="inv_model_name">
			<option value=""></option>
		</select>
		<br><br>

		Product/part Code:<br>
		<select id="inv_model_number">
			<option value=""></option>
		</select>
		<br><br>

		<input id="inv_hsn_code" type="text" disabled="disabled" placeholder="HSN Code">
		<br><br>
		<input id="inv_description" type="text" disabled="disabled" placeholder="Description">
		<br><br>

		<input id="inv_quantity" type="number" placeholder="Quantity">
		<br><br>
		<input id="inv_sales_price" type="number" placeholder="Sales Price">
		<br><br>
		<input id="inv_supplier_price" type="number" placeholder="Supplier Price">
		<br><br>
		
		<input type="submit" id="inv_add_button" value="Add">
	</div>

	<span class="inventory_feed"></span>
	<br><br>
	<input type="submit" id="inv_add_new_button" value="Add New">
	<br><br>

<!-----------script------------>
	<script type="text/javascript">
	//on selecting a type
		$('#inv_type').change(function()
		{
			type = $(this).val();
			$(this).attr('disabled', true).css('border', '1px solid lightgrey');

		//populating brand
			var query = "SELECT brand FROM inventory WHERE type ='" + type + "' GROUP BY brand";
			var to_get = "brand";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				$('#inv_brand').html(data);
			});
		});

	//on selecting a brand
		$('#inv_brand').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			brand = $(this).val();
			var query = "SELECT model_name FROM inventory WHERE brand ='" + brand + "' AND type = '" + type + "' GROUP BY model_name";
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
			var query = "SELECT model_number FROM inventory WHERE model_name ='" + model_name + "' AND type = '" + type + "' GROUP BY model_number";
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
			this_thing = $(this);
			model_number = $(this).val();

		//populating hsn code
			var query = "SELECT hsn_code FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND type = '" + type + "'";
			var to_get = "hsn_code";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				$('#inv_hsn_code').val(data);
			});

		//populating description
			var query = "SELECT description FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND type = '" + type + "'";
			var to_get = "description";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				$('#inv_description').val(data);
			});
		});

	//on clicking on stock add button
		$('#inv_add_button').click(function()
		{
			$('.inventory_feed').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			var inv_type = $.trim($('#inv_type').val());
			var inv_brand = $.trim($('#inv_brand').val());
			var inv_model_name = $.trim($('#inv_model_name').val());
			var inv_model_num = $.trim($('#inv_model_number').val());
			var inv_hsn_code = $.trim($('#inv_hsn_code').val());
			var inv_description = $.trim($('#inv_description').val());

			var inv_quantity = $.trim($('#inv_quantity').val());
			var inv_sales_price = $.trim($('#inv_sales_price').val());
			var inv_supplier_price = $.trim($('#inv_supplier_price').val());
			
			var inv_part_name = "";
			var inv_part_number = "";

			if(inv_type !="" && inv_brand !="" && inv_model_name !="" && inv_quantity !="")
			{	
			//checking if that product or part is already added in the sock or not
				var creator_branch_code = "<?php echo $creator_branch_code; ?>";
				var query_recieved = "SELECT id FROM stock WHERE brand = '" + inv_brand + "' && model_name = '" + inv_model_name + "' && model_number = '" + inv_model_num + "' AND creator_branch_code = '" + creator_branch_code + "' AND type ='" + inv_type + "'";

				$.post('php/query_result_counter.php', {query_recieved: query_recieved}, function(e)
				{
					if(e == 0)
					{
						$.post('php/create_stock.php', {inv_type:inv_type, inv_brand:inv_brand, inv_model_name:inv_model_name, inv_model_num:inv_model_num, inv_part_name:inv_part_name, inv_part_number:inv_part_number, inv_quantity:inv_quantity, inv_type:inv_type, inv_sales_price:inv_sales_price, inv_supplier_price:inv_supplier_price, inv_hsn_code:inv_hsn_code}, function(e)
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
						$('.inventory_feed').text('This item has already been added in the stock at this branch. For editing the item go to manage stock tab.').css('color', 'red');
					}
				});
			}
			else
			{
				$('.inventory_feed').text('At least you need to choose brand, model name, model number and quantity.').css('color', 'red');
			}		
		});

	//on clicking on add new inventory button
		$('#inv_add_new_button').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_stock.php');
		});

	</script>