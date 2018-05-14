<?php
	include 'connect_db.php';
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
		<input type="text" id="purchase_inv_num">
		<br>
		<br>

		<table class="purchase_entry_table">
			<tr>
				<th>Item</th>
				<th>Quantity</th>
				<th>Rate per unit</th>
				<th>Total Price</th>
				<th>Action</th>
			</tr>

			<tr>
				<td>
					<select id="purchase_item">
						<option value=""></option>					
					</select>
				</td>

				<td><input type="number" value="0" placeholder="Quantity" id="purchase_quantity"></td>
				<td><input type="number" value="0" placeholder="Rate per unit" id="purchase_rate"></td>
				<td><input type="number" value="0" placeholder="Total Price" id="purchase_total_price"></td>
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
	//on choosing purchase supplier
		$('#purchase_supplier').change(function()
		{
			var supplier = $(this).val(); //making supplier universal variable for using it to handle when user click on add new item button
			
			$.post('php/list_supplier_item.php', {supplier: supplier}, function(data)
			{
			//adding those items selector in each of the table row item field
				$('#purchase_item').each(function()
				{				    
					$(this).html(data);
				//for making the supplier field uneditable after choosing a value
					$("#purchase_supplier").attr("disabled", true).css('border', '1px lightgrey solid');
				});
			});
		});

	//on clicking on add new item button
		$('#add_new_item_button').click(function()
		{
			var supplier = $('#purchase_supplier').val();
			if(supplier != "")
			{
				var add_html = "<tr></tr>";

				$('.purchase_entry_table').append(add_html);
				$('.purchase_entry_table tr:last-child').load('php/item_info_form.php');

			//for giving user options to choose item from that supplier
				var supplier = $('#purchase_supplier').val();

				$.post('php/list_supplier_item.php', {supplier: supplier}, function(data)
				{
					$('.purchase_entry_table tr:last-child').find('#purchase_item').html(data);
				});

			}
			else
			{
				$('.warn_box').html("Choose a supplier first");
				$('.warn_box').fadeIn(200).delay(2000).fadeOut(200);

				$("#purchase_supplier").css('border', '1.5px red solid'); //making border of supplier input area red
			}
		});

	//on clicking on delete item button
		$('.item_delete_icon').click(function()
		{
			$(this).parent().parent().remove();
		});

	//for calculating total price
		// $('#purchase_quantity').keyup(function()
		// {
		// 	var purchase_quantity = parseInt($(this).val());
		// 	var purchase_rate = parseInt($(this).parent().parent().find('#purchase_rate').val());
		// 	var purchase_total_price = purchase_rate * purchase_quantity;

		// 	$(this).parent().parent().find('#purchase_total_price').val(purchase_total_price);
		// });

		// $('#purchase_rate').keyup(function()
		// {
		// 	var purchase_rate = parseInt($(this).val());
		// 	var purchase_quantity = parseInt($(this).parent().parent().find('#purchase_quantity').val());
		// 	var purchase_total_price = purchase_rate * purchase_quantity;
			
		// 	$(this).parent().parent().find('#purchase_total_price').val(purchase_total_price);
		// });

	//on clicking on add purchase button
		$('#purchase_add_button').click(function()
		{
		//getting variable values
			var purchase_supplier = $('#purchase_supplier').val();
			var purchase_date = $('#purchase_date').val();
			var purchase_inv_num = $('#purchase_inv_num').val();
			
			if(purchase_supplier !="" && purchase_date !="" && purchase_inv_num !="")
			{
			//for getting inputs of each of the row
				var count = $(".purchase_entry_table tr").length;
				var row_count = count -1;

				var i = 1;
				for(i; i<= row_count; i++)
				{
					var child_no = i + 1;
					var purchase_item = $('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_item').val();
					var purchase_quantity = $('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_quantity').val();
					var purchase_rate = $('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_rate').val();
					var purchase_total_price = $('.purchase_entry_table tr:nth-child('+ child_no + ') #purchase_total_price').val();

					$.post('php/create_purchase.php', {purchase_supplier: purchase_supplier, purchase_date: purchase_date, purchase_inv_num:purchase_inv_num, purchase_item:purchase_item, purchase_quantity:purchase_quantity, purchase_rate:purchase_rate, purchase_total_price:purchase_total_price}, function(e)
					{
						if(e==1)
						{
							$('.add_purchase_span').text('Purchase has been successfully created').css('color','green');
							$('#create_new_user_button').fadeIn(100);

						//disappearing the user entry form
							$('.user_entry_form').fadeOut(0);
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
			$('.user_module_content').load('php/add_purchase.php');
		});

	</script>