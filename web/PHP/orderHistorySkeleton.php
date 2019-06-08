 <?php
 	//Displays Errors
    /*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL); 
	*/
	session_start();
	$_SESSION['pagename'] = "EC Games - Order History";
	include "header.html";
	$username = $_SESSION['username'];
	$customer_id = $_SESSION['customer_id'];
	
	$link=@mysqli_connect('localhost','cst178','365645','ICS199Group01_dev');
	if(!$link){
		die('ERROR: '.mysqli_connect_error());
	}
	
	if (isset($_GET)){
			$row=mysqli_fetch_array($result);
			$numrows = mysqli_num_rows($result);
	}
	
?>

<!DOCTYPE html>
<html>
<style>
	#history th {
		padding-top: 2px;
		padding-bottom: 2px;
		background-color: crimson;
		color: white;
	}
	
	#history tr:nth-child(even){background-color: #dddddd;}
	#history tr:nth-child(odd){background-color: #eeeeee;}
	#history tr:hover {background-color: orange;}
</style>

 <?php
	
	// The 'info table' for each order in order history
	$info_table_starter = "
	<table id='history' style='width:45%' align=center>
		<tr>
			<th><center>Order#</center></th>
			<th><center>Date Purchased</center></th>
			<th><center>Order Status</center></th>
		</tr>";
		
	// The 'picture table' for each order in order history 
	$picture_table_starter = "
	<table id='history'  style='width:50%' align=center>
		<tr>
			<th colspan=2><center>Game</center></th> <!-- The game's name AND picture -->
			<th><center>Bought for:</center></th>	
			<th><center>Quantity</center></th>
			<th><center>Sub-total</center></th>
		</tr>";
		
	// The start of the 'cost table' (displays $total_before_tax, tax, and grand total)
	$cost_table_starter = "
	<table id='history' style='width:45%' align=center>
		<tr>
			<th><center>Total Before Tax</center></th>
			<th><center>Tax</center></th>
			<th><center>Grand Total</center></th>
		</tr>";	
	
	$result=mysqli_query($link, 
			"SELECT o.customer_id, op.order_id, op.game_id, op.quantity, op.price as 'price_at_the_time', c.first_name, c.last_name, g.game_name, o.purchase_date, g.picture, (op.price * op.quantity) as 'sub-total', o.order_status
			FROM order_product op, orders o, customer c, game g
				WHERE op.order_id = o.order_id
			AND g.game_id = op.game_id
			AND c.customer_id = o.customer_id
			AND o.customer_id = $customer_id
			ORDER BY op.order_id DESC, g.game_name desc"
			);

	//Displays the number of rows
	$total_before_tax = 0;
	if($result){
		//Count records
		$row_count=mysqli_num_rows($result);
		if($row_count > 0){
			// Display how many total games the user has bought.
			echo'Found '.$row_count.' games. Hallelujah!<br><br>';
		}else{
			// User has not made any orders yet
			echo "<h2>No Orders Yet! Go and buy some games!</h2>";
		}

		$current_order_id = "";
		$total_before_tax = 0;
		
		// Fetch rows
		while($row=mysqli_fetch_array($result)){
			
			if ($current_order_id == "") {
				
				// first order printed; print the order_id
				echo '<strong><font color=green>order_id: ' . $row['order_id'] . '</font><br></strong>';
				$current_order_id = $row['order_id'];
			}
			
			if ($current_order_id != $row['order_id']) {
				echo "<font color=orange>total before tax = " . $total_before_tax . "<br></font>";
				echo '<br>';
				$total_before_tax = 0;
				echo '<strong><font color=green>order_id: ' . $row['order_id'] . '</font><br></strong>';
			}	
			//echo '<strong><font color=green>order_id: ' . $row['order_id'] . '</font><br></strong>';
			echo $row['order_id'] . " " . $row['game_id'] . " " . $row['quantity'] . " " . $row['price'] .  " <i>sub-total = $" . $row['sub-total'] . "</i><br>";
			$total_before_tax += $row['sub-total'];

			
			$current_order_id = $row['order_id'];
			
		}
		// This is the last game
		echo "<font color=red>total before tax = " . $total_before_tax . "<br></font>";				
	}
	
	
	
	
	mysqli_free_result($result);
	mysqli_close($link);
		
		//$tax = $total_before_tax * .12;
		//$grand_total = $total_before_tax + $tax;
	

?>	

</table>

<BR><BR>
<hr>
</body>
<?php
	include "footer.html";
?>
</html>




