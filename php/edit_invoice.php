<?php
	include('connect_db.php');

//getting the quotation number
	$quotation_num = $_POST['quotation_num'];
	$this_year = date('y');
	$next_year = $this_year +1;

	$quotation_code = "VOLTA/" . $this_year . "-" . $next_year . "/" . $quotation_num;

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
		<h4>Choose The Payment Date</h4>
		
		<input type="date" value="<?php echo date('Y-m-d'); ?>" id="quotation_date">
		<br><br>

		<input type="button" value="Save" id="user_save_edit_button">
		<br>
	</div>
	
	<span class="user_edit_span"></span>

<!---------script----------->
	<script type="text/javascript">
		$('#user_save_edit_button').click(function()
		{
			var quotation_num = "<?php echo $quotation_num; ?>";
			var date = $('#quotation_date').val();
			var redirect_page = $.trim("<?php echo $redirect_page; ?>");

			var query_recieved = "UPDATE quotation SET date_of_payment= '" + date + "' WHERE quotation_num ='" + quotation_num + "'";	
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
