<?php
	include_once(ROOT_PATH . 'includes/common.inc.php');
	include_once(SMARTY_DIR.'Smarty.class.php');
	
	// include the language files
	include_once(ROOT_PATH . './languages/'.$userdata['user_lang'].'/lang_main.php');
	// include lang file with admin customizable words (no error message because their might not be one
	@include_once(ROOT_PATH . './languages/'.$userdata['user_lang'].'/lang_custom.php');
	@include_once(ROOT_PATH . './languages/'.$userdata['user_lang'].'/lang_error.php');
	

	$smarty = new Smarty;
	$smarty->template_dir = ROOT_PATH . 'templates/';
	$smarty->compile_dir = ROOT_PATH . 'templates_c/';
	$smarty->config_dir = ROOT_PATH . 'templates/';
	$smarty->cache_dir = ROOT_PATH . 'smarty_cache/';
	$smarty->left_delimiter = '<!--{';
	$smarty->right_delimiter = '}-->';
	$smarty->force_compile = '0';
	
	$smarty->debugging = false;
	//$smarty->debug_tpl = SMARTY_DIR.'debug.tpl';

	
	//this vars are needed on every page
	$smarty->assign('title_site',$board_config['sitename']);
	
		
	//this vars are needed in view_cat AND view_content
	$smarty->assign('lang',$lang);
	$smarty->assign('phpbb_path', ROOT_PATH . PHPBB_PATH);
	$smarty->assign('server_name', SERVER_NAME);
	$smarty->assign('template_name', $userdata['photo_user_template']);
	$smarty->assign('sid','&sid='.$userdata['session_id']);
	$smarty->assign('username', $userdata['username']);
	$smarty->assign('user_id', $userdata['user_id']);
	
	
	//set if the actual user is admin or not
	if ($userdata['user_level'] == 1)
	{
		$smarty->assign('user_level','admin');
	}
	else
	{
		$smarty->assign('user_level','user');
	}
?>
