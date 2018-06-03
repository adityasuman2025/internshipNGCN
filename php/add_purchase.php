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
				$get_brand_query = "SELECT name FROM supplier WHERE creator_branch_code = '$creator_branch_code'";
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
		<input type="text" id="purchase_inv_num">
		<br>
		<br>

		<table class="purchase_entry_table">
			<tr>
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
					<select id="purchase_brand">
						<option value=""></option>
						<?php
							$choose_purchase_brand_query = "SELECT brand FROM inventory GROUP BY brand";
							$choose_purchase_brand_query_run = mysqli_query($connect_link, $choose_purchase_brand_query);

							while($choose_purchase_brand = mysqli_fetch_assoc($choose_purchase_brand_query_run))
							{
								$purchase_brand = $choose_purchase_brand['brand'];
								echo "<option value=\"$purchase_brand\">$purchase_brand</option>";
							}
						?>					
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
				<td><input type="text" disabled="disabled" id="purchase_description"></td>

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

	//on selecting a brand
		$('#purchase_brand').change(function()
		{
			brand = $(this).val();
			$(this).attr('disabled', true)

		//populating product/part according to the selected brand
			var query = "SELECT model_name FROM inventory WHERE brand ='" + brand + "' GROUP BY model_name";
			var to_get = "model_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#purchase_model_name').html(data);
			});

		});
	
	//on selecting a model_name
		$('#purchase_model_name').change(function()
		{
			$(this).attr('disabled', 'disabled');
			model_name = $(this).val();

		//populating product/part code according to the selected brand	
			var query = "SELECT model_number FROM inventory WHERE model_name ='" + model_name + "' AND brand ='" + brand + "' GROUP BY model_number";
			var to_get = "model_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#purchase_model_num').html(data);
			});
		});

	//on selecting a model_number
		$('#purchase_model_num').change(function()
		{
			$(this).attr('disabled', 'disabled');
			model_number = $(this).val();

		//populating hsn code
			var query = "SELECT hsn_code FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "'";
			var to_get = "hsn_code";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				$('#purchase_hsn_code').val(data);
			});

		//populating description
			var query = "SELECT description FROM inventory WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "'";
			var to_get = "description";

			$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
			{
				$('#purchase_description').val(data);
			});
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

	//on clicking on add purchase button
		$('#purchase_add_button').click(function()
		{
			$('.add_purchase_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
		//getting variable values
			// var purchase_supplier = $.trim($('#purchase_supplier').val());
			// var purchase_date = $.trim($('#purchase_date').val());
			// var purchase_inv_num = $.trim($('#purchase_inv_num').val());
			
			// if(purchase_supplier !="" && purchase_date !="" && purchase_inv_num !="")
			// {
			// //for getting inputs of each of the row
			// 	var count = $(".purchase_entry_table tr").length;
			// 	var row_count = count -1;

			// 	var i = 1;
			// 	for(i; i<= row_count; i++)
			// 	{
			// 		var child_no = i + 1;
			// 		var purchase_item = $('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_item').val();
			// 		var purchase_quantity = $('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_quantity').val();
			// 		var purchase_rate = $('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_rate').val();
			// 		var purchase_total_price = $('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_total_price').val();

			// 		$.post('php/create_purchase.php', {purchase_supplier: purchase_supplier, purchase_date: purchase_date, purchase_inv_num:purchase_inv_num, purchase_item:purchase_item, purchase_quantity:purchase_quantity, purchase_rate:purchase_rate, purchase_total_price:purchase_total_price}, function(e)
			// 		{
			// 			if(e==1)
			// 			{
			// 			//disappearing the user entry form
			// 				$('.user_entry_form').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);
							
			// 				$('.add_purchase_span').text('Purchase has been successfully created').css('color','green');
			// 				$('#create_new_user_button').fadeIn(100);
			// 			}
			// 			else
			// 			{
			// 				$('.add_purchase_span').text('Something went wrong while creating purchase').css('color','red');
			// 			}
			// 		});
			// 	}
			// }
			// else
			// {
			// 	$('.add_purchase_span').text('Please fill all the details').css('color','red');
			// }

		});

	//on clicking on add new purchase button
		$('#create_new_user_button').click(function()
		{
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/add_purchase.php');
		});

	</script>