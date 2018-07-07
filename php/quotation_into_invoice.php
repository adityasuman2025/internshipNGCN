<?php
	include('connect_db.php');

	if(isset($_POST['generated_from']))
	{
		$generated_from = $_POST['generated_from'];
	}
	else
	{
		$generated_from = "";
	}

//getting the quotation number
	$quotation_num = $_POST['quotation_num'];
	$this_year = date('y');
	$next_year = $this_year +1;

	$website = $_SERVER['HTTP_HOST'];
	if($website == "localhost" OR $website == "volta.pnds.in" OR $website == "erp.voltatech.in")
	{
		$comp_code = "VOLTA/";
	}
	else if($website == "oxy.pnds.in")
	{
		$comp_code = "OXY/";
	}
	else
	{
		$comp_code = "VOLTA/";
	}		

	$quotation_code = $comp_code . $this_year . "-" . $next_year . "/" . $quotation_num;

//getting the email id of the customer
	$get_customer_name_query = "SELECT customer FROM quotation WHERE quotation_num = '$quotation_num'";

	$get_customer_name_query_run = mysqli_query($connect_link, $get_customer_name_query);
	$get_customer_name_assoc = mysqli_fetch_assoc($get_customer_name_query_run);
	$get_customer_name = $get_customer_name_assoc['customer'];

	$get_customer_email_query = "SELECT email FROM customers WHERE name ='$get_customer_name'";
	$get_customer_email_query_run = mysqli_query($connect_link, $get_customer_email_query);
	$get_customer_email_assoc = mysqli_fetch_assoc($get_customer_email_query_run);
	$get_customer_email = $get_customer_email_assoc['email'];
?>

<!-----choose payment form------->
	<h3><?php echo $quotation_code; ?></h3>

	<div class="user_entry_form" id="user_edit_form">
		<h4>Choose Your Payment Method</h4>
		<select id="payment_method_selector">
			<option value=""></option>
			<option value="Credit">Credit</option>
			<option value="Cash">Cash</option>
			<option value="Card">Credit/Debit Card</option>
			<option value="Net">Net Banking</option>
		</select>
		<br><br>
		
		<input type="button" value="Generate Invoice" id="user_save_edit_button">
		<br>
	</div>
	
	<button class="view_quotation_button">View Invoice</button>
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
				$(this).fadeOut(0);

				if(payment_method == "Credit")
				{
					var query_recieved = "UPDATE quotation SET payment_method ='" + payment_method + "', date_of_payment= '00' WHERE quotation_num = '" + quotation_num + "'";
				}
				else
				{
					var query_recieved = "UPDATE quotation SET payment_method ='" + payment_method + "', date_of_payment = now() WHERE quotation_num = '" + quotation_num + "'";
				}				
				
				$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
				{
					if(e==1)
					{					
					//changing the stock of that item in the batabase
						$.post('php/invoice_changes_stock.php', {quotation_num: quotation_num}, function()
						{
							if(e == 1)
							{
								$('.user_edit_span').text('').css('color','green');
								$('#user_edit_form').fadeOut(0);

								$('.ajax_loader_box').fadeOut(0);
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

	//on clicking on view quotation button
		$('.view_quotation_button').click(function()
		{	
		//for getting pdf of the quotation
			var quotation_num = "<?php echo $quotation_num?>";
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
					$('.warn_box').text("Something went wrong while generating pdf file of the invoice.");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});

			//window.open('php/quotation_pdf.php', '_blank');	
		});

	</script>