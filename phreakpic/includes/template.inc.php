<?php
	require_once('./includes/common.inc.php');
	require_once(SMARTY_DIR.'Smarty.class.php');

	$smarty = new Smarty;
	$smarty->template_dir = './templates/';
	$smarty->compile_dir = './templates_c/';
//	$smarty->config_dir = ’/web/www.mydomain.com/smarty/guestbook/configs/’;
	$smarty->cache_dir = './smarty_cache/';
?>