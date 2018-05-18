<!-----for exce,csv generation------>
	<script type="text/javascript" src="js/FileSaver.min.js"></script>
	<script type="text/javascript" src="js/tableexport.min.js"></script>

	<script type="text/javascript">
		$('#table_export1').tableExport();
		$('#table_export2').tableExport();
	</script>

<!---------user list container------>

	<div id="table_export" class="inventory_list_container">

		<table id="table_export2">
			<tr>
				<th>Brand</th>
				<th>Model Name</th>
				<th>Model Number</th>
				<th>Part Name</th>
				<th>Part Number</th>
				<th>Sold Items</th>
				<th>In-Stock Items</th>
				<th>Sales Price</th>
				<th>Supplier Price</th>
				<th>HSN Code</th>
			</tr>

			<?php
				include 'connect_db.php';
				$creator_branch_code = $_COOKIE['logged_username_branch_code'];

				$list_user_query = "SELECT * FROM stock WHERE creator_branch_code = '$creator_branch_code' ORDER BY id DESC";
				$list_user_query_run = mysqli_query($connect_link, $list_user_query);

				while($list_user_assoc = mysqli_fetch_assoc($list_user_query_run))
				{
					$user_id = $list_user_assoc['id'];
					echo "<tr>";
						
						echo "<td>" . $list_user_assoc['brand'] . "</td>";
						echo "<td>" . $list_user_assoc['model_name'] . "</td>";
						echo "<td>" . $list_user_assoc['model_number'] . "</td>";
						echo "<td>" . $list_user_assoc['part_name'] . "</td>";
						echo "<td>" . $list_user_assoc['part_number'] . "</td>";
						echo "<td>" . $list_user_assoc['sold'] . "</td>";
						echo "<td>" . $list_user_assoc['in_stock'] . "</td>";
						echo "<td>" . $list_user_assoc['sales_price'] . "</td>";
						echo "<td>" . $list_user_assoc['supplier_price'] . "</td>";
						echo "<td>" . $list_user_assoc['hsn_code'] . "</td>";

					echo "</tr>";
				}
			?>
		</table>

	</div>