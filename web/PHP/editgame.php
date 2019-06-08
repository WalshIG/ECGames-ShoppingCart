<html>
<head>
<title>Edit a Game </title>
</head>

<body>

<?php
session_start();
//include('includes/header.html');

$_SESSION['pagename'] = "EC Games!";
include "header.html";

// If they are logged in AND logged in as an administrator
if (isset($_SESSION['username']) && $admin_result[0] == 1) {

	include ('mysqli_connect.php');
	// check to see if it is a POST (which means we need to UPDATE)
	if (isset($_POST['game_id'])) {
		// code to do an UPDATE
		$game_id=$_POST['game_id'];
		$game_name = $_POST['game_name'];
		$game_desc = $_POST['game_desc'];
		$console = $_POST['console'];
		$release_date = $_POST['release_date'];
		$publisher = $_POST['publisher'];
		$developer = $_POST['developer'];
		$price = $_POST['price'];
		$uquery = "UPDATE game 
			SET	game_name=\"$game_name\",
				game_desc=\"$game_desc\",
				console=\"$console\", 
				release_date=$release_date,
				publisher=\"$publisher\",
				developer=\"$developer\",
				price=$price
			WHERE game_id=$game_id";
		
		// RUN THE QUERY
		//echo "The UPDATE query is: $uquery<br />";
		if (mysqli_query($dbc, $uquery)) {
			//echo "Game details updated successfully! <br />";
		?>
		<script type="text/javascript">
			window.alert("Game details have been succesfully altered!");
		</script>
		<?php
		header('Refresh: 0; URL=index.php');
		} else {
			echo "Error updating game: <br>" . mysqli_error($dbc);
			// debugging
			echo '<br><br><strong><font color=red><i>DEBUG</i><BR>';
			echo $uquery;
			echo '</font></strong>';
		}
		echo "<strong><a href='index.php'>Back to Home Page</a></strong>";
		
		//$r = mysqli_query ($dbc, $uquery);

		//echo "HERE WE ARE AT A POST with userid: $userid <br />";
		//echo "Post a messrelease_date of success after UPDATE";
	} else { // OTHERWISE it's a GET and we need to SELECT
		$game_id = $_GET['game_id'];
		// show that we have the userid
		$query = "SELECT * from game WHERE game_id = $game_id";
		//echo "The query is: $query <br />";
		
		$r = mysqli_query ($dbc, $query);
		$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
		?>
		
		
		<!-- The form/table that contains the details you want to edit about the game -->
		<form action="editgame.php" method="POST">
			<table align=center id='editgame' style="width:50%">
			<tr> <!-- First row: header -->
				<th colspan=3><center>Edit Game: Details</center></th>
			</tr>
			<tr> <!-- Second row: Game name, Console, and Release Year -->
				<td style="padding:3px;"><center><p>Game Name:<br><input type="text" name="game_name" value="<?php echo $row['game_name']; ?>" /></p></center></td>
				<td style="padding:3px;"><center><p>Console:<br><input type="text" name="console" value="<?php echo $row['console']; ?>" /></p></center></td>
				<td style="padding:3px;"><center><p>Year:<br><input type="text" name="release_date" value="<?php echo $row['release_date']; ?>"/></p></center></td>
			</tr>
			<tr> <!-- Third row: Publisher, Developer, and Price -->
				<td style="padding:3px;"><center><p>Publisher:<br><input type="text" name="publisher" value="<?php echo $row['publisher']; ?>"/></p></center></td>
				<td style="padding:3px;"><center><p>Developer:<br><input type="text" name="developer" value="<?php echo $row['developer']; ?>" /></p></center></td>
				<td style="padding:3px;"><center><p>Price:<br><input type="text" name="price" value="<?php echo $row['price']; ?>"/></p></center></td>
			</tr>
			<tr> <!-- Fourth row: Game Description and picture-->
				<td style="padding:3px;" colspan=2><center><p>Game Description:<br><textarea name="game_desc" rows="7" cols="50"><?php echo $row['game_desc']; ?></textarea></p></center>
				<input type="hidden" name="game_id" value="<?php echo $row['game_id']; ?>" />
				<center><input type="submit" value="UPDATE GAME!" /></center></td>
				</td>
				<td style="padding:3px;" colspan=1><center><img class='zoom' align=center height=200px src="<?php echo $row['picture']; ?>"></td>
			</tr>
			</table>

			
		
		
		</form>
	<?php
	}// END Of IF statement to check if POST or GET
	mysqli_close($dbc);
	}else {
		echo  "<h4>You are not logged in as an administrator. Please <a href='login.php'>LOG IN</a></h4>";
	}
	include('includes/footer.html');
	?>

</body>