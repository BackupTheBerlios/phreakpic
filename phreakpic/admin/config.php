<?php
define ("ROOT_PATH",'../');
include_once(ROOT_PATH . 'includes/common.inc.php');
include_once(ROOT_PATH . 'classes/album_content.inc.php');
include_once(ROOT_PATH . 'includes/template.inc.php');

if (isset($HTTP_POST_VARS['submit']))
{
 $config_vars['thumb_table_cols'] = $HTTP_POST_VARS['thumb_table_cols'];
 $config_vars['default_content_per_page'] = $HTTP_POST_VARS['default_content_per_page'];
 $config_vars['default_template'] = $HTTP_POST_VARS['default_template'];
 $config_vars['default_lang'] = $HTTP_POST_VARS['default_lang'];
 
 write_config(SMARTY_DIR,PHPBB_PATH,PHREAKPIC_PATH,SERVER_NAME);
 
	if (!is_file('./languages/'.$userdata['user_lang'].'/lang_main.php'))
	{
		$userdata['user_lang'] = $config_vars['default_lang'];
	}

	if ((!is_dir($userdata['photo_user_template'])) or (!isset($userdata['photo_user_template'])))
	{
		$userdata['photo_user_template'] = $config_vars['default_template'];
	}

	if (!isset($userdata['content_per_page']))
	{
		$userdata['content_per_page'] = $config_vars['default_content_per_page'];
	}
	
	// include the language files
	unset($lang);
	include(ROOT_PATH . './languages/'.$userdata['user_lang'].'/lang_main.php');
	// include lang file with admin customizable words (no error message because their might not be one
	@include(ROOT_PATH . './languages/'.$userdata['user_lang'].'/lang_custom.php');
	$smarty->assign('lang',$lang);


}


$smarty->assign('installed_templates',get_installed_templates());
$smarty->assign('installed_language',get_installed_languages());

$smarty->assign('config_vars',$config_vars);

$smarty->display($userdata['photo_user_template'].'/admin/config.tpl');
?>
