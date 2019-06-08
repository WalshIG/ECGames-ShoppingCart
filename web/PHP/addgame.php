<!doctype html>
<html>
<?php
	session_start();
	$_SESSION['pagename'] = "Add Game"; 
	include "header.html";
	include 'mysqli_connect.php';
?>
<form action="addgame.php" method="POST" enctype="multipart/form-data">
<center>
<body>

<table align=center id='editgame' style="width:50%">

	<tr> <!-- First row: header -->
		<th colspan=3><center>Add Game: Details</center></th>
	</tr>
	<tr> <!-- Second row: Game name, Console, and Release Year -->
		<td style="padding:3px;"><center><p>Game Name: <input type="text" name="gamename"></p></center></td>
		<td style="padding:3px;"><center><p>Console: <input type="text" name="console"></p></center></td>
		<td style="padding:3px;"><center><p>Release Date (Year): <input type="text" name="releasedate"></p></center></td>
	</tr>
	<tr> <!-- Third row: Publisher, Developer, and Price -->
		<td style="padding:3px;"><center><p>Publisher: <input type="text" name="publisher"></p></center></td>
		<td style="padding:3px;"><center><p>Developer: <input type="text" name="developer"></p></center></td>
		<td style="padding:3px;"><center><p>Price: <input type="text" name="price"></p></center></td>
	</tr>
	<tr> <!-- Fourth row: Game Description and picture-->
		<td style="padding:3px;" colspan=2><center><p><p>Description: <input type="text" name="gamedesc"></p></p></center></td>
		<td style="padding:3px;"><center><p>Image: <br><input type="file" name="picture" id="picture"></p></center></td>
	</tr>
	<tr> <!-- Fifth row: Categories-->
		<td style="padding:3px;"><h4><center>Categories:</center></h4>


<?php
	// ===== This code Dynamically Generates the Category check boxes ===== //
	// ===== Thanks John ===== //
	//Checks whether or not they have accepted the privacy policy. If they have not agreed, kill the page
	$policy_session = $_SESSION['has_agreed'];
	if($policy_session == 0){
		if(isset($_SESSION['username'])){
			die('ERROR: Please agree to the privacy policy');
		}
	}
	
	//Variable Declarations
	$username = $_SESSION['username'];
	$admin_query = "SELECT admin_privilege FROM customer WHERE username = '$username'";
	$check_admin = mysqli_query($link, $admin_query);
	
	//This returns the value from the query
	$admin_result = $check_admin->fetch_array(MYSQLI_NUM);

	//Display the back button if they are not an administrator
	if($admin_result[0] != 1){
		echo "<a href='index.php' class='button' input type='button' value='Back'>Back</a> <br> <br>";
	}
	
	//Also kill the page if they are not an administrator
	if($admin_result[0] != 1){
		die('ERROR: please log in as administrator');
	}
	
	//Dynamically generate all the categories and category values
	$query = "SELECT * FROM category ORDER BY category_name ASC";
	$rows = mysqli_query($link, $query);
	$genre = $_POST['genre'];
	
	//Displays the checkbox
	while($row = mysqli_fetch_array($rows)) {
		$cat_id = $row['category_id'];
		$cat_name = $row['category_name'];
		echo "<input type='checkbox' id='genre' name='genre[]' value=$cat_id> ";
		echo "<label for='genre'>$cat_name</label><br>";
	}
	
?>

<br>	
<a href='addcategory.php' class='button'><input type='button' value='Add Category'/></a> <br><br>
<a href='removecategory.php' class='button'><input type='button' value='Remove Category'/></a>
</table>
<br>
<br>
<input type="submit" name="submit" value="Submit"/>

<a href="index.php" class="button"><input type="button" value="Back"/></a>
<br>
<br>
<?php
	//Initial setup for debugging
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);
	//error_reporting(E_ALL);

	//If the submit button is pressed
	if(isset($_POST['submit'])){
		//Variable declarations
		$submit_status = "";
		$target_dir = "images/";
		$target_file = $target_dir . basename($_FILES['picture']['name']);
		$file_name = basename($_FILES['picture']['name']);
		
		$gamename = $_POST['gamename'];
		$gamedesc = $_POST['gamedesc'];
		$console = $_POST['console'];
		$releasedate = $_POST['releasedate'];
		$publisher = $_POST['publisher'];
		$developer = $_POST['developer'];
		$price = $_POST['price'];
		
		//ERROR HANDLING CODE
		if($gamename == "" || $gamedesc == "" || $console == "" || $releasedate == "" || $publisher == "" || $developer == "" || $price == ""){
			?>
			<script type="text/javascript">
				window.alert("Error, text fields can not be empty.");
			</script>
			<?php
			die ("ERROR: "."text fields can not be empty");
		}
		if(!is_numeric($price)){
			?>
			<script type="text/javascript">
				window.alert("Error, price must contain only numbers.");
			</script>
			<?php
			die ("ERROR: "."price must contain only numbers");
		}
		if( (!is_numeric($releasedate)) || ($releasedate < 1957) ){
			?>
			<script type="text/javascript">
				window.alert("Error, the Release date must be a valid integer year after 1957.");
			</script>
			<?php
			die ("ERROR: "."the Release date must be a valid integer year after 1957");
		}
		
		if(file_exists($target_file)){
			?>
			<script type="text/javascript">
				window.alert("Error, file already exists.");
			</script>
			<?php
			die ("ERROR: "."file already exists");
		}
		
		
		//If the post method is requested check if the file is an image
		if(preg_match("/.bng/",$file_name) || preg_match("/.jpg/",$file_name) || preg_match("/.jpeg/",$file_name) || preg_match("/.png/",$file_name)) {
			//echo "File is an image";
		} else {
			?>
			<script type="text/javascript">
				window.alert("Error, file is not an image.");
			</script>
			<?php
			die("ERROR: "."file is not an image");
		}
		
		if (!isset($_POST['genre'])) {
			?>
			<script type="text/javascript">
				window.alert("Error, please select at least one checkbox.");
			</script>
			<?php
			die("ERROR: "."checkbox not set");
		}
		
		//SQL for inserting into the database
		$sql = "INSERT INTO game
		(
		game_name,
		game_desc,
		console,
		release_date,
		publisher,
		developer,
		price,
		picture) VALUES (\"$gamename\",\"$gamedesc\",\"$console\",$releasedate,\"$publisher\",\"$developer\",$price, \"$target_file\")";
		
		//This is the insert for the game data
		$first_result=mysqli_query($link,$sql);
		
		if($first_result){
		?>
		<script type = "text/javascript">
			window.alert("Data successfully inserted");
		</script>
		<?php
		$submit_status = 'Data successfully inserted';
			echo $submit_status;
		} else {
			?>
		<script type = "text/javascript">
			window.alert("Insert Failed");
		</script>
		<?php
			$submit_status = "Insert Failed";
			echo $submit_status;
		}

		//SQL to select recently added game ID
		//Gets the next game id
		$game_id_query = "SELECT MAX(game_id) FROM game";
		$second_result =$link->query($game_id_query);
		
		$row = $second_result->fetch_array(MYSQLI_NUM);
		$game_id = $row[0];
		
		//For each selected genre, insert the game id with the selected genre(s) into the database
		foreach ($genre as $category){
			//echo $category;
			$genreSQL = 
			"INSERT INTO category_has_game 
			(game_id, 
			category_id)
			VALUES ('$game_id', '$category')";
			
			$updateCategory=mysqli_query($link,$genreSQL);
			
			//Check if category was updated
			if($updateCategory){
				//echo "Categories updated";
			}else {
				echo "Category update failed";
			 }
		}
		
		// Try to move the uploaded file:
		if (move_uploaded_file($_FILES['picture']["tmp_name"], $target_file))  {
				echo '<p>Your file has been uploaded.</p>';
			}else{
				echo "Uploading error.";
			}	
	}	
	include "footer.html";	
?>


</center>
</form>
</body>
</html>

