<?php
require_once "functions.php";
require_once "db_config.php";

session_start();

$is_auth = false;
$user_name = "";
$user_avatar = "";

if (isset($_SESSION['user'])) {
  $is_auth = true;
  $user_name = $_SESSION['user']['name'];
  $user_avatar = $_SESSION['user']['avatar'] ? $_SESSION['user']['avatar'] : "img/user_default.png";
}

$page_title = "Лот";
$categories = [];

date_default_timezone_set("Europe/Moscow");


$db_conf = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$db_conf) {
  $error = "Ошибка подключения: " . mysqli_connect_error();
  $page_content = "<p>Ошибка MySQL: " . $error . "</p>";

  $layout_content = renderTemplate("templates/layout.php", ['page_title' => $page_title, 'categories' => $categories, 'content' => $page_content]);
  print($layout_content);

  exit(1);
}

mysqli_set_charset($db_conf, "utf8");

$sql = "SELECT `categories`.`id`, `categories`.`name` "
    . "FROM `categories` "
    . "ORDER BY `categories`.`id` ASC";

$result = mysqli_query($db_conf, $sql);
if (!$result) {
  $error = mysqli_error($db_conf);
  $categories['errors']['name'] = "<p>Ошибка MySQL: " . $error . "</p>";
} else {
  $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}


if (!isset($_GET['id'])) {
  http_response_code(404);
  $page_content = renderTemplate("templates/lot_404.php", []);

  $layout_content = renderTemplate("templates/layout.php", ['page_title' => $page_title, 'categories' => $categories, 'content' => $page_content]);
  print($layout_content);

  exit(1);
} 


$lot_id = intval($_GET['id']);

$sql = "SELECT `lots`.`id`, `lots`.`name`, `lots`.`description`, `lots`.`picture`, `lots`.`end_date`, `lots`.`start_price`, IF(`bids`.`lot` IS NULL, `lots`.`start_price`, MAX(`bids`.`amount`)) AS `price`, `lots`.`bet_step`, `categories`.`name` AS `category_name` "
    . "FROM `lots` "
    . "INNER JOIN `categories` ON `lots`.`category` = `categories`.`id` "
    . "LEFT JOIN `bids` ON `lots`.`id` = `bids`.`lot` "
    . "WHERE `lots`.`id` = " . $lot_id . " "
    . "GROUP BY `lots`.`id`;";

$result = mysqli_query($db_conf, $sql);
if (!$result) {
  $error = mysqli_error($db_conf);
  $page_content = "<p>Ошибка MySQL: " . $error . "</p>";
} else if (!mysqli_num_rows($result)) {
  http_response_code(404);
  $page_content = renderTemplate("templates/lot_404.php", []);
} else {
  $lot = mysqli_fetch_assoc($result);

  $page_title = $lot['name'];

  $sql = "SELECT COUNT(`bids`.`lot`) AS `bids_count` "
      . "FROM `lots` "
      . "LEFT JOIN `bids` ON `lots`.`id` = `bids`.`lot` "
      . "WHERE `lots`.`id` = " . $lot_id . ";";

  $result = mysqli_query($db_conf, $sql);
  if (!$result) {
    $error = mysqli_error($db_conf);
    $bids_count = "<p>Ошибка MySQL: " . $error . "</p>";
  } else {
    $bids_count_arr = mysqli_fetch_assoc($result);
    $bids_count = $bids_count_arr['bids_count'];
  }

  $sql = "SELECT `bids`.`date`, `bids`.`amount`, `users`.`name` "
      . "FROM `bids` "
      . "INNER JOIN `users` ON `bids`.`user` = `users`.`id` "
      . "WHERE `bids`.`lot` = " . $lot_id . " "
      . "ORDER BY `bids`.`date` DESC;";

  $result = mysqli_query($db_conf, $sql);
  if (!$result) {
    $error = mysqli_error($db_conf);
    $bids = [['name' => "<p>Ошибка MySQL: " . $error . "</p>"]];
  } else {
    $bids = mysqli_fetch_all($result, MYSQLI_ASSOC);
  }

  $page_content = renderTemplate("templates/lot_index.php", ['lot' => $lot, 'bids' => $bids, 'bids_count' => $bids_count, 'bids_count' => $bids_count, 'is_auth' => $is_auth]);
}

$layout_content = renderTemplate("templates/layout.php", ['page_title' => $page_title, 'is_auth' => $is_auth, 'user_name' => $user_name, 'user_avatar' => $user_avatar, 'categories' => $categories, 'content' => $page_content]);

print($layout_content);

?>
