<?php
	include 'connect_db.php';

	$product_id = mysqli_real_escape_string($connect_link, $_POST['product_id']);

	$edit_user_query = "SELECT * FROM products WHERE id='$product_id'";
	$list_user_query_run = mysqli_query($connect_link, $edit_user_query);

	$list_user_assoc = mysqli_fetch_assoc($list_user_query_run);

	$customer_id = $list_user_assoc['id'];
	$quantity = $list_user_assoc['quantity'];
	$price = $list_user_assoc['price'];
	$supplier_name = $list_user_assoc['supplier_name'];

?>

<!-----edit customer form------->
	<div class="user_entry_form" id="user_edit_form">
		Quantity:
		<br>
		<input id="user_entry_quantity" type="number" value="<?php echo $quantity; ?>">
		<br>

		Price:
		<br>
		<input type="number" value="<?php echo $price; ?>" id="user_entry_price">
		<br>
		
		Supplier:<br>
		<select id="user_entry_supplier">
			<option value="<?php echo $supplier_name; ?>"><?php echo $supplier_name; ?></option>
			<?php
				$get_brand_query = "SELECT name FROM supplier WHERE name !='$supplier_name'";
				$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

				while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
				{
					echo "<option>";
						echo $get_brand_result['name'];
					echo "</option>";
				}
			?>	
		</select>
		<br>

		<br>
		<input type="button" value="Save" id="user_save_edit_button">
		<br>
	</div>
		
	<span class="user_edit_span"></span>

<!----script----->
	<script type="text/javascript">
	//on clicking on edit button
		$('#user_save_edit_button').click(function()
		{
			var product_id = "<?php echo $product_id;?>";
			var quantity = $('#user_edit_form #user_entry_quantity').val();
			var price = $('#user_edit_form #user_entry_price').val();
			var supplier_name = $('#user_edit_form #user_entry_supplier').val();

			if(quantity!= "" && price!= "" && supplier_name!="")
			{
				var query_recieved = "UPDATE products SET quantity ='" + quantity + "', price ='" + price + "', supplier_name = '" + supplier_name + "' WHERE id = '" + product_id + "'";
				// alert(query_recieved);

				$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
				{
					if(e==1)
					{
						$('.user_edit_span').text('Successfully edited').css('color','green');
						$('#user_edit_form').fadeOut(0);
						$('.user_module_content').load('php/manage_product.php');
					}
					else
					{
						$('.user_edit_span').text('Something went wrong while editing the product').css('color','red');
					}
				});
			}
			else
			{
				$('.user_edit_span').text('Please fill all the details').css('color','red');
			}
		});

	</script>