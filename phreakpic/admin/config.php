<?php
define ("ROOT_PATH",'../');
include_once(ROOT_PATH . 'includes/common.inc.php');
include_once(ROOT_PATH . 'classes/album_content.inc.php');
include_once(ROOT_PATH . 'includes/template.inc.php');

session_start();

if (isset($HTTP_POST_VARS['submit']))
{
 $config_vars['thumb_table_cols'] = $HTTP_POST_VARS['thumb_table_cols'];
 $config_vars['default_content_per_page'] = $HTTP_POST_VARS['default_content_per_page'];
 $config_vars['default_template'] = $HTTP_POST_VARS['default_template'];
 $config_vars['default_lang'] = $HTTP_POST_VARS['default_lang'];
 $config_vars['default_usergroup_ids'] = $HTTP_SESSION_VARS['default_usergroup_ids'];
 $config_vars['registered_users_usergroup_ids'] = $HTTP_SESSION_VARS['registered_users_usergroup_ids'];
 
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

if (isset($HTTP_POST_VARS['add_default_usergroup']))
{

	if (isset($HTTP_POST_VARS['selected_not_default_usergroups']))
	{
		foreach ($HTTP_POST_VARS['selected_not_default_usergroups'] as $value)
		{
			$HTTP_SESSION_VARS['default_usergroup_ids'][] = $value;
		}
	}
}
elseif (isset($HTTP_POST_VARS['remove_default_usergroup']))
{
	foreach ($HTTP_POST_VARS['selected_default_usergroups'] as $value)
	{
		$key=array_keys($HTTP_SESSION_VARS['default_usergroup_ids'],$value);
		unset($HTTP_SESSION_VARS['default_usergroup_ids'][$key[0]]);
	}

}
else
{
	$HTTP_SESSION_VARS['default_usergroup_ids'] = $config_vars['default_usergroup_ids'];
}


if (isset($HTTP_POST_VARS['add_registered_users_usergroup']))
{

	if (isset($HTTP_POST_VARS['selected_not_registered_users_usergroups']))
	{
		foreach ($HTTP_POST_VARS['selected_not_registered_users_usergroups'] as $value)
		{
			$HTTP_SESSION_VARS['registered_users_usergroup_ids'][] = $value;
		}
	}
}
elseif (isset($HTTP_POST_VARS['remove_default_usergroup']))
{
	foreach ($HTTP_POST_VARS['selected_registered_users_usergroups'] as $value)
	{
		$key=array_keys($HTTP_SESSION_VARS['registered_users_usergroup_ids'],$value);
		unset($HTTP_SESSION_VARS['registered_users_usergroup_ids'][$key[0]]);
	}

}
else
{
	$HTTP_SESSION_VARS['registered_users_usergroup_ids'] = $config_vars['registered_users_usergroup_ids'];
}




// get all usergroups
$sql = "SELECT id,name from " . $config_vars['table_prefix'] . "usergroups";
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not get groups of user", '', __LINE__, __FILE__, $sql);
}

 while ($row = $db->sql_fetchrow($result))
 {
 	$group['id'] = $row['id'];
 	$group['name'] = $row['name'];
	$usergroups[] = $group;	
 }
 
 
foreach($HTTP_SESSION_VARS['default_usergroup_ids'] as $group_ids)
{
	$group_obj = new usergroup();
	$group_obj->generate_from_id($group_ids);
	$group['id'] = $group_obj->id;
	$group['name'] = $group_obj->get_name();
	$default_usergroups[] = $group;
}

foreach($HTTP_SESSION_VARS['registered_users_usergroup_ids'] as $group_ids)
{
	$group_obj = new usergroup();
	$group_obj->generate_from_id($group_ids);
	$group['id'] = $group_obj->id;
	$group['name'] = $group_obj->get_name();
	$registered_users_usergroups[] = $group;
}


/*foreach($usergroups as $group_obj)
{
	$group['id'] = $group_obj->id;
	$group['name'] = $group_obj->get_name();
	$not_default_usergroups[] = $group;
}*/

 
 
 
$smarty->assign('default_usergroup_ids',$default_usergroups);
$smarty->assign('registered_users_usergroup_ids',$registered_users_usergroups);

@$smarty->assign('not_default_usergroup_ids',array_minus_array($usergroups,$registered_users_usergroups));
@$smarty->assign('not_registered_users_usergroup_ids',array_minus_array($usergroups,$registered_users_usergroups));




$smarty->assign('installed_templates',get_installed_templates());
$smarty->assign('installed_language',get_installed_languages());

$smarty->assign('config_vars',$config_vars);

$smarty->display($userdata['photo_user_template'].'/admin/config.tpl');
?>
