<?php
require_once "functions.php";

$is_auth = (bool) rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

date_default_timezone_set("Europe/Moscow");

$db_host = "localhost";
$db_user = "root";
$db_password = "9562_9562";
$db_name = "yeti_cave";


if (!isset($_GET['id'])) {
  http_response_code(404);
  $page_content = renderTemplate("templates/lot_404.php", []);
} else {
  $lot_id = intval($_GET['id']);

  $db_conf = mysqli_connect($db_host, $db_user, $db_password, $db_name);

  if (!$db_conf) {
    $error = "Ошибка подключения: " . mysqli_connect_error();
    $page_content = "<p>Ошибка MySQL: " . $error . "</p>";
  } else {
    mysqli_set_charset($db_conf, "utf8");

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
    } elseif (!mysqli_num_rows($result)) {
      http_response_code(404);
      $page_content = renderTemplate("templates/lot_404.php", []);
    } else {
      $lot = mysqli_fetch_assoc($result);


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

      $page_content = renderTemplate("templates/lot_index.php", ['lot' => $lot, 'bids' => $bids, 'bids_count' => $bids_count, 'bids_count' => $bids_count]);
    }
  }
}


$layout_content = renderTemplate("templates/lot_layout.php", ['content' => $page_content]);

print($layout_content);

?>
