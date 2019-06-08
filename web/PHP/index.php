<!DOCTYPE html>
 <html>
 <head>
	<link rel="stylesheet" href="index.css">
</head>
 <?php 
	session_start();
	$_SESSION['pagename'] = "EC Games!";
	include "header.html";
	include('mysqli_connect.php');
	$search_text = $_GET['searchtext'];
	$policy_session = $_SESSION['has_agreed'];
	
	//If they have not agreed, kill the page
	if($policy_session == 0){
		if(isset($_SESSION['username'])){
			die('ERROR: Please agree to the privacy policy');
		}
	}
	
	if (isset($_GET) && $_GET["Genres"]) {//If the get method is set and there is a genre.
		$category = $_GET["Genres"];
	}
	echo "<center><h3>Recommended Game:</h3></center>";
	$recommended_result=mysqli_query($link, "SELECT g.game_id, g.game_name, g.game_desc, GROUP_CONCAT(c.category_name SEPARATOR ', ') 
											AS 'genres', g.console, release_date, g.publisher, g.developer, g.price, g.picture 
												FROM ICS199Group01_dev.game g, ICS199Group01_dev.category c, ICS199Group01_dev.category_has_game cg
													WHERE c.category_id = cg.category_id 
														AND cg.game_id = g.game_id 
														AND g.game_id = (SELECT game_id FROM order_product WHERE order_id = (SELECT MAX(order_id) FROM order_product))
															GROUP BY cg.game_id 
															ORDER BY game_name ASC");
															
	if($recommended_result){
		$game_table_start = "<table id='viewcart' align=center id='history' style='width:75%'>
		<tr><center>
		<th><center>Box Art </center></th>
        <th><center>Game Details</center></th>
        <th><center>Add to Cart</center></th>
		</tr>";
		//Count records
		$row_count=mysqli_num_rows($recommended_result);
		
	
			// Create the header for the game detail table
			echo $game_table_start;
			
			// Fetch rows
			while($row=mysqli_fetch_array($recommended_result)){
				echo'<tr>';
					echo'<td>';
						echo "<center><a href='gamepage.php?game_id=" . $row[game_id] . "' ><img class='zoom' src='$row[picture]' height='200'/></a></center>";
					echo'</td>';			
				
				echo'<td style="padding:5px;">';
				echo "<strong><a class=\"gamename\">" . $row['game_name'] . "</a></strong>";
				
				//Are they an administrator? If so, give the option to 'hide' or 'edit' the game from the database
				if ($admin_result[0] == 1) {
					//echo "<font color=red><strong><i> game id: " . $row['game_id'] . " </font></i></strong>"; //***** debugging *****
					echo "<br><br>";
					//echo "<strong><i><a href=\"editgame.php?game_id={$row['game_id']}\"> [Edit Game]</a></i></strong>";	//old version
					echo "<strong><a class=\"submit\" href=\"editgame.php?game_id={$row['game_id']}\">Edit</a></strong>";	
					echo " ";
					// Hide does the same thing as edit right now. We may not have enough time to implement hiding
					//echo "<strong><a class=\"submit\" href=\"editgame.php?game_id={$row['game_id']}\">Hide</a></strong>";
				}
				
				echo "<br><br>";
				echo $row['game_desc'];
				echo "<br><br>";
				
				
				echo "<table align=left id='invisible'>";
					echo "<tr>";
						echo "<td style=padding:5px><i><small>Release Year: " . "</small>" . $row['release_date'] . "</td>";
						echo "<td style=padding:5px><i><small>Platform: " . "</small>" . $row['console'] . "</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td style=padding:5px><i><small>Publisher: " . "</small>" . $row['publisher'] . "</td>";
						echo "<td style=padding:5px><i><small>Developer: " . "</small>" . $row['developer'] . "</td>";
					echo "</tr>";
					echo "<tr>";
						if($category != 0){
							echo "<td style=padding:5px><i><small>Genres: " . "</small>" . $category_name['category_name'] . "</td>";
						} else {
							echo "<td style=padding:5px><i><small>Genres: " . "</small>" . $row['genres'] . "</td>";
						}
						echo "<td style=padding:5px><i><small>Price: " . "</small><strong>$" . $row['price'] . "</strong></td>";
					echo "</tr>";
					
				echo "</table>";
				
				// echo the "add to cart button"
				echo '<td>';
				if(isset($_SESSION['username'])){
					echo "<center><a href='shoppingcart.php?prod=" . $row['game_id'] . "'><img class='zoom' src='images/sys/cartAdd.png' width='100'/></a></center>";
				} else {
					echo "<a href='login.php?prod=" . $row['game_id'] . "'><img class='zoom' src='images/sys/cartAdd.png' width='100'/></a>";
					//echo $row['game_id']; //testing 
					$_SESSION['temp-game'] = $row['game_id'];
				}
				echo '</td>';
				
				echo'</tr>';
				echo '</table><br>';
				echo '</center>';
		}// Fetch all
	} else {
		echo "Error";
	}
	
	if (!isset($category) || $category==0) {//If the category is not set display all otherwise select the category
		if($search_text == ""){//If the user does not enter a search value
			$result=mysqli_query($link, "SELECT g.game_id, g.game_name, g.game_desc, GROUP_CONCAT(c.category_name SEPARATOR ', ') 
											AS 'genres', g.console, release_date, g.publisher, g.developer, g.price, g.picture 
												FROM ICS199Group01_dev.game g, ICS199Group01_dev.category c, ICS199Group01_dev.category_has_game cg 
													WHERE c.category_id = cg.category_id AND cg.game_id = g.game_id GROUP BY cg.game_id ORDER BY game_name ASC");
		} else{//They enter a search value, the database is searched for a matching game title.
			$result=mysqli_query($link, "SELECT g.game_id, g.game_name, g.game_desc, GROUP_CONCAT(c.category_name SEPARATOR ', ')
											AS 'genres', g.console, release_date, g.publisher, g.developer, g.price, g.picture 
												FROM ICS199Group01_dev.game g, ICS199Group01_dev.category c, ICS199Group01_dev.category_has_game cg 
													WHERE c.category_id = cg.category_id AND cg.game_id = g.game_id AND g.game_name LIKE '%$search_text%' GROUP BY cg.game_id ORDER BY game_name ASC");
		}
	} else {
		$category_name = mysqli_query($link,
			'SELECT category_name FROM category WHERE category_id = ' . $category);
		$category_name = mysqli_fetch_array($category_name);
		  
		 if($search_text == ""){//If the user does not enter a search value
			 $result=mysqli_query($link,
										'SELECT * FROM game g
										 INNER JOIN category_has_game chg ON g.game_id = chg.game_id
										 WHERE chg.category_id = ' . $category . ' ORDER BY g.game_name ASC');
		 } else{//Search for the game that matches the category and the name
			 $result=mysqli_query($link,
										"SELECT * FROM game g
										 INNER JOIN category_has_game chg ON g.game_id = chg.game_id
										 WHERE chg.category_id = $category AND g.game_name LIKE '%$search_text%' ORDER BY g.game_name ASC");
		 }
	}
?>

<center><h3>Search By Game Title:</h3></center>


<form name="searchproduct" action="index.php" method="GET">
  <center> <select name='Genres'>
	<option value="0" <?php if ($category == 0){echo'selected';} ?>>All</option>

<?php
	// ===== This code Dynamically Generates the categories in the drop-down box before search ===== //

	
	//Dynamically generate all the categories and category values
	$query = "SELECT * FROM category ORDER BY category_name ASC";
    $rows = mysqli_query($link, $query);
	$genre = $_POST['genre'];
	
	// Post a category in the drop down menu each time
	while($row = mysqli_fetch_array($rows)) {
		$cat_id = $row['category_id'];
		$cat_name = $row['category_name'];
		echo "<option value=$cat_id <?php if ($category == $cat_id){echo 'selected';} ?>$cat_name</option>";
	}
?>

</select>
<input type="text" placeholder="Search By Name" name="searchtext" id="searchtext"></input>
<input type= "submit" value="Search">
</center>
<br>
</form>

<?php
// Start of the table where the games we sell are displayed
$game_table_start = "<table id='viewcart' align=center id='history' style='width:75%'>
    <tr>
		<th><center>Box Art </center></th>
        <th><center>Game Details</center></th>
        <th><center>Add to Cart</center></th>
    </tr>";
?>

 <?php
	
	if($result){
	//Count records
	$row_count=mysqli_num_rows($result);
	
		// The # of records found, displayed underneath the header
		echo '<table align=center id=history><th style=padding:5px;>';
		if(isset($_GET) && $_GET["Genres"]){
			echo'<center><i> Found '.$row_count.' record(s) for: ' . $category_name['category_name'] . ' </i></center>';
		} else {
			echo "<center><i>Displaying All Results.</i></center>";
		}
		echo '</th></table>';
		
		// Create the header for the game detail table
		echo $game_table_start;
		
		// Fetch rows
		while($row=mysqli_fetch_array($result)){
			echo'<tr>';
				echo'<td>';
					echo "<center><a href='gamepage.php?game_id=" . $row[game_id] . "' ><img class='zoom' src='$row[picture]' height='200'/></a></center>";
				echo'</td>';			
			
            echo'<td style="padding:5px;">';
			echo "<strong><a class=\"gamename\">" . $row['game_name'] . "</a></strong>";
			
			//Are they an administrator? If so, give the option to 'hide' or 'edit' the game from the database
			if ($admin_result[0] == 1) {
				//echo "<font color=red><strong><i> game id: " . $row['game_id'] . " </font></i></strong>"; //***** debugging *****
				echo "<br><br>";
				//echo "<strong><i><a href=\"editgame.php?game_id={$row['game_id']}\"> [Edit Game]</a></i></strong>";	//old version
				echo "<strong><a class=\"submit\" href=\"editgame.php?game_id={$row['game_id']}\">Edit</a></strong>";	
				echo " ";
				// Hide does the same thing as edit right now. We may not have enough time to implement hiding
				//echo "<strong><a class=\"submit\" href=\"editgame.php?game_id={$row['game_id']}\">Hide</a></strong>";
			}
			
			echo "<br><br>";
			echo $row['game_desc'];
			echo "<br><br>";
			
			
			echo "<table align=left id='invisible'>";
				echo "<tr>";
					echo "<td style=padding:5px><i><small>Release Year: " . "</small>" . $row['release_date'] . "</td>";
					echo "<td style=padding:5px><i><small>Platform: " . "</small>" . $row['console'] . "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td style=padding:5px><i><small>Publisher: " . "</small>" . $row['publisher'] . "</td>";
					echo "<td style=padding:5px><i><small>Developer: " . "</small>" . $row['developer'] . "</td>";
				echo "</tr>";
				echo "<tr>";
					if($category != 0){
						echo "<td style=padding:5px><i><small>Genres: " . "</small>" . $category_name['category_name'] . "</td>";
					} else {
						echo "<td style=padding:5px><i><small>Genres: " . "</small>" . $row['genres'] . "</td>";
					}
					echo "<td style=padding:5px><i><small>Price: " . "</small><strong>$" . $row['price'] . "</strong></td>";
				echo "</tr>";
				
			echo "</table>";
			
			// echo the "add to cart button"
			echo '<td>';
			if(isset($_SESSION['username'])){
				echo "<a href='shoppingcart.php?prod=" . $row['game_id'] . "'><img class='zoom' src='images/sys/cartAdd.png' width='100'/></a>";
			} else {
				echo "<a href='login.php?prod=" . $row['game_id'] . "'><img class='zoom' src='images/sys/cartAdd.png' width='100'/></a>";
				//echo $row['game_id']; //testing 
				$_SESSION['temp-game'] = $row['game_id'];
			}
			echo '</td>';
			
			echo'</tr>';
		}// Fetch all
	}
	mysqli_free_result($result);
	mysqli_close($link);
			
?>
</table>

<?php include "footer.html"; ?>

<BR><BR>

</html>
