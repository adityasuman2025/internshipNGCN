<!-------inventory area----->
	<h3>Add Inventory</h3>
	
<!-----inventory form---->
	<div class="inventory_form">
		<input id="inv_brand" type="text" placeholder="Brand">
		<br><br>
		<input id="inv_model_name" type="text" placeholder="Product/Part">
		<br><br>
		<input id="inv_model_num" type="text" placeholder="Product/Part Code">
		<br><br>
		<input id="inv_hsn_code" type="text" placeholder="HSN Code">
		<br><br>
		<input type="text" id="inv_desc" placeholder="Description">
		<br><br>

		Is a Product or Part
		<br>
		<select id="inv_type">
			<option value="product">Product</option>
			<option value="part">Part</option>

		</select>
		<br><br>
		<input type="submit" id="inv_add_button" value="Add">
	</div>

	<span class="inventory_feed"></span>
	<br><br>
	<input type="submit" id="inv_add_new_button" value="Add New">
	<br><br>

<!-----------script------------>
	<script type="text/javascript">

	//on clicking on inventory add button
		$('#inv_add_button').click(function()
		{
			$('.inventory_feed').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			var inv_brand = $.trim($('#inv_brand').val());
			var inv_model_name = $.trim($('#inv_model_name').val());
			var inv_model_num = $.trim($('#inv_model_num').val());
			var inv_hsn_code = $.trim($('#inv_hsn_code').val());
			var inv_desc = $.trim($('#inv_desc').val());
			var inv_type = $.trim($('#inv_type').val());

			if(inv_brand !="" && inv_model_name !="" && inv_model_num !="" && inv_hsn_code !='' && inv_type !='')
			{
			
				$.post('php/create_inventory.php', {inv_brand:inv_brand, inv_model_name:inv_model_name, inv_model_num:inv_model_num, inv_hsn_code:inv_hsn_code, inv_desc:inv_desc, inv_type:inv_type}, function(e)
				{
					if(e == 1)
					{
						$('.inventory_feed').text('Inventory has been successfully added').css('color', 'green');
						$('.inventory_form').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);
						$('#inv_add_new_button').fadeIn(0);
					}
					else
					{
						$('.inventory_feed').text('Something went wrong while adding inventory').css('color', 'red');
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
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_inventory.php');
		});

	</script>