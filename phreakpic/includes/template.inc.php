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

	
	//this vars are needed in view_cat AND view_content
	$smarty->assign('lang',$lang);
	$smarty->assign('phpbb_path', PHPBB_PATH);
	$smarty->assign('server_name', SERVER_NAME);
	$smarty->assign('template_name', $userdata['photo_user_template']);
	$smarty->assign('sid','&sid='.$userdata['session_id']);
	$smarty->assign('username', $userdata['username']);
?>
