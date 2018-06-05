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

	$quotation_code = "VOLTA/" . $this_year . "-" . $next_year . "/" . $quotation_num;

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

<!-----edit customer form------->
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
				var query_recieved = "UPDATE quotation SET payment_method ='" + payment_method + "', date_of_payment = now() WHERE quotation_num = '" + quotation_num + "'";
				
				$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
				{
					if(e==1)
					{
					//for getting pdf of the quotation
						// var session_of = quotation_num;
						// var session_name = "pdf_invoice_of";
							
						// $.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
						// {
						// 	if(e ==1)
						// 	{
						// 	//mailing to the customer
						// 		var customer_email = "<?php echo $get_customer_email; ?>";
						// 		var website = window.location.hostname;

						// 		var mail_email = customer_email;
						// 		var mail_subject = "Invoice from Voltatech";
						// 		var mail_header = "From: voltatech@pnds.in";
						// 		var mail_body = "Dear Customer \nInvoice generated from our online resource is linked with this mail. Please find your invoice by following the link: http://" + website + "/invoice/Invoice-" + quotation_num + ".pdf \n \nRegards \nVoltatech \nhttp://" + website;

						// 		$.post('php/mailing.php', {mail_email: mail_email, mail_subject: mail_subject, mail_header:mail_header, mail_body:mail_body}, function(e)
						// 		{
						// 			if(e == 1)
						// 			{

						// 			}
						// 			else
						// 			{
						// 				$('.gen_quotation_span').text('something went wrong while mailing the customer.').css('color','red');
						// 			}
						// 		});

						// 		window.open('php/invoice_pdf.php', '_blank');	
						// 	}
						// 	else
						// 	{
						// 		$('.warn_box').text("Something went wrong while generating pdf file of the invoice.");
						// 		$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
						// 	}
						// });

					//changing the stock of that item in the batabase
						$.post('php/invoice_changes_stock.php', {quotation_num: quotation_num}, function()
						{
							if(e == 1)
							{
								$('.user_edit_span').text('').css('color','green');
								$('.view_quotation_button').fadeIn(0);

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