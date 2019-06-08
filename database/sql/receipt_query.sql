SELECT c.first_name, 
c.last_name, 
c.phone_number, 
c.email, 
c.street, 
c.city, 
c.region, 
c.country, 
c.postal_code,
o.order_id,
o.purchase_date,
g.game_name,
op.game_id,
op.quantity,
op.price,
(op.quantity * op.price) as 'subtotal'
FROM customer c, orders o, order_product op, game g;
WHERE c.customer_id = o.customer_id
AND o.order_id = op.order_id
AND g.game_id = op.game_id;