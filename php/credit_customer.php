<div class="inventory_list_container">
	<table class="list_inventory_table">
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Mobile</th>
			<th>Address</th>
			<th>Balance</th>
			<th>Created By</th>
		</tr>

		<?php
			include('connect_db.php');
			$user_username = $_COOKIE['logged_username'];
			$creator_branch_code = $_COOKIE['logged_username_branch_code'];

			$manage_customer_query = "SELECT * FROM customers WHERE type='credit' AND creator_branch_code='$creator_branch_code' ORDER BY id DESC";
			$manage_customer_query_run = mysqli_query($connect_link, $manage_customer_query);

			while($manage_customer_result = mysqli_fetch_assoc($manage_customer_query_run))
			{
				$customer_id = $manage_customer_result['id'];
				
				echo "<tr>";
					echo "<td>" .$manage_customer_result['name'] . "</td>";
					echo "<td>" . $manage_customer_result['email'] . "</td>";
					echo "<td>" . $manage_customer_result['mobile'] . "</td>";
					echo "<td>" . $manage_customer_result['address'] . "</td>";
					echo "<td>" . $manage_customer_result['balance'] . "</td>";		
					echo "<td>" . $manage_customer_result['creator_username'] . "</td>";			
				echo "</tr>";
			}
		?>
	</table>
</div