<?php
	include 'connect_db.php';
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];
	$is_admin = $_COOKIE['isadmin'];

	$invoice_num = $_POST['invoice_num'];

	$edit_user_query = "SELECT * FROM purchases WHERE invoice_num='$invoice_num'";
	$list_user_query_run = mysqli_query($connect_link, $edit_user_query);

	$list_user_assoc = mysqli_fetch_assoc($list_user_query_run);

	$invoice_num = $list_user_assoc['invoice_num'];
	$supplier = $list_user_assoc['supplier'];
	$date = $list_user_assoc['date'];
?>

<!-----edit customer form------->
	<div class="user_entry_form" id="user_edit_form" style="text-align: left">

		<b>Supplier:</b>
		<br>
		<select id="purchase_supplier">
			<option value="<?php echo $supplier; ?>"><?php echo $supplier; ?></option>
			<?php
				$get_brand_query = "SELECT name FROM supplier WHERE creator_branch_code = '$creator_branch_code' AND name !='$supplier'";
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
		<input type="date" placeholder="Purchase Date" value="<?php echo $date; ?>" id="purchase_date">
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
				<th>CGST Amount</th>
				<th>IGST Rate</th>
				<th>CGST Amount</th>
				<th>Total Amount</th>
				<th>Action</th>
			</tr>

			<?php
				$edit_user_query = "SELECT * FROM purchases WHERE invoice_num='$invoice_num'";
				$list_user_query_run = mysqli_query($connect_link, $edit_user_query);

				while($list_user_assoc = mysqli_fetch_assoc($list_user_query_run)) 
				{
					$id = $list_user_assoc['id'];

					$brand = $list_user_assoc['brand'];
					$model_name = $list_user_assoc['model_name'];
					$model_num = $list_user_assoc['model_num'];
					$hsn_code = $list_user_assoc['hsn_code'];
					$description = $list_user_assoc['description'];
					$type = $list_user_assoc['type'];

					$quantity = $list_user_assoc['quantity'];
					$rate = $list_user_assoc['rate'];
					
					$cgst = $list_user_assoc['cgst'];
					$cgst_amount = ($rate*$cgst/100)*$quantity;

					$sgst = $list_user_assoc['sgst'];
					$sgst_amount = ($rate*$sgst/100)*$quantity;

					$igst = $list_user_assoc['igst'];
					$igst_amount = ($rate*$igst/100)*$quantity;

					$total_amount = $list_user_assoc['total_amount'];

					echo "<tr id=\"$id\">";
						echo "<td>";
							echo "<select id=\"purchase_type\">";
							
									echo "<option value=\"$type\">$type</option>";

									if($type == "product")
									{
										echo "<option value=\"part\">Part</option>";
										echo "<option value=\"service\">Service</option>";
									}
									else if($type == "part")
									{
										echo "<option value=\"product\">Product</option>";
										echo "<option value=\"service\">Service</option>";
									}
									else if($type == "service")
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

							echo "</select>";
						echo "</td>";

						echo "<td>";
							echo "<select id=\"purchase_brand\">";
								echo "<option value=\"$brand\">$brand</option>";
							echo "</select>";
						echo "</td>";

						echo "<td>";
							echo "<select id=\"purchase_model_name\">";
								echo "<option value=\"$model_name\">$model_name</option>";
							echo "</select>";
						echo "</td>";

						echo "<td>";
							echo "<select id=\"purchase_model_num\">";
								echo "<option value=\"$model_num\">$model_num</option>";
							echo "</select>";
						echo "</td>";

						echo "<td><input type=\"text\" value=\"$hsn_code\" disabled=\"disabled\" id=\"purchase_hsn_code\"></td>";
						echo "<td><input type=\"text\" value=\"$description\" disabled=\"disabled\" id=\"purchase_description\"></td>";

						echo "<td><input type=\"number\" value=\"$quantity\" id=\"purchase_quantity\"></td>";
						echo "<td><input type=\"number\" value=\"$rate\" id=\"purchase_rate\"></td>";
						echo "<td><input type=\"number\" value=\"$cgst\" id=\"purchase_cgst_rate\"></td>";
						echo "<td><input type=\"number\" disabled=\"disabled\" value=\"$cgst_amount\" id=\"purchase_cgst_amount\"></td>";
						echo "<td><input type=\"number\" value=\"$sgst\" id=\"purchase_sgst_rate\"></td>";
						echo "<td><input type=\"number\" disabled=\"disabled\" value=\"$sgst_amount\" id=\"purchase_sgst_amount\"></td>";
						echo "<td><input type=\"number\" value=\"$igst\" id=\"purchase_igst_rate\"></td>";
						echo "<td><input type=\"number\" disabled=\"disabled\" value=\"$igst_amount\" id=\"purchase_igst_amount\"></td>";

						echo "<td><input type=\"button\" style=\"background: #cc0000; color: white; margin: 2px; width: auto;\" value=\"$total_amount\" id=\"purchase_total_amount\"></td>";

						echo "<td><img class=\"item_delete_icon\" src=\"img/delete.png\"></td>";
					echo "</tr>";
				}

			?>
		</table>
		<br>

		<button id="add_new_item_button">Add New Item</button>
		<br><br><br>

		<input type="button" value="Save Changes" id="purchase_add_button">
		
	</div>
		
	<span class="user_edit_span"></span>

<!----script----->
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

		//emptying all the upcoming fields
			this_thing.parent().parent().find('#purchase_brand').html('<option value=\"\"></option>');
			this_thing.parent().parent().find('#purchase_model_name').html('<option value=\"\"></option>');
			this_thing.parent().parent().find('#purchase_model_num').html('<option value=\"\"></option>');
			this_thing.parent().parent().find('#purchase_hsn_code').val('');
			this_thing.parent().parent().find('#purchase_description').val('');

		//populating product/part according to the selected brand
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
			$('.user_edit_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
		//getting variable values
			var invoice_num = $.trim("<?php echo $invoice_num; ?>");
			var supplier = $.trim($('#purchase_supplier').val());
			var date = $.trim($('#purchase_date').val());		
		
		//creating a new id in database to store this row
			$.post('php/create_new_purchase_row_db.php', {invoice_num: invoice_num, supplier: supplier, date:date}, function(e)
			{
				if(e != 0)
				{
					var new_id = e;
					$.post('php/add_new_purchase_item.php', {new_id: new_id},function(data)
					{
						$('.purchase_entry_table tbody').append(data);
						$('.user_edit_span').html("Row has been successfully added").css('color', 'green');
					});
				}
				else
				{
					$('.warn_box').html("Something went wrong while adding new row in the database");
					$('.warn_box').fadeIn(200).delay(2000).fadeOut(200);
				}
			});
		});

	//on clicking on delete item button
		$('.purchase_entry_table tr .item_delete_icon').click(function()
		{
			//$(this).parent().parent().remove();

			$('.add_purchase_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");

			var id = $(this).parent().parent().attr('id');
			var this_item = $(this).parent().parent();

		//deleting that row from database
			var query_recieved = "DELETE FROM purchases WHERE id ='" + id + "'";
			$.post('php/query_runner.php', {query_recieved: query_recieved}, function(e)
			{
				if(e == 1)
				{
					this_item.remove();
					$('.add_purchase_span').html("Selected row has been successfully deleted").css('color', 'green');
				}
				else
				{
					$('.add_purchase_span').html("Something weant wrong while deleting the row from database").css('color', 'red');
				}
			});
		});

	//on clicking on add purchase button
		$('#purchase_add_button').click(function()
		{
			$('.user_edit_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
		//getting variable values
			var purchase_supplier = $.trim($('#purchase_supplier').val());
			var purchase_date = $.trim($('#purchase_date').val());
			
			if(purchase_supplier !="" && purchase_date !="")
			{
			//for getting inputs of each of the row
				var count = $(".purchase_entry_table tr").length;
				var row_count = count -1;

				var i = 1;
				for(i; i<= row_count; i++)
				{
					var child_no = i + 1;

				//defining variables of each table row
					var id = $('.purchase_entry_table tr:nth-child('+ child_no + ')').attr('id');

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
					var query_recieved = "UPDATE purchases SET type = '" + purchase_type + "', brand = '" + purchase_brand + "', model_name = '" + purchase_model_name + "', model_num ='" + purchase_model_num + "', hsn_code ='" + purchase_hsn_code + "', description = '" + purchase_description + "', quantity = '" + purchase_quantity + "', rate = '" + purchase_rate + "', cgst = '" + purchase_cgst_rate + "', sgst = '" + purchase_sgst_rate + "', igst = '" + purchase_igst_rate + "', total_amount = '" + purchase_total_amount + "' WHERE id = '" + id + "'";
					
					$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
					{
						if(e==1)
						{
						//changing the stock of the particular purchased item
							//here is a bug, stock will not change when u edit the purchase	

						//disappearing the user entry form
							$('.user_entry_form').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").fadeOut(0);
							$('.user_edit_span').text('Purchase has been successfully added').css('color','green');

							var is_admin = parseInt("<?php echo $is_admin; ?>");
							if(is_admin == 1)
							{
								$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/admin_manage_purchase.php');
							}
							else
							{
								$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/manage_purchase.php');
							}
							
						}
						else
						{
							$('.user_edit_span').text('Something went wrong while editing purchase').css('color','red');
						}
					});
				}
			}
			else
			{
				$('.user_edit_span').text('Please fill all the details').css('color','red');
			}
		});

	</script>