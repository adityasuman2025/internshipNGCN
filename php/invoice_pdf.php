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
			$customer_gst = $get_customer_info_assoc['gst'];
			$customer_shipping_address = $get_customer_info_assoc['shipping_address'];

		//checking to mail to customer or not
			$session_name = "mail_pdf_of_" . $quotation_num;

			if(isset($_SESSION[$session_name]))
			{
			//mailing to the customer
				$website = $_SERVER['HTTP_HOST'];

				$mail_email = $customer_email;
				$mail_subject = "Invoice from Voltatech";
				$mail_header = "From: voltatech@pnds.in";
				$mail_body = "Dear Customer \Invoice generated from our online resource is linked with this mail. Please find your invoice by following the link: http://" . $website . "/invoice/Invoice-" . $quotation_num . ".pdf \n \nRegards \nVoltatech \nhttp://" . $website;

				if(@mail($mail_email, $mail_subject, $mail_body, $mail_header))
				{
					//echo 1;
				}
				else 
				{
					//echo 0;
				}
			}

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
		
	//cell(width, height, text, border, endline, [align])
		
	//first line(logo and quotation text)
		$pdf->SetFont('Arial', '', 12); //font

		$image1 = "../img/logo.jpg";
		$pdf->Cell( 120, 25, $pdf->Image($image1, 10, 10, 35, 25), 0, 0, 'L', false );
		//$pdf->Image("../img/logo.jpg", 10, 10, 40, 30, "JPG");

		$pdf->SetFont('Arial', 'B', 11); //font
		$pdf->Cell(69, 4,  $branch_company_name, 0, 1); //end of line

		$pdf->SetFont('Arial', '', 10); //font
		$pdf->Cell(120, 4, '', 0, 0);
		$pdf->Cell(69, 4, $branch_address_array[0], 0, 1);

		$pdf->Cell(120, 4, '', 0, 0);
		$pdf->Cell(69, 4, $branch_address_array[1], 0, 1);

		$pdf->Cell(120, 4, '', 0, 0);
		$pdf->Cell(69, 4, $branch_address_array[2], 0, 1);

		$pdf->Cell(120, 4, '', 0, 0);
		$pdf->Cell(69, 4, $branch_address_array[3], 0, 1);

		$pdf->Cell(120, 4, '', 0, 0);
		$pdf->Cell(69, 4, $branch_address_array[4], 0, 1);

		$pdf->Cell(120, 4, '', 0, 0);
		$pdf->Cell(69, 4, "Ph: " . $branch_phone_number, 0, 1);

		$pdf->Cell(120, 4, '', 0, 0);
		$pdf->Cell(69, 4,"Email: " . $branch_email, 0, 1);

		$pdf->Cell(120, 4, '', 0, 0);
		$pdf->Cell(69, 4,"GST Number: ". $branch_gst_number, 0, 1);

	//black space and line
		$pdf -> Line(0, 47, 70, 47);
		$pdf -> Line(109, 47, 219, 47);

		$pdf->Cell(189, 1, '', 0, 1);

	//quotation number and date line
		$pdf->SetFont('Arial', '', 12); //font
		$pdf->SetTextColor(0,0,0); //text color //black
		$pdf->Cell(20, 6, 'Date: ', 0, 0);
		
		$pdf->SetTextColor(204,0,0); //text color //red
		$pdf->Cell(44, 6, $date_of_generation, 0, 0);

		$pdf->SetFont('Arial', 'B', 16); //font
		$pdf->Cell(55, 6, 'Invoice', 0, 0);

		$pdf->SetFont('Arial', '', 12); //font
		$pdf->SetTextColor(0,0,0); //text color //black
		$pdf->Cell(35, 6, 'Quotation No: ', 0, 0);

		$pdf->SetTextColor(204,0,0); //text color //red
		$pdf->Cell(39, 6, $quotation_code, 0, 1);//end of line

	//black space and line
		$pdf -> Line(0, 53, 70, 53);
		$pdf -> Line(109, 53, 219, 53);

		$pdf->Cell(189, 3, '', 0, 1);

	//second line (address)
		$pdf->SetTextColor(0,0,0); //text color //black

		$pdf->SetFont('Arial', 'B', 12); //font
		$pdf->Cell(120, 4, 'Billing To', 0, 0);
		
		$pdf->Cell(30, 4, 'Shipping To', 0, 1);

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

	//4th line (address)
		$pdf->Cell(120, 4, $customer_address_array[4] , 0, 0);
		$pdf->Cell(69, 4, $customer_shipping_address_array[4], 0, 1); //end of line

	//5th line
		$pdf->Cell(120, 4, "GST Number: " . $customer_gst, 0, 0);
		$pdf->Cell(69, 4,"Ph: " .  $customer_mobile, 0, 1); //end of line

	//6th line
		$pdf->Cell(120, 4,"", 0, 0);
		$pdf->Cell(69, 4,"Email: " . $customer_email, 0, 1); //end of line

	//leaving blank space
		$pdf->Cell(189, 3, '', 0, 1);

	//13th line (Purchased Item heading)
		$pdf->SetFont('Arial', 'B', 11); //font
		$pdf->SetTextColor(0,0,0); //text color //black

		$pdf->Cell(11, 5, 'S. No', 1, 0, 'C');
		$pdf->Cell(62, 5, 'ITEM', 1, 0, 'C');

		$pdf->Cell(13, 5, 'Type', 1, 0, 'C');
		$pdf->Cell(11, 5, 'HSN', 1, 0, 'C');	
		$pdf->Cell(8, 5, 'Qty', 1, 0, 'C');
		$pdf->Cell(13, 5, 'Rate', 1, 0, 'C');
		$pdf->Cell(18, 5, 'Discount', 1, 0, 'C');
		$pdf->Cell(13, 5, 'CGST', 1, 0, 'C');
		$pdf->Cell(13, 5, 'SGST', 1, 0, 'C');
		$pdf->Cell(13, 5, 'IGST', 1, 0, 'C');
		$pdf->Cell(15, 5, 'Price', 1, 1, 'C'); //end of line

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
			$item_hsn_code = $get_item_info_assoc['hsn_code'];
			$type = $get_item_info_assoc['type'];

			$item_serial_number = $get_item_info_assoc['serial_num'];
			$purchase_order = $get_item_info_assoc['purchase_order'];
			$item_hsn_code = $get_item_info_assoc['hsn_code'];

			$item_quantity = $get_item_info_assoc['quantity'];
			$item_rate = round($get_item_info_assoc['rate'], 2);
			$item_discount = $get_item_info_assoc['discount'];

			$discount_amount = $item_discount*$item_quantity*$item_rate/100;

			$item_cgst = $get_item_info_assoc['cgst'];
			$item_sgst = $get_item_info_assoc['sgst'];
			$item_igst = $get_item_info_assoc['igst'];

			$item_total_price = round($get_item_info_assoc['total_price'],2);
			$total_amount = $total_amount + $item_total_price;

		//generating pdf of the item
			$pdf->SetFont('Arial', '', 10); //font
			$pdf->SetTextColor(30, 30, 30); //text color //black

			$pdf->Cell(11, 5, $item_serial, 'LR', 0, 'C');

			$pdf->Cell(62, 5, $item_brand . ' ' . $item_model_name . ' ' . $item_model_number. "(Serial: " . $item_serial_number . ")", 0, 0);
			
			$pdf->Cell(13, 5, $type ,'L', 0,'C');
			$pdf->Cell(11, 5, $item_hsn_code ,'L', 0,'C');	
			$pdf->Cell(8, 5, $item_quantity , 'L', 0,'C');
			$pdf->Cell(13, 5, $item_rate ,  'L', 0,'C');
			$pdf->Cell(18, 5, $item_discount*$item_quantity*$item_rate/100 ,  'L', 0,'C');
			$pdf->Cell(13, 5, ($item_quantity*$item_rate - $discount_amount)*$item_cgst/100,  'L', 0,'C');
			$pdf->Cell(13, 5, ($item_quantity*$item_rate - $discount_amount)*$item_sgst/100, 'L', 0,'C');
			$pdf->Cell(13, 5, ($item_quantity*$item_rate - $discount_amount)*$item_igst/100, 'L', 0,'C');
			$pdf->Cell(15, 5, $item_total_price, 'LR', 1,'C'); //end of line

		//description area
			$pdf->Cell(11, 5, '', 'LRB', 0);
			$pdf->SetFont('Arial', 'B', 10); //font
			$pdf->Cell(62, 5, substr("DESC: " . $item_description, 0, 34), 'B', 0);

			$pdf->Cell(13, 5, '' ,'LB', 0);	
			$pdf->Cell(11, 5, '' , 'LB', 0);
			$pdf->Cell(8, 5, '' , 'LB', 0);
			$pdf->Cell(13, 5, '' , 'LB', 0);
			$pdf->Cell(18, 5, '' ,  'LB', 0);
			$pdf->Cell(13, 5, '',  'LB', 0);
			$pdf->Cell(13, 5, '', 'LB', 0);
			$pdf->Cell(13, 5, '', 'LB', 0);
			$pdf->Cell(15, 5, '', 'LRB', 1); //end of line
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
		$pdf->Cell(135, 7, $in_words, 1, 0);

		$pdf->SetFont('Arial', 'B', 12); //font
		$pdf->SetTextColor(0, 0, 0); //text color //black
		$pdf->Cell(30, 7, 'Total Amount', 1, 0);
		$pdf->Cell(25, 7, $total_amount, 1, 1); //end of line

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
	
	$filename = "../invoice/Invoice-" . $quotation_num . ".pdf";
	$pdf->Output($filename, 'F');	

?>