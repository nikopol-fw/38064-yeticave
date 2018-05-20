<?php
require_once "functions.php";

$page_title = "Добавление лота";

$is_auth = (bool) rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

$errors = [];

date_default_timezone_set("Europe/Moscow");

$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "yeti_cave";

$db_conf = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (!$db_conf) {
  $error = mysqli_connect_error();
  $page_content = "<p>Ошибка подключения: " . $error . "</p>";

  $layout_content = renderTemplate("templates/add_layout.php", ['page_title' => $page_title, 'content' => $page_content]);
  print($layout_content);

  exit(1);
}

mysqli_set_charset($db_conf, "utf8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $lot = $_POST['lot'];

  $required = ['title', 'category', 'description', 'start_price', 'bet_step', 'end_date'];

  foreach ($required as $field) {
    if (empty($_POST['lot'][$field])) {
      $errors[$field] = "Это поле надо заполнить";

      switch ($field) {
        case 'title':
          $errors[$field] = "Введите наименование лота";
          break;

        case 'category':
          $errors[$field] = "Выберите категорию";
          break;

        case 'description':
          $errors[$field] = "Напишите описание лота";
          break;

        case 'start_price':
          $errors[$field] = "Введите начальную цену";
          break;

        case 'bet_step':
          $errors[$field] = "Введите шаг ставки";
          break;

        case 'end_date':
          $errors[$field] = "Укажите дату завершения торгов";
          break;

        default:
          break;
      }
    } else {
      switch ($field) {
        case 'start_price':
          if (!filter_var($_POST['lot'][$field], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
            $errors[$field] = "Укажите целое число больше 0";
          }
          break;
        
        case 'bet_step':
          if (!filter_var($_POST['lot'][$field], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
            $errors[$field] = "Укажите целое число больше 0";
          }
          break;

        case 'end_date':
          $end_date = date_create_from_format("Y-m-d", $_POST['lot']['end_date']);

          if (!$end_date) {
            $errors[$field] = "Укажите дату в корректном формате";
          } else {
            date_time_set($end_date, 0, 0, 0);

            $now_date = date_create("now");
            date_time_set($now_date, 0, 0, 0);

            $diff = date_diff($end_date, $now_date);

            $utm_end_date = date_timestamp_get($end_date);
            $utm_diff_date = date_timestamp_get($now_date);

            if (!(($utm_end_date > $utm_diff_date) && (($diff->d >= 1) || ($diff->m >= 1) || ($diff->y >= 1)))) {
              $errors[$field] = "Дата окончания торгов должна быть больше текущей даты хотя бы на один день";
            }
          }
          break;

        default:
          break;
      }
    }
  }

  if (empty($_FILES['picture']['name'])) {
    $errors['picture'] = "Загрузите изображение лота";
  } else {
    $tmp_name = htmlspecialchars($_FILES['picture']['tmp_name']);
    $name = htmlspecialchars($_FILES['picture']['name']);

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_type = finfo_file($finfo, $tmp_name);

    if (!($file_type == "image/jpeg" || $file_type == "image/png")) {
      $errors['picture'] = "Загрузите изображение в формате jpg/jpeg или png";
    } else {
      list($img_width, $img_height) = getimagesize($_FILES['picture']['tmp_name']);

      if ($img_width > 1024 || $img_height > 768) {
        $errors['picture'] = "Загружаемое изображение не должно превышать размеров 1024x768";
      }
    }
  }

  if (count($errors)) {
    $sql = "SELECT `categories`.`id`, `categories`.`name` "
        . "FROM `categories`;";

    $result = mysqli_query($db_conf, $sql);

    if (!$result) {
      $error = mysqli_error($db_conf);
      $page_content = "<p>Ошибка MySQL: " . $error . "</p>";
    } else {
      $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
      $page_content = renderTemplate("templates/add_index.php", ['lot' => $lot, 'errors' => $errors, 'categories' => $categories]);
    }
  } else {
    $upload_path = __DIR__ . "/img/uploads/";
    $extension = pathinfo($name, PATHINFO_EXTENSION);
    $uniq_name = bin2hex(random_bytes(16)) . "." . $extension;

    if (move_uploaded_file($tmp_name, $upload_path . $uniq_name)) {
      $sql = "INSERT INTO `lots` (`name`, `description`, `picture`, `creation_date`, `end_date`, `start_price`, `bet_step`, `author`, `category`)"
          . "VALUES (?, ?, ?, NOW(), ?, ?, ?, 1, ?)";

      $stmt = mysqli_prepare($db_conf, $sql);
      mysqli_stmt_bind_param($stmt, 'ssssiii', htmlspecialchars($lot['title']), htmlspecialchars($lot['description']), $uniq_name, htmlspecialchars($lot['end_date']), htmlspecialchars($lot['start_price']), htmlspecialchars($lot['bet_step']), htmlspecialchars($lot['category']));
      $result =  mysqli_stmt_execute($stmt);

      if (!$result) {
        $error = mysqli_error($db_conf);
        $page_content = "<p>Ошибка MySQL: " . $error . "</p>";
      } else {
        $lot_id = mysqli_insert_id($db_conf);
        header("Location: lot.php?id=" . $lot_id);
      }
    } else {
      $page_content = "<p>Файл не может быть записан на сервер. Пожалуйста, обратитесь к администратору сайта</p>";
    }
  }
} else {
  $sql = "SELECT `categories`.`id`, `categories`.`name` "
      . "FROM `categories`;";

  $result = mysqli_query($db_conf, $sql);

  if (!$result) {
    $error = mysqli_error($db_conf);
    $page_content = "<p>Ошибка MySQL: " . $error . "</p>";
  } else {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $page_content = renderTemplate("templates/add_index.php", ['lot' => $lot, 'errors' => $errors, 'categories' => $categories]);
  }
}

$layout_content = renderTemplate("templates/add_layout.php", ['page_title' => $page_title, 'content' => $page_content]);

print($layout_content);

?>
