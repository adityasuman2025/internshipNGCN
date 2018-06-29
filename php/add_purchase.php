<?php
	include 'connect_db.php';
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];
?>
<!----add customer form------>
	<div class="user_entry_form add_purchase_form">

		<b>Supplier:</b>
		<br>
		<select id="purchase_supplier">
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
		<button id="purchase_add_supplier">Add Supplier</button>
		<br>
		<br>

		<b>Purchase Date:</b>
		<br>
		<input type="date" placeholder="Purchase Date" value="<?php echo date('Y-m-d'); ?>" id="purchase_date">
		<br>
		<br>

		<b>Invoice Number:</b>
		<br>
		<input type="text" id="purchase_inv_num1">
		<br>
		<br>

		<table class="purchase_entry_table">
			<tr>
				<th>Type</th>
				<th>Brand</th>
				<th>Product/part</th>
				<th>Product/part Code</th>
				<th>HSN Code</th>
				<th>Description</th>
				<th>Quantity</th>
				<th>Rate per unit</th>
				
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
					<select id="purchase_type">
						<option value=""></option>
						<option value="product">Product</option>
						<option value="part">Part</option>
						<option value="service">Service</option>
					</select>
				</td>

				<td>
					<select id="purchase_brand">
						<option value=""></option>
					</select>
				</td>

				<td>
					<select id="purchase_model_name">
						<option value=""></option>
					</select>
				</td>

				<td>
					<select id="purchase_model_num">
						<option value=""></option>
					</select>
				</td>

				<td><input type="text" disabled="disabled" id="purchase_hsn_code"></td>
				<td><input type="text" id="purchase_description"></td>

				<td><input type="number" value="0" placeholder="Quantity" id="purchase_quantity"></td>
				<td><input type="number" value="0" placeholder="Rate per unit" id="purchase_rate"></td>
				<td><input type="number" value="0" placeholder="CGST Rate" id="purchase_cgst_rate"></td>
				<td><input type="number" disabled="disabled" value="0" placeholder="CGST Amount" id="purchase_cgst_amount"></td>
				<td><input type="number" value="0" placeholder="SGST Rate" id="purchase_sgst_rate"></td>
				<td><input type="number" disabled="disabled" value="0" placeholder="SGST Amount" id="purchase_sgst_amount"></td>
				<td><input type="number" value="0" placeholder="IGST Rate" id="purchase_igst_rate"></td>
				<td><input type="number" value="0" disabled="disabled" placeholder="IGST Amount" id="purchase_igst_amount"></td>

				<td><input type="button" style="background: #cc0000; color: white; margin: 2px; width: auto;" value="calculate" id="purchase_total_amount"></td>

				<td>
					<img class="item_delete_icon" src="img/delete.png">
				</td>
			</tr>
		</table>
		<br>

		<button id="add_new_item_button">Add New Item</button>
		<br><br><br>

		<input type="button" value="Add Purchase" id="purchase_add_button">
	</div>
	
	<span class="add_purchase_span"></span>
	<br><br>
	<input type="submit" value="Add New Purchase" id="create_new_user_button">

<!--------script-------->
	<script type="text/javascript">
	//on clicking on add supplier button
		$('#purchase_add_supplier').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_supplier.php');
		});

	//on selecting a type
		$('.purchase_entry_table tr #purchase_type').change(function()
		{
			type = $(this).val();
			this_thing = $(this);
			$(this).attr('disabled', true);

		//populating brand
			var query = "SELECT brand FROM inventory WHERE type ='" + type + "' GROUP BY brand";
			var to_get = "brand";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#purchase_brand').html(data);
			});
		});

	//on selecting a brand
		$('.purchase_entry_table tr #purchase_brand').change(function()
		{
			brand = $(this).val();
			this_thing = $(this);
			$(this).attr('disabled', true);

		//populating product/part according to the selected brand
			var query = "SELECT model_name FROM inventory WHERE brand ='" + brand + "' AND type = '" + type + "' GROUP BY model_name";
			var to_get = "model_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#purchase_model_name').html(data);
			});

		});
	
	//on selecting a model_name
		$('.purchase_entry_table tr #purchase_model_name').change(function()
		{
			$(this).attr('disabled', 'disabled');
			this_thing = $(this);
			model_name = $(this).val();

		//populating product/part code according to the selected brand	
			var query = "SELECT model_number FROM inventory WHERE model_name ='" + model_name + "' AND brand ='" + brand + "' AND type = '" + type + "' GROUP BY model_number";
			var to_get = "model_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#purchase_model_num').html(data);
			});
		});

	//on selecting a model_number
		$('.purchase_entry_table tr #purchase_model_num').change(function()
		{
			$(this).attr('disabled', 'disabled');
			this_thing = $(this);
			model_number = $(this).val();

		//populating hsn code
			var query = "SELECT hsn_code FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND type = '" + type + "'";
			var to_get = "hsn_code";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#purchase_hsn_code').val(data);
			});

		//populating description
			var query = "SELECT description FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "' AND type = '" + type + "'";
			var to_get = "description";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				this_thing.parent().parent().find('#purchase_description').val(data);
			});
		});

	//on entering cgst rate
		$('.purchase_entry_table tr #purchase_cgst_rate').keyup(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#purchase_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#purchase_rate').val());
			var cgst_rate = parseInt($(this).parent().parent().find('#purchase_cgst_rate').val());

			var cgst_amount = (rate*cgst_rate/100)*quantity;

			$(this).parent().parent().find('#purchase_cgst_amount').val(cgst_amount);
		});

	//on entering sgst rate
		$('.purchase_entry_table tr #purchase_sgst_rate').keyup(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#purchase_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#purchase_rate').val());
			var sgst_rate = parseInt($(this).parent().parent().find('#purchase_sgst_rate').val());

			var sgst_amount = (rate*sgst_rate/100)*quantity;

			$(this).parent().parent().find('#purchase_sgst_amount').val(sgst_amount);
		});

	//on entering igst rate
		$('.purchase_entry_table tr #purchase_igst_rate').keyup(function()
		{
			var quantity = parseInt($(this).parent().parent().find('#purchase_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#purchase_rate').val());
			var igst_rate = parseInt($(this).parent().parent().find('#purchase_igst_rate').val());

			var igst_amount = (rate*igst_rate/100)*quantity;

			$(this).parent().parent().find('#purchase_igst_amount').val(igst_amount);
		});

	//on clicking on calculate
		$('.purchase_entry_table tr #purchase_total_amount').click(function()
		{
			//alert('hello');
			var quantity = parseInt($(this).parent().parent().find('#purchase_quantity').val());
			var rate = parseInt($(this).parent().parent().find('#purchase_rate').val());

			var cgst = parseInt($(this).parent().parent().find('#purchase_cgst_rate').val());
			var sgst = parseInt($(this).parent().parent().find('#purchase_sgst_rate').val());
			var igst = parseInt($(this).parent().parent().find('#purchase_igst_rate').val());

			var total_price = (rate + (rate * (cgst+sgst+igst)/100))*quantity;

			$(this).val(total_price);
			//alert(total_price);
		});

	//on change of quantity, rate or gst after calculation
		$('.purchase_entry_table tr #purchase_quantity').keyup(function()
		{
			$(this).parent().parent().find('#purchase_total_amount').val('calculate');
		});

		$('.purchase_entry_table tr #purchase_rate').keyup(function()
		{
			$(this).parent().parent().find('#purchase_total_amount').val('calculate');
		});

		$('.purchase_entry_table tr #purchase_cgst_rate').keyup(function()
		{
			$(this).parent().parent().find('#purchase_total_amount').val('calculate');
		});

		$('.purchase_entry_table tr #purchase_sgst_rate').keyup(function()
		{
			$(this).parent().parent().find('#purchase_total_amount').val('calculate');
		});

		$('.purchase_entry_table tr #purchase_igst_rate').keyup(function()
		{
			$(this).parent().parent().find('#purchase_total_amount').val('calculate');
		});

	//on clicking on add new item button
		$('#add_new_item_button').click(function()
		{
			$.post('php/add_new_purchase_item.php', {},function(data)
			{
				//alert(data);
				$('.purchase_entry_table tbody').append(data);
			});
		});

	//on clicking on delete item button
		$('.purchase_entry_table tr .item_delete_icon').click(function()
		{
			$(this).parent().parent().remove();
		});

	//on clicking on add purchase button
		$('#purchase_add_button').click(function()
		{
			$('.add_purchase_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
		//getting variable values
			var purchase_supplier = $.trim($('#purchase_supplier').val());
			var purchase_date = $.trim($('#purchase_date').val());
			var purchase_inv_num = $.trim($('#purchase_inv_num1').val());
		
			if(purchase_supplier !="" && purchase_date !="" && purchase_inv_num !="")
			{
			//for getting inputs of each of the row
				var count = $(".purchase_entry_table tr").length;
				var row_count = count -1;

				var i = 1;
				for(i; i<= row_count; i++)
				{
					var child_no = i + 1;

				//defining variables of each table row
					var purchase_type = $('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_type').val();
					var purchase_brand = $('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_brand').val();
					var purchase_model_name = $('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_model_name').val();
					var purchase_model_num = $('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_model_num').val();
					var purchase_hsn_code = $('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_hsn_code').val();
					var purchase_description = $('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_description').val();

					var purchase_quantity = parseInt($('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_quantity').val());
					var purchase_rate = parseInt($('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_rate').val());
					var purchase_cgst_rate = parseInt($('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_cgst_rate').val());
					var purchase_sgst_rate = parseInt($('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_sgst_rate').val());
					var purchase_igst_rate = parseInt($('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_igst_rate').val());
					var purchase_total_amount = $.trim($('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_total_amount').val());

				//if user forget to calculate total price
					if(purchase_total_amount == "calculate")
					{
						var purchase_total_amount = (purchase_rate + (purchase_rate * (purchase_cgst_rate+purchase_sgst_rate+purchase_igst_rate)/100))*purchase_quantity;
					}
				
				//adding this to database	
					$.post('php/create_purchase.php', {purchase_supplier: purchase_supplier, purchase_date: purchase_date, purchase_inv_num:purchase_inv_num, purchase_type:purchase_type, purchase_brand:purchase_brand, purchase_model_name:purchase_model_name, purchase_model_num:purchase_model_num, purchase_hsn_code:purchase_hsn_code, purchase_description:purchase_description, purchase_quantity:purchase_quantity, purchase_rate:purchase_rate, purchase_cgst_rate:purchase_cgst_rate, purchase_sgst_rate:purchase_sgst_rate, purchase_igst_rate:purchase_igst_rate, purchase_total_amount:purchase_total_amount}, function(e)
					{
						if(e==1)
						{
						//changing the stock of the particular purchased item
							var creator_branch_code = "<?php echo $creator_branch_code; ?>";

							var check_stock_query = "SELECT * FROM stock WHERE brand = '" + purchase_brand + "' AND model_name = '" + purchase_model_name + "' AND model_number = '" + purchase_model_num + "' AND type='" + purchase_type + "' AND creator_branch_code = '" + creator_branch_code + "'";
							var query_recieved = check_stock_query;

						//checking stock is already added or not
							$.post('php/check_stock.php', {query_recieved:query_recieved}, function(e)
							{
								if(e =='') //if stock is already not added, then adding a new stock
								{
									var inv_part_name = '';
									var inv_part_number = '';
									var inv_type = purchase_type;
									var inv_sales_price = '';

									$.post('php/create_stock.php', {inv_brand:purchase_brand, inv_model_name:purchase_model_name, inv_model_num:purchase_model_num, inv_part_name:inv_part_name, inv_part_number:inv_part_number, inv_quantity:purchase_quantity, inv_type:inv_type, inv_sales_price:inv_sales_price, inv_supplier_price:purchase_rate, inv_hsn_code:purchase_hsn_code}, function(e)
									{
										if(e == 1)
										{
											$('.add_purchase_span').text('Stock has been successfully added').css('color', 'green');
										}
										else
										{
											$('.add_purchase_span').text('Something went wrong while adding stock').css('color', 'red');
										}
									});
								}
								else  //if stock is already added, then editting in_stock in that stock
								{
									var old_quantity = parseInt(e);
									var quantity = old_quantity + purchase_quantity;

									var update_stock_query = "UPDATE stock SET in_stock = '" + quantity + "' WHERE brand = '" + purchase_brand + "' AND model_name = '" + purchase_model_name + "' AND model_number = '" + purchase_model_num + "' AND type = '" + purchase_type + "' AND creator_branch_code = '" + creator_branch_code + "'";
									var query_recieved = update_stock_query;

									$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
									{
										if(e ==1)
										{
											$('.add_purchase_span').text('Stock has been successfully edited').css('color', 'green');
										}
									});
								}
							});

						//disappearing the user entry form
							$('.user_entry_form').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);
							
							$('.add_purchase_span').text('Purchase has been successfully added').css('color','green');
							$('#create_new_user_button').fadeIn(100);
						}
						else
						{
							$('.add_purchase_span').text('Something went wrong while creating purchase').css('color','red');
						}
					});
				}
			}
			else
			{
				$('.add_purchase_span').text('Please fill all the details').css('color','red');
			}
		});

	//on clicking on add new purchase button
		$('#create_new_user_button').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_purchase.php');
		});

	</script>