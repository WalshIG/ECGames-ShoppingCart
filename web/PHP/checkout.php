<form action="charge.php" method="POST" enctype="multipart/form-data">

<?php
	session_start();
	$_SESSION['pagename'] = "EC Games - Checkout";
	include "header.html";
	include('mysqli_connect.php');
	$username = $_SESSION['username'];
	$customer_id = $_SESSION['customer_id'];
	
	//Checks whether or not they have accepted the privacy policy. If they have not agreed, kill the page
	$policy_session = $_SESSION['has_agreed'];
	if($policy_session == 0){
		if(isset($_SESSION['username'])){
			die('ERROR: Please agree to the privacy policy');
		}
	}
	$result=mysqli_query($link, 
		"SELECT g.game_id, g.game_name AS 'game', g.picture, g.price, (c.quantity) , (g.price * c.quantity) AS 'sub-total', g.console, g.release_date as 'year'
		FROM ICS199Group01_dev.game g, ICS199Group01_dev.cart c
		WHERE g.game_id = c.game_id
		AND c.customer_id = $customer_id
		ORDER BY g.game_name ASC"
		);
	
	
	$total = 0;
	
	//Displays the number of rows
	if($result){
		//Count records
		$row_count=mysqli_num_rows($result);
		
		//If cart is empty, display js prompt
		if($row_count == 0){
			?><script type="text/javascript">alert("Cart is empty...");</script>
			<?php
			echo "<center><h2>Cart is empty</h2></center>";
		}
		
		//Display where the order receipt will be sent to
		echo "<center><h3>Order Details:</h3></center>";
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
		
	//Display customer information
		echo "<center>";
		//First table
		echo "<table id = 'viewcart'>";
			echo "<tr>";
				echo "<th>";
				echo "First Name: ";
				echo "</th>";
				
				echo "<th>";
				echo "Last Name: ";
				echo "</th>";
				
				echo "<th>";
				echo "Phone Number: ";
				echo "</th>";
			echo "</tr>";

			echo "<tr>";
				echo "<td>";
				echo $first_name;
				echo "</td>";
				
				echo "<td>";
				echo $last_name;
				echo "</td>";
				
				echo "<td>";
				echo $phone_number;
				echo "</td>";
			echo "</tr>";
		echo "</table>";
		
		//Second Table
		echo "<table id = 'viewcart'>";
			echo "<tr>";
				echo "<th>";
				echo "Email Address: ";
				echo "</th>";
				
				echo "<th>";
				echo "Street Address: ";
				echo "</th>";
				
				echo "<th>";
				echo "City: ";
				echo "</th>";
			echo "</tr>";
			
		echo "<br>";
		
			echo "<tr>";
				echo "<td>";
				echo $email;
				echo "</td>";
				
				echo "<td>";
				echo $street;
				echo "</td>";
				
				echo "<td>";
				echo $city;
				echo "</td>";
			echo "</tr>";
		echo "</table>";
		
		//Third Table
		echo "<table id = 'viewcart'>";
			echo "<tr>";
				echo "<th>";
				echo "Region: ";
				echo "</th>";
				
				echo "<th>";
				echo "Country: ";
				echo "</th>";
				
				echo "<th>";
				echo "Postal Code: ";
				echo "</th>";
			echo "</tr>";
			
		echo "<br>";
		
			echo "<tr>";
				echo "<td>";
				echo $region;
				echo "</td>";
				
				echo "<td>";
				echo $country;
				echo "</td>";
				
				echo "<td>";
				echo $postal_code;
				echo "</td>";
			echo "</tr>";
		echo "</table>";
		echo "</center>";
		echo "<br>";
		?>
		
	<table id='viewcart' style="width:60%" align=center>
		<tr>
			<th colspan=2><center>Game<center></th> 			<!-- 1,2 -->
			<th><center>Price<center></th>						<!-- 3 -->
			<th><center>Quantity</center></th>					<!-- 4 -->
			<th><center>Sub-total</center></th>					<!-- 5 -->
		</tr>
	
	<?php		
		// Fetch rows
		while($row=mysqli_fetch_array($result)){
			//Display game name, image, quantity, price
			echo'<tr>';
				// <!-- 1 --> Game Name, Release Year, Console
				echo '<td style="padding:3px;">';
				echo "<center><strong>" . $row['game'] . "</strong>";
				echo "<br>";
				echo $row['year'] . " - " . $row['console'];
				echo'</td>';
				
				// <!-- 2 -->
				echo '<td style="padding:3px;">';
				echo "<center><a href='$row[picture]' target='_blank'><img src='$row[picture]' height='200'/></center></a>";
				echo '</td>';
					
				// <!-- 3 -->
				echo '<td style="padding:3px;">';
				echo "<center>$" . $row['price'];
				echo '</td>';
				
				// <!-- 4 -->
				echo '<td style="padding:3px;">';
				echo "<center>" . $row['quantity'];
				echo'</td>';
				
				// <!-- 5 -->
				echo '<td>';
				echo "<center>$" . $row['sub-total'];
				$total = $total + $row['sub-total'];
				$_SESSION['ordertotal'] = $total;
				echo'</td>';
			echo'</tr>';
		}// Fetch all
		echo '</table>';
	}
	
	mysqli_free_result($result);
	mysqli_close($link);
	//payment form popup maybe with remember me option to store card info

	//If the total is 1 or higher, then subtotal is assigned the 'ordertotal' session variable. Otherwise, it is 0.
	    if ($total > 0) {
			$sub_total = $_SESSION['ordertotal'];	
		} else {
			$sub_total = 0;
		}
		
		$tax = $sub_total * .12;
		$grand_total = $sub_total + $tax;
		$_SESSION['order_total'] = $grand_total;
	
	//Subtotal
	echo '<table align=center><tr>
		<th>Total</th>					
		<td><p>' . '$' . number_format($sub_total,2) . '</p></td>				
		</tr>';

	//Tax
	echo '<tr>
		<th>GST/PST: (12%)</th>	
		<td><p>' . '$' . number_format($tax,2) . '</p></td>
		</tr>';
	
	//Order Total
	echo '<tr>
			<th>Grand Total</th>							
			<th><p>' . '$' . number_format($grand_total,2) . '</p></th>							
		 </tr></table>';	 	
?>	
	
</table>

<br>
<center>
<?php require_once('./config.php'); ?>
<form action="charge.php" method="post">
  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key="<?php echo $stripe['publishable_key']; ?>"
          data-description="<?php echo 'Payment Form'; ?>"
          data-amount="<?php echo $grand_total*100; ?>"
          data-locale="auto"></script>
		  <input type="hidden" name="totalamt" value="<?php echo $grand_total*100; ?>" />
</center>
</form>
</form>
<BR><BR>
<hr>
</body>
</html>

	
<?php 
	include "footer.html";
?>