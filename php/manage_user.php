<!-----for exce,csv generation------>
	<script type="text/javascript" src="js/FileSaver.min.js"></script>
	<script type="text/javascript" src="js/tableexport.min.js"></script>

	<script type="text/javascript">
		$('#table_export').tableExport();
	</script>

<!-----for pdf generation------>
<!-- 	<script type="text/javascript" src="js/jspdf.debug.js"></script>
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
	 				pdf.save('users.pdf');
	            }
	        });
	    }
    </script> -->

<h3>Manage User</h3>

<!---------user list container------>
	<div class="user_list_container">
		<!-- <button id="pdf" onclick="javascript:generatePDF()">Export to pdf</button> -->

		<table id="table_export" class="list_user_title">
			<tr>
				<th>Username</th>
				<th>Admin</th>
				<th>Address</th>
				<th>Email ID</th>
				<th>Service Tax Number</th>
				<th>Branch Code</th>
				<th>Actions</th>
			</tr>

			<?php
				include 'connect_db.php';

				$list_user_query = "SELECT * FROM users ORDER BY id DESC";
				$list_user_query_run = mysqli_query($connect_link, $list_user_query);

				while($list_user_assoc = mysqli_fetch_assoc($list_user_query_run))
				{
					$user_id = $list_user_assoc['id'];
					$isadmin = $list_user_assoc['isadmin'];
					echo "<tr>";
						echo "<td>" . $list_user_assoc['username'] . "</td>";
							if($isadmin =='0')
							{
								echo "<td>No</td>";
							}
							else
							{
								echo "<td>Yes</td>";
							}
						
						echo "<td>" . $list_user_assoc['address'] . "</td>";
						echo "<td>" . $list_user_assoc['email'] . "</td>";
						echo "<td>" . $list_user_assoc['serv_tax_no'] . "</td>";
						echo "<td>" . $list_user_assoc['branch_code'] . "</td>";
						echo "<td>";
							echo "<img user_id=\"$user_id\" class=\"user_edit_icon\" src=\"img/edit.png\"/>";
							echo "<img user_id=\"$user_id\" class=\"user_delete_icon\" src=\"img/delete.png\"/>";
							
						echo "</td>";
					echo "</tr>";
				}
			?>
		</table>
	</div>

<!------script---------->
	<script type="text/javascript">
	//on clicking on user delete icon
		$('.user_delete_icon').click(function()
		{
			var user_id = $(this).attr('user_id');
			var query_recieved = "DELETE FROM users WHERE id = '" + user_id + "'";

			$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
			{
				if(e==1)
				{
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/manage_user.php');
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
			var user_id = $(this).attr('user_id');

			$('.ajax_loader_bckgrnd').fadeIn(400);
			
			$.post('php/edit_user_form.php', {user_id:user_id}, function(data)
			{
				$('.ajax_loader_box').fadeIn(400);
				$('.ajax_loader_content').html(data);
			});					
		});
	</script>