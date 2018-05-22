<?php
	include('connect_db.php');

	$quotation_num = $_POST['quotation_num'];

	$this_year = date('y');
	$next_year = $this_year +1;

	$quotation_code = "VOLTA/" . $this_year . "-" . $next_year . "/" . $quotation_num;
?>

<!-----edit customer form------->
	<h3><?php echo $quotation_code; ?></h3>

	<div class="user_entry_form" id="user_edit_form">
		<h4>Choose Your Payment Method</h4>
		<select id="payment_method_selector">
			<option value=""></option>
			<option value="Credit">Credit</option>
			<option value="Cash">Cash</option>
			<option value="Card">Credit/Debit Card</option>
			<option value="Net">New Banking</option>
		</select>
		<br><br>
		
		<input type="button" value="Generate Invoice" id="user_save_edit_button">
		<br>
	</div>
		
	<span class="user_edit_span"></span>

<!----script----->
	<script type="text/javascript">
	//on clicking on edit button (for generating quotation)
		$('#user_save_edit_button').click(function()
		{
			$('.user_edit_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
			
			var quotation_num = "<?php echo $quotation_num;?>";
			var payment_method = $('#user_edit_form #payment_method_selector').val();

		//adding payment method in the database and converting that quotation into invoice
			if(payment_method!= "")
			{
				var query_recieved = "UPDATE quotation SET payment_method ='" + payment_method + "', date_of_payment = now() WHERE quotation_num = '" + quotation_num + "'";
				// alert(query_recieved);

				$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
				{
					if(e==1)
					{
					//changing the stock of that item in the batabase
						$.post('php/invoice_changes_stock.php', {quotation_num: quotation_num}, function()
						{
							if(e == 1)
							{
								$('.user_edit_span').text('Invoice has been successfully generated from the quotation').css('color','green');
								$('#user_edit_form').fadeOut(0);
								$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/manage_invoice.php');
							}
							else
							{
								$('.user_edit_span').text('Something went wrong while changing the stock data in the database').css('color','red');
							}
						});
					}
					else
					{
						$('.user_edit_span').text('Something went wrong while generating invoice from the quotation').css('color','red');
					}
				});
			}
			else
			{
				$('.user_edit_span').text('Please fill all the details').css('color','red');
			}
		});

	</script>