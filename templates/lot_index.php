<h2><?= $lot['name']; ?></h2>
<div class="lot-item__content">
  <div class="lot-item__left">
    <div class="lot-item__image">
      <img src="img/uploads/<?= $lot['picture']; ?>" width="730" height="548" alt="Сноуборд">
    </div>
    <p class="lot-item__category">Категория: <span><?= $lot['category_name']; ?></span></p>
    <p class="lot-item__description"><?= $lot['description']; ?></p>
  </div>
  <div class="lot-item__right">
    <div class="lot-item__state">
      <div class="lot-item__timer timer">
        10:54:12
      </div>
      <div class="lot-item__cost-state">
        <div class="lot-item__rate">
          <span class="lot-item__amount">Текущая цена</span>
          <span class="lot-item__cost"><?= format_price($lot['price']); ?></span>
        </div>
        <div class="lot-item__min-cost">
          Мин. ставка <span><?= format_price(min_bet($bids_count, $lot['price'], $lot['bet_step'])); ?></span>
        </div>
      </div>
      <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post">
        <p class="lot-item__form-item">
          <label for="cost">Ваша ставка</label>
          <input id="cost" type="number" name="cost" placeholder="<?= format_price__without_r(min_bet($bids_count, $lot['price'], $lot['bet_step'])); ?>">
        </p>
        <button type="submit" class="button">Сделать ставку</button>
      </form>
    </div>
    <div class="history">
      <h3>История ставок (<span><?= $bids_count; ?></span>)</h3>
      <table class="history__list">
      <?php foreach ($bids as $key => $bet): ?>
        <tr class="history__item">
          <td class="history__name"><?= $bet['name']; ?></td>
          <td class="history__price"><?= format_price__without_r($bet['amount']); ?> р</td>
          <td class="history__time"><?= $bet['date']; ?></td>
        </tr>
      <?php endforeach; ?>
      </table>
    </div>
  </div>
</div>
