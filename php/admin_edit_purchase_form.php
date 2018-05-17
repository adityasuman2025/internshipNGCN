<?php
	include 'connect_db.php';

	$purchase_id = mysqli_real_escape_string($connect_link, $_POST['purchase_id']);

	$edit_user_query = "SELECT * FROM purchases WHERE id='$purchase_id'";
	$list_user_query_run = mysqli_query($connect_link, $edit_user_query);

	$list_user_assoc = mysqli_fetch_assoc($list_user_query_run);

	$purchase_id = $list_user_assoc['id'];
	$purchase_inv_num = $list_user_assoc['purchase_inv_num'];
	$purchase_quantity = $list_user_assoc['purchase_quantity'];
	$purchase_rate = $list_user_assoc['purchase_rate'];
	$purchase_total_price = $list_user_assoc['purchase_total_price'];

?>

<!-----edit customer form------->
	<div class="user_entry_form" id="user_edit_form">
		Invoice Number:
		<br>
		<input id="purchase_inv_num" type="text" value="<?php echo $purchase_inv_num; ?>">
		<br>

		Quantity:
		<br>
		<input type="number" value="<?php echo $purchase_quantity; ?>" id="purchase_quantity">
		<br>
		
		Rate:
		<br>
		<input id="purchase_rate" type="text" value="<?php echo $purchase_rate; ?>">
		<br>

		Total Price:
		<br>
		<input id="purchase_total_price" type="text" value="<?php echo $purchase_total_price; ?>">
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
			$('.user_edit_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			var purchase_id = "<?php echo $purchase_id;?>";

			var purchase_inv_num = $('#user_edit_form #purchase_inv_num').val();
			var purchase_quantity = $('#user_edit_form #purchase_quantity').val();
			var purchase_rate = $('#user_edit_form #purchase_rate').val();
			var purchase_total_price = $('#user_edit_form #purchase_total_price').val();
			
			if(purchase_inv_num!= "" && purchase_quantity!= "" && purchase_quantity!= "" & purchase_total_price!= "")
			{
				var query_recieved = "UPDATE purchases SET purchase_inv_num ='" + purchase_inv_num + "', purchase_quantity ='" + purchase_quantity + "', purchase_rate = '" + purchase_rate + "', purchase_total_price = '" + purchase_total_price + "' WHERE id = '" + purchase_id + "'";
				// alert(query_recieved);

				$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
				{
					if(e==1)
					{
						$('.user_edit_span').text('Successfully edited').css('color','green');
						$('#user_edit_form').fadeOut(0);
						$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/admin_manage_purchase.php');
					}
					else
					{
						$('.user_edit_span').text('Something went wrong while editing the customer').css('color','red');
					}
				});
			}
			else
			{
				$('.user_edit_span').text('Please fill all the details').css('color','red');
			}
		});

	</script>