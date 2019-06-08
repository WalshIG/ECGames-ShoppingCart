<!doctype html>
<html>
<?php
	session_start();
	$_SESSION['pagename'] = "Privacy Policy";
	include "header.html";
	include('mysqli_connect.php');
?>
<strong>Privacy Statement</strong>
<br><br>
Your privacy is important to us. This privacy statement explains the personal data that we collect and store, how we processes it, and for what purposes.
<br>
To be able to add items to your cart and purchase them here, you must allow us to accept the following data about you:
<ul>
    <li> Name
    <li> Email
    <li> Home Address
	<li> Biggest Fear
</ul>   
EC Games website collects data from you for the purpose of selling it to <a href="http://camosun.ca/learn/school/trades-technology/bios/cumiskey.html">Jason Cumiskey</a> so that he can build a cybernetic clone of you from that data.
<br><br>
If you do not agree to these terms, you will be unable to use this website. Thank you for your understanding.
<br><br>

<!----Accept and Decline buttons ----->
<form method="POST" action="privacyPolicyLogin.php">

<?php
	$customer_id = $_SESSION['customer_id'];
	//echo "customer_id is: " . $customer_id[0];
	$bool_has_agreed = $_SESSION['has_agreed'];
	
		//display agree
		echo '<input type="submit" name="Agree" value="Agree">';
		//display disagree
		echo '<input type="submit" name="Disagree" value="Disagree">';
		//echo '<a href="http://deepblue.cs.camosun.bc.ca/~cst178/199_dev/index.php" class="button"><input type="button" value="I do not accept"/></a>';
	
	
?>

<!--/form-->



</body>
<?php
	include "footer.html";
?>
</html>