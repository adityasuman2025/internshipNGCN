<?php
	include 'connect_db.php';
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	$quotation_num = $_POST['quotation_num'];
	$query = "SELECT * FROM quotation WHERE quotation_num = '$quotation_num'";

	$query_run = mysqli_query($connect_link, $query);
	$query_assoc = mysqli_fetch_assoc($query_run);

	$quotation_id = $query_assoc['id'];
	
	$customer = $query_assoc['customer'];
	$date = $query_assoc['date'];
	$brand = $query_assoc['brand'];
	$model_name = $query_assoc['model_name'];
	$model_number = $query_assoc['model_number'];
	$serial_num = $query_assoc['serial_num'];
	$service_id = $query_assoc['service_id'];

?>
<!----add customer form------>
	<div class="user_entry_form add_quotation_form">

	<!------customer and date selection-------->
		<div>
			<b>Customer:</b>
			<br>
			<select id="quotation_customer">
				<option value="<?php echo $customer; ?>"><?php echo $customer; ?></option>
				<?php
					$get_brand_query = "SELECT name FROM customers WHERE creator_branch_code = '$creator_branch_code' AND name !='$customer'";
					$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

					while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
					{
						echo "<option>";
							echo $get_brand_result['name'];
						echo "</option>";
					}
				?>	
			</select>
			<button id="quotation_add_customer">Add Customer</button>
		</div>
		
		<div>
			<b>Quotation Date:</b>
			<br>
			<input type="date" value="<?php echo $date; ?>" id="quotation_date">
		</div>
		<br><br>

	<!------inventory selection-------->
		<div>
			<b>Brand:</b>
			<br>
			<select id="quotation_brand">
				<option value="<?php echo $brand; ?>"><?php echo $brand; ?></option>
					<?php
						$get_brand_query = "SELECT brand FROM stock WHERE brand !='$brand'";
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
		</div>

		<div>
			<b>Model Name:</b>
			<br>
			<select id="quotation_model_name">
				<option value="<?php echo $model_name; ?>"><?php echo $model_name; ?></option>
			</select>
		</div>
		
		<div>
			<b>Model Number:</b>
			<br>
			<select id="quotation_model_number">
				<option value="<?php echo $model_number; ?>"><?php echo $model_number; ?></option>
			</select>
		</div>
		<br><br>

	<!------serial number and serial id-------->
		<div>
			<input type="text" value="<?php echo $serial_num; ?>" id="quotation_serial_num">
		</div>

		<div>
			<input type="text" value="<?php echo $service_id; ?>" id="quotation_service_id">
		</div>
		<br>
		<br>

	<!--------part table------->
		<table class="quotation_entry_table">
			<tr>
				<th>Part Name</th>
				<th>Part Serial Number</th>
				<th>Available In-Stock</th>
				<th>Quantity</th>
				<th>Rate</th>
				<th>GST (in %)</th>
				<th>HSN Code</th>
				<th>Price</th>
				<th>Action</th>
			</tr>

			<?php
				$get_more_info_query = "SELECT * FROM quotation WHERE quotation_num = '$quotation_num'";
				$get_more_info_query_run = mysqli_query($connect_link, $get_more_info_query);

				while ($query_assoc = mysqli_fetch_assoc($get_more_info_query_run))
				{
					$quotation_id = $query_assoc['id'];
					$part_name = $query_assoc['part_name'];
					$part_serial_num = $query_assoc['part_serial_num'];
					
					$quantity = $query_assoc['quantity'];
					$rate = $query_assoc['rate'];
					$gst = $query_assoc['gst'];
					$hsn_code = $query_assoc['hsn_code'];
					$total_price = $query_assoc['total_price'];


				//for getting availability of that item in stock
					$check_availability_query = "SELECT in_stock FROM stock WHERE model_name = '$model_name' AND model_number = '$model_number' AND part_name = '$part_name'";
					$check_availability_query_run = mysqli_query($connect_link, $check_availability_query);
					$check_availability_assoc = mysqli_fetch_assoc($check_availability_query_run);

					$in_stock = $check_availability_assoc['in_stock'];

					echo "<tr id=\"$quotation_id\">";
						echo "	<td>
									<select id=\"quotation_part_name\">
										<option value=\"$part_name\">$part_name</option>					
									</select>
								</td>";

						echo "	<td><input type=\"text\" value=\"$part_serial_num\" id=\"quotation_part_serial_num\"></td>";
							
								if($in_stock =='0' OR $in_stock =='')
								{
									echo "<td><input type=\"button\" value=\"See Availability\" class=\"change_branch_button\" id=\"quotation_part_in_stock\" style=\"background: #cc0000; color: white;\"></td>";
								}
								else
								{
									echo "<td><input type=\"number\" value=\"$in_stock\" disabled=\"disabled\" id=\"quotation_part_in_stock\"></td>";
								}
								
						echo "	<td><input type=\"number\" value=\"$quantity\" id=\"quotation_part_quantity\"></td>
								<td><input type=\"number\" value=\"$rate\" id=\"quotation_part_rate\"></td>
								<td><input type=\"number\" value=\"$gst\" id=\"quotation_part_gst\"></td>
								<td><input type=\"text\" value=\"$hsn_code\" id=\"quotation_part_hsn_code\"></td>
								<td><input type=\"button\" value=\"$total_price\" style=\"background: #cc0000; color: white; margin: 2px; width: auto;\" value=\"calculate\" id=\"quotation_part_total_price\"></td>
								<td>
									<img class=\"item_delete_icon\" src=\"img/delete.png\">
								</td>";

					echo "</tr>";
				}
			?>
		</table>
		<br>

		<button id="add_new_part_button">Add New Item</button>
		<br><br><br>

		<input type="button" value="Save Edit" id="quotation_gen_button">
	</div>
	
	<span class="gen_quotation_span"></span>
	
<!--------script-------->
	<script type="text/javascript">
	//if change is done in model name and model number
		model_name = $.trim("<?php echo $model_name; ?>");
		model_number = $.trim("<?php echo $model_number; ?>");

	//on clicking on add customer button
		$('#quotation_add_customer').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_customer.php');
		});

	//on selecting a brand
		$('#quotation_brand').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			var brand = $(this).val();
			var query = "SELECT model_name FROM stock WHERE brand ='" + brand + "'";
			var to_get = "model_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#quotation_model_name').html(data);
			});

		//emptying 
			$('#quotation_model_number').html('');
			
			$('.quotation_entry_table tr td #quotation_part_name').each(function()
			{
				$(this).html('');
			});
		});

	//on selecting a model_name
		$('#quotation_model_name').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			model_name = $(this).val();
			var query = "SELECT model_number FROM stock WHERE model_name ='" + model_name + "'";
			var to_get = "model_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#quotation_model_number').html(data);
			});

		//emptying 
			$('.quotation_entry_table tr td #quotation_part_name').each(function()
			{
				$(this).html('');
			});
		});

	//on selecting a model_number
		$('#quotation_model_number').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			model_number = $(this).val(); //making it universal variable to use in, on selecting a part_name
			var query = "SELECT part_name FROM stock WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "'";
			var to_get = "part_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				$('.quotation_entry_table tr td #quotation_part_name').each(function()
				{
					$(this).html(data);
				});
			});
		});

	//on selecting a part_name
		$('.quotation_entry_table tr td #quotation_part_name').change(function()
		{
			$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
					
			$(this).attr('disabled', 'disabled');
			var this_thing = $(this).parent().parent();

			part_name = $(this).val();

		//checking the availability of that item in stock			
			var query = "SELECT in_stock FROM stock WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND part_name = '" + part_name + "'";
			var to_get = "in_stock";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				if(data == '0') //if the item is not available in stock
				{
					$('.gen_quotation_span').html('The item you have selected is not available at your branch. Click on See Availability button to see the availability of that item in different branches').css('color', 'red');
					
				//for generating see availability button
					this_thing.find('#quotation_part_in_stock').attr('type', 'button').attr('value', 'See Availability').attr('disabled', false).addClass('change_branch_button').css('background', '#cc0000').css('color', 'white');

					$('.quotation_entry_table tr td .change_branch_button').click(function()
					{
						$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/search_item_in_branch.php');
					});
				}
				else
				{
					this_thing.find('#quotation_part_in_stock').val(data);
					$('.gen_quotation_span').html('');
				}
			});

		});

	//on clicking on see availability button
		$('.quotation_entry_table tr td .change_branch_button').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/search_item_in_branch.php');
		});

	//on clicking on calculate
		$('.quotation_entry_table tr td #quotation_part_total_price').click(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var gst = parseInt($(this).parent().parent().find('#quotation_part_gst').val());

			var total_price = (rate + (rate * gst/100))*quantity;

			$(this).val(total_price);
			//alert(total_price);
		});

	//on change of quantity, rate or gst after calculation
		$('.quotation_entry_table tr td #quotation_part_quantity').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

		$('.quotation_entry_table tr td #quotation_part_rate').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

		$('.quotation_entry_table tr td #quotation_part_gst').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

	//on clicking on delete part button
		$('.quotation_entry_table tr td .item_delete_icon').click(function()
		{
			$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");

			var id = $(this).parent().parent().attr('id');
			var this_item = $(this).parent().parent();

		//deleting that row from database
			var query_recieved = "DELETE FROM quotation WHERE id ='" + id + "'";
			$.post('php/query_runner.php', {query_recieved: query_recieved}, function(e)
			{
				if(e == 1)
				{
					this_item.remove();
					$('.gen_quotation_span').html("Selected row has been successfully deleted").css('color', 'green');
				}
				else
				{
					$('.gen_quotation_span').html("Something weant wrong while deleting the row from database").css('color', 'red');
				}
			});
		});
	
	//on clicking on add new part button
		$('#add_new_part_button').click(function()
		{
			$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
		//getting variable values
			var quotation_num = $.trim("<?php echo $quotation_num; ?>");
			
			var quotation_customer = $.trim($('#quotation_customer').val());
			var quotation_date = $.trim($('#quotation_date').val());		
			var quotation_brand = $.trim($('#quotation_brand').val());
			var quotation_model_name = $.trim($('#quotation_model_name').val());
			var quotation_model_number = $.trim($('#quotation_model_number').val());
			var quotation_serial_num = $.trim($('#quotation_serial_num').val());
			var quotation_service_id = $.trim($('#quotation_service_id').val());

			var type = 'service';

		//creating a new id in database to store this row
			$.post('php/create_new_quotation_row_db.php', {quotation_customer: quotation_customer, quotation_date: quotation_date, quotation_num:quotation_num, quotation_brand:quotation_brand, quotation_model_name:quotation_model_name, quotation_model_number:quotation_model_number, quotation_serial_num:quotation_serial_num, quotation_service_id:quotation_service_id, type:type}, function(e)
			{
				if(e != 0)
				{
					var new_id = e;
					$.post('php/add_new_part_form.php', {new_id: new_id},function(data)
					{
						$('.quotation_entry_table tbody').append(data);
						$('.gen_quotation_span').html("Row has been successfully added").css('color', 'green');
					});
				}
				else
				{
					$('.warn_box').html("Something went wrong while adding new row in the database");
					$('.warn_box').fadeIn(200).delay(2000).fadeOut(200);
				}
			});
		});

	//on clicking on add quotation button
		$('#quotation_gen_button').click(function()
		{
			$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			var quotation_num = $.trim("<?php echo $quotation_num; ?>");
			
		//getting variable values
			var quotation_customer = $.trim($('#quotation_customer').val());
			var quotation_date = $.trim($('#quotation_date').val());		
			var quotation_brand = $.trim($('#quotation_brand').val());
			var quotation_model_name = $.trim($('#quotation_model_name').val());
			var quotation_model_number = $.trim($('#quotation_model_number').val());
			var quotation_serial_num = $.trim($('#quotation_serial_num').val());
			var quotation_service_id = $.trim($('#quotation_service_id').val());

			if(quotation_customer !="" && quotation_date !="" && quotation_num !="" && quotation_brand !="" && quotation_model_name !="" && quotation_model_number !="" && quotation_serial_num !="" && quotation_service_id !="")
			{
			//for getting inputs of each of the row
				var count = $(".quotation_entry_table tr").length;
				var row_count = count -1;

				var i = 1;
				for(i; i<= row_count; i++)
				{
					var child_no = i + 1;

				//defining variables of each table row
					var quotation_id = $('.quotation_entry_table tr:nth-child('+ child_no + ')').attr('id');
					// alert(quotation_id);

					var quotation_part_name = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_name').val());
					var quotation_part_serial_num = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_serial_num').val());

					var quotation_part_quantity = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_quantity').val());
					var quotation_part_rate = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_rate').val());
					var quotation_part_gst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_gst').val());

					var quotation_part_hsn_code = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_hsn_code').val());
					var quotation_part_total_price = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_total_price').val());
					var type = "service";

				//if user forget to calculate total price
					if(quotation_part_total_price == "calculate")
					{
						//alert('plx calculate');
						var quotation_part_total_price = (quotation_part_rate + (quotation_part_rate * quotation_part_gst/100))*quotation_part_quantity;
					}
					
				//adding this to database	
					var query_recieved = "UPDATE quotation SET customer ='" + quotation_customer + "', date ='" + quotation_date + "', brand = '" + quotation_brand + "', model_name = '" + quotation_model_name + "', model_number = '" + quotation_model_number + "', serial_num = '" + quotation_serial_num + "', service_id = '" + quotation_service_id + "', part_name = '" + quotation_part_name + "', part_serial_num = '" + quotation_part_serial_num + "', quantity = '" + quotation_part_quantity + "', rate = '" + quotation_part_rate + "', gst = '" + quotation_part_gst + "', hsn_code = '" + quotation_part_hsn_code + "', total_price = '" + quotation_part_total_price + "' WHERE id = '" + quotation_id + "'";
				
					$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
					{
						if(e==1)
						{
							$('.user_edit_span').text('Successfully edited').css('color','green');
							$('#user_edit_form').fadeOut(0);
							$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/manage_quotation.php');
						}
						else
						{
							$('.user_edit_span').text('Something went wrong while editing the supplier').css('color','red');
						}
					});
				}
			}
			else
			{
				$('.gen_quotation_span').text('Please fill all the details').css('color','red');
			}
		});

	</script>