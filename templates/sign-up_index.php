<?php $classname = count($errors) ? " form--invalid" : ""; ?>
<form class="form container<?= $classname; ?>" action="sign-up.php" method="post" enctype="multipart/form-data">
  <h2>Регистрация нового аккаунта</h2>
  <?php $form_item = formatFormItem($errors['email'], $user['email']); ?>
  <div class="form__item<?= $form_item['classname']; ?>">
    <label for="email">E-mail*</label>
    <input id="email" type="text" name="user[email]" placeholder="Введите e-mail" value="<?= $form_item['value']; ?>">
    <?= $form_item['error']; ?>
  </div>
  <?php $form_item = formatFormItem($errors['password'], $user['password']); ?>
  <div class="form__item<?= $form_item['classname']; ?>">
    <label for="password">Пароль*</label>
    <input id="password" type="password" name="user[password]" placeholder="Введите пароль" >
    <?= $form_item['error']; ?>
  </div>
  <?php $form_item = formatFormItem($errors['name'], $user['name']); ?>
  <div class="form__item<?= $form_item['classname']; ?>">
    <label for="name">Имя*</label>
    <input id="name" type="text" name="user[name]" placeholder="Введите имя" value="<?= $form_item['value']; ?>">
    <?= $form_item['error']; ?>
  </div>
  <?php $form_item = formatFormItem($errors['message'], $user['message']); ?>
  <div class="form__item<?= $form_item['classname']; ?>">
    <label for="message">Контактные данные*</label>
    <textarea id="message" name="user[message]" placeholder="Напишите как с вами связаться" ><?= $form_item['value']; ?></textarea>
    <?= $form_item['error']; ?>
  </div>
  <?php $form_item = formatFormItem($errors['picture']); ?>
  <div class="form__item form__item--file form__item--last<?= $form_item['classname']; ?>">
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
    <?= $form_item['error']; ?>
  </div>
  <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
  <button type="submit" class="button">Зарегистрироваться</button>
  <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
