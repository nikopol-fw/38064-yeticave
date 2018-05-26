<section class="lot-item container">
<?php if ($is_auth): ?>
  <p><?= $user_name; ?>, добро пожаловать!</p>
<?php else: ?>
  <?php $classname = count($errors) ? " form--invalid" : ""; ?>
  <form class="form container<?= $classname; ?>" action="" method="post"> <!-- form--invalid -->
    <h2>Вход</h2>
    <?php $form_item = formatFormItem($errors['email'], $user['email']); ?>
    <div class="form__item<?= $form_item['classname']; ?>"> <!-- form__item--invalid -->
      <label for="email">E-mail*</label>
      <input id="email" type="text" name="login[email]" placeholder="Введите e-mail" value="<?= $form_item['value']; ?>">
      <?= $form_item['error']; ?>
    </div>
    <?php $form_item = formatFormItem($errors['password']); ?>
    <div class="form__item form__item--last<?= $form_item['classname']; ?>">
      <label for="password">Пароль*</label>
      <input id="password" type="password" name="login[password]" placeholder="Введите пароль" >
      <?= $form_item['error']; ?>
    </div>
    <button type="submit" class="button">Войти</button>
  </form>
<?php endif; ?>
</section>
