<?php

require_once 'functions.php';
require_once 'db_config.php';

session_start();

$is_mainpage = false;
$is_auth = false;
$user_name = '';
$user_avatar = '';

$page_title = 'Все лоты';
$categories = [];
$errors = [];

if (isset($_SESSION['user'])) {
  $is_auth = true;
  $user_name = $_SESSION['user']['name'];
  $user_avatar = $_SESSION['user']['avatar'] ? 'img/uploads/users/' . $_SESSION['user']['avatar']: 'img/user_default.png';
}


date_default_timezone_set('Europe/Moscow');

$db_conf = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$db_conf) {
  $error = 'Ошибка подключения: ' . mysqli_connect_error();
  $page_content = '<p>Ошибка MySQL: ' . $error . '</p>';

  $layout_content = renderTemplate('templates/layout.php', [
    'page_title' => $page_title,
    'is_mainpage' => $is_mainpage,
    'categories' => $categories,
    'content' => $page_content
  ]);

  print($layout_content);
  exit(1);
}

mysqli_set_charset($db_conf, 'utf8');

$sql = "SELECT `categories`.`name` "
    . "FROM `categories` "
    . "ORDER BY `categories`.`id` ASC;";

$result = mysqli_query($db_conf, $sql);

if (!$result) {
  $error = mysqli_error($db_conf);
  $categories['errors']['name'] = '<p>Ошибка MySQL: ' . $error . '</p>';
} else {
  $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if (!isset($_GET['category'])) {
	$sql = "SELECT `lots`.`id`, `lots`.`name`, `categories`.`name` AS `category_name`, `lots`.`picture`, `lots`.`end_date`, `bids_count`.`count`, "
			. "IF (`bids`.`lot` IS NULL, `lots`.`start_price`, MAX(`bids`.`amount`)) AS `price` "
			. "FROM `lots` "
			. "INNER JOIN `categories` "
			. "ON `categories`.`id` = `lots`.`category` "
			. "LEFT JOIN (SELECT COUNT(`bids`.`lot`) AS `count`, `bids`.`lot` "
				. "FROM `bids` "
				. "INNER JOIN `lots` ON `bids`.`lot` = `lots`.`id` "
				. "WHERE `bids`.`lot` = `lots`.`id` "
				. "GROUP BY `bids`.`lot`) AS `bids_count` "
			. "ON `lots`.`id` = `bids_count`.`lot` "
			. "LEFT JOIN `bids` ON `lots`.`id` = `bids`.`lot` "
			. "WHERE `lots`.`end_date` > NOW() "
			. "GROUP BY `lots`.`id`;";
 
	$result = mysqli_query($db_conf, $sql); 
	$lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

	foreach ($lots as $key => $lot) {
		if (!empty($lot['count'])) {
			$lots[$key]['count'] = $lot['count'] . ' ' . formatWordBids(intval($lot['count']));
		} else {
			$lots[$key]['count'] = 'Стартовая цена';
		}

		$lots[$key]['time_left'] = timeLot($lot['end_date']);
	}



} else {
	$category_get = $_GET['category'];
}


$lot_id = intval($_GET['id']);


$page_content = renderTemplate('templates/all-lots.php', ['lots' => $lots]);

$layout_content = renderTemplate('templates/layout.php', [
  'page_title' => $page_title,
  'is_mainpage' => $is_mainpage,
  'is_auth' => $is_auth,
  'user_name' => $user_name,
  'user_avatar' => $user_avatar,
  'categories' => $categories,
  'content' => $page_content
]);

print($layout_content);
