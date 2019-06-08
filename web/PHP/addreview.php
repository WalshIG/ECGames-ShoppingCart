<html>
<head>
<title>Add Review</title>
</head>

<body>

<?php 
//debug variables
$debugred1 = "<small><center><font color=red><i><strong>";
$debugred2 = "</strong></i></font></center></small>";
?>


<?php
	session_start();
	//include('includes/header.html');

	$_SESSION['pagename'] = "EC Games!";
	include "header.html";

	// If they are logged in AND logged in as an administrator
	if (isset($_SESSION['username']) == 1) {

		include ('mysqli_connect.php');
		// check to see if it is a POST (which means we need to UPDATE)
		if (isset($_POST['game_id'])) {
			// code to do an UPDATE
			$game_id=$_POST['game_id'];
			$game_name = $_POST['game_name'];
			$game_desc = $_POST['game_desc'];
			$customer_id=$_SESSION['customer_id'];
			$review_desc = $_POST['review_desc'];
			$rating = $_POST['rating'];
			
			
			
			// This is the query used when inserting the review into the review table
			$uquery = "INSERT INTO review (game_id, customer_id, review_desc, review_date, rating) VALUES ($game_id, $customer_id, \"$review_desc\", CURDATE(), $rating )";
			
			// RUN THE QUERY
			//echo "The UPDATE query is: $uquery<br />";
			if (mysqli_query($dbc, $uquery)) {
				?>
					<script type="text/javascript">
						window.alert("Your review has been added!");
					</script>
				<?php
				header('Refresh: 0; URL=orderhistory.php');
			} else if(!isset($rating)){
				?>
				<script type="text/javascript">
					window.alert("Please rate the game!");
				</script>
				<?php
				header('Refresh: 0; URL=orderhistory.php');
			} else{
				?>
					<script type="text/javascript">
						window.alert("Either you've already written a review for this game, or you exceeded 3000 characters!");
					</script>
				<?php
				header('Refresh: 0; URL=orderhistory.php');
			}
				/* ***the old code for debugging purposes***
				echo "Error updating review: <br>" . mysqli_error($dbc);
				// debugging
				echo '<br><br><strong><font color=orange><i>D E B U G</i><BR>';
				echo $uquery;
				echo '</font></strong>';
				*/
			
			echo "<strong><a href='index.php'>Back to Home Page</a></strong>";
			
			//$r = mysqli_query ($dbc, $uquery);

		} else { // OTHERWISE it's a GET and we need to SELECT
			$game_id = $_GET['game_id'];
			//echo $debugred1 . "game_id: " . $game_id . $debugred2; // *** debugging ***
			
			// The html table-form below uses THIS query to get the picture, game_name, etc...
			//$query = "SELECT g.game_name as 'game_name', g.picture as 'picture', g.game_id as 'game_id', r.review_desc as 'review_desc', r.review_date as 'review_date' FROM game g, review r, customer c WHERE g.game_id = $game_id AND r.game_id = g.game_id"; 
			$query = "SELECT g.game_name as 'game_name', g.picture as 'picture', g.game_id as 'game_id', r.review_desc as 'review_desc', r.review_date as 'review_date' FROM game g, review r, customer c WHERE g.game_id = $game_id"; 
			

			//echo $debugred1 . "The query is: $query <br>" . $debugred2; // *** debugging ***
			
			$r = mysqli_query ($dbc, $query);
			$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
			?>

			<!-- The form/table that contains the details you want to edit about the game -->
			<form action="addreview.php" method="POST">
				<table align=center id='viewcart' style="width:50%">
				<tr> <!-- First row: header -->
					<th colspan=3><center>Write a review for: <?php echo $row[game_name] ?></center></th>
				</tr>
				<tr> <!-- Second row: the box where you type your review, and the game's boxart -->
					<td style="padding:3px;" colspan=2><center><h4><br>
						Please write your review in 3000 characters or less:<br><br>
						<textarea name="review_desc" value="<?php echo $row['review_desc'];?>" rows="9" cols="50"></textarea></h4>
					<input type="hidden" name="game_id" value="<?php echo $row['game_id']; ?>" />
					<h4>Please rate the game:</h4>
					<input type="radio" name="rating" value="1">Terrible...
					<input type="radio" name="rating" value="2">Poor
					<input type="radio" name="rating" value="3">Average
					<input type="radio" name="rating" value="4">Pretty Good!
					<input type="radio" name="rating" value="5">Amazing!!!
					<br>
					<br>
					</center>
					<center><input type="submit" value="Submit!" /></center></td>
					</td>
					<td style="padding:3px;" colspan=1><center><img class='zoom' align=center height=200px src="<?php echo $row['picture']; ?>"></td>
				</tr>
				</table>

				
			
			
			</form>
		<?php
		}// END Of IF statement to check if POST or GET
		mysqli_close($dbc);
	} else {
		echo  "<h4>You are not logged in. Please <a href='login.php'>LOG IN</a></h4>";
	}
	include('includes/footer.html');
?>

</body>