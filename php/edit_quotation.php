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
	$customer_company = $query_assoc['customer_company'];

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
				<option customer_company= "<?php echo $customer_company; ?>" value = "<?php echo $customer; ?>" email="<?php echo $get_customer_email; ?>"><?php echo $customer . ", " . $customer_company; ?></option>
				<?php
					$get_brand_query = "SELECT * FROM customers WHERE creator_branch_code = '$creator_branch_code' AND name !='$customer' AND company_name !='$customer_company' ORDER BY id DESC";
					$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

					while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
					{
						$company_name = $get_brand_result['company_name'];
						$name = $get_brand_result['name'];
						$email = $get_brand_result['email'];

						echo "<option value=\"$name\" email=\"$email\" customer_company=\"$company_name\">";
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
			<input type="date" value="<?php echo $date; ?>" id="quotation_date">
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
	
					<th class="to_invoice">Serial No.</th>	
	
					<th>Service ID</th>
					
					<th class="to_invoice">Purchase Order</th>	

					<th>Quantity</th>

					<th class="to_invoice">Availability</th>	

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

				<?php
					$get_more_info_query = "SELECT * FROM quotation WHERE quotation_num = '$quotation_num'";
					$get_more_info_query_run = mysqli_query($connect_link, $get_more_info_query);

					while ($query_assoc = mysqli_fetch_assoc($get_more_info_query_run))
					{
						$quotation_id = $query_assoc['id'];

						$serial = $query_assoc['serial'];

						$item_type = $query_assoc['type'];
						$brand = $query_assoc['brand'];
						$model_name = $query_assoc['model_name'];
						$model_number = $query_assoc['model_number'];
						$hsn_code = $query_assoc['hsn_code'];
						$description = $query_assoc['description'];
						$serial_num = $query_assoc['serial_num'];

						$service_id = $query_assoc['service_id'];
						$purchase_order = $query_assoc['purchase_order'];
						$quantity = $query_assoc['quantity'];
						$rate = $query_assoc['rate'];
						$discount = $query_assoc['discount'];

						$cgst = $query_assoc['cgst'];
						$cgst_amount = ($rate*$cgst/100)*$quantity;

						$sgst = $query_assoc['sgst'];
						$sgst_amount = ($rate*$sgst/100)*$quantity;

						$igst = $query_assoc['igst'];	
						$igst_amount = ($rate*$igst/100)*$quantity;

						$total_price = $query_assoc['total_price'];

						$advance_payment = $query_assoc['advance'];

						echo "<tr id=\"$quotation_id\" fo=\"$serial\">";

							echo "	<td>
										<select id=\"quotation_item_type\">";
											echo "<option value=\"$item_type\">$item_type</option>";

											if($item_type == "product")
											{
												echo "<option value=\"part\">Part</option>";
												echo "<option value=\"service\">Service</option>";
											}
											else if($item_type == "part")
											{
												echo "<option value=\"product\">Product</option>";
												echo "<option value=\"service\">Service</option>";
											}
											else if($item_type == "service")
											{
												echo "<option value=\"product\">Product</option>";
												echo "<option value=\"part\">Part</option>";
											}
											else
											{
												echo "<option value=\"product\">Product</option>";
												echo "<option value=\"part\">Part</option>";
												echo "<option value=\"service\">Service</option>";
											}

									echo "</select>

									</td>";

							echo "<td>
									<select id=\"quotation_brand\">
										<option value=\"$brand\">$brand</option>";
							echo "	</select>	
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
									<td><input type=\"text\" value=\"$description\" id=\"quotation_description\"></td>";
							echo "<td><input type=\"text\" value=\"$serial_num\" id=\"quotation_serial_num\"></td>";

							echo "<td><input type=\"text\" value=\"$service_id\" id=\"quotation_service_id\"></td>";
							echo "<td><input type=\"text\" value=\"$purchase_order\" id=\"quotation_purchase_order\"></td>";

							//for getting availability of the item in the stock
								if($item_type == 'service') //the that item is service then neglecting the availability if that item
								{
									$in_stock = 0;

								//keeping the normal border
									$avail_class = "border: grey 0px solid";
									$fo = "1";
								}
								else
								{
									$query = "SELECT in_stock FROM stock WHERE model_number ='" . $model_number . "' AND model_name = '" . $model_name . "' AND brand = '" . $brand . "' AND type ='" . $item_type . "' AND creator_branch_code ='" . $creator_branch_code . "'";
								
									$query_run = mysqli_query($connect_link, $query);
									$query_fetch_assoc = mysqli_fetch_assoc($query_run);
									$in_stock = $query_fetch_assoc['in_stock'];

									if($in_stock == '')
									{
										$in_stock = 0;
									}

									if($quantity <= $in_stock)
									{
										$avail_class = "border: grey 0px solid";
										$fo = "1";
									}
									else
									{
										$avail_class = "border: red 1px solid";
										$fo = "0";
									}

								}
								
							echo "<td><input type=\"number\" fo=\"$fo\" style=\"$avail_class\" value=\"$quantity\" id=\"quotation_part_quantity\"></td>";
							echo "<td><input type=\"number\" disabled=\"disabled\" value=\"$in_stock\" id=\"item_availability\"></td>";

							echo "	<td><input type=\"number\" value=\"$rate\" id=\"quotation_part_rate\"></td>
									<td><input type=\"number\" value=\"$discount\" id=\"quotation_discount\"></td>";

							echo "<td><input type=\"number\" value=\"$cgst\" id=\"quotation_part_cgst\"></td>";							
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

		<button style="color: #cc0000; background: lightgrey;" id="add_note_button">Edit Note</button>
		<br>

		<div style="display: none;" id="add_note_div">
			<?php
				$get_note_query = "SELECT * FROM notes WHERE quotation_num = '$quotation_num'";
				$get_note_query_run = mysqli_query($connect_link, $get_note_query);

				$get_note_query_assoc = mysqli_fetch_assoc($get_note_query_run);
				$get_note = $get_note_query_assoc['note'];
			?>
			<b style="font-size: 120%;">Add Note</b>
			<br>

			<textarea id="invoice_note" style="width: 840px; height: 300px; resize: none;"><?php echo $get_note; ?></textarea>
		</div>

		<br><br>
		<b>Advance</b>
		<input type="number" value="<?php echo $advance_payment; ?>" id="advance_payment">

		<br><br>
		<input type="button" value="Save Edit" id="quotation_gen_button">

		<input type="button" value="Generate Performa Invoice" quotation_num="<?php echo $quotation_num; ?>" id="performa_invoice_gen_edit_button">

		<input type="button" value="Generate Invoice" id="invoice_gen_edit_button">
		
	</div>
	
	<span class="gen_quotation_span"></span>
	<br><br>

	<div class="ask_mailing_div">
		<span>Do you want to mail the invoice to the customer</span>
		<input type="submit" value="Yes" id="mail_yes">
		<input type="submit" value="No" id="mail_no">
	</div>
<!--------script-------->
	<script type="text/javascript">
	//on clicking on add customer button
		creator_branch_code = "<?php echo $creator_branch_code; ?>";

		$('#quotation_add_customer').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_customer.php');
		});

	//on clicking on edit note button
		$('#add_note_button').click(function()
		{
			$(this).fadeOut(0);
			$('#add_note_div').fadeIn(200);
		});

	//on selecting a item type
		$('.quotation_entry_table tr #quotation_item_type').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			this_thing = $(this);
			type = $(this).val();
			var query = "SELECT brand FROM inventory WHERE type= '" + type + "' GROUP BY brand";
			var to_get = "brand";

		//emptying all the preceeding fields
			this_thing.parent().parent().find('#quotation_brand').html("");
			this_thing.parent().parent().find('#quotation_model_name').html("");
			this_thing.parent().parent().find('#quotation_model_number').html("");
			this_thing.parent().parent().find('#quotation_hsn_code').val("");
			this_thing.parent().parent().find('#quotation_description').val("");
			this_thing.parent().parent().find('#item_availability').val("");

		//populating the brand
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

		//populating availability
			var query = "SELECT in_stock FROM stock WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND type ='" + type + "' AND creator_branch_code ='" + creator_branch_code + "'";
			var to_get = "in_stock";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				if(data == '')
				{
					data = 0;
				}

				this_thing.parent().parent().find('#item_availability').val(data);
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

	//on clicking on delete item button
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
	
	//on clicking on add new item button
		$('#add_new_goods_button').click(function()
		{
			$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
		//getting variable values
			var quotation_num = $.trim("<?php echo $quotation_num; ?>");
			
			var quotation_customer = $.trim($('#quotation_customer').val());
			var quotation_customer_company = $.trim($('#quotation_customer option:selected').attr('customer_company'));

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
					var way = "edit";
					$.post('php/add_new_quotation_item.php', {new_id: new_id, way:way},function(data)
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

	//checking the availability of that product or part in stock
		$('.quotation_entry_table tr #quotation_part_quantity').keyup(function()
		{
			$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");

			this_thing = $(this);			
			var quantity = parseInt($(this).val());
			var in_stock = parseInt(this_thing.parent().parent().find('#item_availability').val());
			var type = this_thing.parent().parent().find('#quotation_item_type').val();
			
			if(type == 'service')
			{
				$('.gen_quotation_span').text("").css('color', 'black');
			}
			else
			{
				if(quantity > in_stock)
				{
					this_thing.css('border', 'red 1px solid');
					$('.gen_quotation_span').text("You have entered a quantity greater than its avavilability in stock. You are not able to generate invoice.").css('color', 'red');
				}
				else
				{
					this_thing.css('border', 'red 0px solid');
					$('.gen_quotation_span').text("").css('color', 'black');
				}	
			}
		});

	//on clicking on save edit button
		$('#quotation_gen_button').click(function()
		{
			$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");

		//disabling the type, brand, model_name, model_number field
			$('select#quotation_item_type').attr("disabled", true);
			$('select#quotation_brand').attr("disabled", true);
			$('select#quotation_model_name').attr("disabled", true);
			$('select#quotation_model_number').attr("disabled", true);	
			$('input#quotation_part_quantity').attr("disabled", true);		
						
		//getting variable values
			var quotation_num = $.trim("<?php echo $quotation_num; ?>");			

			var quotation_customer = $.trim($('#quotation_customer').val());
			var quotation_customer_company = $.trim($('#quotation_customer option:selected').attr('customer_company'));

			var quotation_date = $.trim($('#quotation_date').val());

			var advance_payment = $.trim($('#advance_payment').val());	
			var invoice_note = $.trim($('#invoice_note').val());	
			
			if(quotation_customer !="" && quotation_date !="" && quotation_num !="")
			{
			//updating note in the database
				var query_recieved = "UPDATE notes SET note = '" + invoice_note + "' WHERE quotation_num = '" + quotation_num + "'";
				$.post('php/query_runner.php', {query_recieved: query_recieved}, function(l)
				{
					//alert(l);
				});

			//for getting inputs of each of the row
				var count = $(".quotation_entry_table tr").length;
				var row_count = count -1;

				var i = 1;
				var to_display_button = 0;

				for(i; i<= row_count; i++)
				{
					var child_no = i + 1;

				//defining variables of each table row
					var quotation_id = $('.quotation_entry_table tr:nth-child('+ child_no + ')').attr('id');
					
					var quotation_serial = child_no - 1;

					var quotation_item_type = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_item_type').val());
					var quotation_brand = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_brand').val());
					var quotation_model_name = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_model_name').val());
					var quotation_model_number = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_model_number').val());
					var quotation_part_hsn_code = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_hsn_code').val());
					var quotation_description = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_description').val());

					var quotation_service_id = $('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_service_id').val();
					var quotation_purchase_order =$('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_purchase_order').val();
					var quotation_part_quantity = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_quantity').val());
					var quotation_part_rate = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_rate').val());
					var quotation_discount = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_discount').val());

					var quotation_part_cgst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_cgst').val());
					var quotation_part_sgst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_sgst').val());
					var quotation_part_igst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_igst').val());
					var quotation_part_total_price = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_total_price').val());
					
					var in_stock = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #item_availability').val());

					var quotation_serial_num = "";

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
					var query_recieved = "UPDATE quotation SET type = '" + quotation_item_type + "', serial = '" + quotation_serial + "', description = '" + quotation_description + "', customer ='" + quotation_customer + "', customer_company = '" + quotation_customer_company + "', date ='" + quotation_date + "', brand = '" + quotation_brand + "', model_name = '" + quotation_model_name + "', model_number = '" + quotation_model_number + "', serial_num = '" + quotation_serial_num + "', service_id = '" + quotation_service_id + "', quantity = '" + quotation_part_quantity + "', rate = '" + quotation_part_rate + "', discount ='" + quotation_discount + "', cgst = '" + quotation_part_cgst + "', sgst = '" + quotation_part_sgst + "', igst = '" + quotation_part_igst + "', hsn_code = '" + quotation_part_hsn_code + "', total_price = '" + quotation_part_total_price + "', purchase_order = '" +  quotation_purchase_order + "', advance = '" + advance_payment + "' WHERE id = '" + quotation_id + "'";
				
					$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
					{
						if(e==1)
						{						
						//disappearing the user entry form														
							$('#quotation_gen_button').fadeOut();
							$('#add_new_goods_button').fadeOut();

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

	//on clicking on add invoice button
		$('#invoice_gen_edit_button').click(function()
		{
			$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			quotation_num = $.trim("<?php echo $quotation_num; ?>");
			
		//getting variable values
			var quotation_customer = $.trim($('#quotation_customer').val());
			var quotation_customer_company = $.trim($('#quotation_customer option:selected').attr('customer_company'));

			var quotation_date = $.trim($('#quotation_date').val());

			var advance_payment = $.trim($('#advance_payment').val());
			var invoice_note = $.trim($('#invoice_note').val());	
			
			if(quotation_customer !="" && quotation_date !="" && quotation_num !="")
			{

			//updating note in the database
				var query_recieved = "UPDATE notes SET note = '" + invoice_note + "' WHERE quotation_num = '" + quotation_num + "'";
				$.post('php/query_runner.php', {query_recieved: query_recieved}, function(l)
				{
					//alert(l);
				});
			
			//for getting inputs of each of the row
				var count = $(".quotation_entry_table tr").length;
				var row_count = count -1;

				var i = 1;
				var to_display_button = 0;

				for(i; i<= row_count; i++)
				{
					var child_no = i + 1;

				//defining variables of each table row
					var quotation_id = $('.quotation_entry_table tr:nth-child('+ child_no + ')').attr('id');					

					var quotation_serial = child_no - 1;

					var quotation_item_type = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_item_type').val());
					var quotation_brand = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_brand').val());
					var quotation_model_name = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_model_name').val());
					var quotation_model_number = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_model_number').val());
					var quotation_part_hsn_code = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_hsn_code').val());
					var quotation_description = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_description').val());

					var quotation_service_id = $('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_service_id').val();
					var quotation_part_quantity = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_quantity').val());
					var quotation_part_rate = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_rate').val());
					var quotation_discount = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_discount').val());

					var quotation_part_cgst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_cgst').val());
					var quotation_part_sgst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_sgst').val());
					var quotation_part_igst = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_igst').val());
					var quotation_part_total_price = $.trim($('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_part_total_price').val());
					
					var quotation_serial_num =$('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_serial_num').val();
					var quotation_purchase_order =$('.quotation_entry_table tr:nth-child('+ child_no + ') #quotation_purchase_order').val();
					
					var in_stock = parseInt($('.quotation_entry_table tr:nth-child('+ child_no + ') #item_availability').val())

				//checking availability
					if(quotation_item_type == 'service')
					{
						to_display_button = to_display_button + 0;
					}
					else
					{
						if(quotation_part_quantity > in_stock)
						{
							to_display_button = to_display_button + 1;
						}
						else
						{
							to_display_button = to_display_button + 0;
						}	
					}

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
					var query_recieved = "UPDATE quotation SET type = '" + quotation_item_type + "', serial = '" + quotation_serial + "', description = '" + quotation_description + "', customer ='" + quotation_customer + "', customer_company = '" + quotation_customer_company + "', date ='" + quotation_date + "', brand = '" + quotation_brand + "', model_name = '" + quotation_model_name + "', model_number = '" + quotation_model_number + "', serial_num = '" + quotation_serial_num + "', service_id = '" + quotation_service_id + "', purchase_order = '" + quotation_purchase_order + "', quantity = '" + quotation_part_quantity + "', rate = '" + quotation_part_rate + "', discount ='" + quotation_discount + "', cgst = '" + quotation_part_cgst + "', sgst = '" + quotation_part_sgst + "', igst = '" + quotation_part_igst + "', hsn_code = '" + quotation_part_hsn_code + "', total_price = '" + quotation_part_total_price + "', date_of_payment='', advance='" + advance_payment + "' WHERE id = '" + quotation_id + "'";
						
					$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
					{
						if(e==1)
						{
						//showing the payment method and payment date input field
							var generated_from = "invoice";
							$.post('php/quotation_into_invoice.php', {quotation_num:quotation_num, generated_from:generated_from}, function(data)
							{
								$('.ajax_loader_content').html(data);
							});

						//checking availability 														
						    if(to_display_button == "0") //good to generate invoice
						    {
						    	$('.ajax_loader_box').fadeIn(0);
								
						    	$('.user_entry_form').fadeOut(0);
						    	$('.gen_quotation_span').text('Invoice successfully generated.').css('color','green');
						   
						   	//asking to mail or not
								$('.ask_mailing_div').fadeIn(0);
						    }
						    else //availability is not enough to generate invoice
						    {
						    	$('.ajax_loader_box').fadeOut(0);

						    	$('.user_entry_form').fadeIn(0);
						    	$('.gen_quotation_span').text("You have entered a quantity greater than its availability in stock. Invoice failed to generate.").css('color', 'red');

						    //asking to mail or not
								$('.ask_mailing_div').fadeOut(0);
						    }
						}
						else
						{
							$('.gen_quotation_span').text('Something went wrong while creating invoice').css('color','red');
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

		//for defining type of the invoice
			var session_of = "normal";
			var session_name = "invoice_type";
				
			$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
			{

			});
			
		//setting quotation view session
			var session_of = quotation_num;
			var session_name = "pdf_invoice_of";

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
							window.open('php/invoice_pdf.php', '_blank');	
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
			var session_name = "pdf_invoice_of";

			$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
			{
				if(e ==1)
				{
					window.open('php/invoice_pdf.php', '_blank');	
				}
				else
				{
					$('.warn_box').text("Something went wrong while mailing the customer.");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});
	
	//on clicking performa invoice button
		$('#performa_invoice_gen_edit_button').click(function() 
		{
			var quotation_num =  $.trim($(this).attr('quotation_num'));

		//for defining type of the quotation
			var session_of = "performa";
			var session_name = "quotation_type";
				
			$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
			{
				if(e ==1)
				{
					//for getting pdf of the invoice
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
							$('.warn_box').text("Something went wrong while generating pdf file of the invoice.");
							$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
						}
					});
				}
				else
				{
					$('.warn_box').text("Something went wrong while generating pdf file of the invoice.");
				}
			});		
		});
	</script>