<?php
	include('connect_db.php');
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];
?>

<!-----for exce,csv generation------>
	<script type="text/javascript" src="js/FileSaver.min.js"></script>
	<script type="text/javascript" src="js/tableexport.min.js"></script>

	<script type="text/javascript">
		$('#table_export').tableExport();
	</script>

<div id="table_export1" class="quotation_list_container">
	<table id="table_export">
		<tr>
			<th>Quotation Number</th>
			<th>Customer Number</th>
			<th>Date of Generation</th>
			<th>Total Amount</th>
			<th>Created By</th>
			<th>Actions</th>
		</tr>

		<?php
			$query = "SELECT * FROM quotation WHERE creator_branch_code = '$creator_branch_code' AND payment_method ='' GROUP BY quotation_num ORDER BY quotation_num DESC";
			$list_quotation_query_run = mysqli_query($connect_link, $query);
			while($list_quotation_assoc = mysqli_fetch_assoc($list_quotation_query_run))
			{
				$quotation_id = $list_quotation_assoc['id'];

				$quotation_num = $list_quotation_assoc['quotation_num'];
				$customer = $list_quotation_assoc['customer'];
				$date = $list_quotation_assoc['date'];
				$type = $list_quotation_assoc['type'];
				$creator_username = $list_quotation_assoc['creator_username'];

			//gettting date of generation of quoatation
				$date = str_replace('/', '-', $date);
				$date = date('d M Y', strtotime($date));

			//for getting quotation code
				$this_year = date('y');
				$next_year = $this_year +1;

				$website = $_SERVER['HTTP_HOST'];
				if($website == "localhost" OR $website == "volta.pnds.in" OR $website == "erp.voltatech.in")
				{
					$comp_code = "VOLTA/";
				}
				else if($website == "oxy.pnds.in")
				{
					$comp_code = "OXY/";
				}
				else
				{
					$comp_code = "VOLTA/";
				}		

				$quotation_code = $comp_code . $this_year . "-" . $next_year . "/" . $quotation_num;

			//for getting total price of the quotation
				$final_price = 0;

				$get_element_price_query = "SELECT total_price FROM quotation WHERE quotation_num='$quotation_num'";
				$get_element_price_query_run = mysqli_query($connect_link, $get_element_price_query);

				while($get_element_price_assoc = mysqli_fetch_assoc($get_element_price_query_run))
				{
					$element_price = $get_element_price_assoc['total_price'];

					$final_price = $final_price + $element_price;
				}

				echo "<tr>";
					echo "<td>$quotation_code</td>";
					echo "<td>$customer</td>";
					echo "<td>$date</td>";
					echo "<td>$final_price</td>";
					// echo "<td><button quotation_num=\"$quotation_num\" class=\"quot_into_invoice_button\">Generate</button></td>";
					echo "<td>$creator_username</td>";
					echo "<td>";
						echo "<img quotation_num=\"$quotation_num\" class=\"user_view_icon\" src=\"img/view.png\"/>";
						echo "<img quotation_num=\"$quotation_num\" type=\"$type\" class=\"user_edit_icon\" src=\"img/edit.png\"/>";
						echo "<img quotation_num=\"$quotation_num\" class=\"user_delete_icon\" src=\"img/delete.png\"/>";
						echo "<br>";
						echo "<button class=\"performa_button\" style=\"color: #cc0000; background: white;\" quotation_num=\"$quotation_num\">Performa Invoice</button>";
					echo "</td>";
				echo "</tr>";
			}
		?>
	</table>
</div>

<!----------script------------>		
	<script type="text/javascript">
	//on clicking on user delete icon
		$('.user_delete_icon').click(function()
		{
			var quotation_num = $.trim($(this).attr('quotation_num'));
		
		//deleting notes of the quotation
			var query_recieved2 = "DELETE FROM notes WHERE quotation_num = '" + quotation_num + "'";
			$.post('php/query_runner.php', {query_recieved: query_recieved2}, function(f)
			{
				if(f == 1)
				{
					//$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/manage_invoice.php');
				}
				else
				{
					$('.warn_box').text("Something went wrong while deleting the notes");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});		

		//deleting quotation
			var query_recieved = "DELETE FROM quotation WHERE quotation_num = '" + quotation_num + "'";
			$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
			{
				if(e==1)
				{
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/manage_quotation.php');
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
			var quotation_num =  $.trim($(this).attr('quotation_num'));
			var type =  $.trim($(this).attr('type'));
			
			$.post('php/edit_quotation.php', {quotation_num:quotation_num}, function(data)
			{
				$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").html(data);	
			});
					
		});

	//on clicking on view icon
		$('.user_view_icon').click(function()
		{
			var quotation_num =  $.trim($(this).attr('quotation_num'));

		//for getting pdf of the quotation
			var session_of = quotation_num;
			var session_name = "pdf_quotation_of";
				
			$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
			{
				if(e ==1)
				{
					window.open('php/quotation_pdf.php', '_blank');	
				}
				else
				{
					$('.warn_box').text("Something went wrong while generating pdf file of the quotation.");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});
	
	//on clicking on performa invoice button
		$('.performa_button').click(function()
		{
			var quotation_num =  $.trim($(this).attr('quotation_num'));

		//for defining type of the quotation
			var session_of = "performa";
			var session_name = "quotation_type";
				
			$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
			{

			});

		//for getting pdf of the invoice
			var session_of = quotation_num;
			var session_name = "pdf_quotation_of";
				
			$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
			{
				if(e ==1)
				{
					window.open('php/quotation_pdf.php', '_blank');	
				}
				else
				{
					$('.warn_box').text("Something went wrong while generating pdf file of the invoice.");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});
	</script>