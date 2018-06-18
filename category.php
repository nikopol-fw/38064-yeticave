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
$lots = [];
$errors = [];
$lots_pagination = 9;
$no_lots = true;
$no_pagination = true;

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

$categories = getCategories($db_conf);


$cur_page = 1;

if (empty($_GET['category'])) {
	$sql = "SELECT COUNT(*) AS `count` FROM `lots` WHERE `lots`.`end_date` > NOW();";
	$result = mysqli_query($db_conf, $sql);
	$items_count = mysqli_fetch_assoc($result)['count'];

	$pages_count = (int) ceil($items_count / $lots_pagination);

  if (isset($_GET['page'])) {
    if (((int) $_GET['page'] > 0) && ((int) $_GET['page'] <= $pages_count)) {
  		$cur_page = (int) $_GET['page'];
    }
  }

	$offset = ($cur_page - 1) * $lots_pagination;
	$pages = range(1, $pages_count);

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
			. "GROUP BY `lots`.`id` "
			. "ORDER BY `lots`.`id` ASC "
			. "LIMIT $lots_pagination OFFSET $offset;";

	$result = mysqli_query($db_conf, $sql);
	$lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

	if ($lots) {
		$no_lots = false;

		foreach ($lots as $key => $lot) {
			if (!empty($lot['count'])) {
				$lots[$key]['count'] = $lot['count'] . ' ' . formatWordBids((int) $lot['count']);
			} else {
				$lots[$key]['count'] = 'Стартовая цена';
			}

			$lots[$key]['time_left'] = timeLot($lot['end_date']);

			if (timeFinishing($lot['end_date'])) {
				$lots[$key]['time_finishing'] = true;
			}
		}
	}

	if ($pages_count > 1) {
		$no_pagination = false;
	}

	$page_content = renderTemplate('templates/all-lots.php', [
		'no_lots' => $no_lots,
		'no_pagination' => $no_pagination,
		'lots' => $lots,
		'pages' => $pages,
		'cur_page' => $cur_page,
		'pages_count' => $pages_count
	]);

} else {
	$category = (int) $_GET['category'];

	$check = false;
	foreach ($categories as $key => $value) {
		if ((int) $value['id'] === $category) {
			$check = true;
			break;
		}
	}

	if (!$check) {
		http_response_code(404);
		$page_content = renderTemplate('templates/all-lots_404.php', []);

	} else {
		$sql = "SELECT COUNT(*) AS `count` FROM `lots` "
				. "WHERE `lots`.`end_date` > NOW() AND `lots`.`category` = '$category';";

		$result = mysqli_query($db_conf, $sql);
		$items_count = (int) mysqli_fetch_assoc($result)['count'];

		$pages_count = 1;
		if ($items_count > 0) {
			$pages_count = (int) ceil($items_count / $lots_pagination);
		}

    if (isset($_GET['page'])) {
      if (((int) $_GET['page'] > 0) && ((int) $_GET['page'] <= $pages_count)) {
        $cur_page = (int) $_GET['page'];
      }
    }

		$offset = ($cur_page - 1) * $lots_pagination;

		$pages = range(1, $pages_count);


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
				. "WHERE `lots`.`end_date` > NOW() AND `lots`.`category` = '$category' "
				. "GROUP BY `lots`.`id` "
				. "ORDER BY `lots`.`id` ASC "
				. "LIMIT $lots_pagination OFFSET $offset;";

		$result = mysqli_query($db_conf, $sql);
		$lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

		$sql = "SELECT `categories`.`name` FROM `categories` "
				. "WHERE `categories`.`id` = '$category';";

		$result = mysqli_query($db_conf, $sql);
		$category_name = mysqli_fetch_assoc($result)['name'];

		$page_title .= ' в категории «' . $category_name . '»';

		if ($lots) {
			$no_lots = false;

			foreach ($lots as $key => $lot) {
				if (!empty($lot['count'])) {
					$lots[$key]['count'] = $lot['count'] . ' ' . formatWordBids((int) $lot['count']);
				} else {
					$lots[$key]['count'] = 'Стартовая цена';
				}

				$lots[$key]['time_left'] = timeLot($lot['end_date']);

				if (timeFinishing($lot['end_date'])) {
					$lots[$key]['time_finishing'] = true;
				}
			}
		}

		$page_content = renderTemplate('templates/all-lots.php', [
			'no_lots' => $no_lots,
			'no_pagination' => $no_pagination,
			'lots' => $lots,
			'category_id' => $category,
			'category_name' => $category_name,
			'pages' => $pages,
			'cur_page' => $cur_page,
			'pages_count' => $pages_count
		]);
	}
}


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
