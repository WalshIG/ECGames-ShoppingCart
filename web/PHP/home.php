<?php 
	session_start();
	include "header.html";
?>
<!doctype html>
<html>

<h1> Home </h1>
    
<div> <h4> Welcome <?php echo $_SESSION['username']; ?> <a href="shoppingcart.php"> click here to shop </a> </h4>
</div>		
</body>
</html>