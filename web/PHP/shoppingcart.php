 <?php
 	//Displays Errors
    /*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL); 
	*/
	session_start();
	$_SESSION['pagename'] = "EC Games - View Cart";
	include "header.html";
	$username = $_SESSION['username'];
	$customer_id = $_SESSION['customer_id'];
	
	$link=@mysqli_connect('localhost','cst178','365645','ICS199Group01_dev');
	if(!$link){
		die('ERROR: '.mysqli_connect_error());
	}
	
	//Checks whether or not they have accepted the privacy policy. If they have not agreed, kill the page
	$policy_session = $_SESSION['has_agreed'];
	if($policy_session == 0){
		if(isset($_SESSION['username'])){
			die('ERROR: Please agree to the privacy policy');
		}
	}
	if (isset($_GET)){
		//This is the code for adding a game to the cart
		if($_GET["prod"]) {
			$product_id = $_GET["prod"];

			//Select the items in the customers cart
			$result=mysqli_query($link, 
				"SELECT customer_id, game_id, quantity
				FROM cart
				WHERE customer_id = $customer_id
				AND game_id = $product_id
				");
				
			$row=mysqli_fetch_array($result);
			$numrows = mysqli_num_rows($result);
			
			//If the number of rows is greater than 0, add 1 to the current cart. Otherwise, insert a new row.
			if ($numrows > 0){
				$result=mysqli_query($link, 
				"UPDATE cart
				SET quantity =  quantity + 1
				WHERE customer_id = $customer_id
				AND game_id = $product_id
				");
				
				//go to view cart
				header('Refresh: 0; URL=shoppingcart.php?prod=0');
			} else {
				$add_to_cart = mysqli_query($link, "INSERT INTO cart (customer_id, game_id, quantity) VALUES ($customer_id, $product_id, 1)");
				
				//go to view cart
				header('Refresh: 0; URL=shoppingcart.php?prod=0');
			}
			
		}
		
		//This is the code for removing a game from the cart
		if($_GET["minusprod"]) {
			$product_id = $_GET["minusprod"];

			$result=mysqli_query($link, 
				"SELECT customer_id, game_id, quantity
				FROM cart
				WHERE customer_id = $customer_id
				AND game_id = $product_id
				");
			$row=mysqli_fetch_array($result);
			$numrows = mysqli_num_rows($result);
			
			//If the number of rows is 2 or higher, use the update method. Otherwise delete the row.
			if ($numrows > 0){
				if ($row['quantity'] > 1) {
					$result=mysqli_query($link, 
						"UPDATE cart
						SET quantity =  quantity - 1
						WHERE customer_id = $customer_id
						AND game_id = $product_id");
						
						//go to view cart
						header('Refresh: 0; URL=shoppingcart.php?prod=0');
				} else {
					$result=mysqli_query($link, 
						"DELETE FROM cart
						WHERE customer_id = $customer_id
						AND game_id = $product_id");
						
						//go to view cart
						header('Refresh: 0; URL=shoppingcart.php?prod=0');
				}
			}
		}
		
		//This is the code for removing all items from the cart
		if($_GET["delprod"]) {
			$product_id = $_GET["delprod"];
			
			$result=mysqli_query($link, 
				"SELECT customer_id, game_id, quantity
				FROM cart
				WHERE customer_id = $customer_id
				AND game_id = $product_id
				");
			$row=mysqli_fetch_array($result);
			$numrows = mysqli_num_rows($result);
			$delete_all = mysqli_query($link,"DELETE FROM cart
						WHERE customer_id = $customer_id
						AND game_id = $product_id");
						
		//go to view cart
		header('Refresh: 0; URL=shoppingcart.php?prod=0');
		}
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="index.css">
</head>

<!-- this is the start of the outer table which holds games on the left, and total on the right -->
<table id='invisible' style="width:70%" align=center>
	<td>

<!-- this is the start of the left inner table, which cycles through all the games in a user's cart and echoes (echos?) them -->
<table id='viewcart' style="width:50%" align=center>
    <tr>
        <th colspan=1><center>Game<center></th> 			<!-- 1,2 -->
		<th><center>Price<center></th>						<!-- 3 -->
		<th><center>Quantity</center></th>					<!-- 4 -->
        <th><center>Sub-total</center></th>					<!-- 5 -->
		<th colspan=3><center>Modify Cart</center></th>		<!-- 6,7,8 -->
    </tr>

 <?php
	
	$result=mysqli_query($link, 
		"SELECT g.game_id, g.game_name AS 'game', g.picture, g.price, (c.quantity) , (g.price * c.quantity) AS 'sub-total', g.console, g.release_date as 'year'
		FROM ICS199Group01_dev.game g, ICS199Group01_dev.cart c
		WHERE g.game_id = c.game_id
		AND c.customer_id = $customer_id
		ORDER BY g.game_name ASC"
		);
	
	//Displays the number of rows
	$total = 0;
	
	if($result){
		//Count records
		$row_count=mysqli_num_rows($result);
		if($row_count > 0){
			//echo'Found '.$row_count.' records';
		}else{
			echo "<center><h2>Cart is empty</h2></center>";
		}
		
		// Fetch rows
		while($row=mysqli_fetch_array($result)){
			//View the table values in the cart
			echo'<tr>';
				// <!-- 1 --> Game Name, Release Year, Console
				echo '<td style="padding:3px;">';
				// <!-- 2 --> Picture
				echo "<center><a href='$row[picture]' target='_blank'><img class='zoom2' src='$row[picture]' height='100'/></center></a>";
				echo "<center><strong>" . $row['game'] . "</strong>";
				echo "<br>";
				echo $row['year'] . " - " . $row['console'];
				echo'</td>';
					
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

				// <!-- 6 --> Button: Add 1 more
				echo '<td>';
				echo "<center><a href='shoppingcart.php?prod=" . $row['game_id'] . "'><img class='zoom' src='images/sys/cartAdd.png' width='100'/></a></center>";
				echo '</td>';
				
				// <!-- 7 --> Button: Remove 1
				echo '<td>';
				echo "<center><a href='shoppingcart.php?minusprod=" . $row['game_id'] . "'> <img class='zoom' src='images/sys/cartRemove.png' width='100'/></center>";
				echo '</td>';
				
				// <!-- 8 --> Button: Remove ALL
				echo '<td>';
				echo "<center><a href='shoppingcart.php?delprod=" . $row['game_id'] . "'> <img class='zoom' src='images/sys/cartRemoveAll.png' width='100'/></center>";
				echo '</td>';

			echo'</tr>';
		}// Fetch all
		echo '</table>';
	}
	
	mysqli_free_result($result);
	mysqli_close($link);
	
	//If the total is 1 or higher, then subtotal is assigned the 'ordertotal' session variable. Otherwise, it is 0.
	    if ($total > 0) {
			$sub_total = $_SESSION['ordertotal'];	
		} else {
			$sub_total = 0;
		}
		
		$tax = $sub_total * .12;
		$grand_total = $sub_total + $tax;
?>	
	</td>
	<td>
	<?php
	echo '<table id=viewcart align=center><tr>
		<th>Total</th>					
		<td><p>' . '$' . number_format($sub_total,2) . '</p></td>				
		</tr>';

	
	echo '<tr>
		<th>GST/PST: (12%)</th>	
		<td><p>' . '$' . number_format($tax,2) . '</p></td>
		</tr>';
	
	echo '<tr>
			<th>Grand Total</th>							
			<th><p>' . '$' . number_format($grand_total,2) . '</p></th>							
		 </tr></table></table>';	
	
	include 'footer.html';
	?>
	</td>
</table>

</table>

<BR><BR>
<hr>
</body>
 </html>



