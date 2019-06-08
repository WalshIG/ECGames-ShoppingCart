/*==========================================================
=== This is what will displayed on the display cart page ===
=== for customer 1 (minus the alter cart column) ===========
==========================================================*/
SELECT g.game_name AS 'game', g.picture, g.price, (c.quantity) , (g.price * c.quantity) AS 'sub-total'
FROM ICS199Group01_dev.game g, ICS199Group01_dev.cart c
WHERE g.game_id = c.game_id
AND c.customer_id = 1
ORDER BY g.game_name ASC;