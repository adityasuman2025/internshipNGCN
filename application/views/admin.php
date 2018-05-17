<html>
<head>
	<?php
		include('php/header.php');

		if(isset($_COOKIE['logged_username']) && $_COOKIE['isadmin'] == '1')
		{
			//echo "gud";
		}
		else
		{
			die('wrong attempt is made to view this resource');
		}
	?>

	<title>Admin</title>
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
			<li>Branch
				<ul>
					<li work="add_branch">Add Branch</li>
					<li work="manage_branch">Manage Branch</li>
				</ul>
			</li>

			<li>User
				<ul>
					<li work="add_user">Add User</li>
					<li work="manage_user">Manage User</li>
				</ul>
			</li>

			<li>Inventory
				<ul>
					<li work="add_inventory">Add Inventory</li>
					<li work="manage_inventory">Manage Inventory</li>
				</ul>
			</li>

			<li work="admin_manage_customer">Manage Customer</li>

			<!-- <li work="admin_manage_product">Manage Product</li>
 -->
			<li work="admin_manage_supplier">Manage Supplier</li>

			<li work="admin_manage_purchase">Manage Purchase</li>

			<li work="admin_manage_return">Manage Return</li>

			<li work="admin_manage_quotation">Manage Quotation</li>
			<li work="admin_manage_invoice">Manage Invoice</li>
			<li work="admin_manage_stock">Manage Stock</li>

			<li>Reports
				<ul>
					<li>Todays Report</li>
					<li>Purchase Report</li>
					<li>Sales Report</li>
					<li>Sales Report Product Wise</li>
					<li>Sales Report Invoice Wise</li>
				</ul>
			</li>

		</ul>
	</div>

<!-----user module title bar------->
	<div class="user_module_title">
		<div class="logo_menu">
			<img class="mob_menu_button" active="no" src="img/mob_menu.png">
			<img class="company_logo" src="img/logo.png">
		</div>

		<div class="user_logout">
			<img class="user_logout_button" src="img/logout.png">	
		</div>
	</div>

<!-----------user module area----->
	<div class="user_module_area">
	
	<!-----user module content area------->
		<h3 class="user_module_heading"></h3>
		<div class="user_module_content">
			
		</div>
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
					location.href ='index';
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
		$('.user_menu li').click(function()
		{
			var heading = $(this).text();
			$('.user_module_heading').text(heading);
		});

	//to generate the content of the selected option
		$('.user_menu li li, .user_menu li').click(function()
		{
			var user_username = "<?php echo $_COOKIE['logged_username']; ?>";
			var work = $(this).attr('work');
			var file = work + ".php";
			var file_address = "php/" + file;

			$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load(file_address);
		});

	</script>

</body>
</html>