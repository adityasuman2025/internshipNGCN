<?php
	include 'connect_db.php';
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	$quotation_num = $_POST['quotation_num'];
	$query = "SELECT * FROM quotation WHERE quotation_num = '$quotation_num'";

	$query_run = mysqli_query($connect_link, $query);
	$query_assoc = mysqli_fetch_assoc($query_run);

	$quotation_id = $query_assoc['id'];
	$date = $query_assoc['date'];
	
	$customer = $query_assoc['customer'];

//getting the email id of the customer
	$get_customer_email_query = "SELECT email FROM customers WHERE name ='$customer'";
	$get_customer_email_query_run = mysqli_query($connect_link, $get_customer_email_query);
	$get_customer_email_assoc = mysqli_fetch_assoc($get_customer_email_query_run);
	$get_customer_email = $get_customer_email_assoc['email'];

?>
<!----add customer form------>
	<div class="user_entry_form add_quotation_form">

	<!------customer and date selection-------->
		<div>
			<b>Customer:</b>
			<br>
			<select id="quotation_customer">
				<option value = "<?php echo $customer; ?>" email="<?php echo $get_customer_email; ?>"><?php echo $customer; ?></option>
				<?php
					$get_brand_query = "SELECT * FROM customers WHERE creator_branch_code = '$creator_branch_code' AND name !='$customer'";
					$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

					while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
					{
						$email = $get_brand_result['email'];
						echo "<option email=\"$email\">";
							echo $get_brand_result['name'];
						echo "</option>";
					}
				?>	
			</select>
			<button id="quotation_add_customer">New Customer</button>
		</div>
		
		<div>
			<b>Quotation Date:</b>
			<br>
			<input type="date" value="<?php echo $date; ?>" id="quotation_date">
		</div>
		<br><br>

	<!--------goods table------->
		<table class="quotation_entry_table">
			<tbody>
				<tr>
					<th>Brand</th>
					<th>Product/Part</th>
					<th>Product/Part Code</th>
					<th>HSN Code</th>
					<th>Description</th>
					
					<th>Service ID</th>
					<th>Quantity</th>
					<th>Rate</th>

					<th>CGST Rate</th>
					<th>CGST Amount</th>

					<th>SGST Rate</th>
					<th>SGST Amount</th>

					<th>IGST Rate</th>
					<th>IGST Amount</th>

					<th>Total Amount</th>
					<th>Action</th>
				</tr>

				<?php
					$get_more_info_query = "SELECT * FROM quotation WHERE quotation_num = '$quotation_num'";
					$get_more_info_query_run = mysqli_query($connect_link, $get_more_info_query);

					while ($query_assoc = mysqli_fetch_assoc($get_more_info_query_run))
					{
						$quotation_id = $query_assoc['id'];

						$brand = $query_assoc['brand'];
						$model_name = $query_assoc['model_name'];
						$model_number = $query_assoc['model_number'];
						$hsn_code = $query_assoc['hsn_code'];
						$description = $query_assoc['description'];

						$service_id = $query_assoc['service_id'];
						$quantity = $query_assoc['quantity'];
						$rate = $query_assoc['rate'];

						$cgst = $query_assoc['cgst'];
						$cgst_amount = ($rate*$cgst/100)*$quantity;

						$sgst = $query_assoc['sgst'];
						$sgst_amount = ($rate*$sgst/100)*$quantity;

						$igst = $query_assoc['igst'];	
						$igst_amount = ($rate*$igst/100)*$quantity;

						$total_price = $query_assoc['total_price'];

						echo "<tr id=\"$quotation_id\">";

							echo "<td>
								<select id=\"quotation_brand\">
									<option value=\"$brand\">$brand</option>";

										$get_brand_query = "SELECT brand FROM stock WHERE brand !='$brand' AND creator_branch_code = '$creator_branch_code' GROUP BY brand";
										$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

										while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
										{
											$brand = $get_brand_result['brand'];
											echo "<option value=\"$brand\">";
												echo $brand;
											echo "</option>";
										}

							echo "</select>	
								</td>";

							echo "	<td>
										<select id=\"quotation_model_name\">
											<option value=\"$model_name\">$model_name</option>
										</select>
									</td>

									<td>
										<select id=\"quotation_model_number\">
											<option value=\"$model_number\">$model_number</option>
										</select>
									</td>";

							echo "	<td><input type=\"text\" value=\"$hsn_code\" disabled=\"disabled\" id=\"quotation_hsn_code\"></td>
									<td><input type=\"text\" value=\"$description\" disabled=\"disabled\" id=\"quotation_description\"></td>";

							echo "<td><input type=\"text\" value=\"$service_id\" id=\"quotation_service_id\"></td>";

							echo "<td><input type=\"number\" value=\"$quantity\" id=\"quotation_part_quantity\"></td>
								<td><input type=\"number\" value=\"$rate\" id=\"quotation_part_rate\"></td>

								<td><input type=\"number\" value=\"$cgst\" id=\"quotation_part_cgst\"></td>";
							
							echo "<td><input type=\"number\" disabled=\"disabled\" value=\"$cgst_amount\" id=\"quotation_cgst_amount\"></td>";

							echo"<td><input type=\"number\" value=\"$sgst\" id=\"quotation_part_sgst\"></td>";
							echo "<td><input type=\"number\" disabled=\"disabled\" value=\"$sgst_amount\" id=\"quotation_sgst_amount\"></td>";

							echo"<td><input type=\"number\" value=\"$igst\" id=\"quotation_part_igst\"></td>";
							echo "<td><input type=\"number\" disabled=\"disabled\" value=\"$igst_amount\" id=\"quotation_igst_amount\"></td>";

							echo"<td><input type=\"button\" style=\"background: #cc0000; color: white; margin: 2px; width: auto;\" value=\"$total_price\" id=\"quotation_part_total_price\"></td>
								<td>
									<img class=\"item_delete_icon\" src=\"img/delete.png\">
								</td>";

						echo "</tr>";
					}
				?>
			</tbody>
			
		</table>
		<br>

		<button id="add_new_goods_button">Add New Item</button>
		<br><br><br>

		<input type="button" value="Save Edit" id="quotation_gen_button">
	</div>
	
	<span class="gen_quotation_span"></span>
	
<!--------script-------->
	<script type="text/javascript">
	//on clicking on add customer button
		creator_branch_code = "<?php echo $creator_branch_code; ?>";

		$('#quotation_add_customer').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_customer.php');
		});

	//on selecting a brand
		$('.quotation_entry_table tr #quotation_brand').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			this_thing = $(this);
			brand = $(this).val();
			var query = "SELECT model_name FROM inventory WHERE brand ='" + brand + "' GROUP BY model_name";
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
			var query = "SELECT model_number FROM inventory WHERE model_name ='" + model_name + "' AND brand ='" + brand + "' GROUP BY model_number";
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
			var query = "SELECT hsn_code FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "'";
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

	//on clicking on delete goods button
		$('.quotation_entry_table tr .item_delete_icon').click(function()
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
	
	//on clicking on add new goods button
		$('#add_new_goods_button').click(function()
		{
			$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
		//getting variable values
			var quotation_num = $.trim("<?php echo $quotation_num; ?>");
			
			var quotation_customer = $.trim($('#quotation_customer').val());
			var quotation_date = $.trim($('#quotation_date').val());		
			var quotation_brand = '';
			var quotation_model_name = '';
			var quotation_model_number = '';
			var quotation_serial_num = '';
			var quotation_service_id = '';

			var type = '';

		//creating a new id in database to store this row
			$.post('php/create_new_quotation_row_db.php', {quotation_customer: quotation_customer, quotation_date: quotation_date, quotation_num:quotation_num, quotation_brand:quotation_brand, quotation_model_name:quotation_model_name, quotation_model_number:quotation_model_number, quotation_serial_num:quotation_serial_num, quotation_service_id:quotation_service_id, type:type}, function(e)
			{
				if(e != 0)
				{
					var new_id = e;
					$.post('php/add_new_quotation_item.php', {new_id: new_id},function(data)
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
			
			if(quotation_customer !="" && quotation_date !="" && quotation_num !="")
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
					
					var quotation_serial = child_no - 1;

					var quotation_brand = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_brand').val());
					var quotation_model_name = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_model_name').val());
					var quotation_model_number = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_model_number').val());
					var quotation_part_hsn_code = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_hsn_code').val());
					var quotation_description = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_description').val());

					var quotation_service_id = $('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_service_id').val();
					var quotation_part_quantity = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_quantity').val());
					var quotation_part_rate = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_rate').val());
					var quotation_part_cgst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_cgst').val());
					var quotation_part_sgst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_sgst').val());
					var quotation_part_igst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_igst').val());
					var quotation_part_total_price = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_total_price').val());
					
					var type = "";
					var quotation_serial_num = "";
					var quotation_part_name = "";
					var quotation_part_serial_num = "";

				//if user forget to calculate total price
					if(quotation_part_total_price == "calculate")
					{
						var quotation_part_total_price = (quotation_part_rate + (quotation_part_rate * (quotation_part_cgst+quotation_part_sgst+quotation_part_igst)/100))*quotation_part_quantity;
					}

				//adding this to database	
					var query_recieved = "UPDATE quotation SET serial = '" + quotation_serial + "', description = '" + quotation_description + "', customer ='" + quotation_customer + "', date ='" + quotation_date + "', brand = '" + quotation_brand + "', model_name = '" + quotation_model_name + "', model_number = '" + quotation_model_number + "', serial_num = '" + quotation_serial_num + "', service_id = '" + quotation_service_id + "', part_name = '" + quotation_part_name + "', part_serial_num = '" + quotation_part_serial_num + "', quantity = '" + quotation_part_quantity + "', rate = '" + quotation_part_rate + "', cgst = '" + quotation_part_cgst + "', sgst = '" + quotation_part_sgst + "', igst = '" + quotation_part_igst + "', hsn_code = '" + quotation_part_hsn_code + "', total_price = '" + quotation_part_total_price + "' WHERE id = '" + quotation_id + "'";
				
					$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
					{
						if(e==1)
						{
						//disappearing the user entry form							
							$('.user_entry_form').fadeOut(0);
							$('.gen_quotation_span').text('Successfully edited').css('color','green');
						}
						else
						{
							$('.gen_quotation_span').text('Something went wrong while editing the quotation').css('color','red');
						}
					});
				}
			}
			else
			{
				$('.gen_quotation_span').text('Please fill all the details').css('color','red');
			}
		});

	//on clicking on add new purchase button
		$('#gen_new_quotation_button').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_sales_quotation.php');
		});

	</script>