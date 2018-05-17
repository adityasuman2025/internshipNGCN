<?php
	include 'connect_db.php';
?>

<!------product entry form-------->
	<div class="user_entry_form">
		<div class="inventory_tab">
			<button class="whole_unit_button">Whole Unit</button>
			<button class="parts_only_button">Parts Only</button>
		</div>
		<br><br>

		Brand:<br>
		<select class="sel_pro_brand">
			<option value=""></option>
			<?php
				$get_brand_query = "SELECT brand FROM inventory";
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
		<select class="sel_pro_model_name">
		</select>
		<br><br>

		Model Number:<br>
		<select class="sel_pro_model_number">
		</select>
		<br><br>

		<div class="parts_only_input">
			Part Name:<br>
			<select class="sel_pro_part_name">
			</select>
			<br><br>

			Part Number:<br>
			<select class="sel_pro_part_number">
			</select>
			<br><br>
		</div>

		<input type="number" placeholder="Quantity" id="sel_pro_quantity">
		<br>
		<br>
		<input type="number" placeholder="Price" id="sel_pro_price">
		<br>
		<br>

		Supplier:<br>
		<select class="sel_pro_supplier">
			<option value=""></option>
			<?php
				$get_brand_query = "SELECT name FROM supplier";
				$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

				while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
				{
					echo "<option>";
						echo $get_brand_result['name'];
					echo "</option>";
				}
			?>	
		</select>
		<br><br>

		<input type="button" value="Add Product" id="sel_pro_add_button">
	</div>
	
	<span class="sel_pro_feed"></span>
	<br><br>
	<button id="sel_new_pro_add_button">Add New Product</button>
	<br><br>

<!------script--------->
	<script type="text/javascript">
	//switching tab b/w whole unit and parts only
		$('.whole_unit_button').click(function()
		{
			$('.parts_only_input').fadeOut(0);
		});

		$('.parts_only_button').click(function()
		{
			$('.parts_only_input').fadeIn(0);
		});

	//for getting model names of the brand
		$('.sel_pro_brand').change(function()
		{
			brand = $(this).val();
			var to_get = "model_name";
			var query = "SELECT model_name FROM inventory WHERE brand='" + brand + "'";

			//alert(query);
			$.post('php/product_query_runner.php', {query:query, to_get:to_get}, function(e)
			{
				//alert(e);
				$('.sel_pro_model_name').html(e);
			});

		//emptying all child options
			$('.sel_pro_model_name').val('');
			$('.sel_pro_model_number').val('');
			$('.sel_pro_part_name').val('');
			$('.sel_pro_part_number').val('');
		})

	//for getting model number of the brand
		$('.sel_pro_model_name').change(function()
		{
			var to_get = "model_number";
			var query = "SELECT model_number FROM inventory WHERE brand='" + brand + "'";

			//alert(query);
			$.post('php/product_query_runner.php', {query:query, to_get:to_get}, function(e)
			{
				//alert(e);
				$('.sel_pro_model_number').html(e);
			});

		//emptying all child options
			$('.sel_pro_model_number').val('');
			$('.sel_pro_part_name').val('');
			$('.sel_pro_part_number').val('');
		})

	//for getting part name of the brand
		$('.sel_pro_model_number').change(function()
		{
			var to_get = "part_name";
			var query = "SELECT part_name FROM inventory WHERE brand='" + brand + "'";

			//alert(query);
			$.post('php/product_query_runner.php', {query:query, to_get:to_get}, function(e)
			{
				//alert(e);
				$('.sel_pro_part_name').html(e);
			});

		//emptying all child options
			$('.sel_pro_part_name').val('');
			$('.sel_pro_part_number').val('');
		})

	//for getting part number of the brand
		$('.sel_pro_part_name').change(function()
		{
			var to_get = "part_number";
			var query = "SELECT part_number FROM inventory WHERE brand='" + brand + "'";

			$.post('php/product_query_runner.php', {query:query, to_get:to_get}, function(e)
			{
				$('.sel_pro_part_number').html(e);
			});

		//emptying all child options
			$('.sel_pro_part_number').val('');
		})

	//on clicking on product add button
		$('#sel_pro_add_button').click(function()
		{
			$('.sel_pro_feed').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			var inv_brand = $('.sel_pro_brand').val();
			var inv_model_name = $.trim($('.sel_pro_model_name').val());
			var inv_model_num = $.trim($('.sel_pro_model_number').val());
			var inv_part_name = $.trim($('.sel_pro_part_name').val());
			var inv_part_number = $.trim($('.sel_pro_part_number').val());
			var inv_quantity = $.trim($('#sel_pro_quantity').val());
			var inv_price = $.trim($('#sel_pro_price').val());
			var inv_supplier = $.trim($('.sel_pro_supplier').val());
			
			var inv_type = "";

			if(inv_brand !="" && inv_model_name !="" && inv_model_num !="" && inv_quantity !="" && inv_price !="" && inv_supplier !="")
			{
			//for whole unit or part only
				if(inv_part_name =="" && inv_part_number =="")
				{
					var inv_type = "whole";
				}
				else //for parts only
				{
					var inv_type = "part";
				}

				$.post('php/create_product.php', {inv_brand:inv_brand, inv_model_name:inv_model_name, inv_model_num:inv_model_num, inv_part_name:inv_part_name, inv_part_number:inv_part_number, inv_quantity:inv_quantity, inv_type:inv_type, inv_price:inv_price, inv_supplier:inv_supplier}, function(e)
				{
					if(e == 1)
					{
						$('.sel_pro_feed').text('Inventory has been successfully added').css('color', 'green');
						$('.user_entry_form').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);
						$('#sel_new_pro_add_button').fadeIn(0);
					}
					else
					{
						$('.sel_pro_feed').text('Something went wrong while adding product').css('color', 'red');
					}
				});
			}
			else
			{
				$('.sel_pro_feed').text('Please fill all the details').css('color', 'red');
			}
		
		});

	//on clicking on create new user button
		$('#sel_new_pro_add_button').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_product.php');
		});

	</script>