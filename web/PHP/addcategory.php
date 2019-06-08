<?php
	echo '<form action="addcategory.php" method="POST" enctype="multipart/form-data">';
	echo '<link rel="stylesheet" type="text/css" href="stylesheet.css">';
	
	
	session_start();
	$_SESSION['pagename'] = "EC Games - Add Category";
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
		//
		$category = $_POST['category'];
		$category_query = "SELECT COUNT(category_name) FROM category WHERE category_name = '$category'";
		$sql_insert_category = "INSERT INTO category(category_name) VALUES ('$category')";
		
		//If the field is blank
		if($category == ""){
			die ("ERROR: text can not be blank");
		}
		//Connection with the select query
		$con_cat_query = mysqli_query($link, $category_query);

		//Returns the number of matching columns (0 or 1)
		$cat_result = $con_cat_query->fetch_array(MYSQLI_NUM);
		
		//If there is a result, it is already in the database
		if($cat_result[0] == 1 ){
			die ("ERROR: category already exists");
		}
		
		//Try to insert
		$result = mysqli_query($link, $sql_insert_category);
		
		//Check if successful
		if($result){
			echo "<br>";
			echo "Category Inserted";
		}
		else{
			echo "<br>";
			echo "Error inserting category";
		}
		echo"</center>";
	}
?>