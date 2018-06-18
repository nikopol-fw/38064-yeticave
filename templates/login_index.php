<section class="lot-item container">
<?php if ($is_auth): ?>
  <p><?= $user_name; ?>, добро пожаловать!</p>
<?php else: ?>
  <?php $classname = count($errors) ? ' form--invalid' : ''; ?>
  <form class="form container<?= $classname; ?>" action="" method="post">
    <h2>Вход</h2>
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
      <input id="email" type="text" name="login[email]" placeholder="Введите e-mail" value="<?= $value; ?>">
      <?= $error; ?>
    </div>
    <?php
      $classname = '';
      $error = '';

      if (isset($errors['password'])):
        $classname = ' form__item--invalid';
        $error = '<span class="form__error">' . $errors['password'] . '</span>';
      endif;
    ?>
    <div class="form__item form__item--last<?= $classname; ?>">
      <label for="password">Пароль*</label>
      <input id="password" type="password" name="login[password]" placeholder="Введите пароль" >
      <?= $error; ?>
    </div>
    <button type="submit" class="button">Войти</button>
  </form>
<?php endif; ?>
</section>
