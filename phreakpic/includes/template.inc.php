<?php
	include_once(ROOT_PATH . 'includes/common.inc.php');
	include_once(SMARTY_DIR.'Smarty.class.php');
	include_once(ROOT_PATH . './languages/'.$userdata['user_lang'].'/lang_main.php');

	$smarty = new Smarty;
	$smarty->template_dir = ROOT_PATH . 'templates/';
	$smarty->compile_dir = ROOT_PATH . 'templates_c/';
	$smarty->config_dir = ROOT_PATH . 'templates/';
	$smarty->cache_dir = ROOT_PATH . 'smarty_cache/';
	$smarty->left_delimiter = '<!--{';
	$smarty->right_delimiter = '}-->';
	$smarty->force_compile = '1';
	
	$smarty->debugging = false;
	//$smarty->debug_tpl = SMARTY_DIR.'debug.tpl';
	$smarty->assign('lang',$lang);
?>
