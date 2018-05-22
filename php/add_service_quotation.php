<?php
	include 'connect_db.php';
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	session_start();
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
					$get_brand_query = "SELECT name FROM customers WHERE creator_branch_code = '$creator_branch_code'";
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
		<br><br>

	<!------inventory selection-------->
		<div>
			<b>Brand:</b>
			<br>
			<select id="quotation_brand">
				<option value=""></option>
					<?php
						$get_brand_query = "SELECT brand FROM stock WHERE creator_branch_code = '$creator_branch_code' GROUP BY brand";
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

			</select>
		</div>
		
		<div>
			<b>Model Number:</b>
			<br>
			<select id="quotation_model_number">

			</select>
		</div>
		<br><br>

	<!------serial number and serial id-------->
		<div>
			<input type="text" placeholder="Serial Number" id="quotation_serial_num">
		</div>

		<div>
			<input type="text" placeholder="Service ID" id="quotation_service_id">
		</div>
		<br>
		<br>

	<!--------part table------->
		<table class="quotation_entry_table">
			<tr>
				<!-- <th>S. No</th> -->
				<th>Description</th>
				<th>Part Name</th>
				<th>Part Serial Number</th>
				<th>Available In-Stock</th>
				<th>Quantity</th>
				<th>Rate</th>
				<th>CGST (in %)</th>
				<th>SGST (in %)</th>
				<th>IGST (in %)</th>
				<th>HSN Code</th>
				<th>Price</th>
				<th>Action</th>
			</tr>

			<tr>
				<!-- <td><input type="number" value="1" disabled="disabled" id="quotation_serial"></td> -->
				<td><input type="text" id="quotation_description"></td>
			
				<td>
					<select id="quotation_part_name">
						<option value=""></option>					
					</select>
				</td>
				<td><input type="text" id="quotation_part_serial_num"></td>

				<td><input type="number" disabled="disabled" id="quotation_part_in_stock"></td>
				<td><input type="number" value="0" id="quotation_part_quantity"></td>
				<td><input type="number" value="0" id="quotation_part_rate"></td>
				<td><input type="number" value="0" id="quotation_part_cgst"></td>
				<td><input type="number" value="0" id="quotation_part_sgst"></td>
				<td><input type="number" value="0" id="quotation_part_igst"></td>
				<td><input type="text" id="quotation_part_hsn_code"></td>

				<td><input type="button" style="background: #cc0000; color: white; margin: 2px; width: auto;" value="calculate" id="quotation_part_total_price"></td>
				<td>
					<img class="item_delete_icon" src="img/delete.png">
				</td>
			</tr>

		</table>
		<br>

		<button id="add_new_part_button">Add New Item</button>
		<br><br><br>

		<input type="button" value="Generate Quotation" id="quotation_gen_button">
		<input type="button" value="Generate Invoice" id="invoice_gen_button">
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

	//on selecting a brand
		$('#quotation_brand').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			brand = $(this).val();
			var query = "SELECT model_name FROM stock WHERE brand ='" + brand + "' AND creator_branch_code = '" + creator_branch_code + "' GROUP BY model_name";
			var to_get = "model_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#quotation_model_name').html(data);
			});
		});

	//on selecting a model_name
		$('#quotation_model_name').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			model_name = $(this).val();
			var query = "SELECT model_number FROM stock WHERE model_name ='" + model_name + "' AND brand ='" + brand + "' AND creator_branch_code = '" + creator_branch_code + "' GROUP BY model_number";
			var to_get = "model_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#quotation_model_number').html(data);
			});
		});

	//on selecting a model_number
		$('#quotation_model_number').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			model_number = $(this).val(); //making it universal variable to use in, on selecting a part_name
			var query = "SELECT part_name FROM stock WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND creator_branch_code = '" + creator_branch_code + "' GROUP BY part_name";
			var to_get = "part_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#quotation_part_name').html(data);
			});
		});

	//on selecting a part_name
		$('#quotation_part_name').change(function()
		{
			$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
					
			$(this).attr('disabled', 'disabled');

			part_name = $(this).val();
			
		//checking the availability of that item in stock
			var query = "SELECT in_stock FROM stock WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND part_name = '" + part_name + "' AND creator_branch_code = '" + creator_branch_code + "'";
			var to_get = "in_stock";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				if(data == '0') //if the item is not available in stock
				{
					$('.gen_quotation_span').html('The item you have selected is not available at your branch. Click on See Availability button to see the availability of that item in different branches').css('color', 'red');
					
				//for generating see availability button
					$('#quotation_part_in_stock').attr('type', 'button').attr('value', 'See Availability').attr('disabled', false).addClass('change_branch_button').css('background', '#cc0000').css('color', 'white');

					$('.change_branch_button').click(function()
					{
						$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/search_item_in_branch.php');
					});
				}
				else
				{
					$('#quotation_part_in_stock').val(data);
					$('.gen_quotation_span').html('');
				}
			});

		});

	//on clicking on calculate
		$('#quotation_part_total_price').click(function()
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
		$('#quotation_part_quantity').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

		$('#quotation_part_rate').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

		$('#quotation_part_cgst').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

		$('#quotation_part_sgst').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

		$('#quotation_part_igst').keyup(function()
		{
			$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
		});

	//on clicking on delete part button
		$('.item_delete_icon').click(function()
		{
			$(this).parent().parent().remove();
		});
	
	//on clicking on add new part button
		$('#add_new_part_button').click(function()
		{
			var model_name = $.trim($('#quotation_model_name').val());
			var model_number = $.trim($('#quotation_model_number').val());

			if(model_number != "" && model_name != "")
			{
				$.post('php/add_new_part_form.php', {},function(data)
				{
					$('.quotation_entry_table tbody').append(data);
				});
			}
			else
			{
				$('.warn_box').html("Choose a Model first");
				$('.warn_box').fadeIn(200).delay(2000).fadeOut(200);

				$("#quotation_model_name, #quotation_model_number").css('border', '1.5px red solid'); //making border of model name & model number input area red
			}
		});

	//on clicking on generate quotation button
		$('#quotation_gen_button').click(function()
		{			
			$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
		//getting variable values
			var quotation_customer = $.trim($('#quotation_customer').val());
			var quotation_date = $.trim($('#quotation_date').val());
			var quotation_num = $.trim($('#quotation_num').attr('quotation_num'));
			
			var quotation_brand = $.trim($('#quotation_brand').val());
			var quotation_model_name = $.trim($('#quotation_model_name').val());
			var quotation_model_number = $.trim($('#quotation_model_number').val());
			var quotation_serial_num = $.trim($('#quotation_serial_num').val());
			var quotation_service_id = $.trim($('#quotation_service_id').val());

		//checking if all fields are filled or not
			if(quotation_customer !="" && quotation_date !="" && quotation_num !="" && quotation_brand !="" && quotation_model_name !="" && quotation_model_number !="" && quotation_serial_num !="")
			{
			//for getting inputs of each of the row
				var count = $(".quotation_entry_table tr").length;
				var row_count = count -1;

				var i = 1;
				for(i; i<= row_count; i++)
				{
					var child_no = i + 1;

				//defining variables of each table row
					var quotation_serial = child_no - 1;
					var quotation_description = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_description').val());
					
					var quotation_part_name = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_name').val());
					var quotation_part_serial_num = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_serial_num').val());

					var quotation_part_quantity = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_quantity').val());
					var quotation_part_rate = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_rate').val());
					var quotation_part_cgst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_cgst').val());
					var quotation_part_sgst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_sgst').val());
					var quotation_part_igst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_igst').val());

					var quotation_part_hsn_code = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_hsn_code').val());
					var quotation_part_total_price = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_total_price').val());
					var type = "service";

					//alert(quotation_part_serial_num);

				//if user forget to calculate total price
					if(quotation_part_total_price == "calculate")
					{
						var quotation_part_total_price = (quotation_part_rate + (quotation_part_rate * (quotation_part_cgst+quotation_part_sgst+quotation_part_igst)/100))*quotation_part_quantity;
					}
					
				//adding this to database					
					$.post('php/create_quotation.php', {quotation_customer: quotation_customer, quotation_date: quotation_date, quotation_num:quotation_num, quotation_brand:quotation_brand, quotation_model_name:quotation_model_name, quotation_model_number:quotation_model_number, quotation_serial_num:quotation_serial_num, quotation_service_id:quotation_service_id, quotation_serial:quotation_serial, quotation_description:quotation_description, quotation_part_name:quotation_part_name, quotation_part_serial_num:quotation_part_serial_num, quotation_part_quantity:quotation_part_quantity, quotation_part_rate:quotation_part_rate, quotation_part_cgst:quotation_part_cgst, quotation_part_sgst:quotation_part_sgst, quotation_part_igst:quotation_part_igst, quotation_part_hsn_code:quotation_part_hsn_code, quotation_part_total_price:quotation_part_total_price, type:type}, function(e)
					{
						if(e==1)
						{
						//disappearing the user entry form
							$('.user_entry_form').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);
							
							$('.gen_quotation_span').text('Quotation has been successfully generated').css('color','green');
							$('#gen_new_quotation_button').fadeIn(100);
							$('.view_quotation_button').fadeIn(100);
							
						}
						else
						{
							$('.gen_quotation_span').text('Something went wrong while generating quotation').css('color','red');
						}
					});
				}
			}
			else
			{
				$('.gen_quotation_span').text('Please fill all the details').css('color','red');
			}
		});

	//on clicking on generate invoice button 
		$('#invoice_gen_button').click(function()
		{
		//getting variable values
			var quotation_customer = $.trim($('#quotation_customer').val());
			var quotation_date = $.trim($('#quotation_date').val());
			var quotation_num = $.trim($('#quotation_num').attr('quotation_num'));
			
			var quotation_brand = $.trim($('#quotation_brand').val());
			var quotation_model_name = $.trim($('#quotation_model_name').val());
			var quotation_model_number = $.trim($('#quotation_model_number').val());
			var quotation_serial_num = $.trim($('#quotation_serial_num').val());
			var quotation_service_id = $.trim($('#quotation_service_id').val());

		//checking if all fields are filled or not
			if(quotation_customer !="" && quotation_date !="" && quotation_num !="" && quotation_brand !="" && quotation_model_name !="" && quotation_model_number !="" && quotation_serial_num !="")
			{
			//for getting inputs of each of the row
				var count = $(".quotation_entry_table tr").length;
				var row_count = count -1;

				var i = 1;
				for(i; i<= row_count; i++)
				{
					var child_no = i + 1;

				//defining variables of each table row
					var quotation_serial = child_no - 1;
					var quotation_description = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_description').val());
					
					var quotation_part_name = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_name').val());
					var quotation_part_serial_num = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_serial_num').val());

					var quotation_part_quantity = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_quantity').val());
					var quotation_part_rate = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_rate').val());
					var quotation_part_cgst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_cgst').val());
					var quotation_part_sgst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_sgst').val());
					var quotation_part_igst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_igst').val());

					var quotation_part_hsn_code = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_hsn_code').val());
					var quotation_part_total_price = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_total_price').val());
					var type = "service";

					//alert(quotation_part_serial_num);

				//if user forget to calculate total price
					if(quotation_part_total_price == "calculate")
					{
						//alert('plx calculate');
						var quotation_part_total_price = (quotation_part_rate + (quotation_part_rate * (quotation_part_cgst+quotation_part_sgst+quotation_part_igst)/100))*quotation_part_quantity;
					}

				//adding this to database					
					$.post('php/create_quotation.php', {quotation_customer: quotation_customer, quotation_date: quotation_date, quotation_num:quotation_num, quotation_brand:quotation_brand, quotation_model_name:quotation_model_name, quotation_model_number:quotation_model_number, quotation_serial_num:quotation_serial_num, quotation_service_id:quotation_service_id, quotation_serial:quotation_serial, quotation_description:quotation_description, quotation_part_name:quotation_part_name, quotation_part_serial_num:quotation_part_serial_num, quotation_part_quantity:quotation_part_quantity, quotation_part_rate:quotation_part_rate, quotation_part_cgst:quotation_part_cgst, quotation_part_sgst:quotation_part_sgst, quotation_part_igst:quotation_part_igst, quotation_part_hsn_code:quotation_part_hsn_code, quotation_part_total_price:quotation_part_total_price, type:type}, function(e)
					{
						if(e==1)
						{
							$('.ajax_loader_bckgrnd').fadeIn(400);
					
							$.post('php/quotation_into_invoice.php', {quotation_num:quotation_num}, function(data)
							{
								//alert(data);
								$('.ajax_loader_box').fadeIn(400);
								$('.ajax_loader_content').html(data);
							});	

							$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");

						//disappearing the user entry form
							$('.user_entry_form').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);
							
							$('.gen_quotation_span').text('Quotation has been successfully generated').css('color','green');
							$('#gen_new_quotation_button').fadeIn(100);
							$('.view_quotation_button').fadeIn(100);
							
						}
						else
						{
							$('.gen_quotation_span').text('Something went wrong while generating quotation').css('color','red');
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
		$('#gen_new_quotation_button').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_service_quotation.php');
		});

	//on clicking on view quotation button
		$('.view_quotation_button').click(function()
		{	
		//for getting pdf of the quotation
			var quotation_num = "<?php echo $new_quotation_num?>";
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

			//window.open('php/quotation_pdf.php', '_blank');	
		});

	</script>