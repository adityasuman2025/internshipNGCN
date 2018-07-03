<?php
	include('connect_db.php');

//getting the quotation number
	$quotation_num = $_POST['quotation_num'];
	$this_year = date('y');
	$next_year = $this_year +1;

	$quotation_code = "VOLTA/" . $this_year . "-" . $next_year . "/" . $quotation_num;

	$query = "SELECT * FROM quotation WHERE quotation_num='$quotation_num'";
	$query_run = mysqli_query($connect_link, $query);
	$query_result = mysqli_fetch_assoc($query_run);

	$payment_method = $query_result['payment_method'];
	$payment_date = $query_result['date_of_payment'];

//checking if user is admin or not
	$isadmin = $_COOKIE['isadmin'];
	if($isadmin == 1)//is admin
	{
		$redirect_page = "php/admin_manage_invoice.php";
	}
	else if($isadmin == 0) //not admin
	{
		$redirect_page = "php/manage_invoice.php";
	}
?>

<!-----choose payment date form------->
	<h3><?php echo $quotation_code; ?></h3>

	<div class="user_entry_form" id="user_edit_form">

		<h4>Payment Method</h4>
		<select id="payment_method">
			<option value="<?php echo $payment_method; ?>"><?php echo $payment_method; ?></option>
			
			<?php
				if($payment_method == "Net")
				{
					echo "<option value=\"Credit\">Credit</option>";
					echo "<option value=\"Cash\">Cash</option>";
					echo "<option value=\"Card\">Credit/Debit Card</option>";
				}
				else if($payment_method == "Card")
				{
					echo "<option value=\"Credit\">Credit</option>";
					echo "<option value=\"Cash\">Cash</option>";
					echo "<option value=\"Net\">Net Banking</option>";
				}
				else if($payment_method == "Cash")
				{
					echo "<option value=\"Credit\">Credit</option>";
					echo "<option value=\"Card\">Credit/Debit Card</option>";
					echo "<option value=\"Net\">Net Banking</option>";
				}
				else if($payment_method == "Credit")
				{
					echo "<option value=\"Cash\">Cash</option>";
					echo "<option value=\"Card\">Credit/Debit Card</option>";
					echo "<option value=\"Net\">Net Banking</option>";
				}
			?>
		</select>
		<br>

		<h4>Payment Date</h4>
		<input type="date" value="<?php echo date('Y-m-d'); ?>" id="payment_date">
		<br><br>

		<input type="button" value="Save" id="user_save_edit_button">
		<br>
	</div>
	
	<span class="user_edit_span"></span>

<!---------script----------->
	<script type="text/javascript">
		$('#user_save_edit_button').click(function()
		{
			var redirect_page = $.trim("<?php echo $redirect_page; ?>");

			var quotation_num = "<?php echo $quotation_num; ?>";
			var payment_method = $('#payment_method').val();
			var payment_date = $('#payment_date').val();			

			var query_recieved = "UPDATE quotation SET date_of_payment= '" + payment_date + "', payment_method = '" + payment_method + "' WHERE quotation_num ='" + quotation_num + "'";	
			$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
			{
				if(e==1)
				{	
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load(redirect_page);

					$('.ajax_loader_bckgrnd').fadeOut(0);
					$('.ajax_loader_box').fadeOut(0);
				}
				else
				{
					$('.user_edit_span').text('Something went wrong while updating date of payment.').css('color','red');
				}
			});
		});
	</script>
