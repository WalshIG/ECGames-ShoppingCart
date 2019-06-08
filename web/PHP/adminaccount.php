<?php 
	session_start();
	$_SESSION['pagename'] = "EC Games - Register";
	include "header.html";
	include('mysqli_connect.php');
	
	//Checks whether or not they have accepted the privacy policy. If they have not agreed, kill the page
	$policy_session = $_SESSION['has_agreed'];
	if($policy_session == 0){
		if(isset($_SESSION['username'])){
			die('ERROR: Please agree to the privacy policy');
		}
	}
		// receive all input values from the form
		$username = $_POST['username'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$dob = $_POST['dob'];
		$phone_number = $_POST['phone_number'];
		$email = $_POST['email'];
		$street = $_POST['street'];
		$city = $_POST['city'];
		$region = $_POST['region'];
		$country = $_POST['country'];
		$postal_code = $_POST['postal_code'];
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
		$fear = $_POST['fear'];
		
		if(isset($_POST['register_btn'])){//If the register button is pressed
			//ERROR HANDLING CODE
			if ($username == "" || $first_name == "" || $last_name == "" || $dob == "" || $phone_number == "" || $email == "" || $street == "" || $city == "" || $region == "" || $country == "" || $postal_code == "" || $password == "" || $password2 == "" || $fear == "") {
				?>
				<script type="text/javascript">
					window.alert("Text fiels can not be empty.");
				</script>
				<?php
			} else if(!isset($_POST['privacyPolicyCheckbox'])) {// Privacy Policy checkbox is not checked{
				?>
				<script type="text/javascript">
					window.alert("You must agree to the privacy policy");
				</script>
				<?php
			} else if(!preg_match('/[1-2][0-9][0-9][0-9]-[0-9][0-9]-[0-3][0-9]/', $dob)){//If the date format is not correct
				?>
				<script type="text/javascript">
					window.alert("Please match the specified date format: YYYY-MM-DD");
				</script>
				<?php
			} else{//Create the account
				if ($password == $password2) {//If the passwords match, insert into database.
					//$password = md5($password);
					//$password2= md5($password2);
					
					//Insert script
					$sql = "INSERT INTO customer (
					first_name, 
					last_name, 
					dob, 
					phone_number, 
					email, 
					street, 
					city, 
					region, 
					country, 
					postal_code, 
					password, 
					username,
					admin_privilege,
					has_agreed,
					last_login,
					fear) VALUES(
					'$first_name', '$last_name', '$dob', '$phone_number', '$email', '$street', '$city', '$region', '$country', UCASE('$postal_code'), '$password', '$username', 1, 1,NOW(), '$fear')";
				
				$result = mysqli_query($link, $sql);
				
				if($result){
					unset($_SESSION['username']);
					$_SESSION['message'] = "You are now logged in!";
					$_SESSION['username'] = $username;
					$_SESSION['has_agreed'] = 1;
					
					//Query runs and returns customer ID, then sets it for the session
					$customer_id = "SELECT customer_id FROM customer WHERE username = '$username'";
					$customer_id_query = mysqli_query($link, $customer_id);
					$customer_id_result = $customer_id_query->fetch_array(MYSQLI_NUM);
					$_SESSION['customer_id'] = $customer_id_result[0];
				?>
					<script type="text/javascript">
						window.alert("Welcome to EC Games!");
					</script>
				<?php
					header('Refresh: 0; URL=index.php');
				}
				else {
					die("Error creating account");
				}
			}
		}
	}
	
	
		
?>
	
<!doctype html>
<html>
<center>
<form method="POST" action="adminaccount.php">
	<table>
		<tr>
			<td>Username: </td>
			<td><input type="username" name="username" class="textInput"
			placeholder="Enter username"></td>
		</tr>
		<tr>
			<td>First Name: </td>
			<td><input type="first_name" name="first_name" class="textInput" placeholder="Enter your first name"></td> 
		</tr>
		<tr>
			<td>Last Name: </td>
    		<td><input type="last_name" name="last_name" class="textInput" placeholder="Enter your last name"></td>	
   		</tr>
	    <tr>
    		<td>Date of birth (YYYY-MM-DD): </td>
 		   	<td><input type="dob"  name="dob" class="textInput" 
 		   	placeholder="Enter your date of birth"></td>
		</tr>
		<tr>      
    		<td>Phone Number: </td>
    		<td><input type="phone_number" name="phone_number" class="textInput" placeholder="Enter your phone number"></td>
		</tr>
		<tr>
 			<td>Email: </td>
    		<td><input type="email" name="email" class="textInput"
    		placeholder="Enter your email" ></td>
		</tr>
    	<tr>    
    		<td>Street Name: </td>
    		<td><input type="street" name="street" class="textInput" placeholder="Enter your street name" ></td>
		</tr>
   		<tr> 
    		<td>City: </td>
    		<td><input type="city" name="city" class="textInput"
    	placeholder="Enter your city"></td>
		</tr>
		<tr>      
    		<td>Region: </td>
    		<td><input type="region" name="region" class="textInput"
    		placeholder="Enter your region"></td>  
		</tr>
		<tr>      
    		<td>Country: </td>
    		<td><input type="country" name="country"  class="textInput"
    		placeholder="Enter your country"></td>
		</tr>
		<tr>   
    		<td>Postal Code: </td>
    		<td><input type="postal_code" name="postal_code" class="textInput" placeholder="Enter your postal code"></td>
		</tr>
		<tr>   
    		<td>Password: </td>
    		<td><input type="password" name="password" class="textInput"
    		placeholder="Enter your password"></td>
		</tr>
		<tr>   
    		<td>Password confirmation: </td>
    		<td><input type="password" name="password2" class="textInput" 
    		placeholder="Confirm your password"></td>
		</tr>
		<tr>   
    		<td>Biggest Fear: </td>
    		<td><input type="text" name="fear" class="textInput" 
    		placeholder="Spiders"></td>
		</tr>
		<br>
		<p>
			Already a member? <a href="login.php">Sign in</a>
		</p>
		</br>
	</table>
	<br>
	<!-- privacy policy agreement table -->
	<table with = 15%>
		<tr>
			<td>Click here to confirm that you have read and agree to the <a href="privacyPolicy.php" target="_blank">Privacy Policy Agreement</a>.</td>
			<td><input type ="checkbox" name="privacyPolicyCheckbox" value="unchecked"></td>
		</tr>
	</table>
	
	<br>
	
	<input type="submit" name="register_btn" class="redbutton" value="Register"></td>
</center>
</form>
</body>
</html>