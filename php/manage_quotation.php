<?php
	include('connect_db.php');
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];
?>

<!-----for exce,csv generation------>
	<script type="text/javascript" src="js/FileSaver.min.js"></script>
	<script type="text/javascript" src="js/tableexport.min.js"></script>

	<script type="text/javascript">
		//$('#table_export').tableExport();
	</script>

<div class="inventory_tab">
	<button class="service_quot_list_button">Service Quotation</button>
	<button class="sales_quot_list_button">Sales Quotation</button>
</div>
<br><br>

<div id="table_export1" class="quotation_list_container">
	<h3></h3>

	<table id="table_export">	
		
	</table>
</div>

<!---script------>
	<script type="text/javascript">
	//by default service quotation are shown
		$('.quotation_list_container h3').text('Service Quotations');
		$('.quotation_list_container table').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");

		var creator_branch_code = "<?php echo $creator_branch_code; ?>";
		var query = "SELECT * FROM quotation WHERE type='service' AND payment_method = '' AND creator_branch_code = '" + creator_branch_code + "' GROUP BY quotation_num ORDER BY quotation_num DESC";
		
		$.post('php/list_quotation.php', {query:query}, function(data)
		{
			$('#table_export').fadeIn(100).html(data);
		});

	//switching tab b/w service quotation and sales quotation
		$('.service_quot_list_button').click(function()
		{
			$('.quotation_list_container h3').text('Service Quotations');
			$('.quotation_list_container table').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");

			var creator_branch_code = "<?php echo $creator_branch_code; ?>";
			var query = "SELECT * FROM quotation WHERE type='service' AND payment_method = '' AND creator_branch_code = '" + creator_branch_code + "' GROUP BY quotation_num ORDER BY quotation_num DESC";
			
			$.post('php/list_quotation.php', {query:query}, function(data)
			{
				$('#table_export').fadeIn(100).html(data);
				//$('#table_export').tableExport();
			});
		});

		$('.sales_quot_list_button').click(function()
		{
			$('.quotation_list_container h3').text('Sales Quotations');
			$('.quotation_list_container table').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");

			var creator_branch_code = "<?php echo $creator_branch_code; ?>";
			var query = "SELECT * FROM quotation WHERE type='sales' AND payment_method = '' AND creator_branch_code = '" + creator_branch_code + "' GROUP BY quotation_num ORDER BY quotation_num DESC";
			
			$.post('php/list_quotation.php', {query:query}, function(data)
			{
				$('#table_export').fadeIn(100).html(data);
				//$('#table_export').tableExport();
			});
		});
	</script>
