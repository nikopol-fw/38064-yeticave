<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= $user_name; ?></p>
<?php
  $url_in_email = '';
  $url_in_email .= isset($_SERVER['HTTPS']) ? 'https' : 'http';
  $url_in_email .= '://' . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
?>
<p>Ваша ставка для лота <a href="<?= $url_in_email; ?>lot.php?id=<?= $lot_id; ?>"><b>«<?= $lot_name; ?>»</b></a> победила.</p>
<p>Перейдите по ссылке <a href="<?= $url_in_email; ?>my-lots.php">мои ставки</a>, чтобы связаться с автором объявления</p>

<small>Интернет Аукцион «YetiCave»</small>
