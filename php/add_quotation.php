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
			<b>If you could not find the product or part. Create the inventory here.</b>
			<button class="create_inventory_button">Create Inventory</button>
		</div>
		<br>

	<!----------search bar---------->
		<div class="quotation_search_div">
			<h4>Search For Product/Part/Service Code</h4>
			<br>
			
			<!-- <div>
				<b>Select Row Number</b>
				<br>
				<select id="quotation_search_row_no">
					<option value=""></option>
				</select>
			</div> -->

			<div>
				<b>Type</b>
				<br>
				<select id="quotation_search_type">
					<option value=""></option>
					<option value="product">Product</option>
					<option value="part">Part</option>
					<option value="service">Service</option>
				</select>
			</div>
			
			<div>
				<b>Search Product/Part/Service Code</b>
				<br>
				<input type="text" id="quotation_search_input">
				<br>
				<div id="quotation_search_suggestion">hello</div>
			</div>
			<br>

			<button id="quotation_search_code_button">Go</button>
		</div>
		<br><br>

	<!--------goods table------->
		<table class="quotation_entry_table">
			<tbody>
				<tr>		
					<th>Type</th>
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
						<select id="quotation_item_type">
							<option value=""></option>
							<option value="product">Product</option>
							<option value="part">Part</option>
							<option value="service">Service</option>
						</select>
					</td>
					
					<td>
						<select id="quotation_brand">
							<option value=""></option>
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

	<div class="ask_mailing_div">
		<span>Do you want to mail the quotation to the customer</span>
		<input type="submit" value="Yes" id="mail_yes">
		<input type="submit" value="No" id="mail_no">
	</div>

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

	//on choosing a row for search bar
		// $('#quotation_search_row_no').focus(function()
		// {
		// 	$(this).html("<option value=''></option>");

		// 	var row_count = $('.quotation_entry_table tr').length;
		// 	var actual_row_count = row_count - 1;

		// 	var i;
		// 	for (i = 1; i <= actual_row_count; i++) 
		// 	{ 
		// 	    $(this).append("<option value='" + i + "'>" + i + "</option>");
		// 	}
		// });

	//on choosing a search type
		$('#quotation_search_type').change(function()
		{
			 $("#quotation_search_input").val('');
		});

	//defining the width and position of search suggestion div
		var search_input_width = $("#quotation_search_input").width();
		$('#quotation_search_suggestion').css('width', search_input_width + 'px');
		
		var search_input_position = $("#quotation_search_input").position();
		var quotation_search_suggestion_top_position = search_input_position.top + 32;
		var quotation_search_suggestion_left_position = search_input_position.left;

		$('#quotation_search_suggestion').css('top', quotation_search_suggestion_top_position + 'px').css('left', quotation_search_suggestion_left_position + 'px');
   		
	//on typing in the search input field
		$('#quotation_search_input').keyup(function()
		{
			var search_type = $('#quotation_search_type').val();
			// var row_no = $('#quotation_search_row_no').val();

			if(search_type !="")
			{
				var search_input = $(this).val();

				var query = "SELECT model_number FROM inventory WHERE type = '" + search_type + "' AND model_number LIKE '" + search_input + "%'";
				var to_get = "model_number";

				$.post('php/query_search_result.php', {query:query, to_get:to_get}, function(data)
				{
					$('#quotation_search_suggestion').fadeIn().html(data);

					$('#quotation_search_suggestion div').click(function()
					{
						var selected_input = $.trim($(this).text());
						$('#quotation_search_input').val(selected_input);
						$('#quotation_search_suggestion').fadeOut();
					});
				});
			}
			else
			{
				$('.warn_box').text("Please select a row number and type.");
				$('.warn_box').fadeIn(200).delay(2000).fadeOut(200);
			}
		});
	
	//on clicking on go on search product or part code
		$('#quotation_search_code_button').click(function()
		{
			// var row_no = parseInt($('#quotation_search_row_no').val());
			var search_type = $('#quotation_search_type').val();			
			var model_number = $('#quotation_search_input').val();
			// var original_row_no = row_no + 1;

		//populating type and model number from the search input
			// $('.quotation_entry_table tr:nth-child('+ original_row_no + ') #quotation_item_type').html("<option value ='"+ search_type + "'>" + search_type + "</option>");

			// $('.quotation_entry_table tr:nth-child('+ original_row_no + ') #quotation_model_number').html("<option value ='"+ model_number + "'>" + model_number + "</option>");

			$('.quotation_entry_table tr:last #quotation_item_type').html("<option value ='"+ search_type + "'>" + search_type + "</option>");

			$('.quotation_entry_table tr:last #quotation_model_number').html("<option value ='"+ model_number + "'>" + model_number + "</option>");

		//getting brand
			var query = "SELECT brand FROM inventory WHERE model_number ='" + model_number + "' AND type='" + search_type + "'";
			var to_get = "brand";

			$.post('php/inventory_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				$('.quotation_entry_table tr:last #quotation_brand').html(data);
			});

		//getting model name
			var query = "SELECT model_name FROM inventory WHERE model_number ='" + model_number + "' AND type='" + search_type + "'";
			var to_get = "model_name";

			$.post('php/inventory_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('.quotation_entry_table tr:last #quotation_model_name').html(data);
			});

		//getting hsn code
			var query = "SELECT hsn_code FROM inventory WHERE model_number ='" + model_number + "' AND type='" + search_type + "'";
			var to_get = "hsn_code";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('.quotation_entry_table tr:last #quotation_hsn_code').val(data);
			});

		//getting description
			var query = "SELECT description FROM inventory WHERE model_number ='" + model_number + "' AND type='" + search_type + "'";
			var to_get = "description";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('.quotation_entry_table tr:last #quotation_description').val(data);
			});
		});

	//on selecting a item type
		$('.quotation_entry_table tr #quotation_item_type').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			this_thing = $(this);
			type = $(this).val();
			var query = "SELECT brand FROM inventory WHERE type= '" + type + "' GROUP BY brand";
			var to_get = "brand";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				this_thing.parent().parent().find('#quotation_brand').html(data);
			});
		});

	//on selecting a brand
		$('.quotation_entry_table tr #quotation_brand').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			this_thing = $(this);
			brand = $(this).val();
			var query = "SELECT model_name FROM inventory WHERE brand ='" + brand + "' AND type= '" + type + "' GROUP BY model_name";
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
			var query = "SELECT model_number FROM inventory WHERE model_name ='" + model_name + "' AND brand ='" + brand + "' AND type= '" + type + "'  GROUP BY model_number";
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
			var query = "SELECT hsn_code FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND type= '" + type + "' ";
			var to_get = "hsn_code";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#quotation_hsn_code').val(data);
			});

		//populating description
			var query = "SELECT description FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND type= '" + type + "' ";
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
			quotation_num = $.trim($('#quotation_num').attr('quotation_num'));		

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

					var quotation_item_type = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_item_type').val());
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
					$.post('php/create_quotation.php', {quotation_customer: quotation_customer, quotation_date: quotation_date, quotation_num:quotation_num, quotation_serial:quotation_serial, quotation_description:quotation_description, quotation_item_type:quotation_item_type, quotation_brand:quotation_brand, quotation_model_name:quotation_model_name, quotation_model_number:quotation_model_number, quotation_serial_num:quotation_serial_num, quotation_service_id:quotation_service_id, quotation_part_name:quotation_part_name, quotation_purchase_order:quotation_purchase_order, quotation_part_quantity:quotation_part_quantity, quotation_part_rate:quotation_part_rate, quotation_part_cgst:quotation_part_cgst, quotation_part_sgst:quotation_part_sgst, quotation_part_igst:quotation_part_igst, quotation_hsn_code:quotation_hsn_code, quotation_part_total_price:quotation_part_total_price}, function(e)
					{
						if(e==1)
						{
						//disappearing the user entry form
							$('.user_entry_form').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);	
							$('.gen_quotation_span').text('Quotation has been successfully created.').css('color','green');
							
						//asking to mail or not
							$('.ask_mailing_div').fadeIn(100);

							// //if user click on yes
							// 	$('#mail_yes').click(function()
							// 	{
							// 		$('.ask_mailing_div').fadeOut(0);

							// 	//setting quotation view session
							// 		var session_of = quotation_num;
							// 		var session_name = "pdf_quotation_of";

							// 		$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
							// 		{
							// 			if(e ==1)
							// 			{
							// 			//setting mailing session
							// 				var session_of = 'yes';
							// 				var session_name = "mail_pdf_of_" + quotation_num;
							// 				var visibility = "hide";

							// 				$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
							// 				{
							// 					if(e ==1)
							// 					{
							// 						window.open('php/quotation_pdf.php', '_blank');	
							// 					}
							// 					else
							// 					{
							// 						$('.warn_box').text("Something went wrong while mailing the customer.");
							// 						$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
							// 					}
							// 				});
							// 			}
							// 			else
							// 			{
							// 				$('.warn_box').text("Something went wrong while generating pdf file of the quotation.");
							// 				$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
							// 			}
							// 		});
							// 	});

							// //if user click on no
							// 	$('#mail_no').click(function()
							// 	{
							// 		$('.ask_mailing_div').fadeOut(0);
							// 	});
						}
						else
						{
							$('.gen_quotation_span').text('Something went wrong while creating quotation').css('color','red');
						}
					});				
				}
			}
			else
			{
				$('.gen_quotation_span').text('Please fill all the details').css('color','red');
			}
		});

	//if user click on yes
		$('#mail_yes').click(function()
		{
			$('.ask_mailing_div').fadeOut(0);

		//setting quotation view session
			var session_of = quotation_num;
			var session_name = "pdf_quotation_of";

			$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
			{
				if(e ==1)
				{
				//setting mailing session
					var session_of = 'yes';
					var session_name = "mail_pdf_of_" + quotation_num;
					var visibility = "hide";

					$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
					{
						if(e ==1)
						{
							window.open('php/quotation_pdf.php', '_blank');	
						}
						else
						{
							$('.warn_box').text("Something went wrong while mailing the customer.");
							$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
						}
					});
				}
				else
				{
					$('.warn_box').text("Something went wrong while generating pdf file of the quotation.");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});

	//if user click on no
		$('#mail_no').click(function()
		{
			$('.ask_mailing_div').fadeOut(0);
		});

	//on clicking on view quotation button
		$('.view_quotation_button').click(function()
		{	
			var session_of = quotation_num;
			var session_name = "pdf_quotation_of";

			$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
			{
				if(e ==1)
				{
					window.open('php/quotation_pdf.php', '_blank');	
				}
				else
				{
					$('.warn_box').text("Something went wrong while generating pdf file of the quotation.");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});
	
	</script>