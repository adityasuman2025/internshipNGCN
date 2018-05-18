<div class="dashboard_container">

	<div>
		<button work="add_customer">
			<img src="img/add.png">
			Add Customer
		</button>

		<button work="manage_invoice">
			<img src="img/manage.png">
			Manage Invoice
		</button>

		<button work="add_stock">
			<img src="img/add.png">
			Add Stock
		</button>
	</div>
	
	<div>
		<button work="add_service_quotation">
			<img src="img/add.png">
			Add Service Quotation
		</button>

		<button work="add_sales_quotation">
			<img src="img/add.png">
			Add Sales Quotation
		</button>

		<button work="manage_quotation">
			<img src="img/manage.png">
			Manage Quotation
		</button>
		
	</div>
	
</div>

<script type="text/javascript">
	//to generate the content and heading of the selected button
		$('.dashboard_container button').click(function()
		{
			var heading = $(this).text();
			$('.user_module_heading').text(heading);

			var user_username = "<?php echo $_COOKIE['logged_username']; ?>";
			var work = $(this).attr('work');
			var file = work + ".php";
			var file_address = "php/" + file;

			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load(file_address);
		});
</script>