<?php
	//fetching data from database
		include('connect_db.php');

		session_start();
		$quotation_num = $_SESSION['pdf_invoice_of'];

	//generating quotation code
		$this_year = date('y');
		$next_year = $this_year +1;

		$quotation_code = "VOLTA/" . $this_year . "-" . $next_year . "/" . $quotation_num;

	//query
		$query = "SELECT * FROM quotation WHERE quotation_num = '$quotation_num' AND payment_method !='' ORDER BY serial";
		$query_run = mysqli_query($connect_link, $query);

		if($query_assoc = mysqli_fetch_assoc($query_run))
		{
			$creator_username = $query_assoc['creator_username'];
			$creator_branch_code = $query_assoc['creator_branch_code'];
			$customer_name = $query_assoc['customer'];
			$type = $query_assoc['type'];

			$payment_method = $query_assoc['payment_method'];
			$date_of_payment = $query_assoc['date_of_payment'];
		
		//gettting date of payemnt of invoice
			$date_of_payment = str_replace('/', '-', $date_of_payment);
			$date_of_payment = date('d M Y', strtotime($date_of_payment));

		//gettting date of generation of quoatation
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
			$branch_bank = $get_branch_info_assoc['bank'];

		//getting customer info
			$get_customer_info_query = "SELECT * FROM customers WHERE name = '$customer_name'";
			$get_customer_info_query_run = mysqli_query($connect_link, $get_customer_info_query);
			$get_customer_info_assoc = mysqli_fetch_assoc($get_customer_info_query_run);

			$customer_company_name = $get_customer_info_assoc['company_name'];
			$customer_address = $get_customer_info_assoc['address'];
			$customer_mobile = $get_customer_info_assoc['mobile'];
			$customer_email = $get_customer_info_assoc['email'];;
			$customer_shipping_address = $get_customer_info_assoc['shipping_address'];;
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
		
	//cell(width, height, text, border, endline, [align])
		
	//first line(logo and quoatation text)
		$pdf->SetFont('Arial', '', 12); //font

		$image1 = "../img/logo.jpg";
		$pdf->Cell( 120, 25, $pdf->Image($image1, 10, 10, 35, 25), 0, 0, 'L', false );
		//$pdf->Image("../img/logo.jpg", 10, 10, 40, 30, "JPG");

		$pdf->SetFont('Arial', 'B', 20); //font
		$pdf->SetTextColor(204,0,0); //text color //red
		$pdf->Cell(49, 25, 'Invoice', 0, 1); //end of line

	//black space and line
		$pdf -> Line(0, 35, 219, 35);

		$pdf->Cell(189, 1, '', 0, 1);

	//quotation number and date line
		$pdf->SetFont('Arial', '', 13); //font
		$pdf->SetTextColor(0,0,0); //text color //black
		$pdf->Cell(20, 5, 'Date: ', 0, 0);
		
		$pdf->SetTextColor(204,0,0); //text color //red
		$pdf->Cell(39, 5, $date_of_generation, 0, 0);

		$pdf->Cell(60, 5, '', 0, 0);//end of line

		$pdf->SetTextColor(0,0,0); //text color //black
		$pdf->Cell(35, 5, 'Quotation No: ', 0, 0);

		$pdf->SetTextColor(204,0,0); //text color //red
		$pdf->Cell(39, 5, $quotation_code, 0, 1);//end of line

	//black space and line
		$pdf -> Line(0, 42, 219, 42);

		$pdf->Cell(189, 3, '', 0, 1);

	//second line (address)
		$pdf->SetTextColor(0,0,0); //text color //black

		$pdf->SetFont('Arial', 'B', 16); //font
		$pdf->Cell(120, 8, 'Billing From', 0, 0);
		
		$pdf->Cell(30, 8, 'Billing To', 0, 1);

	//third line (address)
		$pdf->SetFont('Arial', '', 12); //font
		$pdf->SetTextColor(50, 50, 50); //text color //grey
		
		$pdf->Cell(120, 5, $branch_company_name, 0, 0);
		$pdf->Cell(69, 5, $customer_name . ' (' . $customer_company_name . ')' , 0, 1);

	//4th line (address)
		$pdf->Cell(120, 5, substr($branch_address, 0, 50), 0, 0);
		$pdf->Cell(69, 5, substr($customer_address, 0, 30), 0, 1); //end of line

	//4th line (address)
		$pdf->Cell(120, 5, substr($branch_address, 50, 500), 0, 0);
		$pdf->Cell(69, 5, substr($customer_address, 30, 300), 0, 1); //end of line

	//5th line
		$pdf->Cell(120, 5, $branch_phone_number, 0, 0);
		$pdf->Cell(69, 5, $customer_mobile, 0, 1); //end of line

	//6th line
		$pdf->Cell(120, 5, $branch_email, 0, 0);
		$pdf->Cell(69, 5, $customer_email, 0, 1); //end of line

	//(gst of branch) 7th line 
		$pdf->Cell(120, 5, "GST Number: ". $branch_gst_number, 0, 0);
		$pdf->Cell(69, 5, '', 0, 1); //end of line

	//leaving blank space
		$pdf->Cell(189, 3, '', 0, 1);

	if($type == 'service')
		{
			$get_item_info_query_run = mysqli_query($connect_link, $query);

			$get_item_info_assoc = mysqli_fetch_assoc($get_item_info_query_run);
			$item_brand = $get_item_info_assoc['brand'];
			$item_model_name = $get_item_info_assoc['model_name'];
			$item_model_number = $get_item_info_assoc['model_number'];
			$item_serial_number = $get_item_info_assoc['serial_num'];
			$item_service_id = $get_item_info_assoc['service_id'];


		//8th line
			$pdf->SetFont('Arial', 'B', 16); //font
			$pdf->SetTextColor(0,0,0); //text color //black

			//$pdf->Cell(120, 8, '', 0, 0);
			$pdf->Cell(120, 5, 'Quotation Created By', 0, 0);

			$pdf->Cell(69, 8, 'Product Details', 0, 1); //end of line
			$pdf->SetFont('Arial', '', 12); //font

		//9th line
			$pdf->SetTextColor(50, 50, 50); //text color //grey

			//$pdf->Cell(120, 5, '', 0,0);
			$pdf->Cell(20, 5, 'User: ', 0, 0);
			$pdf->Cell(100, 5, $creator_username, 0, 0);

			$pdf->Cell(69, 5, 'Brand: ' . $item_brand , 0, 1); //end of line
		
		//10th line
			//$pdf->Cell(120, 5, '', 0,0);
			$pdf->Cell(20, 5, 'Branch: ', 0, 0);
			$pdf->Cell(100, 5, $creator_branch_code, 0, 0);

			$pdf->Cell(69, 5, 'Model Name: ' . $item_model_name , 0, 1); //end of line

		//11th line
			//$pdf->Cell(10, 5, '', 0, 0);
			$pdf->Cell(120, 5, '', 0,0);
			$pdf->Cell(69, 5, 'Model Number: ' . $item_model_number , 0, 1);

		//12th line
			//$pdf->Cell(10, 5, '', 0, 0);
			$pdf->Cell(120, 5, '', 0,0);
			$pdf->Cell(69, 5, 'Serial Number: ' . $item_serial_number , 0, 1);

		//13th line
			//$pdf->Cell(10, 5, '', 0, 0);
			$pdf->Cell(120, 5, '', 0,0);
			$pdf->Cell(69, 5, 'Service ID: ' . $item_service_id , 0, 1);

		}
		else if($type == 'sales')
		{

		//8th line
			$pdf->SetFont('Arial', 'B', 16); //font
			$pdf->SetTextColor(0,0,0); //text color //black

			//$pdf->Cell(120, 8, '', 0, 0);
			$pdf->Cell(120, 5, 'Quotation Created By', 0, 0);

			$pdf->Cell(69, 8, 'Shipping Address', 0, 1);//end of line

			$pdf->SetFont('Arial', '', 12); //font

		//9th line
			//$pdf->Cell(10, 5, '', 0, 0);
			$pdf->SetTextColor(50, 50, 50); //text color //grey
			$pdf->Cell(20, 5, 'User: ', 0, 0);
			$pdf->Cell(100, 5, $creator_username, 0, 0);

			$pdf->Cell(69, 5, $customer_name, 0, 1);//end of line

		//10th line	
			if($customer_shipping_address == '')
			{
				$pdf->Cell(20, 5, 'Branch: ', 0, 0);
				$pdf->Cell(100, 5, $creator_branch_code, 0, 0);

				$pdf->Cell(69, 5, substr($customer_address, 0, 30), 0, 1); //end of line

				$pdf->Cell(120, 5, '', 0, 0);
				$pdf->Cell(69, 5, substr($customer_address, 30, 300), 0, 1); //end of line
			}
			else
			{
				$pdf->Cell(20, 5, 'Branch: ', 0, 0);
				$pdf->Cell(100, 5, $creator_branch_code, 0, 0);
				
				$pdf->Cell(69, 5, substr($customer_shipping_address, 0, 30), 0, 1); //end of line

				$pdf->Cell(120, 5, '', 0, 0);
				$pdf->Cell(69, 5, substr($customer_shipping_address, 30, 300), 0, 1); //end of line
			}
			
		//11th line
			$pdf->Cell(120, 5, '', 0, 0); 

			$pdf->Cell(69, 5, $customer_mobile, 0, 1);//end of line
		
		//12th line
			$pdf->Cell(120, 5, '', 0, 0); 

			$pdf->Cell(69, 5, $customer_email, 0, 1);//end of line
		}

	//leaving blank space
		$pdf->Cell(200, 7, '', 0, 1);

	//13th line (Purchased Item heading)
		$pdf->SetFont('Arial', 'B', 13); //font
		$pdf->SetTextColor(0,0,0); //text color //black

		$pdf->Cell(8, 7, 'No', 1, 0, 'C');
		$pdf->Cell(82, 7, 'ITEM', 1, 0, 'C');

		$pdf->Cell(13, 7, 'HSN', 1, 0, 'C');	
		$pdf->Cell(13, 7, 'Unit', 1, 0, 'C');
		$pdf->Cell(18, 7, 'Rate', 1, 0, 'C');
		$pdf->Cell(15, 7, 'CGST', 1, 0, 'C');
		$pdf->Cell(15, 7, 'SGST', 1, 0, 'C');
		$pdf->Cell(15, 7, 'IGST', 1, 0, 'C');
		$pdf->Cell(20, 7, 'Price', 1, 1, 'C'); //end of line
		
	//get item infos
		$total_amount = 0;
		
		$get_item_info_query_run = mysqli_query($connect_link, $query);

		while($get_item_info_assoc = mysqli_fetch_assoc($get_item_info_query_run))
		{
			$item_serial = $get_item_info_assoc['serial'];
			$item_brand = $get_item_info_assoc['brand'];
			$item_model_name = $get_item_info_assoc['model_name'];
			$item_model_number = $get_item_info_assoc['model_number'];
			$item_serial_number = $get_item_info_assoc['serial_num'];

			$item_description = $get_item_info_assoc['description'];
			$item_part_name = $get_item_info_assoc['part_name'];
			$item_part_serial_number = $get_item_info_assoc['part_serial_num'];
			$item_hsn_code = $get_item_info_assoc['hsn_code'];

			$item_quantity = $get_item_info_assoc['quantity'];
			$item_rate = round($get_item_info_assoc['rate'], 2);

			$item_cgst = $get_item_info_assoc['cgst'];
			$item_sgst = $get_item_info_assoc['sgst'];
			$item_igst = $get_item_info_assoc['igst'];

			$item_total_price = round($get_item_info_assoc['total_price'],2);
			$total_amount = $total_amount + $item_total_price;

		//generating pdf of the item
			$pdf->SetFont('Arial', '', 11); //font
			$pdf->SetTextColor(30, 30, 30); //text color //black

			$pdf->Cell(8, 5, $item_serial, 'LR', 0, 'C');

			if($type == 'service')
			{
				$pdf->Cell(82, 5, $item_part_name . ' (Serial: ' . $item_part_serial_number . ')' , 0, 0);
			}
			else if ($type == 'sales')
			{
				$pdf->Cell(82, 5, $item_brand . ' ' . $item_model_name . ' ' . $item_model_number . ' ' . $item_part_name . ' (Serial: ' . $item_part_serial_number . ')', 0, 0);
			}
			
			$pdf->Cell(13, 5, $item_hsn_code ,'L', 0,'C');	
			$pdf->Cell(13, 5, $item_quantity , 'L', 0,'C');
			$pdf->Cell(18, 5, $item_rate ,  'L', 0,'C');
			$pdf->Cell(15, 5, $item_cgst*$item_rate/100,  'L', 0,'C');
			$pdf->Cell(15, 5, $item_sgst*$item_rate/100, 'L', 0,'C');
			$pdf->Cell(15, 5, $item_igst*$item_rate/100, 'L', 0,'C');
			$pdf->Cell(20, 5, $item_total_price, 'LR', 1,'C'); //end of line

		//description area
			$pdf->Cell(8, 5, '', 'LRB', 0);
			$pdf->SetFont('Arial', 'B', 11); //font
			$pdf->Cell(82, 5, "DESC: " . $item_description, 'B', 0);

			$pdf->Cell(13, 5, '' ,'LB', 0);	
			$pdf->Cell(13, 5, '' , 'LB', 0);
			$pdf->Cell(18, 5, '' ,  'LB', 0);
			$pdf->Cell(15, 5, '',  'LB', 0);
			$pdf->Cell(15, 5, '', 'LB', 0);
			$pdf->Cell(15, 5, '', 'LB', 0);
			$pdf->Cell(20, 5, '', 'LRB', 1); //end of line
		}

	//Totaling up line (Purchased Item heading)
		$number = $total_amount; //our number is total amount

		$no = round($number);
		$point = round($number - $no, 2) * 100;
		$hundred = null;
		$digits_1 = strlen($no);
		$i = 0;
		$str = array();
		$words = array('0' => '', '1' => 'one', '2' => 'two',
		'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
		'7' => 'seven', '8' => 'eight', '9' => 'nine',
		'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
		'13' => 'thirteen', '14' => 'fourteen',
		'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
		'18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
		'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
		'60' => 'sixty', '70' => 'seventy',
		'80' => 'eighty', '90' => 'ninety');

		$digits = array('', 'hundred', 'thousand', 'lakh', 'crore');

		while ($i < $digits_1) 
		{
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += ($divider == 10) ? 1 : 2;
			if ($number) 
			{
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
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
		$points = ($point) ?
		"." . $words[$point / 10] . " " . 
		  $words[$point = $point % 10] : '';
		$in_words =  $result . "Rupees";


		$pdf->SetTextColor(204,0,0); //text color //red
		$pdf->SetFont('Arial', '', 11); //font
		$pdf->Cell(144, 8, $in_words, 1, 0);

		$pdf->SetFont('Arial', 'B', 12); //font
		$pdf->SetTextColor(0, 0, 0); //text color //black
		$pdf->Cell(30, 8, 'Total Amount', 1, 0);
		$pdf->Cell(25, 8, $total_amount, 1, 1); //end of line

	//leaving blank space
		$pdf->Cell(200, 5, '', 0, 1);

	//branch bank details
		$pdf->SetFont('Arial', 'B', 12); //font
		$pdf->Cell(189, 5, 'Bank Details:', 0, 1);

		$pdf->SetFont('Arial', '', 11); //font
		$pdf->MultiCell(189, 5, $branch_bank, 0,1);

	//Payment method
		$pdf->Cell(189, 7, '', 0, 1);
		$pdf->SetFont('Arial', '', 12); //font
		
		$pdf->SetTextColor(0,0,0); //text color //black
		$pdf->Cell(45, 5, 'Payment Method:', 0, 0);

		$pdf->SetTextColor(204,0,0); //text color //red
		$pdf->SetFont('Arial', '', 11); //font
		$pdf->Cell(45, 5, $payment_method, 0, 1);

		$pdf->SetFont('Arial', '', 12); //font
		$pdf->SetTextColor(0,0,0); //text color //black
		$pdf->Cell(45, 5, 'Payment Date:', 0, 0);

		$pdf->SetTextColor(204,0,0); //text color //red
		$pdf->SetFont('Arial', '', 11); //font
		$pdf->Cell(45, 5, $date_of_payment, 0, 1);

	//another page for terms and conditions
		$pdf -> AddPage();
		$pdf->SetTextColor(0, 0, 0); //text color //black
		$pdf->Cell(189, 5, 'Bank Details:', 0, 1);

	$pdf->Output();
	//$pdf->Output('D','Invoice-' . $quotation_num. '.pdf');	

?>