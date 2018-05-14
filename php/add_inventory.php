<!-------inventory area----->
	<h3>Add Inventory</h3>

	<div class="inventory_tab">
		<button class="whole_unit_button">Whole Unit</button>
		<button class="parts_only_button">Parts Only</button>
	</div>
	<br><br>

<!-----inventory form---->
	<div class="inventory_form">
		<input id="inv_brand" type="text" placeholder="Brand">
		<br><br>
		<input id="inv_model_name" type="text" placeholder="Model Name">
		<br><br>
		<input id="inv_model_num" type="text" placeholder="Model Number">
		<br><br>

		<div class="parts_only_input">
			<input id="inv_part_name" type="text" placeholder="Part Name">
			<br><br>
			<input id="inv_part_number" type="text" placeholder="Part Number">
			<br><br>
			<input type="text" id="inv_part_desc" placeholder="Part Description">
			<br><br>
		</div>

		<input type="submit" id="inv_add_button" value="Add">
	</div>

	<span class="inventory_feed"></span>
	<br><br>
	<input type="submit" id="inv_add_new_button" value="Add New">
	<br><br>

<!-----------script------------>
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

	//on clicking on inventory add button
		$('#inv_add_button').click(function()
		{
			var inv_brand = $.trim($('#inv_brand').val());
			var inv_model_name = $.trim($('#inv_model_name').val());
			var inv_model_num = $.trim($('#inv_model_num').val());
			var inv_part_name = $.trim($('#inv_part_name').val());
			var inv_part_number = $.trim($('#inv_part_number').val());
			var inv_part_desc = $.trim($('#inv_part_desc').val());
			var inv_type = "";

		//for choosing b/w whole unit and part only
			if(inv_part_name =="" && inv_part_number =="" && inv_part_desc =="")
			{
				var inv_type = "whole";
			}
			else //for parts only
			{
				var inv_type = "part";
			}

			if(inv_brand !="" && inv_model_name !="" && inv_model_num !="")
			{
			
				$.post('php/create_inventory.php', {inv_brand:inv_brand, inv_model_name:inv_model_name, inv_model_num:inv_model_num, inv_part_name:inv_part_name, inv_part_number:inv_part_number, inv_part_desc:inv_part_desc, inv_type:inv_type}, function(e)
				{
					if(e == 1)
					{
						$('.inventory_feed').text('Inventory has been successfully added').css('color', 'green');
						$('.inventory_form').fadeOut(0);
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
			$('.user_module_content').load('php/add_inventory.php');
		});

	//switching tab b/w whole unit and parts only
		$('.whole_unit_list_button').click(function()
		{
			$.post('php/list_whole_inventory.php', {}, function(data)
			{
				$('.list_inventory_table').html(data);

			//on clicking on inventory delete icon
				$('.inventory_delete_icon').click(function()
				{
					var user_id = $(this).attr('user_id');
					
					$.post('php/delete_inventory.php', {user_id:user_id}, function(e)
					{
						if(e==1)
						{
							location.href= "admin#admin_inventory";
							location.reload();
						}
						else
						{
							$('.warn_box').text("Something went wrong while deleting the user");
							$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
						}
					});
				});

			});
		});

		$('.parts_only_list_button').click(function()
		{
			$.post('php/list_part_inventory.php', {}, function(data)
			{
				$('.list_inventory_table').html(data);

			//on clicking on inventory delete icon
				$('.inventory_delete_icon').click(function()
				{
					var user_id = $(this).attr('user_id');
					
					$.post('php/delete_inventory.php', {user_id:user_id}, function(e)
					{
						if(e==1)
						{
							location.href= "admin#admin_inventory";
							location.reload();
						}
						else
						{
							$('.warn_box').text("Something went wrong while deleting the user");
							$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
						}
					});
				});

			});
		});

	</script>