<!doctype html>
<html>
<?php
	include "header.html";
	session_start();
	include('mysqli_connect.php');
?>
	<?php
	//Variable declarations
	$customer_id = $_SESSION['customer_id'];
	$update_customer1 = "UPDATE customer SET has_agreed = 1 WHERE customer_id = $customer_id";
	$update_customer0 = "UPDATE customer SET has_agreed = 0 WHERE customer_id = $customer_id";
	
	//If the username for the session is set
	if (isset($_SESSION['username'])){
		// If the agree button is clicked
		if (isset($_POST["Agree"])) {
			$update_customer_query1 = mysqli_query($link, $update_customer1);
			
			//If the query was successful, alert the user then go to the home page
			if($update_customer_query1){
				$_SESSION['has_agreed'] = 1;
				?>
				<script type="text/javascript">window.alert("Thank you for accepting our terms");</script>
				
				<?php
				//this line
				header('Refresh: 0; URL=http://deepblue.cs.camosun.bc.ca/~cst178/199_dev/index.php');
			}else {
				echo "something went wrong";
			}
		} 
		//If the disagree button is clicked
		if (isset($_POST["Disagree"])) {
			$update_customer_query0 = mysqli_query($link, $update_customer0);
			
			//If the query was successful, alert the user then go to the home page
			if($update_customer_query0){
				$_SESSION['has_agreed'] = 0;
				?>
				<script type="text/javascript">window.alert("Sorry, you need to agree to use the website");</script>
				
				<?php
				//this line
				header('Refresh: 0; URL=http://deepblue.cs.camosun.bc.ca/~cst178/199_dev/index.php');
			}else {
				echo "something went wrong";
			}
		}
	} else {
		?>
			<script type="text/javascript">
				window.alert("Please sign in or create an account before accepting the privacy policy");
			</script>
		<?php
		header('Refresh: 0; URL=http://deepblue.cs.camosun.bc.ca/~cst178/199_dev/login.php');
	}
	?>


	</body>
	<?php
		include "footer.html";
	?>
	</html>