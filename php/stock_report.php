<!-----for exce,csv generation------>
	<script type="text/javascript" src="js/FileSaver.min.js"></script>
	<script type="text/javascript" src="js/tableexport.min.js"></script>

	<script type="text/javascript">
		$('#table_export1').tableExport();
		$('#table_export2').tableExport();
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
	 				pdf.save('inventory.pdf');
	            }
	        });
	    }
    </script>

<!---------user list container------>

	<button id="pdf" onclick="javascript:generatePDF()">Export to pdf</button>

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

				$list_user_query = "SELECT * FROM stock ORDER BY id DESC";
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