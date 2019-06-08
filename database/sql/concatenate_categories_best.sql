/* ==========================================
=== Display each game and it's categories ===
=== (using group concatenation) =============
========================================== */
SELECT g.game_id AS '#', release_date as 'Year', g.game_name AS 'Game', GROUP_CONCAT(c.category_name SEPARATOR ', ') AS 'Genres', console AS 'Console'
FROM ICS199Group01_dev.game g, ICS199Group01_dev.category c, ICS199Group01_dev.category_has_game cg
WHERE c.category_id = cg.category_id
AND cg.game_id = g.game_id
GROUP BY cg.game_id
ORDER BY game_name;