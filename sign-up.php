<?php
require_once "functions.php";

$page_title = "Регистрация";

$categories = [];
$errors_post = [];


date_default_timezone_set("Europe/Moscow");

$db_host = "localhost";
$db_user = "root";
$db_password = "9562_9562";
$db_name = "yeti_cave";

$db_conf = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$db_conf) {
  $error = "Ошибка подключения: " . mysqli_connect_error();
  $page_content = "<p>Ошибка MySQL: " . $error . "</p>";

  $layout_content = renderTemplate("templates/sign-up_layout.php", ['page_title' => $page_title, 'content' => $page_content]);
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
  $page_content = "<p>Ошибка MySQL: " . $error . "</p>";
} else {
  $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user = $_POST['user'];

  $required = ['email', 'password', 'name', 'message'];
  $errors_disc = [
    'email' => "Введите e-mail",
    'password' => "Введите пароль",
    'name' => "Введите имя",
    'message' => "Напишите как с вами связаться",
    'email_occupied' => "Аккаунт с таким e-mail уже существует. Укажите другой",
    'password_short' => "Пароль должен содержать не менее 8 символов",
    'name_occupied' => "Пользователь с таким именем уже существует"
  ];
  foreach ($required as $field) {
    if (empty($_POST['user'][$field])) {
      $errors_post[$field] = $errors_disc[$field];
    } else {
      switch ($field) {
        case 'email':
          if (!filter_var($_POST['user'][$field], FILTER_VALIDATE_EMAIL)) {
            $errors_post[$field] = "E-mail указан в некорректном формате";
          } else {
            $safe_email = mysqli_real_escape_string($db_conf, $_POST['user'][$field]);

            $sql = "SELECT `users`.`email` "
                . "FROM `users` "
                . "WHERE `users`.`email` = '$safe_email';";

            $result = mysqli_query($db_conf, $sql);

            if (!$result) {
              $error = mysqli_error($db_conf);
              $page_content = "<p>Ошибка MySQL: " . $error . "</p>";

              $layout_content = renderTemplate("templates/sign-up_layout.php", ['page_title' => $page_title, 'categories' => $categories, 'content' => $page_content]);
              print($layout_content);

              exit(1);
            } elseif (mysqli_num_rows($result) !== 0) {
              $errors_post[$field] = $errors_disc['email_occupied'];
            }
          }
          break;

        case 'name':
          $safe_name = mysqli_real_escape_string($db_conf, $_POST['user'][$field]);

          $sql = "SELECT `users`.`name` "
              . "FROM `users` "
              . "WHERE `users`.`name` = '$safe_name';";

          $result = mysqli_query($db_conf, $sql);

          if (!$result) {
            $error = mysqli_error($db_conf);
            $page_content = "<p>Ошибка MySQL: " . $error . "</p>";

            $layout_content = renderTemplate("templates/sign-up_layout.php", ['page_title' => $page_title, 'categories' => $categories, 'content' => $page_content]);
            print($layout_content);

            exit(1);
          } elseif (mysqli_num_rows($result) !== 0) {
            $errors_post[$field] = $errors_disc['name_occupied'];
          }
          break;

        case 'password':
          if (strlen($_POST['user'][$field]) < 8) {
            $errors_post[$field] = $errors_disc['password_short'];
          }
          break;
        
        default:
          break;
      }
    }
  }

  if (!empty($_FILES['avatar']['name'])) {
    $tmp_name = htmlspecialchars($_FILES['avatar']['tmp_name']);
    $name = htmlspecialchars($_FILES['avatar']['name']);

    $file_type = mime_content_type($tmp_name);

    if (!($file_type == "image/jpeg" || $file_type == "image/png")) {
      $errors_post['picture'] = "Загрузите изображение в формате jpg/jpeg или png";
    }
  }


  if (count($errors_post)) {
    $page_content = renderTemplate("templates/sign-up_index.php", ['errors' => $errors_post, 'user' => $user]);
  } else {
    $password_hash = password_hash($user['password'], PASSWORD_DEFAULT);

    $avatar = null;
    if (!empty($_FILES['avatar']['name'])) {
      $upload_path = __DIR__ . "/img/uploads/users/";
      $extension = pathinfo($name, PATHINFO_EXTENSION);
      $uniq_name = bin2hex(random_bytes(16)) . "." . $extension;

      if (move_uploaded_file($tmp_name, $upload_path . $uniq_name)) {
        $avatar = $uniq_name;
      }
    }

    $page_content = renderTemplate("templates/sign-up_index.php", ['errors' => $errors_post, 'user' => $user]);

    $sql = "INSERT INTO `users` (`registration_date`, `email`, `name`, `password`, `avatar`, `contacts`) "
        . "VALUES (NOW(), ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($db_conf, $sql);
    mysqli_stmt_bind_param($stmt, 'sssss', $user['email'], $user['name'], $password_hash, $avatar, $user['message']);

    if (mysqli_stmt_execute($stmt)) {
      header("Location: /");
      exit();
    } else {
      $error = mysqli_error($db_conf);
      $page_content = "<p>Регистрация неудалась. Ошибка MySQL: " . $error . "</p>";
    }
  }
} else {
  $page_content = renderTemplate("templates/sign-up_index.php", ['errors' => $errors_post]);
}

$layout_content = renderTemplate("templates/sign-up_layout.php", ['page_title' => $page_title, 'categories' => $categories, 'content' => $page_content]);
print($layout_content);

?>
