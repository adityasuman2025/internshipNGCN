<?php
	session_start();
	include('connect_db.php');
?>

<!-----for exce,csv generation------>
	<script type="text/javascript" src="js/FileSaver.min.js"></script>
	<script type="text/javascript" src="js/tableexport.min.js"></script>

	<script type="text/javascript">
		$('#table_export1').tableExport();
		$('#table_export2').tableExport();
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
	<div id="table_export" class="inventory_list_container">
		<h3>
			<?php
				if(isset($_SESSION['selected_branch']))
				{
					$selected_branch = $_SESSION['selected_branch'];
					echo "Showing Results for Branch Code: $selected_branch";
				}
			?>
		</h3>

	<!-------for whole unit-------->
		<h4>Whole Unit</h4>

		<table id="table_export1" class="list_inventory_table">
			<tr>
				<th>Customer</th>
				<th>Invoice Number</th>
				<th>Brand</th>
				<th>Model Name</th>
				<th>Model Number</th>
				<th>Return Note</th>
				<th>Date</th>
				<th>Created By</th>
				<th>Actions</th>
			</tr>

			<?php
				if(isset($_SESSION['selected_branch']))
				{
					$selected_branch = $_SESSION['selected_branch'];

					$user_username = $_COOKIE['logged_username'];
					$creator_branch_code = $_COOKIE['logged_username_branch_code'];

					$manage_customer_query = "SELECT * FROM returns WHERE type='whole' AND creator_branch_code='$selected_branch' ORDER BY id DESC";
					$manage_customer_query_run = mysqli_query($connect_link, $manage_customer_query);

					while($manage_customer_result = mysqli_fetch_assoc($manage_customer_query_run))
					{
						$return_id = $manage_customer_result['id'];
						
						echo "<tr>";
							echo "<td>" .$manage_customer_result['customer'] . "</td>";
							echo "<td>" . $manage_customer_result['invoice_num'] . "</td>";
							echo "<td>" . $manage_customer_result['brand'] . "</td>";
							echo "<td>" . $manage_customer_result['model_name'] . "</td>";
							echo "<td>" . $manage_customer_result['model_number'] . "</td>";
							echo "<td>" . $manage_customer_result['return_note'] . "</td>";
							echo "<td>" . $manage_customer_result['date'] . "</td>";		
							echo "<td>" . $manage_customer_result['creator_username'] . "</td>";

							echo "<td>";
								echo "<img return_id=\"$return_id\" class=\"product_edit_icon\" src=\"img/edit.png\"/>";
								echo "<img return_id=\"$return_id\" class=\"product_delete_icon\" src=\"img/delete.png\"/>";
								
							echo "</td>";
						echo "</tr>";
					}
				}
			?>
		</table>

	<!-------for parts only-------->
		<br>
		<h4>Parts Only</h4>

		<table id="table_export2" class="list_inventory_table">
			<tr>
				<th>Customer</th>
				<th>Invoice Number</th>
				<th>Brand</th>
				<th>Model Name</th>
				<th>Model Number</th>
				<th>Part Name</th>
				<th>Part Number</th>
				<th>Return Note</th>
				<th>Date</th>
				<th>Created By</th>
				<th>Actions</th>
			</tr>

			<?php
				if(isset($_SESSION['selected_branch']))
				{
					$selected_branch = $_SESSION['selected_branch'];
					
					include('connect_db.php');
					$user_username = $_COOKIE['logged_username'];
					$creator_branch_code = $_COOKIE['logged_username_branch_code'];

					$manage_customer_query = "SELECT * FROM returns WHERE type='part' AND creator_branch_code= '$selected_branch' ORDER BY id DESC";
					$manage_customer_query_run = mysqli_query($connect_link, $manage_customer_query);

					while($manage_customer_result = mysqli_fetch_assoc($manage_customer_query_run))
					{
						$return_id = $manage_customer_result['id'];
						
						echo "<tr>";
							echo "<td>" .$manage_customer_result['customer'] . "</td>";
							echo "<td>" . $manage_customer_result['invoice_num'] . "</td>";
							echo "<td>" . $manage_customer_result['brand'] . "</td>";
							echo "<td>" . $manage_customer_result['model_name'] . "</td>";
							echo "<td>" . $manage_customer_result['model_number'] . "</td>";
							echo "<td>" . $manage_customer_result['part_name'] . "</td>";
							echo "<td>" . $manage_customer_result['part_number'] . "</td>";
							echo "<td>" . $manage_customer_result['return_note'] . "</td>";
							echo "<td>" . $manage_customer_result['date'] . "</td>";		
							echo "<td>" . $manage_customer_result['creator_username'] . "</td>";

							echo "<td>";
								echo "<img return_id=\"$return_id\" class=\"product_edit_icon\" src=\"img/edit.png\"/>";
								echo "<img return_id=\"$return_id\" class=\"product_delete_icon\" src=\"img/delete.png\"/>";
								
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
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/admin_manage_return.php');
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
		$('.product_delete_icon').click(function()
		{
			var return_id = $.trim($(this).attr('return_id'));
			var query_recieved = "DELETE FROM returns WHERE id = '" + return_id + "'";
			//alert(query_recieved);

			$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
			{
				if(e==1)
				{
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/admin_manage_return.php');
				}
				else
				{
					$('.warn_box').text("Something went wrong while deleting the user");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});

	//on clicking on user edit icon
		$('.product_edit_icon').click(function()
		{
			var return_id = $(this).attr('return_id');

			$('.ajax_loader_bckgrnd').fadeIn(400);
				
			$.post('php/admin_edit_return_form.php', {return_id:return_id}, function(data)
			{
				//alert(data);
				$('.ajax_loader_box').fadeIn(400);
				$('.ajax_loader_content').html(data);
			});					
		});
	</script>
