<!-----for exce,csv generation------>
	<script type="text/javascript" src="js/FileSaver.min.js"></script>
	<script type="text/javascript" src="js/tableexport.min.js"></script>

	<script type="text/javascript">
		$('#table_export1').tableExport();
		$('#table_export2').tableExport();
	</script>

<div id="table_export" class="inventory_list_container">
	
<!-------for whole unit-------->
	<h4>Whole Unit</h4>

	<table id="table_export1" class="list_inventory_table">
		<tr>
			<th>Brand</th>
			<th>Model Name</th>
			<th>Model Number</th>
			<th>Quantity</th>
			<th>Price</th>
			<th>Supplier</th>
			<th>Created By</th>
			<th>Actions</th>
		</tr>

		<?php
			include('connect_db.php');
			$user_username = $_COOKIE['logged_username'];
			$creator_branch_code = $_COOKIE['logged_username_branch_code'];

			$manage_customer_query = "SELECT * FROM products WHERE type='whole' AND creator_branch_code='$creator_branch_code' ORDER BY id DESC";
			$manage_customer_query_run = mysqli_query($connect_link, $manage_customer_query);

			while($manage_customer_result = mysqli_fetch_assoc($manage_customer_query_run))
			{
				$product_id = $manage_customer_result['id'];
				
				echo "<tr>";
					echo "<td>" .$manage_customer_result['brand'] . "</td>";
					echo "<td>" . $manage_customer_result['model_name'] . "</td>";
					echo "<td>" . $manage_customer_result['model_number'] . "</td>";

					echo "<td>" . $manage_customer_result['quantity'] . "</td>";
					echo "<td>" . $manage_customer_result['price'] . "</td>";
					echo "<td>" . $manage_customer_result['supplier_name'] . "</td>";
					echo "<td>" . $manage_customer_result['creator_username'] . "</td>";		

					echo "<td>";
						echo "<img product_id=\"$product_id\" class=\"product_edit_icon\" src=\"img/edit.png\"/>";
						echo "<img product_id=\"$product_id\" class=\"product_delete_icon\" src=\"img/delete.png\"/>";
						
					echo "</td>";
				echo "</tr>";
			}
		?>
	</table>

<!-------for parts only-------->
	<br>
	<h4>Parts Only</h4>

	<table id="table_export2" class="list_inventory_table">
		<tr>
			<th>Brand</th>
			<th>Model Name</th>
			<th>Model Number</th>

			<th>Part Name</th>
			<th>Part Number</th>

			<th>Quantity</th>
			<th>Price</th>
			<th>Supplier</th>
			<th>Created By</th>
			<th>Actions</th>
		</tr>

		<?php
			include('connect_db.php');
			$user_username = $_COOKIE['logged_username'];
			$creator_branch_code = $_COOKIE['logged_username_branch_code'];

			$manage_customer_query = "SELECT * FROM products WHERE type='part' AND creator_branch_code= '$creator_branch_code' ORDER BY id DESC";
			$manage_customer_query_run = mysqli_query($connect_link, $manage_customer_query);

			while($manage_customer_result = mysqli_fetch_assoc($manage_customer_query_run))
			{
				$product_id = $manage_customer_result['id'];
				
				echo "<tr>";
					echo "<td>" .$manage_customer_result['brand'] . "</td>";
					echo "<td>" . $manage_customer_result['model_name'] . "</td>";
					echo "<td>" . $manage_customer_result['model_number'] . "</td>";

					echo "<td>" . $manage_customer_result['part_name'] . "</td>";
					echo "<td>" . $manage_customer_result['part_number'] . "</td>";

					echo "<td>" . $manage_customer_result['quantity'] . "</td>";
					echo "<td>" . $manage_customer_result['price'] . "</td>";
					echo "<td>" . $manage_customer_result['supplier_name'] . "</td>";
					echo "<td>" . $manage_customer_result['creator_username'] . "</td>";		

					echo "<td>";
						echo "<img product_id=\"$product_id\" class=\"product_edit_icon\" src=\"img/edit.png\"/>";
						echo "<img product_id=\"$product_id\" class=\"product_delete_icon\" src=\"img/delete.png\"/>";
						
					echo "</td>";
				echo "</tr>";
			}
		?>
	</table>

</div>

<!---script------>
	<script type="text/javascript">
	//on clicking on user delete icon
		$('.product_delete_icon').click(function()
		{
			var product_id = $.trim($(this).attr('product_id'));
			var query_recieved = "DELETE FROM products WHERE id = '" + product_id + "'";
			//alert(query_recieved);

			$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
			{
				if(e==1)
				{
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/manage_product.php');
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
			var product_id = $(this).attr('product_id');

			$('.ajax_loader_bckgrnd').fadeIn(400);
				
			$.post('php/edit_product_form.php', {product_id:product_id}, function(data)
			{
				//alert(data);
				$('.ajax_loader_box').fadeIn(400);
				$('.ajax_loader_content').html(data);
			});					
		});
	</script>
