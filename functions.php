<?php

function time_lot()
{
	$time_tommorow = strtotime('tomorrow');
	$time_lot = $time_tommorow - time();

	$time_hours = floor($time_lot / 3600);
	$time_minutes = floor(($time_lot % 3600) / 60);

	$get_time = $time_hours . ':' . $time_minutes;

	return date('h:i', strtotime($get_time));
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
