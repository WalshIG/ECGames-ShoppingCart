<html>
<head>
<title>EC Games - Edit Mode </title>
</head>
<body>
<?php
session_start();
include('includes/header.html');
if (isset($_SESSION['username'])) {

	include ('mysqli_connect.php');
	// check to see if it is a POST (which means we need to UPDATE
	if (isset($_POST['game_id'])) {
		// code to do an UPDATE
		$game_id=$_POST['game_id'];
		$game_name = $_POST['game_name'];
		$game_desc = $_POST['game_desc'];
		$console = $_POST['console'];
		$release_date = $_POST['release_date'];
		$publisher = $_POST['publisher'];
		$developer = $_POST['developer'];
		$price = $_PRICE['price'];
		$picture = $_PICTURE['picture'];
		$uquery =	"UPDATE trees SET 
						game_name='$game_name', 
						game_desc='$game_desc',
						console='$console',
						release_date='$release_date',
						publisher='$publisher',
						developer='$developer',
						price='$price',
						picture='$picture',
						description='$description' 
					WHERE game_id=$game_id";
		
		// RUN THE QUERY
			//echo "The UPDATE query is: $uquery<br />";
		if (mysqli_query($dbc, $uquery)) {
			echo "Record updated successfully <br />";
		} else {
			echo "Error updating record: " . mysqli_error($dbc);
		}
		echo "<strong><a href='view_trees.php'>Back to Trees</a></strong>";
		
		//$r = mysqli_query ($dbc, $uquery);

		//echo "HERE WE ARE AT A POST with userid: $userid <br />";
		//echo "Post a messrelease_date of success after UPDATE";
	} else { // OTHERWISE it's a GET and we need to SELECT
		$game_id = $_GET['game_id'];
		// show that we have the userid
		$query = "SELECT * from trees WHERE game_id = $game_id";
		//echo "The query is: $query <br />";
		
		$r = mysqli_query ($dbc, $query);
		$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
		?>
		<form action="edit_tree.php" method="POST">
		<p>TREE NAME: <input type="text" name="game_name" value="<?php echo $row['game_name']; ?>"/></p>
		<p>LATIN NAME: <input type="text" name="game_desc" value="<?php echo $row['game_desc']; ?>" /></p>
		<p>DESCRIPTION: <textarea name="description" rows="4" cols="50"><?php echo $row['description']; ?></textarea></p>
		<input type="hidden" name="game_id" value="<?php echo $row['game_id']; ?>" />
		<input type="submit" value="UPDATE RECORD" />
		</form>
	<?php
	}// END Of IF statement to check if POST or GET
	mysqli_close($dbc);
}else {
	echo  "<h4>You are not logged in. Please <a href='loginex.php'>LOG IN</a></h4>";
}
include('footer.html');		
?>

</body>