<?php
	session_start();
	include 'connect_db.php';
?>

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

<h3>Stock Report</h3>

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
	<br><br>

<!---------user list container------>
	<button id="pdf" onclick="javascript:generatePDF()">Export to pdf</button>

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
		<br>
		
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
				if(isset($_SESSION['selected_branch']))
				{
					$selected_branch = $_SESSION['selected_branch'];	

					$list_user_query = "SELECT * FROM stock WHERE creator_branch_code = '$selected_branch' ORDER BY id DESC";
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
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/admin_stock_report.php');
				}
				else
				{
					//alert(e);
					$('.warn_box').text("Something went wrong.");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});
	</script>