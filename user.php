<html>
<head>
	<?php
		include('php/header.php');

		if(isset($_COOKIE['logged_username']) && $_COOKIE['isadmin'] == '0')
		{
			//echo "gud";
		}
		else
		{
			die('wrong attempt is made to view this resource');
		}
	?>

	<title>User</title>
</head>
<body>

<!-----------menu bar----->
	<div class="user_module_menu_bar">
		<img class="menu_close" src="img/close.png">
		<br>

	<!----user info----->
		<div class="user_module_profile">
			<img src="img/profile.png">
			<br>
			<span>
				<?php
					echo $_COOKIE['logged_username'];
				?>	
			</span>
			<br><br>

		</div>

	<!----user module menu----->
		<ul class="user_menu">

			<li id="dashboard_button">Dashboard</li>

			<li>Product/Service
				<ul>
					<li work="add_inventory">Add Product/Service</li>
					<li work="manage_inventory">Manage Product/Service</li>
				</ul>
			</li>

			<li>Customer
				<ul>
					<li work="add_customer" id="menu_add_customer">Add Customer</li>
					<li work="manage_customer">Manage Customer</li>
				</ul>
			</li>

			<li>Supplier
				<ul>
					<li work="add_supplier">Add Supplier</li>
					<li work="manage_supplier">Manage Supplier</li>
				</ul>
			</li>

			<li>Purchase
				<ul>
					<li work="add_purchase">Add Purchase</li>
					<li work="manage_purchase">Manage Purchase</li>
				</ul>
			</li>

			<li>Return
				<ul>
					<li work="add_return">Add Return</li>
					<li work="manage_return">Manage Return</li>
				</ul>
			</li>

			<li>Quotation/Invoice
				<ul>
					<li work="add_quotation">New Quotation</li>
					<li work="add_invoice">New Invoice</li>
			
					<li work="manage_quotation">Manage Quotation</li>
					<li work="manage_invoice">Manage Invoice</li>
				</ul>
			</li>

			<li>Stock
				<ul>
					<li work="add_stock">Add Stock</li>
					<li work="manage_stock">Manage Stock</li>
				</ul>
			</li>

			<li>Report
				<ul>
					<li work="quotation_report">Quotation Report</li>
					<li work="invoice_report">Invoice Report</li>
					<li work="stock_report">Stock Report</li>
				</ul>
			</li>
		</ul>
	</div>

<!-----user module title bar------->
	<div class="user_module_title">
		<div class="logo_menu">
			<img class="mob_menu_button" active="no" src="img/mob_menu.png">
			<img class="company_logo" src="img/logo.png">
			<!-- <span>VOLTA</span> -->
		</div>

		<div class="user_logout">
			<img class="user_logout_button" src="img/logout.png">	
		</div>
	</div>

<!-----------user module area----->
	<div class="user_module_area">
	
	<!-----user module content area------->
		<h3 class="user_module_heading"></h3>
		<div class="user_module_content"></div>
	</div>

<!--------script-------->
	<script type="text/javascript">
	//on clicking on logout button
		$('.user_logout_button').click(function()
		{
			$.post('php/logout.php', {}, function(e)
			{
				if(e==1)
				{
					location.href ='index.php';
				}
				else //warn_box will appear with the error
				{
					$('.warn_box').text("Something went wrong while logging you out");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});

	//on selecting on menu
		$('.user_menu li').click(function()
		{
			$(this).css('background', '#3e454c').css('border-left', '5px solid #cc0000');
			$('.user_menu li').not(this).css('background', 'none').css('border-left', 'none');
		});

	//for showing sub menu
		$('.user_menu li').click(function()
		{
			var selected_li_display = $(this).find('ul').css('display');
			if(selected_li_display =='none')
			{
				$(this).find('ul').slideDown(300);
				$('.user_menu li').not(this).find('ul').slideUp(300);
			}
		});

	//on clicking on mob menu button
		$('.mob_menu_button').click(function()
		{
			var active = $(this).attr('active');
			if(active == "no")
			{
				//$(this).hide("slide", { direction: "left" }, 1000);
				//$(this).show("slide", { direction: "left" }, 1000);
				$('.user_module_menu_bar').css('left', '0');
				$(this).attr('active', 'yes');
			}
			else if(active == "yes")
			{
				$('.user_module_menu_bar').css('left', '-70%');
				$(this).attr('active', 'no');
			}
		});

		$('.menu_close').click(function()
		{
			$('.user_module_menu_bar').css('left', '-70%');
			$('.mob_menu_button').attr('active', 'no');
		})

	//to generate heading of the content
		$('.user_menu li li').click(function()
		{
			var heading = $(this).text();
			$('.user_module_heading').text(heading);
		});

	//to generate the content of the selected option
		$('.user_menu li li').click(function()
		{
			var user_username = "<?php echo $_COOKIE['logged_username']; ?>";
			var work = $.trim($(this).attr('work'));
			var file = work + ".php";
			var file_address = "php/" + file;

			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load(file_address);
		});

	//on clicking on dashboard button
		$('#dashboard_button').click(function()
		{
			$('.user_module_heading').text('User Dashboard');
			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/dashboard.php');
		});

	//by default dashboard is opened
		$('.user_module_heading').text('User Dashboard');
		$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/dashboard.php');
		$('#dashboard_button').css('background', '#3e454c').css('border-left', '5px solid #cc0000');
		
	</script>

</body>
</html>