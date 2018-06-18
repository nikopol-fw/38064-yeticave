<?php $classname = count($errors) ? " form--invalid" : ""; ?>
<form class="form container<?= $classname; ?>" autocomplete="off" action="sign-up.php" method="post" enctype="multipart/form-data">
  <h2>Регистрация нового аккаунта</h2>
  <?php
    $classname = '';
    $error = '';

    if (isset($errors['email'])):
      $classname = ' form__item--invalid';
      $error = '<span class="form__error">' . $errors['email'] . '</span>';
    endif;

    $value = isset($user['email']) ? $user['email'] : '';
  ?>
  <div class="form__item<?= $classname; ?>">
    <label for="email">E-mail*</label>
    <input id="email" type="text" name="user[email]" placeholder="Введите e-mail" value="<?= $value; ?>" required>
    <?= $error; ?>
  </div>
  <?php
    $classname = '';
    $error = '';

    if (isset($errors['password'])):
      $classname = ' form__item--invalid';
      $error = '<span class="form__error">' . $errors['password'] . '</span>';
    endif;

    $value = isset($user['password']) ? $user['password'] : '';
  ?>
  <div class="form__item<?= $classname; ?>">
    <label for="password">Пароль*</label>
    <input id="password" type="password" name="user[password]" placeholder="Введите пароль" required>
    <?= $error; ?>
  </div>
  <?php
    $classname = '';
    $error = '';

    if (isset($errors['name'])):
      $classname = ' form__item--invalid';
      $error = '<span class="form__error">' . $errors['name'] . '</span>';
    endif;

    $value = isset($user['name']) ? $user['name'] : '';
  ?>
  <div class="form__item<?= $classname; ?>">
    <label for="name">Имя*</label>
    <input id="name" type="text" name="user[name]" placeholder="Введите имя" value="<?= $value; ?>" required>
    <?= $error; ?>
  </div>
  <?php
    $classname = '';
    $error = '';

    if (isset($errors['message'])):
      $classname = ' form__item--invalid';
      $error = '<span class="form__error">' . $errors['message'] . '</span>';
    endif;

    $value = isset($user['message']) ? $user['message'] : '';
  ?>
  <div class="form__item<?= $classname; ?>">
    <label for="message">Контактные данные*</label>
    <textarea id="message" name="user[message]" placeholder="Напишите как с вами связаться" required><?= $value; ?></textarea>
    <?= $error; ?>
  </div>
  <?php
    $classname = '';
    $error = '';

    if (isset($errors['message'])):
      $classname = ' form__item--invalid';
      $error = '<span class="form__error">' . $errors['message'] . '</span>';
    endif;
  ?>
  <div class="form__item form__item--file form__item--last<?= $classname; ?>">
    <label>Аватар</label>
    <div class="preview">
      <button class="preview__remove" type="button">x</button>
      <div class="preview__img">
        <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
      </div>
    </div>
    <div class="form__input-file">
      <input class="visually-hidden" type="file" id="photo2" name="avatar" value="">
      <label for="photo2">
        <span>+ Добавить</span>
      </label>
    </div>
    <?= $error; ?>
  </div>
  <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
  <button type="submit" class="button">Зарегистрироваться</button>
  <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
