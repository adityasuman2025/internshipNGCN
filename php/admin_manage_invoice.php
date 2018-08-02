<?php
	session_start();
	include('connect_db.php');
?>

<!-----for exce,csv generation------>
	<script type="text/javascript" src="js/FileSaver.min.js"></script>
	<script type="text/javascript" src="js/tableexport.min.js"></script>

	<script type="text/javascript">
		$('#table_export').tableExport();
		//$('#table_export2').tableExport();
	</script>

<!--------option to choose branch-------->
	<b class="admin_select_branch_text">Select Branch Code</b>

	<select id="admin_select_branch">
		<option value=""></option>
		<option value="*">All Branch</option>
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
	<br>
	<br>

<!------------page area---------->
	<div class="inventory_list_container">
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

	<!--------search date area--------->
		<div class="search_date_div">

			<b style="font-size: 120%;">From: </b> 
			<input type="date" class="date_lower_limit"> 

			<b style="font-size: 120%;">To: </b> 
			<input type="date" class="date_uper_limit"> 

			<button class="search_date_button">Search</button>
			<br><br>

			<?php
				if(isset($_SESSION['date_lower_limit']) && isset($_SESSION['date_uper_limit']))
				{
					$date_lower_limit = $_SESSION['date_lower_limit'];
					$date_uper_limit = $_SESSION['date_uper_limit'];

				//getting date lower limit
					$date_lower_limit = str_replace('/', '-', $date_lower_limit);
					$date_lower_limit = date('d M Y', strtotime($date_lower_limit));

				//getting date uper limit
					$date_uper_limit = str_replace('/', '-', $date_uper_limit);
					$date_uper_limit = date('d M Y', strtotime($date_uper_limit));

					echo "<span>Showing results from <b>$date_lower_limit</b> to <b>$date_uper_limit</b></span>";
				}
				else
				{
					//echo 'hello';
				}

			?>
		</div>
		<br>

	<!--------table area--------->
		<table id="table_export" class="list_inventory_table">				
			<tr>
				<th>Quotation Number</th>
				<?php
					if(isset($selected_branch))
					{
						if($selected_branch == '*')
						{
							echo "<th>Branch Code</th>";
						}
					}
					else
					{
						echo "<th>Branch Code</th>";
					}
				?>
				<th>Customer Number</th>
				<th>Date of Generation</th>
				<th>Total Amount</th>
				
				<th>Payment Method</th>
				<th>Payment Date</th>
				<th>Created By</th>
				<th>Actions</th>
			</tr>

			<?php

				if(isset($_SESSION['selected_branch']))
				{
				//getting total count of quotation num at that branch
					$selected_branch = $_SESSION['selected_branch'];
					
					$count_quotation_num_query = "SELECT quotation_num FROM quotation WHERE creator_branch_code = '$selected_branch' AND payment_method !='' GROUP BY quotation_num ";
					if($count_quotation_num_query_run = mysqli_query($connect_link, $count_quotation_num_query))
					{
						$count_quotation_num =  mysqli_num_rows($count_quotation_num_query_run);
					}
					else
					{
						$count_quotation_num = 0;
					}

				//setting limits of shown results	
					$gap = 25;

					if(isset($_SESSION['lower_limit']) && isset($_SESSION['uper_limit']))
					{
						$lower_limit = $_SESSION['lower_limit'];
						$uper_limit = $_SESSION['uper_limit'];
					}
					else
					{
						$lower_limit = 0;
						$uper_limit = 25;
					}

				//setting limits of shown results	
					if(isset($_SESSION['date_lower_limit']) && isset($_SESSION['date_uper_limit']))
					{
						$date_lower_limit = $_SESSION['date_lower_limit'];
						$date_uper_limit = $_SESSION['date_uper_limit'];
					}
					else
					{
						$date_lower_limit = "0-0-0";
						$date_uper_limit = date('Y-m-d');
					}

				//showing result of the selected branch
					$user_username = $_COOKIE['logged_username'];
					$creator_branch_code = $_COOKIE['logged_username_branch_code'];

					if($selected_branch == '*')
					{
						$manage_customer_query = "SELECT * FROM quotation WHERE payment_method !='' AND date >= '$date_lower_limit' AND date <= '$date_uper_limit' GROUP BY quotation_num ORDER BY quotation_num DESC LIMIT " . $lower_limit . ", " . $uper_limit;
					}
					else
					{
						$manage_customer_query = "SELECT * FROM quotation WHERE creator_branch_code ='$selected_branch' AND payment_method !='' AND date >= '$date_lower_limit' AND date <= '$date_uper_limit' GROUP BY quotation_num ORDER BY quotation_num DESC LIMIT " . $lower_limit . ", " . $uper_limit;
					}

					// $manage_customer_query = "SELECT * FROM quotation WHERE creator_branch_code ='$selected_branch' AND payment_method !='' AND date >= '$date_lower_limit' AND date <= '$date_uper_limit' GROUP BY quotation_num ORDER BY quotation_num DESC LIMIT " . $lower_limit . ", " . $uper_limit;

					$manage_customer_query_run = mysqli_query($connect_link, $manage_customer_query);

					while($manage_customer_result = mysqli_fetch_assoc($manage_customer_query_run))
					{
						$quotation_id = $manage_customer_result['id'];

						$quotation_num = $manage_customer_result['quotation_num'];
						$customer = $manage_customer_result['customer'];
						$date = $manage_customer_result['date'];
						$type = $manage_customer_result['type'];

						$payment_method = $manage_customer_result['payment_method'];
						$date_of_payment = $manage_customer_result['date_of_payment'];

						$creator_username = $manage_customer_result['creator_username'];
						$creator_branch_code = $manage_customer_result['creator_branch_code'];

					//gettting date of generation of quoatation
						$date = str_replace('/', '-', $date);
						$date = date('d M Y', strtotime($date));

					//gettting date of payment of quoatation
						$date_of_payment = str_replace('/', '-', $date_of_payment);
						$date_of_payment = date('d M Y', strtotime($date_of_payment));

						if($date_of_payment == "01 Jan 1970")
						{
							$date_of_payment = "not paid";
						}

					//for getting quotation code
						$this_year = date('y');
						$next_year = $this_year +1;
						
						$comp_code = $_SESSION["comp_code"];

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
							if($selected_branch == '*')
							{
								echo "<td>" .$creator_branch_code . "</td>";
							}

							echo "<td>$customer</td>";
							echo "<td>$date</td>";
							echo "<td>$final_price</td>";
							// echo "<td>$type</td>";
							echo "<td>$payment_method</td>";
							echo "<td>$date_of_payment</td>";
							echo "<td>$creator_username</td>";
							echo "<td>";
								echo "<img quotation_num=\"$quotation_num\" class=\"user_view_icon\" src=\"img/view.png\"/>";
								echo "<img quotation_num=\"$quotation_num\" type=\"$type\" class=\"user_edit_icon\" src=\"img/edit.png\"/>";
								echo "<img quotation_num=\"$quotation_num\" class=\"user_delete_icon\" src=\"img/delete.png\"/>";
							echo "</td>";
						echo "</tr>";
					
					}
				}
				else
				{
					$gap = 25;
					$lower_limit = 0;
					$uper_limit = 25;
					$count_quotation_num = 0;

					//getting total count of quotation num at that branch
					$selected_branch = '*';

					if($selected_branch == '*')
					{
						$count_quotation_num_query = "SELECT quotation_num FROM quotation WHERE payment_method !='' GROUP BY quotation_num ";
					}
					else
					{
						$count_quotation_num_query = "SELECT quotation_num FROM quotation WHERE creator_branch_code = '$selected_branch' AND payment_method !='' GROUP BY quotation_num ";
					}

					// $count_quotation_num_query = "SELECT quotation_num FROM quotation WHERE creator_branch_code = '$selected_branch' AND payment_method !='' GROUP BY quotation_num ";
					if($count_quotation_num_query_run = mysqli_query($connect_link, $count_quotation_num_query))
					{
						$count_quotation_num =  mysqli_num_rows($count_quotation_num_query_run);
					}
					else
					{
						$count_quotation_num = 0;
					}

				//setting limits of shown results	
					$gap = 25;

					if(isset($_SESSION['lower_limit']) && isset($_SESSION['uper_limit']))
					{
						$lower_limit = $_SESSION['lower_limit'];
						$uper_limit = $_SESSION['uper_limit'];
					}
					else
					{
						$lower_limit = 0;
						$uper_limit = 25;
					}

				//setting limits of shown results	
					if(isset($_SESSION['date_lower_limit']) && isset($_SESSION['date_uper_limit']))
					{
						$date_lower_limit = $_SESSION['date_lower_limit'];
						$date_uper_limit = $_SESSION['date_uper_limit'];
					}
					else
					{
						$date_lower_limit = "0-0-0";
						$date_uper_limit = date('Y-m-d');
					}

				//showing result of the selected branch
					$user_username = $_COOKIE['logged_username'];
					$creator_branch_code = $_COOKIE['logged_username_branch_code'];

					if($selected_branch == '*')
					{
						$manage_customer_query = "SELECT * FROM quotation WHERE payment_method !='' AND date >= '$date_lower_limit' AND date <= '$date_uper_limit' GROUP BY quotation_num ORDER BY quotation_num DESC LIMIT " . $lower_limit . ", " . $uper_limit;
					}
					else
					{
						$manage_customer_query = "SELECT * FROM quotation WHERE creator_branch_code ='$selected_branch' AND payment_method !='' AND date >= '$date_lower_limit' AND date <= '$date_uper_limit' GROUP BY quotation_num ORDER BY quotation_num DESC LIMIT " . $lower_limit . ", " . $uper_limit;
					}

					// $manage_customer_query = "SELECT * FROM quotation WHERE creator_branch_code ='$selected_branch' AND payment_method !='' AND date >= '$date_lower_limit' AND date <= '$date_uper_limit' GROUP BY quotation_num ORDER BY quotation_num DESC LIMIT " . $lower_limit . ", " . $uper_limit;

					$manage_customer_query_run = mysqli_query($connect_link, $manage_customer_query);

					while($manage_customer_result = mysqli_fetch_assoc($manage_customer_query_run))
					{
						$quotation_id = $manage_customer_result['id'];

						$quotation_num = $manage_customer_result['quotation_num'];
						$customer = $manage_customer_result['customer'];
						$date = $manage_customer_result['date'];
						$type = $manage_customer_result['type'];

						$payment_method = $manage_customer_result['payment_method'];
						$date_of_payment = $manage_customer_result['date_of_payment'];

						$creator_username = $manage_customer_result['creator_username'];
						$creator_branch_code = $manage_customer_result['creator_branch_code'];

					//gettting date of generation of quoatation
						$date = str_replace('/', '-', $date);
						$date = date('d M Y', strtotime($date));

					//gettting date of payment of quoatation
						$date_of_payment = str_replace('/', '-', $date_of_payment);
						$date_of_payment = date('d M Y', strtotime($date_of_payment));

						if($date_of_payment == "01 Jan 1970")
						{
							$date_of_payment = "not paid";
						}

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
							if($selected_branch == '*')
							{
								echo "<td>" .$creator_branch_code . "</td>";
							}

							echo "<td>$customer</td>";
							echo "<td>$date</td>";
							echo "<td>$final_price</td>";
							// echo "<td>$type</td>";
							echo "<td>$payment_method</td>";
							echo "<td>$date_of_payment</td>";
							echo "<td>$creator_username</td>";
							echo "<td>";
								echo "<img quotation_num=\"$quotation_num\" class=\"user_view_icon\" src=\"img/view.png\"/>";
								echo "<img quotation_num=\"$quotation_num\" type=\"$type\" class=\"user_edit_icon\" src=\"img/edit.png\"/>";
								echo "<img quotation_num=\"$quotation_num\" class=\"user_delete_icon\" src=\"img/delete.png\"/>";
							echo "</td>";
						echo "</tr>";
					
					}
				}
			?>

		</table>
		<br><br>

		<?php
			
			if($lower_limit > 0)
			{
				echo " <button class=\"go_back_button\">Back</button> ";
			}

			if($uper_limit < $count_quotation_num)
			{
				echo " <button class=\"go_next_button\">Next</button> ";
			}

			echo "<br>";
			echo $lower_limit . " - " . $uper_limit ;
		?>

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
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/admin_manage_invoice.php');
				}
				else
				{
					//alert(e);
					$('.warn_box').text("Something went wrong.");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});

	//on clicking on next button
		$('.go_next_button').click(function()
		{
			var gap = parseInt("<?php echo $gap; ?>");

		//changing lower limit
			var session_of = parseInt("<?php echo $lower_limit;?>") + gap;
			var session_name = "lower_limit";

			$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
			{
				if(e==1)
				{
					var gap = parseInt("<?php echo $gap; ?>");

				//changing uper limit
					var session_of = parseInt("<?php echo $uper_limit;?>") + gap;
					var session_name = "uper_limit";

					$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
					{
						if(e==1)
						{
							$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/admin_manage_invoice.php');
						}
						else
						{
							//alert(e);
							$('.warn_box').text("Something went wrong while setting limits");
							$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
						}
					});
				}
				else
				{
					//alert(e);
					$('.warn_box').text("Something went wrong while setting limits");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});

	//on clicking on back button
		$('.go_back_button').click(function()
		{
			var gap = parseInt("<?php echo $gap; ?>");

		//changing lower limit
			var session_of = parseInt("<?php echo $lower_limit;?>") - gap;
			var session_name = "lower_limit";

			$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
			{
			//changing uper limit
				var session_of = parseInt("<?php echo $uper_limit;?>") - gap;
				var session_name = "uper_limit";

				$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
				{
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/admin_manage_invoice.php');
				});		
			});
	
		});

	//on clicking on user delete icon
		$('.user_delete_icon').click(function()
		{
			var quotation_num = $.trim($(this).attr('quotation_num'));
		
		//deleting notes of the invoice
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

		//deleting invoice
			var query_recieved = "DELETE FROM quotation WHERE quotation_num = '" + quotation_num + "'";
			$.post('php/query_runner.php', {query_recieved:query_recieved}, function(e)
			{
				if(e==1)
				{
					$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/admin_manage_invoice.php');
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
			var session_name = "pdf_invoice_of";
				
			$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
			{
				if(e ==1)
				{
					window.open('php/invoice_pdf.php', '_blank');	
				}
				else
				{
					$('.warn_box').text("Something went wrong while generating pdf file of the quotation.");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});
	
	//on clicking on user edit icon
		$('.user_edit_icon').click(function()
		{
			var quotation_num =  $.trim($(this).attr('quotation_num'));			
						
			$.post('php/edit_invoice.php', {quotation_num:quotation_num}, function(data)
			{
				$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").html(data);	
			});					
		});


	//on clicking on search button
		$('.search_date_button').click(function()
		{
			date_lower_limit = $('.date_lower_limit').val();
			date_uper_limit = $('.date_uper_limit').val();

		//setting up date lower limit
			var session_of = date_lower_limit;
			var session_name = "date_lower_limit";

			$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
			{
				if(e==1)
				{
				//setting up date uper limit
					var session_of = date_uper_limit;
					var session_name = "date_uper_limit";

					$.post('php/session_creator.php', {session_of: session_of, session_name: session_name}, function(e)
					{
						if(e==1)
						{
							$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/admin_manage_invoice.php');
						}
						else
						{
							//alert(e);
							$('.warn_box').text("Something went wrong while setting date limits");
							$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
						}
					});
				}
				else
				{
					//alert(e);
					$('.warn_box').text("Something went wrong while setting date limits");
					$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
				}
			});
		});

	</script>
