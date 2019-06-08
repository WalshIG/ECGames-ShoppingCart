<!DOCTYPE html>


<form action="login.php" method="POST" onsubmit= "return validation()";>
</body>
<?php
	session_start();
	$_SESSION['pagename'] = "EC Games - Login";
	include "header.html";
	include('mysqli_connect.php');
?>
<html>
<div class="container">
  <form action="index.php" method="POST">
    <center> 
		<!-- Username field -->
		<label for="username">Username: </label>
		<input type="text" id="username" name="username" placeholder="Enter your username">
		<br>
		<br>
		
		<!-- Password field -->
		<label for="password">Password: </label>
		<input type="password" id="password" name="password" placeholder="Enter your password">
		<br>
		<br>

		<!-- Submit button -->
		<input type="submit" value="Submit">
		
		<!-- Sign-up -->		
		<h4> Not a member yet? <a href="registration.php">Sign Up!</a></h4>
	</center>
    
  </form>
</div>
</body>
</html>
        
<?php
	//Variable declarations
	$username = $_POST['username'];
	$password = $_POST['password'];
	$customer_id = $_POST['customer_id'];
	
	// create query
	$query = "SELECT * FROM customer WHERE username='$username' AND password = '$password'";
	
	// run query
	$row = @mysqli_query ($dbc, $query); // Run the query.
	$has_agreed = "SELECT has_agreed FROM customer WHERE username = '$username'";
	
	// If the number of rows is only one
	if (mysqli_num_rows($row) == 1) {
		//set the username for the session
		$_SESSION['username'] = $username;
		$username = $_SESSION['username'];

		//Query runs and returns customer ID, then sets it for the session
		$customer_id = "SELECT customer_id FROM customer WHERE username = '$username'";
		$customer_id_query = mysqli_query($link, $customer_id);
		$customer_id_result = $customer_id_query->fetch_array(MYSQLI_NUM);
		
		$_SESSION['customer_id'] = $customer_id_result[0];

		//Query runs and returns if the customer has agreed to the privacy policy
		$has_agreed_query = mysqli_query($link, $has_agreed);
		$has_agreed_result = $has_agreed_query->fetch_array(MYSQLI_NUM);
		
		//Gets the customer ID from the table
		$getinfo = "SELECT customer_id from customer where username = '$username'";
		$customer_info = mysqli_query($link, $getinfo);
		$result0 = $customer_info->fetch_array(MYSQLI_NUM);
		
		//Sets customer ID for the session
		$customer_id = $result0[0];
		$_SESSION['customer_id'] = $customer_id;
		
		//Insert the current date into the database
		$update_login = "UPDATE customer SET last_login = NOW() WHERE customer_id = $customer_id";
		$update_login_query = mysqli_query($link, $update_login);
		
		//If the user has agreed to the privacy policy
		if($has_agreed_result[0] == 1){
			//Sets the privacy agreement in the session
			$_SESSION['has_agreed'] = 1;
			$bool_has_agreed = $_SESSION['has_agreed'];
			
			$selected = $_SESSION['selected'];
			?>
			<script type="text/javascript">
				window.alert("You have logged in successfully");
			</script>
			<?php 
			
			//Here is where we want to redirect
			if($selected > 0){
				header('Refresh: 0; URL=http://deepblue.cs.camosun.bc.ca/~cst178/199_dev/shoppingcart.php?prod=' . $selected);
				unset($_SESSION['selected']);
			} else {
				header('Refresh: 0; URL=http://deepblue.cs.camosun.bc.ca/~cst178/199_dev/index.php');
			}
			
		} else{ //They have not agreed
			//echo "has not agreed";
			
			//Sets the privacy agreement in the session
			$_SESSION['has_agreed'] = 0;
			$bool_has_agreed = $_SESSION['has_agreed'];
			
			//echo "USERNAME IS " . $username;
			?>
							
			<script type="text/javascript">
				var username = "<?php echo $username; ?>";
				window.alert("Welcome! " + username);
				window.alert("Please agree to our privacy policy before proceeding");
			</script>
			
			<?php
			//echo $bool_has_agreed;
			header('Refresh: 0; URL=http://deepblue.cs.camosun.bc.ca/~cst178/199_dev/privacyPolicy.php');
		}
			
	 } else { // Not a match! 
	 ?>
			
		<?php
		//echo  "<p> <h4>Invalid login credentials. Please <a href='login.php'>TRY AGAIN</a></p> </h4>";
	}  
?> 
<?php
	$selected = '';
	// Is the URL defined?
	if (isset($_GET['prod'])) {
		// You are now um handling the selected product
		$selected = $_GET['prod'];
		$_SESSION['selected'] = $selected;
	}
?>
<?php 
	include "footer.html";
?>
