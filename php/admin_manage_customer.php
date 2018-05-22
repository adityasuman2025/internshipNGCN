<?php
	session_start();
	include('connect_db.php');
?>
<!-----for exce,csv generation------>
	<script type="text/javascript" src="js/FileSaver.min.js"></script>
	<script type="text/javascript" src="js/tableexport.min.js"></script>

	<script type="text/javascript">
		$('#table_export').tableExport();
	</script>

<!--------option to choose branch-------->
	<b class="admin_select_branch_text">Select Branch Code</b>

	<select id="admin_select_branch">
		<option value=""></option>
		<?php			
			$get_brand_query = "SELECT * FROM branch";
			$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

			while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
			{
				$branch_code = $get_brand_result['branch_code'];

				echo "<option value=\"$branch_code\">";
					echo $get_brand_result['branch_code'];
				echo "</option>";
			}
		?>	
	</select>
	<br>
	<br>

<!------------table area---------->
	<div class="inventory_list_container">
		<h3>
			<?php
				if(isset($_SESSION['selected_branch']))
				{
					$selected_branch = $_SESSION['selected_branch'];
					echo "Showing Results for Branch Code: $selected_branch";
				}
			?>
		</h3>

	<table id="table_export" class="list_inventory_table">
			<tr>
				<th>Name</th>
				<th>Company Name</th>
				<th>Email</th>
				<th>Mobile</th>
				<th>Address</th>
				<th>Pan Number</th>
				<th>GST Number</th>
				<th>Shipping Address</th>
				<th>Created By</th>
				<th>Actions</th>
			</tr>

			<?php
				if(isset($_SESSION['selected_branch']))
				{
					$selected_branch = $_SESSION['selected_branch'];

					$user_username = $_COOKIE['logged_username'];
					$creator_branch_code = $_COOKIE['logged_username_branch_code'];

					$manage_customer_query = "SELECT * FROM customers WHERE creator_branch_code = '$selected_branch' ORDER BY id DESC";
					$manage_customer_query_run = mysqli_query($connect_link, $manage_customer_query);

					while($manage_customer_result = mysqli_fetch_assoc($manage_customer_query_run))
					{
						$customer_id = $manage_customer_result['id'];
						
						echo "<tr>";
							echo "<td>" .$manage_customer_result['name'] . "</td>";
							echo "<td>" .$manage_customer_result['company_name'] . "</td>";
							echo "<td>" . $manage_customer_result['email'] . "</td>";
							echo "<td>" . $manage_customer_result['mobile'] . "</td>";
							echo "<td>" . $manage_customer_result['address'] . "</td>";
							echo "<td>" . $manage_customer_result['pan'] . "</td>";
							echo "<td>" . $manage_customer_result['gst'] . "</td>";
							echo "<td>" . $manage_customer_result['shipping_address'] . "</td>";
							echo "<td>" . $manage_customer_result['creator_username'] . "</td>";
							echo "<td>";
								echo "<img customer_id=\"$customer_id\" class=\"user_edit_icon\" src=\"img/edit.png\"/>";
								echo "<img customer_id=\"$customer_id\" class=\"user_delete_icon\" src=\"img/delete.png\"/>";
								
							echo "</td>";
						echo "</tr>";
					}
				}	
			?>
		</table>
	</div>

<!---script------>
	<script type="text/javascript">
	//on selecting a branch
		$('#admin_select_branch').change(function()
		{
			var selected_branch = $(this).val();
			var session_of = selected_branch;
			var session_name = "selected_branch";

			$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
			{
				if(e==1)
				{
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/admin_manage_customer.php');
				}
				else
				{
					//alert(e);
					$('.warn_box').text("Something went wrong.");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});

	//on clicking on user delete icon
		$('.user_delete_icon').click(function()
		{
			var customer_id = $.trim($(this).attr('customer_id'));
			var query_recieved = "DELETE FROM customers WHERE id = '" + customer_id + "'";

			$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
			{
				if(e==1)
				{
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/admin_manage_customer.php');
				}
				else
				{
					$('.warn_box').text("Something went wrong while deleting the user");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});

	//on clicking on user edit icon
		$('.user_edit_icon').click(function()
		{
			var customer_id = $(this).attr('customer_id');

			$('.ajax_loader_bckgrnd').fadeIn(400);
				
			$.post('php/admin_edit_customer_form.php', {customer_id:customer_id}, function(data)
			{
				//alert(data);
				$('.ajax_loader_box').fadeIn(400);
				$('.ajax_loader_content').html(data);
			});					
		});
	</script>
