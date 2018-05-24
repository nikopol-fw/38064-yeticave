<?php $classname = count($errors) ? " form--invalid" : ""; ?>
<form class="form form--add-lot container<?= $classname; ?>" action="" method="post" enctype="multipart/form-data">
  <h2>Добавление лота</h2>
  <div class="form__container-two">
    <?php $classname = "";
    $error = "";
    if (isset($errors['title'])):
      $classname = " form__item--invalid";
      $error = "<span class=\"form__error\">" . $errors['title'] . "</span>";
    endif;
    $value = isset($lot['title']) ? htmlspecialchars($lot['title']) : ""; ?>
    <div class="form__item<?= $classname; ?>">
      <label for="lot-name">Наименование</label>
      <input id="lot-name" type="text" name="lot[title]" placeholder="Введите наименование лота" value="<?= $value; ?>">
      <?= $error; ?>
    </div>
    <?php $classname = "";
    $error = "";
    if (isset($errors['category'])):
      $classname = " form__item--invalid";
      $error = "<span class=\"form__error\">" . $errors['category'] . "</span>";
    endif;
    $option_none = isset($lot['category']) ? "" : " selected";
    $value = isset($lot['category']) ? htmlspecialchars($lot['category']) : "";
    ?>
    <div class="form__item<?= $classname; ?>">
      <label for="category">Категория</label>
      <select id="category" name="lot[category]">
        <option value=""<?= $option_none; ?> disabled>Выберите категорию</option>
        <?php foreach ($categories as $key => $category): ?>
        <option value="<?= $category['id']; ?>"<?php
        if ($category['id'] == $value):
          echo " selected";
        endif;
        ?>><?= $category['name']; ?></option>
        <?php endforeach; ?>
      </select>
      <?= $error; ?>
    </div>
  </div>
  <?php $classname = "";
  $error = "";
  if (isset($errors['description'])):
     $classname = " form__item--invalid";
     $error = "<span class=\"form__error\">" . $errors['description'] . "</span>";
  endif;
  $value = isset($lot['description']) ? htmlspecialchars($lot['description']) : ""; ?>
  <div class="form__item form__item--wide<?= $classname; ?>">
    <label for="message">Описание</label>
    <textarea id="message" name="lot[description]" placeholder="Напишите описание лота"><?= $value; ?></textarea>
    <?= $error; ?>
  </div>
  <?php
  $classname = "";
  $error = "";
  if (isset($errors['picture'])):
    $classname = " form__item--invalid";
    $error = "<span class=\"form__error\">" . $errors['picture'] . "</span>";
  endif;
  ?>
  <div class="form__item form__item--file<?= $classname; ?>"> <!-- form__item--uploaded -->
    <label>Изображение</label>
    <div class="preview">
      <button class="preview__remove" type="button">x</button>
      <div class="preview__img">
        <img src="" width="113" height="113" alt="Изображение лота">
      </div>
    </div>
    <div class="form__input-file">
      <input class="visually-hidden" type="file" id="photo2" name="picture" value="">
      <label for="photo2">
        <span>+ Добавить</span>
      </label>
    </div>
    <?= $error; ?>
  </div>
  <div class="form__container-three">
    <?php $classname = "";
    $error = "";
    if (isset($errors['start_price'])):
      $classname = " form__item--invalid";
      $error = "<span class=\"form__error\">" . $errors['start_price'] . "</span>";
    endif;
    $value = isset($lot['start_price']) ? htmlspecialchars($lot['start_price']) : ""; ?>
    <div class="form__item form__item--small<?= $classname; ?>">
      <label for="lot-rate">Начальная цена</label>
      <input id="lot-rate" type="text" name="lot[start_price]" placeholder="0" value="<?= $value ?>">
      <?= $error; ?>
    </div>
    <?php $classname = "";
    $error = "";
    if (isset($errors['bet_step'])):
      $classname = " form__item--invalid";
      $error = "<span class=\"form__error\">" . $errors['bet_step'] . "</span>";
    endif;
    $value = isset($lot['bet_step']) ? htmlspecialchars($lot['bet_step']) : ""; ?>
    <div class="form__item form__item--small<?= $classname; ?>">
      <label for="lot-step">Шаг ставки</label>
      <input id="lot-step" type="text" name="lot[bet_step]" placeholder="0" value="<?= $value; ?>">
      <?= $error; ?>
    </div>
    <?php $classname = "";
    $error = "";
    if (isset($errors['end_date'])):
      $classname = " form__item--invalid";
      $error = "<span class=\"form__error\">" . $errors['end_date'] . "</span>";
    endif;
    $value = isset($lot['end_date']) ? htmlspecialchars($lot['end_date']) : ""; ?>
    <div class="form__item<?= $classname; ?>">
      <label for="lot-date">Дата окончания торгов</label>
      <input class="form__input-date" id="lot-date" type="date" name="lot[end_date]" value="<?= $value; ?>">
      <?= $error; ?>
    </div>
  </div>
  <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
  <button type="submit" class="button">Добавить лот</button>
</form>
