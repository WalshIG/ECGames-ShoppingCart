<html>
<head>
	<title>Game Info</title>
	<link rel="stylesheet" href="index.css">
</head>

<body>

<?php
	session_start();
	//include('includes/header.html');

	$_SESSION['pagename'] = "EC Games!";
	include "header.html";
	include ('mysqli_connect.php');
	$policy_session = $_SESSION['has_agreed'];
	
	//If they have not agreed, kill the page
	if($policy_session == 0){
		if(isset($_SESSION['username'])){
			die('ERROR: Please agree to the privacy policy');
		}
	}
?>

<?php
	$game_id = $_GET['game_id'];

	// show that we have the userid
	$query = "SELECT * from game WHERE game_id = $game_id";
	$r = mysqli_query ($dbc, $query);
	$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
	
	// Grab the average rating for the game
	$avg_rating_query = "SELECT AVG(rating) as 'avg' FROM review WHERE game_id = $game_id";
	$avg_r = mysqli_query ($dbc, $avg_rating_query);
	$avg_r_row = mysqli_fetch_array ($avg_r, MYSQLI_ASSOC);
?>

	<table style='width:50%' align=center>
	<tr>
	<td>
	
	<!-- picture, floats to the right) -->
	<a href=<?php echo $row['picture']; ?> target='_blank'><img class='zoom' align=right height=200px src="<?php echo $row['picture']; ?>"></a>
	<a class="gamename"><strong><font size=24px><?php echo $row['game_name']; ?></font></strong></a>
	<strong><a class="gamename"><font size=24px> - $<?php echo $row['price']; ?></a></font></strong><br>
	<?php
		// The 'add-to-cart' button (ripped from the index.php)
		if(isset($_SESSION['username'])){
			echo "<a href='shoppingcart.php?prod=" . $row['game_id'] . "'><img class='zoom' src='images/sys/cartAdd.png' width='100'/></a>";
		} else {
			echo "<a href='login.php?prod=" . $row['game_id'] . "'><img class='zoom' src='images/sys/cartAdd.png' width='100'/></a>";
			//echo $row['game_id']; //testing 
			$_SESSION['temp-game'] = $row['game_id'];
		}
	?>
	
	<br>
	
	
	<br>
	<a class="gamename"><i><small>Console: </i></small><?php echo $row['console']; ?></a><br>
	<a class="gamename"><i><small>Release Year: </i></small><?php echo $row['release_date']; ?></a><br>
	<a class="gamename"><i><small>Publisher: </i></small><?php echo $row['publisher']; ?></a><br>
	<a class="gamename"><i><small>Developer: </i></small><?php echo $row['developer']; ?></a><br>
	<br>
	<?php 
		echo $row['game_desc']; 
		echo "<br>"; 
		
		// Is the current user an admin? If yes, let them edit the game's details
		if ($admin_result[0] == 1) {
			echo "<br><strong><a class=\"submit\" href=\"editgame.php?game_id={$row['game_id']}\">Edit</a></strong>";
		}
	?>
		<!-- dont work right, lets stick wiht the above version for now --
		<table style='padding:5px' style='width:100%' align='left'>
		<tr>
			<td style='padding:3px'>
				<strong><a class="gamename">$<?php echo $row['price']; ?></a></strong><br>
			</td>
			<td style='padding:3px'>
				<a class="gamename"><i><small>Console: </i></small><?php echo $row['console']; ?></a><br>
				<a class="gamename"><i><small>Release Year: </i></small><?php echo $row['release_date']; ?></a><br>
			</td>
			<td style='padding:3px'>
				<a class="gamename"><i><small>Publisher: </i></small><?php echo $row['publisher']; ?></a><br>
				<a class="gamename"><i><small>Developer: </i></small><?php echo $row['developer']; ?></a><br>
			</td>
		</tr>
		<tr>
			<td colspan=3>
				
			}
			</td>
		</tr>
		</table>
		-->


	
	</td>
	</tr>
	<tr>
		<td>
			<br>
			<?php
			// This php block handles the printing out of all the reviews for this game
			
			// The function used to print out mushrooms
			function printMushrooms($count){
				for ($mushrooms = $count; $mushrooms >= 1; $mushrooms--) {
					echo "<img src=\"images/sys/mushroom.png\" height='25'>";
				} 
			}
			
			// Query to fetch reviews for this game
			$review_query = "SELECT g.game_name, CONCAT(c.first_name, ' ', c.last_name) as 'name', r.review_desc, r.review_date, r.rating
				FROM customer c, game g, review r
				WHERE c.customer_id = r.customer_id
				AND g.game_id = r.game_id
				AND r.game_id = $game_id
				ORDER BY r.review_date DESC";
			$review_rows = mysqli_query($link, $review_query);
			
			// Are there any rows?
			if($review_rows){
				$row_count=mysqli_num_rows($review_rows);
				
				if ($row_count > 0) {
					//echo "<h2>Customer reviews</h2>";
					echo "<table id='viewcart' id='history' width=200px><th><center> Customer reviews </center></th></table>";
					echo "Average rating: <strong>" . number_format($avg_r_row['avg'],1) . " out of 5 mushrooms</strong>";
					echo "<br><br>";
				} else {
					echo "<h2>No reviews yet...</h2>";
				}
				
				//Fetch rows
				while($review_row=mysqli_fetch_array($review_rows)){
					echo "<a class='gamename'>By <strong>" . $review_row[name] . "</strong>, on ";
					echo $review_row[review_date] . "</a>";
					echo "<br>";
					echo "<i>" . $review_row[review_desc] . "</i>";
					echo "<br>";
					
					// Mushrooms - echo out 1-5 mushrooms based on the rating column (see function below)
					printMushrooms($review_row['rating']);
					
					/* old version 
					if ($review_row['rating'] == 5) {
						// print out 5 mushrooms 
						printMushrooms(5);
					} else if ($review_row['rating'] == 4) {
						// print out 4 mushrooms 
						printMushrooms(4);
					} else if ($review_row['rating'] == 3) {
						// print out 3 mushrooms 
						printMushrooms(3);
					} else if ($review_row['rating'] == 2) {
						// print out 2 mushrooms 
						printMushrooms(2);
					} else if ($review_row['rating'] == 1) {
						// print out 1 mushroom
						printMushrooms(1);
					} else {
						// poison mushroom?
					}
					*/
					
					echo "<br><br>";
				}
			}
			?>
		</td>
	</tr>	
	</table>
	
<?php
	mysqli_close($dbc);
	include('includes/footer.html');
?>

</body>