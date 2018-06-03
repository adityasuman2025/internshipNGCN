<?php
	include 'connect_db.php';
	$creator_branch_code = $_COOKIE['logged_username_branch_code'];

	if(isset($_POST['new_id']))
	{
		$new_id = (int)$_POST['new_id'];
	}
	else
	{
		$new_id ="";
	}

	//$new_serial = $_POST['new_serial'];
?>

<tr id="<?php echo $new_id; ?>">
	<td><input type="text" id="quotation_description"></td>

	<td>
		<select id="quotation_part_name">
			<option value=""></option>					
		</select>
	</td>
	<td><input type="text" id="quotation_part_serial_num"></td>

	<td><input type="number" disabled="disabled" id="quotation_part_in_stock"></td>
	<td><input type="number" value="0" id="quotation_part_quantity"></td>
	<td><input type="number" value="0" id="quotation_part_rate"></td>
	<td><input type="number" value="0" id="quotation_part_cgst"></td>
	<td><input type="number" value="0" id="quotation_part_sgst"></td>
	<td><input type="number" value="0" id="quotation_part_igst"></td>
	<td><input type="text" id="quotation_part_hsn_code"></td>

	<td><input type="button" style="background: #cc0000; color: white; margin: 2px; width: auto;" value="calculate" placeholder="Total Price" id="quotation_part_total_price"></td>
	<td>
		<img class="item_delete_icon" src="img/delete.png">
	</td>

<!------script----------->
	<script type="text/javascript">
		creator_branch_code = "<?php echo $creator_branch_code; ?>";

	//for giving user options to choose part from that model name and model number of the brand
		$.post('php/list_model_part.php', {model_name: model_name, model_number: model_number}, function(data)
		{
			$('tr:last').find('#quotation_part_name').html(data);

		//on choosing a part name
			$('tr:last').find('#quotation_part_name').change(function()
			{
				$('.gen_quotation_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");
					
				$(this).attr('disabled', 'disabled');
				var this_thing = $(this).parent().parent();

				part_name = $(this).val();

			//checking the availability of that item in stock
				var query = "SELECT in_stock FROM stock WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND part_name = '" + part_name + "' AND creator_branch_code = '" + creator_branch_code + "'";
				var to_get = "in_stock";

				$.post('php/query_result_viewer.php', {query:query , to_get:to_get}, function(data)
				{
					if(data == '0')
					{
						$('.gen_quotation_span').html('The item you have selected is not available at your branch. Click on See Availability button to see the availability of that item in different branches').css('color', 'red');
						
					//for generating see availability button
						this_thing.find('#quotation_part_in_stock').attr('type', 'button').attr('value', 'See Availability').attr('disabled', false).addClass('change_branch_button').css('background', '#cc0000').css('color', 'white');

						this_thing.find('.change_branch_button').click(function()
						{
							$('.user_module_content').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">").load('php/search_item_in_branch.php');
						});
					}
					else
					{
						this_thing.find('#quotation_part_in_stock').val(data);
						$('.gen_quotation_span').html('');
					}
				});
			});

		//on clicking on calculate
			$('tr:last #quotation_part_total_price').click(function()
			{
				var quantity = parseInt($(this).parent().parent().find('#quotation_part_quantity').val());
				var rate = parseInt($(this).parent().parent().find('#quotation_part_rate').val());
				var cgst = parseInt($(this).parent().parent().find('#quotation_part_cgst').val());
				var sgst = parseInt($(this).parent().parent().find('#quotation_part_sgst').val());
				var igst = parseInt($(this).parent().parent().find('#quotation_part_igst').val());

				var total_price = (rate + (rate * (cgst+sgst+igst)/100))*quantity;
			
				$(this).val(total_price);
				//alert(total_price);
			});

		//on change of quantity, rate or gst after calculation
			$('tr:last #quotation_part_quantity').keyup(function()
			{
				$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
			});

			$('tr:last #quotation_part_rate').keyup(function()
			{
				$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
			});

			$('tr:last #quotation_part_cgst').keyup(function()
			{
				$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
			});

			$('tr:last #quotation_part_sgst').keyup(function()
			{
				$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
			});

			$('tr:last #quotation_part_igst').keyup(function()
			{
				$(this).parent().parent().find('#quotation_part_total_price').val('calculate');
			});
		});

	//on entering quantity
		$('tr:last #quotation_part_quantity').keyup(function()
		{
			var quantity = parseInt($(this).val());
			this_thing = $(this);

			var in_stock = parseInt(this_thing.parent().parent().find('#quotation_part_in_stock').val());

			if(quantity > in_stock)
			{
				$('.gen_quotation_span').html("You have entered more quantity than available in-stock at your branch. You can check its availability by clicking on availability tab.").css('color', 'red');
				$('.warn_box').text("Quantity is more than the available in-stock at the branch.");
				$('.warn_box').fadeIn(200).delay(3000).fadeOut(200);
			}
			else
			{
				$('.gen_quotation_span').html("");
			}
		});

	//on clicking on delete item button
		$('.item_delete_icon').click(function()
		{
			$(this).parent().parent().remove();
		});

	</script>

</tr>
