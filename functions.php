<?php

function timeLot($end_date)
{
	$time_lot = strtotime($end_date) - time();

	$time_days = floor($time_lot / 86400);
	$time_hours = floor(($time_lot % 86400) / 3600);
	$time_minutes = floor((($time_lot % 86400) % 3600) / 60);

	$get_time = $time_hours . ':' . $time_minutes;

	$word = 'дней';

	$days = $time_days % 10;
	$days_2 = $time_days % 100;

	if ($days === 1) {
		$word = 'день';
	} else if ($days >= 2 && $days <= 4) {
		$word = 'дня';
	}

	if ($days_2 >= 10 && $days_2 <= 14) {
		$word = 'дней';
	}

	$date = date($time_days . html_entity_decode('&nbsp;') . $word . ' h:i ', strtotime($get_time));

	return $date;
}

function format_price($price)
{
  $price = htmlspecialchars($price);
  $price_formatted = ceil($price);

  if ($price_formatted > 999) {
    $price_formatted = number_format($price_formatted, 0, '', ' ');
  }

  $price_formatted = $price_formatted . ' <b class="rub">р</b>';

  return $price_formatted;
}

function format_price__without_r($price)
{
  $price = htmlspecialchars($price);
  $price_formatted = ceil($price);

  if ($price_formatted > 999) {
    $price_formatted = number_format($price_formatted, 0, '', ' ');
  }

  return $price_formatted;
}

function min_bet($bids_count, $price, $bet_step)
{
	$price = htmlspecialchars($price);
	$min_price = $price;

	if ($bids_count > 0) {
		$bet_step = htmlspecialchars($bet_step);
		$min_price = ceil($price) + ceil($bet_step);
	}

	return $min_price;
}

function renderTemplate($templatePath, $templateData)
{
	$content = '';
	extract($templateData);

	if (file_exists($templatePath)) {
		ob_start();
		require($templatePath);
		$content = ob_get_contents();
		ob_end_clean();
	}

	return $content;
}

function formatFormItem ($error, $value = null)
{
	$value = htmlspecialchars($value);

	$format = [
		'classname' => '',
		'error' => '',
		'value' => ''
	];

	if (isset($error)) {
		$format['classname'] = ' form__item--invalid';
		$format['error'] = '<span class="form__error">' . $error . '</span>';
	}

	if (isset($value)) {
		$format['value'] = $value;
	}

	return $format;
}
