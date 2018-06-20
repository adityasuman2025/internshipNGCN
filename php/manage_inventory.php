<!-----for exce,csv generation------>
	<script type="text/javascript" src="js/FileSaver.min.js"></script>
	<script type="text/javascript" src="js/tableexport.min.js"></script>

	<script type="text/javascript">
		//$('#table_export1').tableExport();
		$('#table_export2').tableExport();
	</script>

<!---------user list container------>
	<h3>Manage Product/Service</h3>
	
	<div id="table_export" class="inventory_list_container">

		<table id="table_export2" class="part_only_table">
			<tr>
				<th>Brand</th>
				<th>Product/Part</th>
				<th>Product/Part Code</th>
				<th>HSN Code</th>
				<th>Description</th>
				<th>Type</th>
				<th>Actions</th>
			</tr>

			<?php
				include 'connect_db.php';

				$list_user_query = "SELECT * FROM inventory ORDER BY id DESC";
				$list_user_query_run = mysqli_query($connect_link, $list_user_query);

				while($list_user_assoc = mysqli_fetch_assoc($list_user_query_run))
				{
					$user_id = $list_user_assoc['id'];
					echo "<tr>";
						
						echo "<td>" . $list_user_assoc['brand'] . "</td>";
						echo "<td>" . $list_user_assoc['model_name'] . "</td>";
						echo "<td>" . $list_user_assoc['model_number'] . "</td>";
						echo "<td>" . $list_user_assoc['hsn_code'] . "</td>";
						echo "<td>" . $list_user_assoc['description'] . "</td>";
						echo "<td>" . $list_user_assoc['type'] . "</td>";
						echo "<td>";
							echo "<img user_id=\"$user_id\" class=\"inventory_edit_icon\" src=\"img/edit.png\"/>";
							echo "<img user_id=\"$user_id\" class=\"inventory_delete_icon\" src=\"img/delete.png\"/>";			
						echo "</td>";
					echo "</tr>";
				}
			?>
		</table>

	</div>

<!-----------script----------->
	<script type="text/javascript">
	//on clicking on inventory delete icon
		$('.inventory_delete_icon').click(function()
		{
			var user_id = $(this).attr('user_id');
			var query_recieved = "DELETE FROM inventory WHERE id = '" + user_id + "'";
			
			$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
			{
				if(e==1)
				{
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/manage_inventory.php');
				}
				else
				{
					$('.warn_box').text("Something went wrong while deleting the user");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});

	//on clicking on user edit icon
		$('.inventory_edit_icon').click(function()
		{
			var user_id = $(this).attr('user_id');

			$('.ajax_loader_bckgrnd').fadeIn(400);
			
			$.post('php/edit_inventory_form.php', {user_id:user_id}, function(data)
			{
				$('.ajax_loader_box').fadeIn(400);
				$('.ajax_loader_content').html(data);
			});					
		});
	</script>