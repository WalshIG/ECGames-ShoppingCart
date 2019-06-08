<html>
    <head>
        <?php
			session_start();
			$_SESSION['pagename'] = "EC Games - Logout";
			include "header.html";
			session_destroy();
			unset($_SESSION['username']);
			unset($_SESSION['has_agreed']);
			//header('location:login.php');
			echo "<center>";
			echo "You have successfully logged out";
			echo  "<h4>To log back in: <a href='login.php'>LOGIN</a></h4>";
			echo "</center>";
		?>
		
		<title>ECGames Web App</title>
        <link rel="stylesheet" type="text/css" href="style.css">
	</head>
</body>
<?php 
		include "footer.html";
?>
</html>
