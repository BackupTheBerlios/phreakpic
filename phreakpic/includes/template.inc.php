<?php
	include_once($root_path . 'includes/common.inc.php');
	include_once(SMARTY_DIR.'Smarty.class.php');

	$smarty = new Smarty;
	$smarty->template_dir = $root_path . 'templates/';
	$smarty->compile_dir = $root_path . 'templates_c/';
	$smarty->config_dir = $root_path . 'templates/';
	$smarty->cache_dir = $root_path . 'smarty_cache/';
	$smarty->left_delimiter = '<!--{';
	$smarty->right_delimiter = '}-->';
	$smarty->force_compile = '1';
	
	$smarty->debugging = false;
	//$smarty->debug_tpl = SMARTY_DIR.'debug.tpl';
?>
