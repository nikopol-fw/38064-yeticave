<?php
require_once "functions.php";

$site_title = "YetiCave";

$is_auth = (bool) rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

$categories = [];


date_default_timezone_set("Europe/Moscow");

$db_host = "localhost";
$db_user = "root";
$db_password = "9562_9562";
$db_name = "yeti_cave";

$db_conf = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$db_conf) {
  $error = "Ошибка подключения: " . mysqli_connect_error();
  $page_content = "<p>Ошибка MySQL: " . $error . "</p>";
} else {
  mysqli_set_charset($db_conf, "utf8");

  $sql = "SELECT DISTINCT `lots`.`id`, `lots`.`name`, `start_price`, `picture`, MAX(IF(`amount` IS NULL, `start_price`, `amount`)) AS `price`, COUNT(`lot`) AS `bids_number`, `categories`.`name` AS `category_name`, `creation_date` "
        . "FROM `lots` "
        . "LEFT JOIN `bids` ON `lots`.`id` = `bids`.`lot` "
        . "INNER JOIN `categories` ON `lots`.`category` = `categories`.`id` "
        . "WHERE CURRENT_TIMESTAMP() < `end_date` "
        . "GROUP BY `lots`.`id`, `lots`.`name`, `start_price`, `picture`, `creation_date`, `category` "
        . "ORDER BY `creation_date` DESC;";

  $result = mysqli_query($db_conf, $sql);

  if (!$result) {
    $error = mysqli_error($db_conf);
    $page_content = "<p>Ошибка MySQL: " . $error . "</p>";
  } else {
    $goods = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $page_content = renderTemplate("templates/index.php", ['goods' => $goods]);
  }

  $sql = "SELECT `name` FROM `categories`;";

  $result = mysqli_query($db_conf, $sql);

  if (!$result) {
    $error = mysqli_error($db_conf);
    $categories = [['name' => '<p>Ошибка MySQL: ' . $error . '</p>']];
  } else {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
  }
}


$layout_content = renderTemplate("templates/layout.php", ['site_title' => $site_title, 'content' => $page_content, 'is_auth' => $is_auth, 'user_name' => $user_name, 'user_avatar' => $user_avatar, 'categories' => $categories]);

print($layout_content);

?>
