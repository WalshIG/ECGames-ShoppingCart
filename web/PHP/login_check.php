<!DOCTYPE html>
<html>
    <?php 
	session_start();
	$_SESSION['pagename'] = "Login";
		include "header.html";
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
<script type="text/javascript">
	function validation() {
		// VALIDATION CODE HERE!
		if (document.getElementByID('username').value == '') {
			alert("You must include a username!");
			window.stop();
		}

		if (document.getElementByID('password').value == '') {
			alert("You must include a password!");
			window.stop();
		}

		if (document.getElementByID('password').value.length < 4) {
			alert("Password must have at least 4 characters!");
			window.stop();
		}
	
	}
	
</script>

<div class="container">
  <form action="login.php" method="POST">
    <center> 
		<!-- Username field -->
		<label for="userName">Username: </label>
		<input type="text" id="username" name="username" placeholder="Enter your username">
		<br>
		<br>
		
		<!-- Password field -->
		<label for="password">Password: </label>
		<input type="password" id="password" name="password" placeholder="Enter your password">
		<br>
		<br>
		 <!---- --->
		<!-- Terms and Conditions notice -->
		By logging into this site, you agree to our <a href="privacyPolicy.php" target="_blank">privacy policy</a>.
		<br>
		<br>

		<!-- Submit button -->
		<input type="submit" value="Submit">
		
		<!-- Sign-up -->		
		<h4> Not a member yet? <a href="registration.php">Sign Up!</a></h4>
	</center>
    
  </form>
</div>

<form action="login.php" method="POST" onsubmit= "return validation()";>
</body>
    <?php 
	include "footer.html";
	?>
</html>