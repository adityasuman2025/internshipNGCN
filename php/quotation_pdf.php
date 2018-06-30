<?php
	session_start();

//getting invoice type
	if(isset($_SESSION['quotation_type']))
	{
		$quotation_type = $_SESSION['quotation_type'];
	}
	else
	{
		$quotation_type = "normal";
	}

	if($quotation_type == "normal")
	{
		$quotation_type_text = "Quotation";
	}
	else if($quotation_type == "performa")
	{
		$quotation_type_text = "Performa Invoice";
	}

//fetching data from database
	include('connect_db.php');

	$quotation_num = $_SESSION['pdf_quotation_of'];

//generating quotation code
	$this_year = date('y');
	$next_year = $this_year +1;

	$quotation_code = "VOLTA/" . $this_year . "-" . $next_year . "/" . $quotation_num;

//query
	$query = "SELECT * FROM quotation WHERE quotation_num = '$quotation_num' AND payment_method='' ORDER BY serial";
	
//getting the service id and purchase order fields from any of the item od that invoice
	$service_id_org = "";
	$purchase_order_org = "";

	$get_invoice_info_query_run = mysqli_query($connect_link, $query);
	while($get_invoice_info_assoc = mysqli_fetch_assoc($get_invoice_info_query_run))
	{
		$service_id = $get_invoice_info_assoc['service_id'];
		if($service_id != "")
		{
			$service_id_org = $service_id;
		}

		$purchase_order = $get_invoice_info_assoc['purchase_order'];
		if($purchase_order != "")
		{
			$purchase_order_org = $purchase_order;
		}
	}

//getting invoice info
	$query_run = mysqli_query($connect_link, $query);

	if($query_assoc = mysqli_fetch_assoc($query_run))
	{
		$creator_username = $query_assoc['creator_username'];
		$creator_branch_code = $query_assoc['creator_branch_code'];
		$customer_name = $query_assoc['customer'];
		$type = $query_assoc['type'];
	
	//gettting date of generation of quotation
		$date = $query_assoc['date'];
		$date = str_replace('/', '-', $date);
		$date_of_generation = date('d M Y', strtotime($date));

	//getting information of branch
		$get_branch_info_query = "SELECT * FROM branch WHERE branch_code = '$creator_branch_code'";
		$get_branch_info_query_run = mysqli_query($connect_link, $get_branch_info_query);

		$get_branch_info_assoc = mysqli_fetch_assoc($get_branch_info_query_run);
		$branch_company_name = $get_branch_info_assoc['company_name'];
		$branch_name = $get_branch_info_assoc['branch_name'];
		$branch_address = $get_branch_info_assoc['address'];
		$branch_email = $get_branch_info_assoc['email'];
		$branch_phone_number = $get_branch_info_assoc['phone_number'];
		$branch_gst_number = $get_branch_info_assoc['gst_number'];
		
		$bank_accnt_name = $get_branch_info_assoc['bank_accnt_name'];
		$bank_accnt_no = $get_branch_info_assoc['bank_accnt_no'];
		$bank_name = $get_branch_info_assoc['bank_name'];
		$bank_ifsc = $get_branch_info_assoc['bank_ifsc'];

		//breaking branch address
			$branch_address_broken = explode("#", $branch_address);
			$hash_count = substr_count($branch_address,"#");
			if($hash_count == 4)
			{
				$branch_address_array[0] = $branch_address_broken[0];
				$branch_address_array[1] = $branch_address_broken[1];
				$branch_address_array[2] = $branch_address_broken[2];
				$branch_address_array[3] = $branch_address_broken[3];
				$branch_address_array[4] = $branch_address_broken[4];
			}
			else if($hash_count == 3)
			{
				$branch_address_array[0] = $branch_address_broken[0];
				$branch_address_array[1] = $branch_address_broken[1];
				$branch_address_array[2] = $branch_address_broken[2];
				$branch_address_array[3] = $branch_address_broken[3];
				$branch_address_array[4] = "";
			}
			else if($hash_count == 2)
			{
				$branch_address_array[0] = $branch_address_broken[0];
				$branch_address_array[1] = $branch_address_broken[1];
				$branch_address_array[2] = $branch_address_broken[2];
				$branch_address_array[3] = "";
				$branch_address_array[4] = "";
			}
			else if($hash_count == 1)
			{
				$branch_address_array[0] = $branch_address_broken[0];
				$branch_address_array[1] = $branch_address_broken[1];
				$branch_address_array[2] = "";
				$branch_address_array[3] = "";
				$branch_address_array[4] = "";
			}
			else if($hash_count == 0)
			{
				$branch_address_array[0] = $branch_address_broken[0];
				$branch_address_array[1] = "";
				$branch_address_array[2] = "";
				$branch_address_array[3] = "";
				$branch_address_array[4] = "";
			}
			else
			{
				$branch_address_array[0] = $branch_address_broken[0];
				$branch_address_array[1] = "";
				$branch_address_array[2] = "";
				$branch_address_array[3] = "";
				$branch_address_array[4] = "";
			}

	//getting customer info
		$get_customer_info_query = "SELECT * FROM customers WHERE name = '$customer_name'";
		$get_customer_info_query_run = mysqli_query($connect_link, $get_customer_info_query);
		$get_customer_info_assoc = mysqli_fetch_assoc($get_customer_info_query_run);
		
		$customer_company_name = $get_customer_info_assoc['company_name'];
		$customer_address = $get_customer_info_assoc['address'];
		$customer_mobile = $get_customer_info_assoc['mobile'];
		$customer_email = $get_customer_info_assoc['email'];
		$customer_gst = $get_customer_info_assoc['gst'];;
		$customer_shipping_address = $get_customer_info_assoc['shipping_address'];

		//breaking customer address
			$customer_address_broken = explode("#", $customer_address);
			$hash_count = substr_count($customer_address,"#");
			if($hash_count == 4)
			{
				$customer_address_array[0] = $customer_address_broken[0];
				$customer_address_array[1] = $customer_address_broken[1];
				$customer_address_array[2] = $customer_address_broken[2];
				$customer_address_array[3] = $customer_address_broken[3];
				$customer_address_array[4] = $customer_address_broken[4];
			}
			else if($hash_count == 3)
			{
				$customer_address_array[0] = $customer_address_broken[0];
				$customer_address_array[1] = $customer_address_broken[1];
				$customer_address_array[2] = $customer_address_broken[2];
				$customer_address_array[3] = $customer_address_broken[3];
				$customer_address_array[4] = "";
			}
			else if($hash_count == 2)
			{
				$customer_address_array[0] = $customer_address_broken[0];
				$customer_address_array[1] = $customer_address_broken[1];
				$customer_address_array[2] = $customer_address_broken[2];
				$customer_address_array[3] = "";
				$customer_address_array[4] = "";
			}
			else if($hash_count == 1)
			{
				$customer_address_array[0] = $customer_address_broken[0];
				$customer_address_array[1] = $customer_address_broken[1];
				$customer_address_array[2] = "";
				$customer_address_array[3] = "";
				$customer_address_array[4] = "";
			}
			else if($hash_count == 0)
			{
				$customer_address_array[0] = $customer_address_broken[0];
				$customer_address_array[1] = "";
				$customer_address_array[2] = "";
				$customer_address_array[3] = "";
				$customer_address_array[4] = "";
			}
			else
			{
				$customer_address_array[0] = $customer_address_broken[0];
				$customer_address_array[1] = "";
				$customer_address_array[2] = "";
				$customer_address_array[3] = "";
				$customer_address_array[4] = "";
			}

		//breaking customer shipping address
			$customer_shipping_address_broken = explode("#", $customer_shipping_address);
			$hash_count = substr_count($customer_shipping_address,"#");
			if($hash_count == 4)
			{
				$customer_shipping_address_array[0] = $customer_shipping_address_broken[0];
				$customer_shipping_address_array[1] = $customer_shipping_address_broken[1];
				$customer_shipping_address_array[2] = $customer_shipping_address_broken[2];
				$customer_shipping_address_array[3] = $customer_shipping_address_broken[3];
				$customer_shipping_address_array[4] = $customer_shipping_address_broken[4];
			}
			else if($hash_count == 3)
			{
				$customer_shipping_address_array[0] = $customer_shipping_address_broken[0];
				$customer_shipping_address_array[1] = $customer_shipping_address_broken[1];
				$customer_shipping_address_array[2] = $customer_shipping_address_broken[2];
				$customer_shipping_address_array[3] = $customer_shipping_address_broken[3];
				$customer_shipping_address_array[4] = "";
			}
			else if($hash_count == 2)
			{
				$customer_shipping_address_array[0] = $customer_shipping_address_broken[0];
				$customer_shipping_address_array[1] = $customer_shipping_address_broken[1];
				$customer_shipping_address_array[2] = $customer_shipping_address_broken[2];
				$customer_shipping_address_array[3] = "";
				$customer_shipping_address_array[4] = "";
			}
			else if($hash_count == 1)
			{
				$customer_shipping_address_array[0] = $customer_shipping_address_broken[0];
				$customer_shipping_address_array[1] = $customer_shipping_address_broken[1];
				$customer_shipping_address_array[2] = "";
				$customer_shipping_address_array[3] = "";
				$customer_shipping_address_array[4] = "";
			}
			else if($hash_count == 0)
			{
				$customer_shipping_address_array[0] = $customer_shipping_address_broken[0];
				$customer_shipping_address_array[1] = "";
				$customer_shipping_address_array[2] = "";
				$customer_shipping_address_array[3] = "";
				$customer_shipping_address_array[4] = "";
			}
			else
			{
				$customer_shipping_address_array[0] = $customer_address_broken[0];
				$customer_shipping_address_array[1] = "";
				$customer_shipping_address_array[2] = "";
				$customer_shipping_address_array[3] = "";
				$customer_shipping_address_array[4] = "";
			}
	}
	else
	{
		die('Something went wrong while generating quotation pdf');
	}
	
//generating pdf
	require('fpdf181/fpdf.php');
	//A4 width: 219mm
	//default margin: 10mm each side
	//writable horizontal: 219 - (10*2) = 189 mm
		$pdf  = new FPDF('p', 'mm', 'A4');
		$pdf -> AddPage();
			
	//heading
		$pdf->SetTextColor(204,0,0); //text color //red		
		$pdf->SetFont('Arial', 'B', 16); //font
		$pdf->Cell(110, 6, $quotation_type_text, 0, 0, 'R');

		$pdf->SetTextColor(0,0,0); //text color //black		
		$pdf->SetFont('Arial', '', 8); //font
		$pdf->Cell(79, 6, '', 0, 1, 'R'); //end of line

	//first line(logo and quotation text)
		$pdf->SetTextColor(0,0,0); //text color //black	
		
		$image1 = "../img/logo.jpg";
		$pdf->Cell(120, 25, $pdf->Image($image1, 15, 15, 35, 25), 0, 0, 'L', false );
		
		$pdf->SetFont('Arial', 'B', 11); //font
		$pdf->Cell(69, 4,  $branch_company_name, 0, 1); //end of line

		$pdf->SetFont('Arial', '', 10); //font
		$pdf->Cell(120, 4, '', 0, 0);
		$pdf->Cell(69, 4, 'GSTN: ' . $branch_gst_number, 0, 1);

		$pdf->Cell(120, 4, '', 0, 0);
		$pdf->Cell(69, 4, 'Date: ' . $date_of_generation , 0, 1);

		if($service_id_org != "")
		{
			$pdf->Cell(120, 4, '', 0, 0);
			$pdf->Cell(69, 4, 'Service ID: ' . $service_id_org , 0, 1);
		}
		else
		{
			$pdf->Cell(120, 4, '', 0, 0);
			$pdf->Cell(69, 4, '', 0, 1);
		}

		if($quotation_type == "performa" && $purchase_order_org != "")
		{
			$pdf->Cell(120, 4, '', 0, 0);
			$pdf->Cell(69, 4, 'Customer PO: ' . $purchase_order_org , 0, 1);
		}
		else
		{
			$pdf->Cell(120, 4, '', 0, 0);
			$pdf->Cell(69, 4, '', 0, 1);
		}

		$pdf->Cell(120, 4, '', 0, 0);
		$pdf->Cell(69, 4, "Quotation NO: " . $quotation_code, 0, 1);

	//black space and line
		$pdf -> Line(0, 42, 219, 42);
		$pdf -> Line(0, 45, 219, 45);
		$pdf->Cell(189, 10, '', 0, 1); //end of line

	//quotation number and date line
		// $pdf->SetFont('Arial', '', 12); //font
		// $pdf->SetTextColor(0,0,0); //text color //black
		// $pdf->Cell(20, 7, 'Date: ', 0, 0);
		
		// $pdf->SetTextColor(204,0,0); //text color //red
		// $pdf->Cell(35, 7, $date_of_generation, 0, 0);

		// $pdf->SetFont('Arial', '', 11); //font
		// $pdf->SetTextColor(0,0,0); //text color //black
		// $pdf->Cell(67, 7, '', 0, 0);

		// $pdf->SetFont('Arial', '', 12); //font
		// $pdf->SetTextColor(0,0,0); //text color //black
		// $pdf->Cell(35, 7, 'Quotation No: ', 0, 0);

		// $pdf->SetTextColor(204,0,0); //text color //red
		// $pdf->Cell(39, 7, $quotation_code, 0, 1);//end of line

		// $pdf->Cell(189, 1, '', 0, 1); //end of line

	//second line (address)
		$pdf->SetTextColor(0,0,0); //text color //black

		$pdf->SetFont('Arial', 'B', 12); //font
		$pdf->Cell(120, 5, 'Billing To', 0, 0);
		
		$pdf->Cell(30, 5, 'Shipping To', 0, 1);

	//third line (address)
		$pdf->SetFont('Arial', '', 10); //font
		$pdf->SetTextColor(50, 50, 50); //text color //grey
		
		$pdf->Cell(120, 4, $customer_name, 0, 0);
		$pdf->Cell(69, 4, $customer_name , 0, 1);

	//4th line (address)
		$pdf->Cell(120, 4, $customer_company_name, 0, 0);
		$pdf->Cell(69, 4, $customer_company_name, 0, 1); //end of line

	//4th line (address)
		$pdf->Cell(120, 4, $customer_address_array[0], 0, 0);
		$pdf->Cell(69, 4, $customer_shipping_address_array[0], 0, 1); //end of line

	//4th line (address)
		$pdf->Cell(120, 4, $customer_address_array[1], 0, 0);
		$pdf->Cell(69, 4, $customer_shipping_address_array[1], 0, 1); //end of line

	//4th line (address)
		$pdf->Cell(120, 4, $customer_address_array[2], 0, 0);
		$pdf->Cell(69, 4, $customer_shipping_address_array[2], 0, 1); //end of line

	//4th line (address)
		$pdf->Cell(120, 4, $customer_address_array[3] , 0, 0);
		$pdf->Cell(69, 4, $customer_shipping_address_array[3], 0, 1); //end of line

	// //4th line (address)
	// 	$pdf->Cell(120, 4, $customer_address_array[4] , 0, 0);
	// 	$pdf->Cell(69, 4, $customer_shipping_address_array[4], 0, 1); //end of line

	//5th line
		$pdf->Cell(120, 4, "GST Number: " . $customer_gst, 0, 0);
		$pdf->Cell(69, 4,"Ph: " .  $customer_mobile, 0, 1); //end of line

	//6th line
		$pdf->Cell(120, 4,"", 0, 0);
		$pdf->Cell(69, 4,"Email: " . $customer_email, 0, 1); //end of line

	//leaving blank space
		$pdf->Cell(189, 3, '', 0, 1);

	//13th line (Purchased Item heading)
		$pdf->SetFont('Arial', 'B', 10); //font
		$pdf->SetTextColor(0,0,0); //text color //black

		$pdf->Cell(8, 5, 'SL', 'LRT', 0, 'C');
		$pdf->Cell(80, 5, 'Item', 'LRT', 0, 'C');
		
		$pdf->Cell(16, 5, 'HSN', 'LRT', 0, 'C');
		$pdf->Cell(8, 5, 'Qty','LRT', 0, 'C');
		$pdf->Cell(12, 5, 'Unit', 'LRT', 0, 'C');
		// $pdf->Cell(12, 5, 'Dscnt', 'LRT', 0, 'C');
		// $pdf->Cell(13, 5, 'Net', 'LRT', 0, 'C');

		$pdf->Cell(11, 5, 'TAX', 'LRT', 0, 'C');
		$pdf->Cell(11, 5, 'TAX', 'LRT',  0, 'C');
		$pdf->Cell(16, 5, 'TAX', 'LRT', 0, 'C');
		$pdf->Cell(17, 5, 'Total', 'LRT', 1, 'C');
		
		$pdf->Cell(8, 5, '','LRB', 0, 'C');
		$pdf->Cell(80, 5, '','LRB', 0, 'C');

		$pdf->Cell(16, 5, 'Code','LRB', 0, 'C');
		$pdf->Cell(8, 5, '','LRB', 0, 'C');
		$pdf->Cell(12, 5, 'Price','LRB', 0, 'C');
		// $pdf->Cell(12, 5, '%', 'LRB', 0, 'C');
		// $pdf->Cell(13, 5, 'Price','LRB', 0, 'C');

		$pdf->Cell(11, 5, 'Rate','LRB', 0, 'C');
		$pdf->Cell(11, 5, 'Type', 'LRB', 0, 'C');
		$pdf->Cell(16, 5, 'Amount', 'LRB', 0, 'C');
		$pdf->Cell(17, 5, 'Amount', 'LRB', 1, 'C');

	//get item infos
		$total_amount = 0;

		$get_item_info_query_run = mysqli_query($connect_link, $query);

		while($get_item_info_assoc = mysqli_fetch_assoc($get_item_info_query_run))
		{
			$item_serial = $get_item_info_assoc['serial'];

			$item_brand = $get_item_info_assoc['brand'];
			$item_model_name = $get_item_info_assoc['model_name'];
			$item_model_number = $get_item_info_assoc['model_number'];
			$item_description = $get_item_info_assoc['description'];

			//breaking description in two lines
				$item_description_1 = substr($item_description, 0,39);
				$item_description_2 = substr($item_description, 39,43);
				$item_description_3 = substr($item_description, 82,43);

			$item_hsn_code = $get_item_info_assoc['hsn_code'];
			$type = $get_item_info_assoc['type'];

			$item_serial_number = $get_item_info_assoc['serial_num'];
			$purchase_order = $get_item_info_assoc['purchase_order'];
			$item_hsn_code = $get_item_info_assoc['hsn_code'];

			$item_quantity = $get_item_info_assoc['quantity'];
			$item_rate = round($get_item_info_assoc['rate'], 2);

			$net_price = $item_quantity*$item_rate;

			$item_cgst = $get_item_info_assoc['cgst'];
			$item_sgst = $get_item_info_assoc['sgst'];
			$item_igst = $get_item_info_assoc['igst'];

			$cgst_amount = ($item_rate*$item_quantity)*$item_cgst/100;
			$sgst_amount = ($item_rate*$item_quantity)*$item_sgst/100;
			$igst_amount = ($item_rate*$item_quantity)*$item_igst/100;

			$item_total_price = round($get_item_info_assoc['total_price'],2);
			$total_amount = $total_amount + $item_total_price;

		//generating pdf of the item
			$pdf->SetFont('Arial', '', 10); //font
			$pdf->SetTextColor(30, 30, 30); //text color //black

		//item line
				$pdf->Cell(8, 5, $item_serial, 'LR', 0, 'C');
				$pdf->Cell(80, 5, $item_brand, 0, 0);
				
				$pdf->Cell(16, 5, $item_hsn_code ,  'L', 0,'C');
				$pdf->Cell(8, 5, $item_quantity ,  'L', 0,'C');
				$pdf->Cell(12, 5, $item_rate ,  'L', 0,'C');
				$pdf->Cell(11, 5, $item_cgst . "%" ,  'L', 0,'C');
				$pdf->Cell(11, 5, 'CGST' ,  'L', 0,'C');
				$pdf->Cell(16, 5, $cgst_amount, 'L', 0, 'C');
				$pdf->Cell(17, 5, '', 'LR', 1,'C'); //end of line

			//type line
				$pdf->Cell(8, 5, '', 'LR', 0, 'C');
				$pdf->Cell(80, 5, $item_model_name . ' ' . $item_model_number, 0, 0);
				
				$pdf->Cell(16, 5, '' ,  'L', 0,'C');
				$pdf->Cell(8, 5, '' ,  'L', 0,'C');
				$pdf->Cell(12, 5, '' ,  'L', 0,'C');
				$pdf->Cell(11, 5, '' ,  'L', 0,'C');
				$pdf->Cell(11, 5, '' ,  'L', 0,'C');
				$pdf->Cell(16, 5, '', 'L', 0, 'C');
				$pdf->Cell(17, 5, '', 'LR', 1,'C'); //end of line

			//serial line
				$pdf->Cell(8, 5, '', 'LR', 0, 'C');
				$pdf->Cell(80, 5, 'SL: ' . $item_serial_number, 0, 0);
				
				$pdf->Cell(16, 5, '' ,  'L', 0,'C');
				$pdf->Cell(8, 5, '' ,  'L', 0,'C');
				$pdf->Cell(12, 5, '' ,  'L', 0,'C');
				$pdf->Cell(11, 5, $item_sgst . "%" , 'L', 0,'C');
				$pdf->Cell(11, 5, 'SGST' ,  'L', 0,'C');
				$pdf->Cell(16, 5, $sgst_amount, 'L', 0, 'C');
				$pdf->Cell(17, 5, '', 'LR', 1,'C'); //end of line

			//hsn line
				$pdf->Cell(8, 5, '', 'LR', 0, 'C');
				$pdf->Cell(80, 5, 'Desc: ' . $item_description_1, 0, 0);
				
				$pdf->Cell(16, 5, '' ,  'L', 0,'C');
				$pdf->Cell(8, 5, '' ,  'L', 0,'C');
				$pdf->Cell(12, 5, '' ,  'L', 0,'C');
				$pdf->Cell(11, 5, '' ,  'L', 0,'C');
				$pdf->Cell(11, 5, '' ,  'L', 0,'C');
				$pdf->Cell(16, 5, '', 'L', 0, 'C');
				$pdf->Cell(17, 5, '', 'LR', 1,'C'); //end of line

			//desc line
				$pdf->Cell(8, 5, '', 'LR', 0, 'C');
				$pdf->Cell(80, 5, $item_description_2, 0, 0);
				
				$pdf->Cell(16, 5, '' ,  'L', 0,'C');
				$pdf->Cell(8, 5, '' ,  'L', 0,'C');
				$pdf->Cell(12, 5, '' ,  'L', 0,'C');
				$pdf->Cell(11, 5, $item_igst . "%" , 'L', 0,'C');
				$pdf->Cell(11, 5, 'IGST' ,  'L', 0,'C');
				$pdf->Cell(16, 5, $igst_amount, 'L', 0, 'C');
				$pdf->Cell(17, 5, '', 'LR', 1,'C'); //end of line

			//total amount line
				$pdf->Cell(8, 5, '', 'LB', 0, 'C');
				$pdf->Cell(80, 5, $item_description_3, 'LB', 0);
				
				$pdf->Cell(16, 5, '' ,  'LB', 0,'C');
				$pdf->Cell(8, 5, '' ,  'LB', 0,'C');
				$pdf->Cell(12, 5, '' ,  'LB', 0,'C');
				$pdf->Cell(11, 5, '' ,  'LB', 0,'C');
				$pdf->Cell(11, 5, '' ,  'LB', 0,'C');
				$pdf->Cell(16, 5, '', 'LB', 0, 'C');

				$pdf->SetFont('Arial', 'B', 10); //font
				$pdf->Cell(17, 5, $item_total_price, 'LRB', 1,'C'); //end of line
		}

	//Totaling up line (Purchased Item heading)
		$number = $total_amount; //our number is total amount

		$no = round($number);
		$point = round($number - $no, 2) * 100;
		$hundred = null;
		$digits_1 = strlen($no);
		$i = 0;
		$str = array();
		$words = array('0' => '', '1' => 'One', '2' => 'Two',
		'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
		'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
		'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
		'13' => 'Thirteen', '14' => 'Fourteen',
		'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
		'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
		'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
		'60' => 'Sixty', '70' => 'Seventy',
		'80' => 'Eighty', '90' => 'Ninety');

		$digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');

		while ($i < $digits_1) 
		{
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += ($divider == 10) ? 1 : 2;
			if ($number) 
			{
				$plural = (($counter = count($str)) && $number > 9) ? '' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str [] = ($number < 21) ? $words[$number] .
				    " " . $digits[$counter] . $plural . " " . $hundred
				    :
				    $words[floor($number / 10) * 10]
				    . " " . $words[$number % 10] . " "
				    . $digits[$counter] . $plural . " " . $hundred;
			} else $str[] = null;
		}
		$str = array_reverse($str);
		$result = implode('', $str);

		// $points = ($point) ?
		// "." . $words[$point / 10] . " " . 
		//   $words[$point = $point % 10] : '';

		$in_words = "Rupees " . $result . "Only";

		$pdf->SetFont('Arial', '', 11); //font
		$pdf->Cell(129, 6, $in_words, 1, 0);

		$pdf->SetFont('Arial', 'B', 12); //font
		$pdf->SetTextColor(0, 0, 0); //text color //black
		$pdf->Cell(30, 6, 'Total Amount', 1, 0);
		$pdf->Cell(20, 6, $total_amount, 1, 1); //end of line

	//leaving blank space
		$pdf->Cell(200, 3, '', 0, 1);

	//branch bank details
		$pdf->SetFont('Arial', 'B', 11); //font
		$pdf->Cell(189, 5, 'Bank Details:', 0, 1);

		$pdf->SetFont('Arial', '', 10); //font
		$pdf->Cell(189, 5, 'A/C Name: ' . $bank_accnt_name , 0,1);
		$pdf->Cell(189, 5, 'A/C Number: ' . $bank_accnt_no , 0,1);
		$pdf->Cell(189, 5, 'Bank Name: ' . $bank_name , 0,1);
		$pdf->Cell(150, 5, 'IFS Code: ' . $bank_ifsc , 0,0);

		$pdf->Cell(39, 5, 'Authorized Signatory', 0,1);

//another page for terms and conditions and branch details
	//adding new page
		$pdf -> AddPage();

	//terms and conditions
		$pdf->SetTextColor(0, 0, 0); //text color //black
		$pdf->SetFont('Arial', 'B', 11); //font
		$pdf->Cell(189, 5, 'Terms & Conditions:', 0, 1);

	//leaving blank space
		$pdf->Cell(200, 5, '', 0, 1);

	//branch details
		$pdf->SetFont('Arial', 'B', 11); //font
		$pdf->Cell(189, 5, 'Address:', 0, 1);

		$pdf->SetFont('Arial', '', 10); //font
		$pdf->Cell(189, 5, $branch_address_array[0], 0,1);

		if($branch_address_array[1] != "")
		{
			$pdf->Cell(189, 5, $branch_address_array[1], 0,1);
		}

		if($branch_address_array[2] != "")
		{
			$pdf->Cell(189, 5, $branch_address_array[2], 0,1);
		}

		if($branch_address_array[3] != "")
		{
			$pdf->Cell(189, 5, $branch_address_array[3], 0,1);
		}

		if($branch_address_array[4] != "")
		{
			$pdf->Cell(189, 5, $branch_address_array[4], 0,1);
		}
		
		$pdf->SetFont('Arial', '', 8); //font
		$pdf->Cell(50, 5, 'Branch Name: ' . $branch_name , 0,0);
		$pdf->Cell(100, 5, 'Created By: ' . $creator_username , 0,1);

//getting output of the pdf in a file if mailing is to be done
	$pdf->Output();
	
//mailing to the customer if it is a normal quotation
	if($quotation_type == "normal")
	{
		$filename = "../quotation/Quotation-" . $quotation_num . ".pdf";
		$pdf->Output($filename, 'F');	

	//checking to mail to customer or not
		$session_name = "mail_pdf_of_" . $quotation_num;

		if(isset($_SESSION[$session_name]))
		{
		//mailing to the customer
			$website = $_SERVER['HTTP_HOST'];
			$mail_email = $customer_email;

				if($website == "localhost" OR $website == "volta.pnds.in" OR $website == "erp.voltatech.in")
				{
					$mail_subject = "Quotation from Voltatech";
					$headers = "From: voltatech@voltatech.in";
					
					$mainMessage = "Dear Customer Quotation generated from our online resource is attached with this mail. Please find your attached Quotation pdf file. \n \nRegards \nVoltatech \nhttp://" . $website;
				}
				else if($website == "oxy.pnds.in")
				{
					$mail_subject = "Quotation from OxyVin";
					$headers = "From: oxyvin@pnds.in";
					
					$mainMessage = "Dear Customer Quotation generated from our online resource is attached with this mail. Please find your attached invoice pdf file. \n \nRegards \nOxyVin \nhttp://" . $website;
				}
				else
				{
					$mail_subject = "Quotation from Voltatech";
					$headers = "From: voltatech@pnds.in";
					
					$mainMessage = "Dear Customer Quotation generated from our online resource is attached with this mail. Please find your attached Quotation pdf file. \n \nRegards \nVoltatech \nhttp://" . $website;
				}
			
			  $fileatt     = "http://" . $website . "/quotation/Quotation-" . $quotation_num . ".pdf"; //file location
			  $fileatttype = "application/pdf";
			  $fileattname = "Quotation-" . $quotation_num . ".pdf"; //name that you want to use to send or you can use the same name
			 
			  // File
			  $file = fopen($fileatt, 'rb');
			  $data = fread($file, 10000);
			  fclose($file);

			  // This attaches the file
			  $semi_rand     = md5(time());
			  $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
			  $headers      .= "\nMIME-Version: 1.0\n" .
			    "Content-Type: multipart/mixed;\n" .
			    " boundary=\"{$mime_boundary}\"";
			    $message = "This is a multi-part message in MIME format.\n\n" .
			    "--{$mime_boundary}\n" .
			    "Content-Type: text/plain; charset=\"iso-8859-1\n" .
			    "Content-Transfer-Encoding: 7bit\n\n" .
			    $mainMessage  . "\n\n";

			  $data = chunk_split(base64_encode($data));
			  $message .= "--{$mime_boundary}\n" .
			    "Content-Type: {$fileatttype};\n" .
			    " name=\"{$fileattname}\"\n" .
			    "Content-Disposition: attachment;\n" .
			    " filename=\"{$fileattname}\"\n" .
			    "Content-Transfer-Encoding: base64\n\n" .
			  $data . "\n\n" .
			   "--{$mime_boundary}--\n";

			if(@mail($mail_email, $mail_subject, $message, $headers))
			{
				//echo 1;
			}
			else 
			{
				//echo 0;
			}
		}

	}

//destroying the mailing session
	if(isset($_SESSION[$session_name]))
	{
		unset($_SESSION[$session_name]);
	}

//destroying the quotation type session
	if(isset($_SESSION['quotation_type']))
	{
		unset($_SESSION['quotation_type']);
	}
?>