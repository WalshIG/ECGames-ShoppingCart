/* =========================
=== Reset auto increment ===
==========================*/
SELECT MAX(game_id)+1 FROM game; /* Put the number from this... */
ALTER TABLE game AUTO_INCREMENT = /*...into here!*/;