<?php
	echo '<form action="removecategory.php" method="POST" enctype="multipart/form-data">';
	echo '<link rel="stylesheet" type="text/css" href="stylesheet.css">';
	
	
	session_start();
	$_SESSION['pagename'] = "EC Games - Remove Category";
	include "header.html";
	include "mysqli_connect.php";
	
	echo"<center>";
	//Query the server for the username
	$username = $_SESSION['username'];
	$admin_query = "SELECT admin_privilege FROM customer WHERE username = '$username'";
	$check_admin = mysqli_query($link, $admin_query);
	
	//This returns the value from the query
	$admin_result = $check_admin->fetch_array(MYSQLI_NUM);

	//If they are not administrator, display the back button
	if($admin_result[0] != 1){
		echo "<a href='index.php' class='button' input type='button' value='Back'>Home</a> <br> <br>";
	}
	
	//Also kill the page if they are not an administrator
	if($admin_result[0] != 1){
		die('ERROR: please log in as administrator');
	}
	
	//Text field and buttons
	echo "<p>Category: <input type='text' name='category' id='category'></p>";
	echo '<input type="submit" name="submit" value="Submit"/>';
	echo "<a href='addgame.php' class='button'><input type='button' value='Back'/></a>";

	//If the submit button is pressed, error checking, then try to insert
	if(isset($_POST['submit'])){
		$category = $_POST['category'];
		$sql_delete_category = "DELETE FROM category WHERE category_name = '$category'";
		
		//If the field is blank
		if($category == ""){
			die ("ERROR: text can not be blank");
		}
		//Connection with the select query
		$con_cat_query = mysqli_query($link, $sql_delete_category);

		//Returns the number of matching columns (0 or 1)
		//$cat_result = $con_cat_query->fetch_array(MYSQLI_NUM);
		

		//Check if successful
		if($con_cat_query){
			echo "<br>";
			echo "Category Removed";
		}
		else{
			echo "<br>";
			echo "Error removing category";
		}
	}
	echo"</center>";
?>