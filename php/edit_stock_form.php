<?php
	include 'connect_db.php';

	$user_id = mysqli_real_escape_string($connect_link, $_POST['user_id']);

	$edit_user_query = "SELECT * FROM stock WHERE id='$user_id'";
	$list_user_query_run = mysqli_query($connect_link, $edit_user_query);

	$list_user_assoc = mysqli_fetch_assoc($list_user_query_run);

	$user_id = $list_user_assoc['id'];
	$brand = $list_user_assoc['brand'];

	$sold = $list_user_assoc['sold'];
	$in_stock = $list_user_assoc['in_stock'];
	$sales_price = $list_user_assoc['sales_price'];
	$supplier_price = $list_user_assoc['supplier_price'];
	$hsn_code = $list_user_assoc['hsn_code'];

	$type = $list_user_assoc['type'];
	
?>

<!-----edit user form------->
	<div class="user_entry_form" id="user_edit_form">

		Sold Items:
		<br>
		<input id="user_entry_sold" type="number" value="<?php echo $sold; ?>">
		<br><br>
		
		In-Stock Items:
		<br>
		<input type="number" value="<?php echo $in_stock; ?>" id="user_entry_in_stock">
		<br><br>

		Sales Price:
		<br>
		<input type="text" value="<?php echo $sales_price; ?>" id="user_entry_sales_price">
		<br><br>

		Supplier Price:
		<br>
		<input type="number" value="<?php echo $supplier_price; ?>" id="user_entry_supplier_price">
		<br><br>

		HSN Code:
		<br>
		<input type="text" value="<?php echo $hsn_code; ?>" id="user_entry_hsn_code">
		<br><br>

		<input type="button" value="Save" id="user_save_edit_button">
		<br>
	</div>
		
	<span class="user_edit_span"></span>

<!----script----->
	<script type="text/javascript">
	//on clicking on edit button
		$('#user_save_edit_button').click(function()
		{
			$('.user_edit_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			var user_id = "<?php echo $user_id;?>";

			var sold = $('#user_edit_form #user_entry_sold').val();
			var in_stock = $('#user_edit_form #user_entry_in_stock').val();
			var sales_price = $('#user_edit_form #user_entry_sales_price').val();
			var supplier_price = $('#user_edit_form #user_entry_supplier_price').val();
			var hsn_code = $('#user_edit_form #user_entry_hsn_code').val();
								
			if(sold!= "" && in_stock!= "")
			{
				var query_recieved = "UPDATE stock SET sold ='" + sold + "', in_stock ='" + in_stock + "', sales_price = '" + sales_price + "', supplier_price = '" + supplier_price + "', hsn_code = '" + hsn_code + "' WHERE id = '" + user_id + "'";
			
				$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
				{
					if(e==1)
					{
						$('.user_edit_span').text('Successfully edited').css('color','green');
						$('#user_edit_form').fadeOut(0);

						$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/manage_stock.php');
					}
					else
					{
						$('.user_edit_span').text('Something went wrong while editing the stock').css('color','red');
					}
				});
			}
			else
			{
				$('.user_edit_span').text('Please fill all the details').css('color','red');
			}

		});

	</script>