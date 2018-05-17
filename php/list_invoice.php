<?php
	include('connect_db.php');
	$query = $_POST['query'];
?>

<tr>
	<th>Quotation Number</th>
	<th>Customer Number</th>
	<th>Date of Generation</th>
	<th>Total Amount</th>

	<th>Payment Method</th>
	<th>Payment Date</th>

	<th>Created By</th>
	<th>Actions</th>
</tr>

<?php
	$list_quotation_query_run = mysqli_query($connect_link, $query);
	while($list_quotation_assoc = mysqli_fetch_assoc($list_quotation_query_run))
	{
		$quotation_id = $list_quotation_assoc['id'];

		$quotation_num = $list_quotation_assoc['quotation_num'];
		$customer = $list_quotation_assoc['customer'];
		$date = $list_quotation_assoc['date'];
		$type = $list_quotation_assoc['type'];
		$creator_username = $list_quotation_assoc['creator_username'];

		$payment_method = $list_quotation_assoc['payment_method'];
		$date_of_payment = $list_quotation_assoc['date_of_payment'];

	//gettting date of generation of quoatation
		$date = str_replace('/', '-', $date);
		$date = date('d M Y', strtotime($date));

	//gettting date of payment of quoatation
		$date_of_payment = str_replace('/', '-', $date_of_payment);
		$date_of_payment = date('d M Y', strtotime($date_of_payment));

	//for getting quotation code
		$this_year = date('y');
		$next_year = $this_year +1;
		$quotation_code = "VOLTA/" . $this_year . "-" . $next_year . "/" . $quotation_num;

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
			echo "<td>$payment_method</td>";
			echo "<td>$date_of_payment</td>";
			echo "<td>$creator_username</td>";
			echo "<td>";
				echo "<img quotation_num=\"$quotation_num\" class=\"user_view_icon\" src=\"img/view.png\"/>";
				echo "<img quotation_num=\"$quotation_num\" class=\"user_delete_icon\" src=\"img/delete.png\"/>";
			echo "</td>";
		echo "</tr>";
	}
?>
<!-----for exce,csv generation------>
	<script type="text/javascript" src="js/FileSaver.min.js"></script>
	<script type="text/javascript" src="js/tableexport.min.js"></script>
	<script type="text/javascript">
		$('#table_export').tableExport();
	</script>
		
	<script type="text/javascript">
	//on clicking on user delete icon
		$('.user_delete_icon').click(function()
		{
			//alert('ok');
			var quotation_num = $.trim($(this).attr('quotation_num'));
			var query_recieved = "DELETE FROM quotation WHERE quotation_num = '" + quotation_num + "'";

			$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
			{
				if(e==1)
				{
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/manage_invoice.php');
				}
				else
				{
					$('.warn_box').text("Something went wrong while deleting the user");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
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
				//alert(e);
			});

			window.open('php/invoice_pdf.php', '_blank');	
		});
		
	</script>