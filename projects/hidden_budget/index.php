<?php
require_once('../../includes/prepend.php');
require_once('includes/header.php');
require_once('../../includes/header.php');

$aParams = array(
	'Month' => isset($_REQUEST['Month']) ? $_REQUEST['Month'] : 0,
	'Year' => isset($_REQUEST['Year']) ? $_REQUEST['Year'] : 0
);
 //Try to get this from the datetime object.
$aMonths = array(
	0 => 'All Months',
	1 => 'January',
	2 => 'February',
	3 => 'March',
	4 => 'April',
	5 => 'May',
	6 => 'June',
	7 => 'July',
	8 => 'August',
	9 => 'September',
	10 => 'October',
	11 => 'November',
	12 => 'December'
);

$aCategories = Utility::getSingleColumn(budgetItemCategory::getAll(), "Name", "CategoryId");
$aReport  = budgetItemCategory::getReportByMonth();
foreach($aReport as $key => $aItem){
	unset($aReport[$key]);
	$aReport["m".$aItem['Month']."c".$aItem['CategoryId']] = $aItem;
}
$aItems = budgetItem::getAll($aParams);

?>
	<style type="text/css">
		body, html {
			padding: 0;
			margin: 0;
			background-color: #000;
			height: 100%;
			min-height: 100%;
		}
		h1, h2, h3, h4, h5, h6 {
			margin: 10px;
		}
		table {
			width: 100%;
			border-spacing: 0px;
			margin: 10px 0;
		}
		td, th {
			margin: 0px;
			padding: 2px 10px;
			border: 1px solid black;
		}
		td {
			border-top: none;
		}
		.left {
			float: left;
		}
		.right {
			float: right;
			margin: 5px;
		}
		.hidden {
			display: none;
		}

		.content {
			background-color: #EDD;
			overflow: hidden;
			padding: 20px;
			margin: 0px auto;
			min-height: 95%;
			width: 1200px;
			border-right: 2px solid black;
			border-left: 2px solid black;
		}

		.data-form, .report {
			background-color: #EBB;
			border-radius: 4px;	
			padding: 10px;
			margin: 10px 0;
		}
		.button {
			margin: 5px 0;
			padding: 2px;
			border-radius: 4px;
			border: 1px solid black;
			font-size: 10px;
		}
		.button:hover {
			box-shadow: 0 0 1px 1px inset;
			cursor: pointer;
		}
		.delete-row {
			font-family: sans-serif;
			border: none;
			padding: 2px 5px;
			font-weight: 900;
			color: red;
			text-shadow: 1px 1px 1px black;
		}
		.edit-row, .finish-edit-row, .cancel-edit-row {
			border: none;
			padding: 2px 5px;
			font-weight: 900;
			color: #00F;
			text-shadow: 1px 1px 1px black;
		}
		.delete-row:hover, .edit-row:hover, .finish-edit-row:hover, .cancel-edit-row:hover {	
			cursor: pointer;
		}
	</style>
	</head>
	<body>
		<div class="content">
			<form id="add_category" class="data-form" action="crud/add_category.php">
				<label>Add new category:</label><br />
				<input type="text" name="Category" placeholder="Category" />
				<input type="submit" value="Add Category" class="button">
			</form>
			<form id="add_item" class="data-form" action="crud/add_item.php">
				<label>Add new item:</label><br />
				$<input type="text" name="Amount" placeholder="Amount" />
				<select name="Category">
					<?php
					foreach($aCategories as $iCategoryId => $sCategory){
						echo "<option value='$iCategoryId'>$sCategory</option>";
					}
					?>
				</select>
				<input type="text" name="Note" placeholder="Note" />
				<input type="submit" value="Add Item" class="button" />
			</form>
			<div class="report">
				<a href='#add_category' class="right">New Category</a>
				<a href='#add_item' class="right">New Item</a>
				<h2>Expenditures Report:</h2>
				<form class="report-parameters" action="index.php">
					for: 
					<select name="Month">
						<?php
						foreach($aMonths as $iMonthId => $sMonth){
							$sSelected = $iMonthId == $aParams['Month'] ? 'selected' : '';
							echo "<option value='$iMonthId' $sSelected>$sMonth</option>";
						}
						?>
					</select>
					<!-- <select name="Year">
						<option value="">All Years</option>
						<option value="2012">2012</option>
					</select> -->
					<input type="submit" value="Update Report" class="button" />
				</form>

				<table>
					<tr>
						<th>Amount</th>
						<th>Category</th>
						<th>Note</th>
						<th>Date</th>
					</tr>
					<?php
					foreach($aItems as $aItem){
						?>
						<tr class="budget-item-row" value="<?=$aItem['ItemId']?>">
							<td class="amount">$<?=$aItem['Amount']?></td>
							<td class="category"><?=$aItem['Category']?></td>
							<td class="note"><?=$aItem['Note']?></td>
							<td class="date"><?=$aItem['Date']?></td>
							<td class="delete-row" value="<?=$aItem['ItemId']?>">X</td>
							<td class="edit-row" value="<?=$aItem['ItemId']?>">Edit</td>
						<tr>
						<!-- This one's for edit mode -->
						<tr class="budget-edit-item-row hidden" value="<?=$aItem['ItemId']?>">
							<td>$<input name='amount' type='text' value='<?=$aItem['Amount']?>' /></td>
							<td>
								<select name="category">
									<?php
									foreach($aCategories as $iCategoryId => $sCategory){
										$sSelected = $sCategory == $aItem['Category'] ? 'selected' : '';
										echo "<option value='$iCategoryId' $sSelected>$sCategory</option>";
									}
									?>
								</select>
							</td>
							<td><textarea name='note'><?=$aItem['Note']?></textarea></td>
							<td><input name='date' type='text' value='<?=$aItem['Date']?>' /></td>
							<td class='finish-edit-row'>Finish</td>
							<td class='cancel-edit-row'>Cancel</td>
						</tr>
						<?php
					}
					?>
				</table>
			</div>
			<div class="report">
				<h2>Monthly Report:</h2>
				<table>
					<tr>
						<th>Month</th>
						<?php
						foreach($aCategories as $iCategoryId => $sCategory){
							echo "<th value='$iCategoryId'>$sCategory</th>";
						}
						?>
						<th>Total Spending</th>
					</tr>
					<?php
					foreach($aMonths as $iMonthId => $sMonth){
						if($iMonthId == 0){
							continue;
						}
					?>
					<tr>
						<td value="<?=$iMonthId?>"><?=$sMonth?></td>
						<?php
						$iTotal = 0;
						foreach($aCategories as $iCategoryId => $sCategory){
							$iValue = isset($aReport["m".$iMonthId."c".$iCategoryId]) ? round($aReport["m".$iMonthId."c".$iCategoryId]['Amount'], 2) : 0;
							echo '<td value="$iCategoryId">$'.$iValue.'</td>';
							$oCategory = new budgetItemCategory($iCategoryId);
							if($oCategory->getName() == "Income - Paycheck"){
								continue;
							}
							else if(strpos($oCategory->getName(), "Income") !== false){
								$iTotal = $iTotal - $iValue;
							}
							else {
								$iTotal += $iValue;
							}
						}
						?>
						<td>$<?=$iTotal?></td>
					</tr>
					<?php
					}
					?>
				</table>
			</div>
			<div class="report">
				<?php
				$aCategories = budgetItemCategory::getAll();
				?>
				<h2>Budget by category:</h2>
				<table>
					<tr>
						<th>Category</th>
						<th>Budgeted Amount</th>
					</tr>
					<?php
					foreach($aCategories as $aCategory){
					?>
						<tr class="category-budget-row" value="<?=$aCategory['CategoryId']?>">
							<td class="category"><?=$aCategory['Name']?></td>
							<td class="amount">$<?=$aCategory['BudgetedAmount']?></td>
							<td class="delete-row" value="<?=$aCategory['CategoryId']?>">X</td>
							<td class="edit-row" value="<?=$aCategory['CategoryId']?>">Edit</td>
						<tr>
						<!-- This one's for edit mode -->
						<tr class="category-budget-edit-row hidden" value="<?=$aCategory['CategoryId']?>">
							<td><input name='category' type='text' value='<?=$aCategory['Name']?>' /></td>
							<td>$<input name='amount' type='text' value='<?=$aCategory['BudgetedAmount']?>' /></td>
							<td class='finish-edit-row'>Finish</td>
							<td class='cancel-edit-row'>Cancel</td>
						</tr>
					<?php
					}
					?>
				</table>
			</div>
			<a href='#add_category' class="right">New Category</a>
			<a href='#add_item' class="right">New Item</a>
		</div>
		<script type="text/javascript">
		$(document).ready(function(){
			$('.budget-item-row .delete-row').click(function(){
				var itemId = $(this).attr('value');
				document.location = "crud/delete_item.php?ItemId="+itemId;
			});
			$('.budget-item-row .edit-row').click(function(){
				var itemId = $(this).attr('value');
				$('.budget-item-row').show()
				$('.budget-edit-item-row').hide()
				$('.budget-item-row[value="'+itemId+'"]').hide();
				$('.budget-edit-item-row[value="'+itemId+'"]').show();
			});
			$('.budget-edit-item-row .cancel-edit-row').click(function(){
				$('.budget-item-row').show();
				$('.budget-edit-item-row').hide();
			});
			$('.budget-edit-item-row .finish-edit-row').click(function(){
				$('.budget-item-row').show();
				$row = $(this).closest('.budget-edit-item-row');
				data = {
					itemId: $row.attr('value'),
					amount: $row.find('[name="amount"]').val(),
					category: $row.find('[name="category"]').val(),
					note: $row.find('[name="note"]').val(),
					date: $row.find('[name="date"]').val()
				}
				$.ajax({
					url: "crud/edit_item.php",
					data: data,
					success: function(res){
						try {
							res = $.parseJSON(res);
						}
						catch(e){
							console.log(e, "crud/edit_item.php returned unparsable JSON.");
							return false;
						}
						if(res.success && !res.error){
							window.location.reload();
						}
						else {
							console.log(res.error);
						}
					}
				});
			});
			//Categories edit
			$('.category-budget-row .delete-row').click(function(){
				var categoryId = $(this).attr('value');
				document.location = "crud/delete_category.php?CategoryId="+categoryId;
			});
			$('.category-budget-row .edit-row').click(function(){
				var categoryId = $(this).attr('value');
				$('.category-budget-row').show()
				$('.category-budget-edit-row').hide()
				$('.category-budget-row[value="'+categoryId+'"]').hide();
				$('.category-budget-edit-row[value="'+categoryId+'"]').show();
			});
			$('.category-budget-edit-row .cancel-edit-row').click(function(){
				$('.category-budget-row').show();
				$('.category-budget-edit-row').hide();
			});
			$('.category-budget-edit-row .finish-edit-row').click(function(){
				$row = $(this).closest('.category-budget-edit-row');
				data = {
					categoryId: $row.attr('value'),
					name: $row.find('[name="category"]').val(),
					amount: $row.find('[name="amount"]').val()
				}
				$.ajax({
					url: "crud/edit_category.php",
					data: data,
					success: function(res){
						console.log(res);
						try {
							res = $.parseJSON(res);
						}
						catch(e){
							console.log(e, "crud/edit_category.php returned unparsable JSON.");
							return false;
						}
						if(res.success && !res.error){
							window.location.reload();
						}
						else {
							console.log(res.error);
						}
					}
				});
			});
		});
		</script>
	</body>
</html>