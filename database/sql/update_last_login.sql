/*==================================================
=== Updating the last_login column =================
=== with the customer's last login date and time ===
==================================================*/
/* Later on when you log in */
UPDATE customer SET last_login=NOW() WHERE customer_id=$customer_id;

/* Test to see if it works on Bob White (customer_id = 1)*/
UPDATE customer SET last_login=NOW() WHERE customer_id='1';

/* confirm that it worked */
SELECT * FROM customer;