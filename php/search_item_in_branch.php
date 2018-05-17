<?php
	include('connect_db.php');
?>

	<div class="user_entry_form add_quotation_form">
		<div>
			<b>Brand:</b>
			<br>
			<select id="quotation_brand">
				<option value=""></option>
					<?php
						$get_brand_query = "SELECT brand FROM stock";
						$get_brand_query_run = mysqli_query($connect_link, $get_brand_query);

						while($get_brand_result = mysqli_fetch_assoc($get_brand_query_run))
						{
							$brand = $get_brand_result['brand'];
							echo "<option value=\"$brand\">";
								echo $brand;
							echo "</option>";
						}
					?>	
			</select>
		</div>

		<div>
			<b>Model Name:</b>
			<br>
			<select id="quotation_model_name">

			</select>
		</div>
		
		<div>
			<b>Model Number:</b>
			<br>
			<select id="quotation_model_number">

			</select>
		</div>
		<br><br>

		<table class="quotation_entry_table" style="margin: auto">
			<tr>
				<th>Part Name</th>
				<th>Available at Branch</th>			
				<th>In-Stock Items</th>
			</tr>
		</table>
	</div>

	<span class="contact_branch_span"></span>


	<script type="text/javascript">
	//on selecting a brand
		$('#quotation_brand').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			brand = $(this).val();
			var query = "SELECT model_name FROM stock WHERE brand ='" + brand + "'";
			var to_get = "model_name";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#quotation_model_name').html(data);
			});
		});

	//on selecting a model_name
		$('#quotation_model_name').change(function()
		{
			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			model_name = $(this).val();
			var query = "SELECT model_number FROM stock WHERE model_name ='" + model_name + "' AND brand ='" + brand + "'";
			var to_get = "model_number";

			$.post('php/product_query_runner.php', {query:query , to_get:to_get}, function(data)
			{
				//alert(data);
				$('#quotation_model_number').html(data);
			});
		});

	//on selecting a model_number
		$('#quotation_model_number').change(function()
		{
			$('.contact_branch_span').html("<img class=\"gif_loader\" src=\"img/loaders1.gif\">");

			$(this).attr('disabled', 'disabled').css('border', '1px solid lightgrey');

			model_number = $.trim($(this).val()); //making it universal variable to use in, on selecting a part_name
			
			var query = "SELECT * FROM stock WHERE model_number ='" + model_number + "' AND model_name = '" + model_name + "' AND brand = '" + brand + "'";
			
			$.post('php/list_item_available_at_branch.php', {query:query}, function(data)
			{
				//alert(data);
				$('.quotation_entry_table').append(data);

				$('.contact_branch_span').html('You can contact to the listed branch and make that item available at your branch');
			});
		});
	</script>