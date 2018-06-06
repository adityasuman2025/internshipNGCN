<?php
	include 'connect_db.php';
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];
?>
<!----add customer form------>
	<div class="user_entry_form add_quotation_form">

	<!------customer and date selection-------->
		<div>
			<b>Customer:</b>
			<br>
			<select id="quotation_customer">
				<option value=""></option>
				<?php
					$get_brand_query = "SELECT * FROM customers WHERE creator_branch_code = '$creator_branch_code'";
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
			<input type="date" value="<?php echo date('Y-m-d'); ?>" id="quotation_date">
		</div>
		
		<div>
			<b>Quotation Number:</b>
			<br>

		<!----------for generating quotation number---------->
			<?php
				$get_last_quotation_num_query = "SELECT MAX(quotation_num) AS max_quotation_no FROM quotation";
				$get_last_quotation_num_query_run = mysqli_query($connect_link, $get_last_quotation_num_query);

				$get_last_quotation_num_assoc = mysqli_fetch_assoc($get_last_quotation_num_query_run);

				$last_quotation_num = $get_last_quotation_num_assoc['max_quotation_no'];

				$new_quotation_num = $last_quotation_num + 1;

				$this_year = date('y');
				$next_year = $this_year +1;

				$quotation_code = "VOLTA/" . $this_year . "-" . $next_year . "/" . $new_quotation_num;
			?>
			<input type="text" id="quotation_num" quotation_num="<?php echo $new_quotation_num; ?>" value="<?php echo $quotation_code; ?>" disabled="disabled">
		</div>
		<br>

		<div>
			<b>Search Product/Part Code</b>
			<br>

			<select id="quotation_search_input">
				<option value=""></option>
				<?php
					$get_code_query = "SELECT model_number FROM inventory";
					$get_code_query_run = mysqli_query($connect_link, $get_code_query);

					while($get_code_assoc = mysqli_fetch_assoc($get_code_query_run))
					{
						$get_model_num = $get_code_assoc['model_number'];
						echo "<option value=\"$get_model_num\">$get_model_num</option>";
					}
				?>
			</select>

			<button id="quotation_search_code_button">Go</button>
		</div>
		<br>

		<div>
			<b>If you could not find the product or part. Create the inventory here.
			<button class="create_inventory_button">Create Inventory</button></b>
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

				<tr>
					<td>
						<select id="quotation_brand">
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
					</td>

					<td>
						<select id="quotation_model_name"></select>
					</td>

					<td>
						<select id="quotation_model_number"></select>
					</td>

					<td><input type="text" disabled="disabled" id="quotation_hsn_code"></td>
					<td><input type="text" id="quotation_description"></td>

					<td><input type="text" id="quotation_service_id"></td>

					<td><input type="number" value="0" id="quotation_part_quantity"></td>
					<td><input type="number" value="0" id="quotation_part_rate"></td>

					<td><input type="number" value="0" id="quotation_part_cgst"></td>
					<td><input type="number" disabled="disabled" value="0" id="quotation_cgst_amount"></td>

					<td><input type="number" value="0" id="quotation_part_sgst"></td>
					<td><input type="number" disabled="disabled" value="0" id="quotation_sgst_amount"></td>

					<td><input type="number" value="0" id="quotation_part_igst"></td>
					<td><input type="number" disabled="disabled" value="0" id="quotation_igst_amount"></td>

					<td><input type="button" style="background: #cc0000; color: white; margin: 2px; width: auto;" value="calculate" id="quotation_part_total_price"></td>
					<td>
						<img class="item_delete_icon" src="img/delete.png">
					</td>
				</tr>
			</tbody>
			
		</table>
		<br>

		<button id="add_new_goods_button">Add New Item</button>
		<br><br><br>

		<input type="button" value="Generate Quotation" id="quotation_gen_button">
	</div>
	
	<span class="gen_quotation_span"></span>
	<br><br>
	<input type="submit" value="Generate New Quotation" id="gen_new_quotation_button">

	<br><br>
	<button class="view_quotation_button">View Quotation</button>

<!--------script-------->
	<script type="text/javascript">
		creator_branch_code = "<?php echo $creator_branch_code; ?>";
		
	//on clicking on add customer button
		$('#quotation_add_customer').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_customer.php');
		});

	//on selecting a customer
		$('#quotation_customer').change(function()
		{
			customer = $(this).find('option:selected');
		});

	//on clicking on create inventory button
		$('.create_inventory_button').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_inventory.php');
		});

	//on clicking on go on search product or part code
		$('#quotation_search_code_button').click(function()
		{
			var model_number = $('#quotation_search_input').val();
			$('#quotation_model_number').html("<option value ='"+ model_number + "'>" + model_number + "</option>");

		//getting brand
			var query = "SELECT brand FROM inventory WHERE model_number ='" + model_number + "'";
			var to_get = "brand";

			$.post('php/inventory_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#quotation_brand').html(data);
			});

		//getting model name
			var query = "SELECT model_name FROM inventory WHERE model_number ='" + model_number + "'";
			var to_get = "model_name";

			$.post('php/inventory_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#quotation_model_name').html(data);
			});

		//getting hsn code
			var query = "SELECT hsn_code FROM inventory WHERE model_number ='" + model_number + "'";
			var to_get = "hsn_code";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#quotation_hsn_code').val(data);
			});

		//getting description
			var query = "SELECT description FROM inventory WHERE model_number ='" + model_number + "'";
			var to_get = "description";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#quotation_description').val(data);
			});
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
			$(this).parent().parent().remove();
		});
	
	//on clicking on add new goods button
		$('#add_new_goods_button').click(function()
		{
			$.post('php/add_new_quotation_item.php', {},function(data)
			{
				//alert(data);
				$('.quotation_entry_table tbody').append(data);
			});
		});

	//on clicking on add quotation button
		$('#quotation_gen_button').click(function()
		{
			$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
		//getting variable values
			var quotation_customer = $.trim($('#quotation_customer').val());
			var quotation_date = $.trim($('#quotation_date').val());
			var quotation_num = $.trim($('#quotation_num').attr('quotation_num'));		

			if(quotation_customer !="" && quotation_date !="" && quotation_num !="")
			{
			//for getting inputs of each of the row
				var count = $(".quotation_entry_table tr").length;
				var row_count = count -1;

				//alert(row_count);
				var i = 1;
				for(i; i<= row_count; i++)
				{
					var child_no = i + 1;

				//defining variables of each table row
					var quotation_serial = child_no - 1;

					var quotation_brand = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_brand').val());
					var quotation_model_name = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_model_name').val());
					var quotation_model_number = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_model_number').val());
					var quotation_hsn_code = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_hsn_code').val());
					var quotation_description = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_description').val());

					var quotation_service_id =$('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_service_id').val();
					var quotation_part_quantity = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_quantity').val());
					var quotation_part_rate = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_rate').val());
					
					var quotation_part_cgst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_cgst').val());
					var quotation_part_sgst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_sgst').val());
					var quotation_part_igst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_igst').val());

					var quotation_part_total_price = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_total_price').val());
					
					var type = "";
					var quotation_serial_num = "";
					
					var quotation_part_name = "";
					var quotation_purchase_order ="";

				//if user forget to calculate total price
					if(quotation_part_total_price == "calculate")
					{
						//alert('plx calculate');
						var quotation_part_total_price = (quotation_part_rate + (quotation_part_rate * (quotation_part_cgst+quotation_part_sgst+quotation_part_igst)/100))*quotation_part_quantity;
					}

				//adding this to database					
					$.post('php/create_quotation.php', {quotation_customer: quotation_customer, quotation_date: quotation_date, quotation_num:quotation_num, quotation_serial:quotation_serial, quotation_description:quotation_description, quotation_brand:quotation_brand, quotation_model_name:quotation_model_name, quotation_model_number:quotation_model_number, quotation_serial_num:quotation_serial_num, quotation_service_id:quotation_service_id, quotation_part_name:quotation_part_name, quotation_purchase_order:quotation_purchase_order, quotation_part_quantity:quotation_part_quantity, quotation_part_rate:quotation_part_rate, quotation_part_cgst:quotation_part_cgst, quotation_part_sgst:quotation_part_sgst, quotation_part_igst:quotation_part_igst, quotation_hsn_code:quotation_hsn_code, quotation_part_total_price:quotation_part_total_price, type:type}, function(e)
					{
						if(e==1)
						{
						//for getting pdf of the quotation
							// var session_of = quotation_num;
							// var session_name = "pdf_quotation_of";
								
							// $.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
							// {
							// 	if(e ==1)
							// 	{
							// 	//mailing to the customer
							// 		var customer_email = customer.attr('email');
							// 		var website = window.location.hostname;

							// 		var mail_email = customer_email;
							// 		var mail_subject = "Quotation from Voltatech";
							// 		var mail_header = "From: voltatech@pnds.in";
							// 		var mail_body = "Dear Customer \nQuotation generated from our online resource is linked with this mail. Please find your quotation by following the link: http://" + website + "/quotation/Quotation-" + quotation_num + ".pdf \n \nRegards \nVoltatech \nhttp://" + website;

							// 		$.post('php/mailing.php', {mail_email: mail_email, mail_subject: mail_subject, mail_header:mail_header, mail_body:mail_body}, function(e)
							// 		{
							// 			if(e == 1)
							// 			{

							// 			}
							// 			else
							// 			{
							// 				$('.gen_quotation_span').text('something went wrong while mailing the customer.').css('color','red');
							// 			}
							// 		});

							// 		window.open('php/quotation_pdf.php', '_blank');	
							// 	}
							// 	else
							// 	{
							// 		$('.warn_box').text("Something went wrong while generating pdf file of the quotation.");
							// 		$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
							// 	}
							// });

						//disappearing the user entry form
							$('.user_entry_form').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);
							
							$('.gen_quotation_span').text('Quotation has been successfully created.').css('color','green');
							//$('#gen_new_quotation_button').fadeIn(100);
							$('.view_quotation_button').fadeIn(100);
						}
						else
						{
							$('.gen_quotation_span').text('Something went wrong while creating purchase').css('color','red');
						}
					});				
				}
			}
			else
			{
				$('.gen_quotation_span').text('Please fill all the details').css('color','red');
			}
		});

	//on clicking on add new quotation button
		// $('#gen_new_quotation_button').click(function()
		// {
		// 	$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_sales_quotation.php');
		// });

	//on clicking on view quotation button
		$('.view_quotation_button').click(function()
		{	
		
		});
	
	</script>