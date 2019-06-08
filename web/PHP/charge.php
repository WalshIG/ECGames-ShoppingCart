<?php
  require_once('config.php');
  session_start();
  include 'header.html';
  include "mysqli_connect.php";

  $token  = $_POST['stripeToken'];
  $email  = $_POST['stripeEmail'];
  $customer_id = $_SESSION['customer_id'];
  $total_amount = $_SESSION['order_total'];
  
  $customer = \Stripe\Customer::create(array(
      'email' => $email,
      'source'  => $token
  ));

  $charge = \Stripe\Charge::create(array(
      'customer' => $customer->id,
      'amount'   => $total_amount * 100,
      'currency' => 'cad'
  ));
	
	//Success message
  echo "<h1>Successfully charged $" . number_format($_SESSION['order_total'],2) . "</h1>";
  
  /*
  //Gets everything from the user's cart
  $get_cart = "SELECT * from cart WHERE customer_id = $customer_id";
  $get_cart_result =  mysqli_query($link, $get_cart);
  */

  //Select all items from the cart
  $result=mysqli_query($link, 
				"SELECT customer_id, game_id, quantity
				FROM cart
				WHERE customer_id = $customer_id
				");	
  //Inserts into orders
  $add_to_order = mysqli_query($link, "INSERT INTO orders (customer_id, purchase_date, order_status) VALUES ($customer_id, curdate(), 'closed')");

  //Insert into order_product
  while($row = mysqli_fetch_array($result)) {
	$game_id = $row['game_id'];
	$quantity = $row['quantity'];
	$insert_sql_first = "INSERT INTO order_product(order_id, game_id, quantity, price) VALUES ((SELECT MAX(order_id) FROM orders),$game_id, $quantity,(SELECT price FROM game WHERE game_id = $game_id))";
	$insert_result = mysqli_query($link, $insert_sql_first);
  }
  
  $_SESSION['checkout_price'] = $total_amount;
  //Deletes all items from cart
  $delete_all = mysqli_query($link,"DELETE FROM cart
	WHERE customer_id = $customer_id");

  //unset($_SESSION['order_total']);
  header('Refresh: 2; URL=receipt.php');
?>