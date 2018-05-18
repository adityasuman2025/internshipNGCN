<div class="dashboard_container">

	<div>
		<button work="add_user">
			<img src="img/add.png">
			Add User
		</button>

		<button work="add_inventory">
			<img src="img/add.png">
			Add Inventory
		</button>

		<button work="admin_manage_customer">
			<img src="img/manage.png">
			Manage Customer
		</button>
	</div>
	
	<div>
		<button work="admin_manage_quotation">
			<img src="img/manage.png">
			Manage Quotation
		</button>

		<button work="admin_manage_invoice">
			<img src="img/manage.png">
			Manage Invoice
		</button>
		
		<button work="admin_manage_stock">
			<img src="img/manage.png">
			Manage Stock
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