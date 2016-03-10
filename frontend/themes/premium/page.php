<?php

require_once('config-premium.php');

// check if the front page is a static page
if(get_option('show_on_front') == 'page' && get_option('page_on_front') == get_the_ID()){

	// load app
	if ($kit_type == 'classic')
		require_once('template-classic.php');
	else
		require_once('template-wpmp.php');

} else {

	// get the page url
	$pageUrlParam = '';

	if (is_numeric(get_the_ID())) {

		if ($kit_type == 'classic') {

			$permalink = get_permalink();

			if (filter_var($permalink, FILTER_VALIDATE_URL)) {

				$permalink = rawurlencode($permalink);
				$permalink = str_replace('.', '%2E', $permalink);

				$pageUrlParam = '#pageUrl/' . $permalink;
			}

		} else {

			$pageUrlParam = '#page/' . get_the_ID();
		}
	}

	// check if we have a valid domain
	if (isset($arr_config_premium['domain_name']) && filter_var('http://' . $arr_config_premium['domain_name'], FILTER_VALIDATE_URL)) {
		header("Location: http://" . $arr_config_premium['domain_name'] . $pageUrlParam);
	} else {
		header("Location: " . home_url() . $pageUrlParam);
	}
}
