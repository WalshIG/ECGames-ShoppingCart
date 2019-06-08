 <?php
 	//Displays Errors
    /*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL); 
	*/
	session_start();
	$_SESSION['pagename'] = "EC Games - Order Receipt";
	include "header.html";
	include "mysqli_connect.php";
	$username = $_SESSION['username'];
	$customer_id = $_SESSION['customer_id'];
	
	if (isset($_GET)){
			$row=mysqli_fetch_array($result);
			$numrows = mysqli_num_rows($result);
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Order Summary</title>
</head>

<style> 


</style>
<body>

<?php
		$customer_detail = "SELECT first_name, last_name, phone_number, email, street, city, region, country, postal_code FROM customer WHERE customer_id = $customer_id";
		$customer_detail_query = mysqli_query($link, $customer_detail);
		$detail_result = $customer_detail_query->fetch_array(MYSQLI_NUM);
	
//Variables for information
		$first_name = $detail_result[0];
		$last_name = $detail_result[1];
		$phone_number = $detail_result[2];
		$email = $detail_result[3];
		$street = $detail_result[4];
		$city = $detail_result[5];
		$region = $detail_result[6];
		$country = $detail_result[7];
		$postal_code = $detail_result[8];
		$order_id = $detail_result[9];
		$purchase_date = $detail_result[10];
		$game_name = $detail_result[11];
		$quantity = $detail_result[12];
		$sub_total = $detail_result[13];
		$tax = $detail_result[14];
		$order_total = $detail_result[15];

//Display customer information
echo "<center>";
		echo "<h4>Thank you " . $first_name .  " for purchasing our item! We are getting your order ready to be shipped and we will notify you when it has been sent.</h4><br>";
	echo '<table id="viewcart">
		<tr>
		<tbody>
		<th>First Name</th>					
		<td><p>' . $first_name . '</p></td>				
		</tr>';

	
	echo '<tr>
		<th>Last Name</th>					
		<td><p>' . $last_name . '</p></td>				
		</tr>';
	
	echo '<tr>
		<th>Phone Number</th>					
		<td><p>' . $phone_number . '</p></td>				
		</tr>';
	
	echo '<tr>
		<th>email</th>					
		<td><p>' . $email . '</p></td>				
		</tr>';
	
	echo '<tr>
		<th>Street</th>					
		<td><p>' . $street . '</p></td>				
		</tr>';
	
	echo '<tr>
		<th>City</th>					
		<td><p>' . $city . '</p></td>				
		</tr>';
	
	echo '<tr>
		<th>Region</th>					
		<td><p>' . $region . '</p></td>				
		</tr>';
	
	echo '<tr>
		<th>Country</th>					
		<td><p>' . $country . '</p></td>				
		</tr>';
	
	echo '<tr>
		<th>Postal Code</th>					
		<td><p>' . $postal_code . '</p></td>				
		</tr>
		<tbody>
		</table>';	
    echo "</tbody>";
echo "</center>";
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
	<table id='history' style='width:30%' align=center>
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
			AND op.order_id = (SELECT MAX(order_id) FROM order_product)
			ORDER BY op.order_id DESC, g.game_name desc"
			);

	//Displays the number of rows
	$total_before_tax = 0;
	if($result){
		
		$current_order_id = "";
		$total_before_tax = 0;
		
		// Fetch rows
		while($row=mysqli_fetch_array($result)){
			
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
				echo "<center>$" . $total_before_tax . "</center>"; // For some reason, this displays the total of the order AFTER this one
				echo'</td>';

				// echo the Tax
				echo '<td style="padding:3px;">';
				//echo $row['order_id'];
				$tax = ($total_before_tax * 0.12);
				echo "<center>$" . $tax . "</center>";
				echo'</td>';

				// echo the Grand Total
				echo '<td style="padding:3px;">';
				//echo $row['order_id'];
				echo "<strong><font color=red><center>$" . ($total_before_tax + $tax) . "</center></strong></font>";
				echo'</td>';
				
				echo '</tr>';
				
				// end this 'cost table'
				echo '</table>';
				
				/* =-=-=-= ALPHA - END =-=-=-= */
				
				// create some space between this and the next order
				echo '<br><br>';
				
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
				echo '</td>';
				$_SESSION['last_game'] = $row[game_name];
				
				// <!-- 1.5 --> The game's picture
				echo '<td style="padding:3px;">';
				echo "<center><img src='$row[picture]' height='100'/></center>";
				echo '</td>';
				$_SESSION['last_picture'] = $row[picture];
							
				// <!-- 2 --> The Game's Price (at the time)
				echo '<td style="padding:3px;">';
				echo "<center>" . "$" . $row['price_at_the_time'] . "</center>";
				echo '</td>';
				$_SESSION['last_price'] = $row['price_at_the_time'];
						
				// <!-- 3 --> Quantity (of that one item)
				echo '<td style="padding:3px;">';
				echo "<center>" . $row['quantity'] . "</center>";
				echo'</td>';
				$_SESSION['last_quantity'] = $row['quantity'];
						
				// <!-- 4 --> Sub-total (for that item)
				echo '<td style="padding:3px;">';
				echo "<center>$" . $row['sub-total'] . "</center>";
				$total_before_tax += $row['sub-total'] ;
				//echo "<strong><center>HERE! " . $total_before_tax . "</center></strong>"; // *** debugging ***
				echo'</td>';
			
			echo'</tr>';
			
			$current_order_id = $row['order_id'];
			
			$filename = "receipt/text-receipt" . $row['order_id'] . ".txt";
			//echo $filename;
			$file = fopen($filename,"w") or die ("Can't open the text file" . $filename);
		
			//This writes to text file
			function writeLine($output_file, $data)  {
				$result = fwrite($output_file, $data);    
				fclose($filename, 'w');
			}
			
			writeLine($file,"Thank you " . $first_name .  " for purchasing our item! We are getting your order ready to be shipped and we will notify you when it has been sent." . "\n");
			writeLine($file, "First Name: " . $first_name . "\n");
			writeLine($file, "Last Name: " . $last_name . "\n");
			writeLine($file, "Phone Number: " . $phone_number . "\n");
			writeLine($file, "Email: " . $email . "\n");
			writeLine($file, "Street: " . $street . "\n");
			writeLine($file, "City: " . $city . "\n");
			writeLine($file, "Region: " . $region . "\n");
			writeLine($file, "Country: " . $country . "\n");
			writeLine($file, "Postal Code: " . $postal_code . "\n");
			
			writeLine($file, "Order ID: " . $row['order_id'] . "\n");
			writeLine($file, "Purchase Date: " . $row['purchase_date'] . "\n");
			writeLine($file, "Game Name: " . $row['game_name'] . "\n");
			writeLine($file, "Quantity: " . $row['quantity'] . "\n");
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
			writeLine($file, "Sub-Total: " . number_format($total_before_tax,2) . "\n");
			writeLine($file, "Tax: " . number_format($tax,2) . "\n");
			writeLine($file, "Order Total: " . number_format($total_before_tax + $tax,2) . "\n");
		/* =-=-=-= ALPHA - END =-=-=-= */
		
		//echo '<br><center><i>No more orders found!</i></center>';
		

		
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
