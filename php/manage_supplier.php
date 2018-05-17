<!-----for exce,csv generation------>
	<script type="text/javascript" src="js/FileSaver.min.js"></script>
	<script type="text/javascript" src="js/tableexport.min.js"></script>

	<script type="text/javascript">
		$('#table_export').tableExport();
	</script>

<!-----for pdf generation------>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.debug.js"></script>
	<script type="text/javascript" src="js/html2canvas.js"></script>

	<script type="text/javascript">
	     function generatePDF() {
	        window.scrollTo(0, 0);

	        var table_height = $('#table_export').height() + 1000;
	        var table_width = $('#table_export').width() + 200;
	 	//	alert(table_width);
	      
	        var pdf = new jsPDF('p', 'pt', [table_width, table_height]);
	 
	        html2canvas($("#table_export")[0], {
	            onrendered: function(canvas) {
	                //document.body.appendChild(canvas);
	                var ctx = canvas.getContext('2d');
	                var imgData = canvas.toDataURL("image/png", 1.0);
	                var width = canvas.width;
	                var height = canvas.clientHeight;
	                pdf.addImage(imgData, 'PNG', 10, 10, (width - 10), (height));
	 				pdf.save('suppliers.pdf');
	            }
	        });
	    }
    </script>

<div class="inventory_list_container">
	<button id="pdf" onclick="javascript:generatePDF()">Export to pdf</button>
	
	<table id="table_export" class="list_inventory_table">
		<tr>
			<th>Supplier Name</th>
			<th>Supplier Email</th>
			<th>Supplier Mobile</th>
			<th>Supplier Address</th>
			<th>Supplier Details</th>
			<th>Name of Products</th>
			<th>Created By</th>
			<th>Actions</th>
		</tr>

		<?php
			include('connect_db.php');
			$user_username = $_COOKIE['logged_username'];
			$creator_branch_code = $_COOKIE['logged_username_branch_code'];

			$manage_customer_query = "SELECT * FROM supplier WHERE creator_branch_code = '$creator_branch_code' ORDER BY id DESC";
			$manage_customer_query_run = mysqli_query($connect_link, $manage_customer_query);

			while($manage_customer_result = mysqli_fetch_assoc($manage_customer_query_run))
			{
				$supplier_id = $manage_customer_result['id'];
				
				echo "<tr>";
					echo "<td>" .$manage_customer_result['name'] . "</td>";
					echo "<td>" . $manage_customer_result['email'] . "</td>";
					echo "<td>" . $manage_customer_result['mobile'] . "</td>";
					echo "<td>" . $manage_customer_result['address'] . "</td>";
					echo "<td>" . $manage_customer_result['details'] . "</td>";
					echo "<td>" . $manage_customer_result['name_of_product'] . "</td>";
					echo "<td>" . $manage_customer_result['creator_username'] . "</td>";
					echo "<td>";
						echo "<img supplier_id=\"$supplier_id\" class=\"user_edit_icon\" src=\"img/edit.png\"/>";
						echo "<img supplier_id=\"$supplier_id\" class=\"user_delete_icon\" src=\"img/delete.png\"/>";
						
					echo "</td>";
				echo "</tr>";
			}
		?>
	</table>
</div>

<!---script------>
	<script type="text/javascript">
	//on clicking on user delete icon
		$('.user_delete_icon').click(function()
		{
			var supplier_id = $.trim($(this).attr('supplier_id'));
			var query_recieved = "DELETE FROM supplier WHERE id = '" + supplier_id + "'";

			$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
			{
				if(e==1)
				{
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/manage_supplier.php');
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
			var supplier_id = $(this).attr('supplier_id');

			$('.ajax_loader_bckgrnd').fadeIn(400);
				
			$.post('php/edit_supplier_form.php', {supplier_id:supplier_id}, function(data)
			{
				//alert(data);
				$('.ajax_loader_box').fadeIn(400);
				$('.ajax_loader_content').html(data);
			});					
		});

	</script>

	