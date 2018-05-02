<?php
function format_price($price) {

  $price = htmlspecialchars($price);
  $price_formatted = ceil($price);

  if ($price_formatted > 999) {
    $price_formatted = number_format($price_formatted, 0, '', ' ');
  }

  $price_formatted = $price_formatted . ' <b class="rub">р</b>';

  return $price_formatted;
}

function renderTemplate($templatePath, $templateData) {
	$content = '';

	if (file_exists($templatePath)) {
		ob_start();
		require($templatePath);
		$content = ob_get_contents();
		ob_end_clean();
	}

	return $content;
}
?>
