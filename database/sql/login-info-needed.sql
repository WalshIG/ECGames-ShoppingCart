SELECT * FROM ICS199Group01_dev.customer;

/* information needed for Login */
SELECT CONCAT(first_name, ' ', last_name) AS 'customer', username, password FROM customer;