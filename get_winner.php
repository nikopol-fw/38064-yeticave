<?php

$sql = "SELECT * FROM `lots` "
		. "WHERE `lots`.`winner` IS NULL "
		. "AND NOW() >= `lots`.`end_date`;";

$result = mysqli_query($db_conf, $sql);
$lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

//echo "<pre>lots:";
// var_dump($lots);
// echo "</pre>";

foreach ($lots as $key => $lot) {
	// echo "<pre>lot:";
	// var_dump($lot['id']);
	// echo "</pre>";

	$lot_id = $lot['id'];

	$sql = "SELECT * "
			. "FROM `bids` "
			. "JOIN (SELECT MAX(`bids`.`date`) AS `bids_last`, `bids`.`lot` "
				. "FROM `bids` "
				. "WHERE `bids`.`lot` = '$lot_id' "
				. "GROUP BY `bids`.`lot`) AS lot_last "
			. "ON `bids`.`lot` = `lot_last`.`lot` AND `bids`.`date` = `lot_last`.`bids_last`;";

	$result = mysqli_query($db_conf, $sql);
	$bet = mysqli_fetch_assoc($result);
	
	$winner = $bet['user'];

	// echo "<pre>bet:";
	// var_dump($bet);
	// echo "</pre>";

	$sql = "UPDATE `lots` "
			. "SET `lots`.`winner` = '$winner' "
			. "WHERE `lots`.`id` = '$lot_id';";

	// echo "<pre>bet:";
	// var_dump($sql);
	// echo "</pre>";

	$result = mysqli_query($db_conf, $sql);

// Отправка e-mail
	
	
}
