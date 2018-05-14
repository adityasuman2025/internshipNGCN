
	<td>
		<select id="purchase_item">
			<option value=""></option>					
		</select>
	</td>

	<td><input type="number" value="0" placeholder="Quantity" id="purchase_quantity"></td>
	<td><input type="number" value="0" placeholder="Rate per unit" id="purchase_rate"></td>
	<td><input type="number" value="0" placeholder="Total Price" id="purchase_total_price"></td>
	<td>
		<img class="item_delete_icon" src="img/delete.png">
	</td>

	<script type="text/javascript">
	//on clicking on delete item button
		$('.item_delete_icon').click(function()
		{
			$(this).parent().parent().remove();
		});
	</script>