<?php
	require_once('./includes/common.inc.php');
	require_once(SMARTY_DIR.'Smarty.class.php');

	$smarty = new Smarty;
	$smarty->template_dir = './templates/';
	$smarty->compile_dir = './templates_c/';
	$smarty->config_dir = './templates/';
	$smarty->cache_dir = './smarty_cache/';
	$smarty->left_delimiter = '<!--{';
	$smarty->right_delimiter = '}-->';
?>