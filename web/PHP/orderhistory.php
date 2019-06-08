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

 <?php
	
	/* ******************************************** */
	/* ********** TABLE HEADER VARIABLES ********** */
	/* ******************************************** */
	
	// The start of the 'info table' for each order in order history
	$info_table_starter = "
	<table id='viewcart' style='width:45%' align=center>
		<tr>
			<th><center>Order#</center></th>
			<th><center>Date Purchased</center></th>
			<th><center>Order Status</center></th>
		</tr>";
		
	// The start of the 'picture table' for each order in order history 
	$picture_table_starter = "
	<table id='viewcart'  style='width:50%' align=center>
		<tr>
			<th colspan=2><center>Game</center></th> <!-- The game's name AND picture -->
			<th><center>Bought for:</center></th>	
			<th><center>Quantity</center></th>
			<th><center>Sub-total</center></th>
		</tr>";
		
	// The start of the 'cost table'
	$cost_table_starter = "
	<table id='viewcart' style='width:45%' align=center>
		<tr>
			<th><center>Total Before Tax</center></th>
			<th><center>Tax</center></th>
			<th><center>Grand Total</center></th>
		</tr>";	

	/* ******************************************** */
	
	// The MySQL Query we're gonna be using for this page
	$result=mysqli_query($link, 
			"SELECT o.customer_id, op.order_id, op.game_id, op.quantity, op.price as 'price_at_the_time', c.first_name, c.last_name, g.game_name, o.purchase_date, g.picture, (op.price * op.quantity) as 'sub-total', o.order_status
			FROM order_product op, orders o, customer c, game g
				WHERE op.order_id = o.order_id
			AND g.game_id = op.game_id
			AND c.customer_id = o.customer_id
			AND o.customer_id = $customer_id
			ORDER BY op.order_id DESC, g.game_name desc"
			);
			
	// The MySQL Query that displays how many orders the user has made (not working quite right yet)
	$number_of_orders = mysqli_query($link, 
			"SELECT count( DISTINCT(op.order_id) ) as 'orders-made'
			FROM order_product op, orders o 
			WHERE o.order_id = op.order_id
			AND o.customer_id = $customer_id"
			);

	//Displays the number of rows
	$total_before_tax = 0;
	if($result){
		//Count records
		$row_count=mysqli_num_rows($result);
		
		echo "<table id='viewcart' style='width:95%' align=center><th>";
		if($row_count > 0){
			// Display how many total orders the user has made. Haven't got it working yet...
			echo ' <i>Found '. $qty_of_orders_as_int .' orders. Hallelujah! </i>';
		} else {
			// User has not made any orders yet
			echo " <i>No Orders Yet! Go and buy some games! </i>";
		}
		echo "</th></table>";
		echo "<br>";

		$current_order_id = "";
		$total_before_tax = 0;
		
		// Fetch rows
		while($row=mysqli_fetch_array($result)){
			
			// This handles the first order printed
			if ($current_order_id == "") { 
				// first order printed; print the order_id
				//echo '<center><strong><font color=green>order_id: ' . $row['order_id'] . '</font><br></strong></center>';
				$current_order_id = $row['order_id'];
				
				/* =-=-=-= GRAPHICS =-=-=-= */
				echo $info_table_starter;
				
				// Print the order_id
				echo "<td style='padding:3px;'><center><strong>" . $row['order_id'] . "</strong></center></td>";
				
				// Print the purchase date
				echo "<td style='padding:3px;'><center><strong>" . $row['purchase_date'] . "</strong></center></td>";
				
				// Print the order status
				echo "<td style='padding:3px;'><center><strong>" . $row['order_status'] . "</strong></center></td>";
				
				// Finish this freakin' table
				echo "</table>";
				
				// Start a new 'picture table'
				echo $picture_table_starter;

			}
			
			if ($current_order_id != $row['order_id']) {
				//echo "<center><font color=orange>total before tax = " . $total_before_tax . "<br></font></center>"; // *** debugging ***
				
				// end the 'picture table'
				echo "</table>";
				
				/* =-=-=-= ALPHA - BEGIN =-=-=-= */
				
				// start a new 'cost table'
				echo $cost_table_starter;
				echo '<tr>';
				
				// echo the Total
				echo '<td style="padding:3px;">';
				//echo $row['order_id'];
				echo "<center>$" . number_format($total_before_tax,2) . "</center>"; // For some reason, this displays the total of the order AFTER this one
				echo'</td>';

				// echo the Tax
				echo '<td style="padding:3px;">';
				//echo $row['order_id'];
				$tax = ($total_before_tax * 0.12);
				echo "<center>$" . number_format($tax,2) . "</center>";
				echo'</td>';

				// echo the Grand Total
				echo '<td style="padding:3px;">';
				//echo $row['order_id'];
				echo "<strong><font color=red><center>$" . number_format(($total_before_tax + $tax),2) . "</center></strong></font>";
				echo'</td>';
				
				echo '</tr>';
				
				// end this 'cost table'
				echo '</table>';
				
				/* =-=-=-= ALPHA - END =-=-=-= */
				
				// create some space between this and the next order
				echo '<br><br><br><br>';
				
				// reset $total_before_tax to zero
				$total_before_tax = 0;
				
				//echo '<center><strong><font color=green>order_id: ' . $row['order_id'] . '</font><br></strong></center>';
				// first order printed; print the order_id
				//echo '<center><strong><font color=green>order_id: ' . $row['order_id'] . '</font><br></strong></center>';
				$current_order_id = $row['order_id'];
				
				echo $info_table_starter;
				
				// Print the order_id
				echo "<td style='padding:3px;'><center><strong>" . $row['order_id'] . "</strong></center></td>";
				
				// Print the purchase date
				echo "<td style='padding:3px;'><center><strong>" . $row['purchase_date'] . "</strong></center></td>";
				
				// Print the order status
				echo "<td style='padding:3px;'><center><strong>" . $row['order_status'] . "</strong></center></td>";
				
				// Finish this freakin' table
				echo "</table>";
				echo $picture_table_starter; 
			}
			//echo '<center><font color=blue>order_id: ' . $row['order_id'] . " / game_id: " . $row['game_id'] . " / QTY: " . $row['quantity'] . " / bought for: " . $row['price_at_the_time'] .  " / <i>sub-total = <strong>$" . $row['sub-total'] . "</i><br></font></center>" . '</strong>'; // *** debugging ***
			echo '<tr>';
					
				// <!-- 1 --> The game's name
				echo '<td style="padding:3px;">';
				echo "<center>" . $row[game_name] . "</center>";
				
				// Link to add your own review
				echo "<br><center><strong><a class=\"submit\" href=\"addreview.php?game_id={$row['game_id']}\">Add a review</a></strong></center>";
				
				
				echo '</td>';
				
				// <!-- 1.5 --> The game's picture
				echo '<td style="padding:3px;">';
				echo "<center><a href='gamepage.php?game_id=" . $row[game_id] . "' ><img class='zoom' src='$row[picture]' height='75'/></a></center>";
				echo '</td>';
							
				// <!-- 2 --> The Game's Price (at the time)
				echo '<td style="padding:3px;">';
				echo "<center>" . "$" . $row['price_at_the_time'] . "</center>";
				echo '</td>';
						
				// <!-- 3 --> Quantity (of that one item)
				echo '<td style="padding:3px;">';
				echo "<center>" . $row['quantity'] . "</center>";
				echo'</td>';
						
				// <!-- 4 --> Sub-total (for that item)
				echo '<td style="padding:3px;">';
				echo "<center>$" . $row['sub-total'] . "</center>";
				$total_before_tax += $row['sub-total'] ;
				//echo "<strong><center>HERE! " . $total_before_tax . "</center></strong>"; // *** debugging ***
				echo'</td>';
			
			echo'</tr>';
			
			$current_order_id = $row['order_id'];
			
			
		}
		// This is the last game
		//echo "<center><font color=red>total before tax = " . $total_before_tax . "<br></font></center>"; // *** debugging ***
		
		/* =-=-=-= ALPHA - BEGIN =-=-=-= */
		
		// end the 'picture table'
		echo "</table>";
		// start a new 'cost table'
		echo $cost_table_starter;
		echo '<tr>';
				
		// echo the Total
		echo '<td style="padding:3px;">';
		//echo $row['order_id'];
		echo "<center>$" . number_format($total_before_tax,2) . "</center>"; // For some reason, this displays the total of the order AFTER this one
		echo'</td>';

		// echo the Tax
		echo '<td style="padding:3px;">';
		//echo $row['order_id'];
		$tax = ($total_before_tax * 0.12);
		echo "<center>$" . number_format($tax,2) . "</center>";
		echo'</td>';

		// echo the Grand Total
		echo '<td style="padding:3px;">';
		//echo $row['order_id'];
		echo "<strong><font color=red><center>$" . number_format(($total_before_tax + $tax),2) . "</center></strong></font>";
		echo'</td>';
				
		echo '</tr>';
		
		// end this 'cost table'
		echo '</table>';
		
		/* =-=-=-= ALPHA - END =-=-=-= */
		
		echo '<br>';
		echo "<table id='viewcart' style='width:95%' align=center><th>";
		echo '<center><i>No more orders found!</i></center>';
		echo "</th></table>";
		
		
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




