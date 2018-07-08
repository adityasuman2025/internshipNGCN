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
					$get_brand_query = "SELECT * FROM customers WHERE creator_branch_code = '$creator_branch_code' ORDER BY id DESC";
					$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

					while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
					{
						$company_name = $get_brand_result['company_name'];
						$name = $get_brand_result['name'];

						echo "<option value=\"$name\" customer_company=\"$company_name\">";
							echo $name . ", $company_name";
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

				$website = $_SERVER['HTTP_HOST'];
				if($website == "localhost" OR $website == "volta.pnds.in" OR $website == "erp.voltatech.in")
				{
					$comp_code = "VOLTA/";
				}
				else if($website == "oxy.pnds.in")
				{
					$comp_code = "OXY/";
				}
				else
				{
					$comp_code = "VOLTA/";
				}		

				$quotation_code = $comp_code . $this_year . "-" . $next_year . "/" . $new_quotation_num;
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
					<th>Product/Part Name</th>
					<th>Product/Part Code</th>
					<th>HSN Code</th>
					<th>Description</th>
					
					<th>Service ID</th>
					<th>Quantity</th>
					<th>Rate</th>
					<th>Discount</th>

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
					<td><input type="number" value="0" id="quotation_discount"></td>

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
		
		<button style="color: #cc0000; background: lightgrey;" id="add_note_button">Add Note</button>
		<br>

		<div style="display: none;" id="add_note_div">
			<b style="font-size: 120%;">Add Note</b>
			<br>

			<textarea id="invoice_note" style="width: 840px; height: 300px; resize: none;"></textarea>
		</div>

		<br><br>
		<b>Advance</b>
		<input type="number" id="advance_payment">

		<br><br>
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

	//on clicking on add note button
		$('#add_note_button').click(function()
		{
			$(this).fadeOut(0);
			$('#add_note_div').fadeIn(200);
		});

	//on clicking on create inventory button
		$('.create_inventory_button').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_inventory.php');
		});

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
				$('.gen_quotation_span').text("");

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
				$('.gen_quotation_span').text("Please select a type.").css('color', 'red');
			}
		});
	
	//on clicking on go on search product or part code
		$('#quotation_search_code_button').click(function()
		{
			var search_type = $('#quotation_search_type').val();			
			var model_number = $('#quotation_search_input').val();

			if(search_type !="" && model_number !="")
			{
				$('.gen_quotation_span').text('');

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

			}
			else
			{
				$('.gen_quotation_span').text('Please select Type and Product/Part/Service Code.').css('color', 'red');
			}
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
			var discount = parseInt($(this).parent().parent().find('#quotation_discount').val());

			var cgst_rate = parseInt($(this).parent().parent().find('#quotation_part_cgst').val());
			var discount_amount = discount*quantity*rate/100;

			var cgst_amount = (rate*quantity - discount_amount)*cgst_rate/100;

			$(this).parent().parent().find('#quotation_cgst_amount').val(cgst_amount);
		});

	//on entering sgst rate
		$('.quotation_entry_table tr #quotation_part_sgst').keyup(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var discount = parseInt($(this).parent().parent().find('#quotation_discount').val());

			var sgst_rate = parseInt($(this).parent().parent().find('#quotation_part_sgst').val());
			var discount_amount = discount*quantity*rate/100;

			var sgst_amount = (rate*quantity - discount_amount)*sgst_rate/100;

			$(this).parent().parent().find('#quotation_sgst_amount').val(sgst_amount);
		});

	//on entering igst rate
		$('.quotation_entry_table tr #quotation_part_igst').keyup(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var discount = parseInt($(this).parent().parent().find('#quotation_discount').val());

			var igst_rate = parseInt($(this).parent().parent().find('#quotation_part_igst').val());
			var discount_amount = discount*quantity*rate/100;

			var igst_amount = (rate*quantity - discount_amount)*igst_rate/100;

			$(this).parent().parent().find('#quotation_igst_amount').val(igst_amount);
		});

	//on clicking on calculate
		$('.quotation_entry_table tr #quotation_part_total_price').click(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var discount = parseInt($(this).parent().parent().find('#quotation_discount').val());

			var cgst_amount = parseFloat($(this).parent().parent().find('#quotation_cgst_amount').val());
			var sgst_amount = parseFloat($(this).parent().parent().find('#quotation_sgst_amount').val());
			var igst_amount =parseFloat( $(this).parent().parent().find('#quotation_igst_amount').val());

			var discount_amount = discount*quantity*rate/100;
			var price = quantity*rate;
			var total_price = quantity*rate - discount_amount +  cgst_amount + sgst_amount + igst_amount;

			$(this).val(total_price);
			//alert(total_price);
		});

	//on change of quantity, rate or gst after calculation
		$('.quotation_entry_table tr #quotation_discount').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');

		//updating gst amounts
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
			var discount = $(this).val();

			var cgst_rate = parseInt($(this).parent().parent().find('#quotation_part_cgst').val());
			var sgst_rate = parseInt($(this).parent().parent().find('#quotation_part_sgst').val());
			var igst_rate = parseInt($(this).parent().parent().find('#quotation_part_igst').val());

			var discount_amount = discount*quantity*rate/100;

			var cgst_amount = (rate*quantity - discount_amount)*cgst_rate/100;
			var sgst_amount = (rate*quantity - discount_amount)*sgst_rate/100;
			var igst_amount = (rate*quantity - discount_amount)*igst_rate/100;

			$(this).parent().parent().find('#quotation_cgst_amount').val(cgst_amount);
			$(this).parent().parent().find('#quotation_sgst_amount').val(sgst_amount);
			$(this).parent().parent().find('#quotation_igst_amount').val(igst_amount);
		});

		$('.quotation_entry_table tr #quotation_part_quantity').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');

		//updating gst amounts
			var quantity = $(this).val();
			var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
			var discount = parseInt($(this).parent().parent().find('#quotation_discount').val());

			var cgst_rate = parseInt($(this).parent().parent().find('#quotation_part_cgst').val());
			var sgst_rate = parseInt($(this).parent().parent().find('#quotation_part_sgst').val());
			var igst_rate = parseInt($(this).parent().parent().find('#quotation_part_igst').val());

			var discount_amount = discount*quantity*rate/100;

			var cgst_amount = (rate*quantity - discount_amount)*cgst_rate/100;
			var sgst_amount = (rate*quantity - discount_amount)*sgst_rate/100;
			var igst_amount = (rate*quantity - discount_amount)*igst_rate/100;

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
			var discount = parseInt($(this).parent().parent().find('#quotation_discount').val());

			var cgst_rate = parseInt($(this).parent().parent().find('#quotation_part_cgst').val());
			var sgst_rate = parseInt($(this).parent().parent().find('#quotation_part_sgst').val());
			var igst_rate = parseInt($(this).parent().parent().find('#quotation_part_igst').val());

			var discount_amount = discount*quantity*rate/100;

			var cgst_amount = (rate*quantity - discount_amount)*cgst_rate/100;
			var sgst_amount = (rate*quantity - discount_amount)*sgst_rate/100;
			var igst_amount = (rate*quantity - discount_amount)*igst_rate/100;

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
	
	//on clicking on add new item button
		$('#add_new_goods_button').click(function()
		{
			var way = "add";
			
			$.post('php/add_new_quotation_item.php', {way:way},function(data)
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
			quotation_num = $.trim($('#quotation_num').attr('quotation_num'));		

			var quotation_customer = $.trim($('#quotation_customer').val());
			var quotation_customer_company = $.trim($('#quotation_customer option:selected').attr('customer_company'));

			var quotation_date = $.trim($('#quotation_date').val());

			var advance_payment = $.trim($('#advance_payment').val());
			var invoice_note = $.trim($('#invoice_note').val());

			if(quotation_customer !="" && quotation_date !="" && quotation_num !="")
			{
				$(this).fadeOut(0);
			
			//adding note in the database
				var query_recieved = "INSERT INTO notes VALUES('', '" + quotation_num + "', '" + invoice_note + "')";
				//alert(query_recieved);
				$.post('php/query_runner.php', {query_recieved: query_recieved}, function(e)
				{
					
				});

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
					var quotation_discount = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_discount').val());

					var quotation_part_cgst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_cgst').val());
					var quotation_part_sgst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_sgst').val());
					var quotation_part_igst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_igst').val());

					var quotation_part_total_price = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_total_price').val());
					
					var quotation_serial_num = "";
					
					var quotation_purchase_order ="";

				//if user forget to calculate total price
					if(quotation_part_total_price == "calculate")
					{
						var cgst_amount = parseFloat($(this).parent().parent().find('#quotation_cgst_amount').val());
						var sgst_amount = parseFloat($(this).parent().parent().find('#quotation_sgst_amount').val());
						var igst_amount =parseFloat( $(this).parent().parent().find('#quotation_igst_amount').val());

						var discount_amount = quotation_discount*quotation_part_quantity*quotation_part_rate/100;

						var quotation_part_total_price = quotation_part_quantity*quotation_part_rate - discount_amount +  cgst_amount + sgst_amount + igst_amount;
					}

				//adding this to database					
					$.post('php/create_quotation.php', {quotation_customer: quotation_customer, quotation_customer_company: quotation_customer_company, quotation_date: quotation_date, quotation_num:quotation_num, quotation_serial:quotation_serial, quotation_description:quotation_description, quotation_item_type:quotation_item_type, quotation_brand:quotation_brand, quotation_model_name:quotation_model_name, quotation_model_number:quotation_model_number, quotation_serial_num:quotation_serial_num, quotation_service_id: quotation_service_id, quotation_purchase_order:quotation_purchase_order, quotation_part_quantity:quotation_part_quantity, quotation_part_rate:quotation_part_rate, quotation_discount:quotation_discount, quotation_part_cgst:quotation_part_cgst, quotation_part_sgst:quotation_part_sgst, quotation_part_igst:quotation_part_igst, quotation_hsn_code:quotation_hsn_code, quotation_part_total_price:quotation_part_total_price, advance_payment: advance_payment}, function(e)
					{
						if(e==1)
						{
						//disappearing the user entry form
							$('.user_entry_form').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);	
							$('.gen_quotation_span').text('Quotation has been successfully created.').css('color','green');
							
						//asking to mail or not
							$('.ask_mailing_div').fadeIn(100);
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