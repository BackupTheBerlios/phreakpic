<?php
define ("ROOT_PATH",'../');
include_once(ROOT_PATH . 'includes/common.inc.php');
include_once(ROOT_PATH . 'classes/album_content.inc.php');
include_once(ROOT_PATH . 'includes/template.inc.php');

session_start();

if ($HTTP_POST_VARS['default_basket_enable'] == 'on')
{
	$HTTP_SESSION_VARS['default_basket_enable'] = true;
}
else
{
	$HTTP_SESSION_VARS['default_basket_enable'] = false;
}



if (isset($HTTP_POST_VARS['submit']))
{
 $config_vars['thumb_table_cols'] = $HTTP_POST_VARS['thumb_table_cols'];
 $config_vars['default_content_per_page'] = $HTTP_POST_VARS['default_content_per_page'];
 $config_vars['default_template'] = $HTTP_POST_VARS['default_template'];
 $config_vars['default_lang'] = $HTTP_POST_VARS['default_lang'];
 $config_vars['default_usergroup_ids'] = $HTTP_SESSION_VARS['default_usergroup_ids'];
 $config_vars['registered_users_usergroup_ids'] = $HTTP_SESSION_VARS['registered_users_usergroup_ids'];
 sort($HTTP_SESSION_VARS['selectable_content_per_page'], SORT_NUMERIC);
 $config_vars['selectable_content_per_page'] = $HTTP_SESSION_VARS['selectable_content_per_page'];
 $config_vars['default_basket_enable'] = $HTTP_SESSION_VARS['default_basket_enable'];
 
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


$changed=false;
if (isset($HTTP_POST_VARS['add_default_usergroup']))
{
	if (isset($HTTP_POST_VARS['selected_not_default_usergroups']))
	{
		foreach ($HTTP_POST_VARS['selected_not_default_usergroups'] as $value)
		{
			$HTTP_SESSION_VARS['default_usergroup_ids'][] = $value;
		}
	}
	$changed=true;
}
elseif (isset($HTTP_POST_VARS['remove_default_usergroup']))
{
	foreach ($HTTP_POST_VARS['selected_default_usergroups'] as $value)
	{
		$key=array_keys($HTTP_SESSION_VARS['default_usergroup_ids'],$value);
		unset($HTTP_SESSION_VARS['default_usergroup_ids'][$key[0]]);
	}
	$changed=true;
}
elseif (isset($HTTP_POST_VARS['add_registered_users_usergroup']))
{
	foreach ($HTTP_POST_VARS['selected_not_registered_users_usergroups'] as $value)
	{	
		$HTTP_SESSION_VARS['registered_users_usergroup_ids'][] = $value;
	}
	$changed=true;
}
elseif (isset($HTTP_POST_VARS['remove_registered_users_usergroup']))
{

	foreach ($HTTP_POST_VARS['selected_registered_users_usergroups'] as $value)
	{
		$key=array_keys($HTTP_SESSION_VARS['registered_users_usergroup_ids'],$value);
		unset($HTTP_SESSION_VARS['registered_users_usergroup_ids'][$key[0]]);
	}
	$changed=true;
}
elseif (isset($HTTP_POST_VARS['add_selectable']))
{
	if (intval($HTTP_POST_VARS['selectable_add_value'] != 0))
	{
		$HTTP_SESSION_VARS['selectable_content_per_page'][] = intval($HTTP_POST_VARS['selectable_add_value']);
		$HTTP_SESSION_VARS['selectable_content_per_page'] = array_unique($HTTP_SESSION_VARS['selectable_content_per_page']);
		sort($HTTP_SESSION_VARS['selectable_content_per_page'], SORT_NUMERIC);
	}
	$changed=true;
}
elseif (isset($HTTP_POST_VARS['remove_selectable']))
{

	foreach ($HTTP_POST_VARS['selected_selecteable_content_per_page'] as $value)
	{
		unset($HTTP_SESSION_VARS['selectable_content_per_page'][$value]);
	}
	sort($HTTP_SESSION_VARS['selectable_content_per_page'], SORT_NUMERIC);
	$changed=true;
}
else
{
	$HTTP_SESSION_VARS['selectable_content_per_page'] = $config_vars['selectable_content_per_page'];
	$HTTP_SESSION_VARS['registered_users_usergroup_ids'] = $config_vars['registered_users_usergroup_ids'];
	$HTTP_SESSION_VARS['default_usergroup_ids'] = $config_vars['default_usergroup_ids'];
	$HTTP_SESSION_VARS['default_basket_enable'] = $config_vars['default_basket_enable'];
}





//print_r($HTTP_SESSION_VARS['selectable_content_per_page']);
$smarty->assign('selectable_content_per_page',$HTTP_SESSION_VARS['selectable_content_per_page']);



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
 
 
foreach($HTTP_SESSION_VARS['default_usergroup_ids'] as $key => $group_ids)
{
	$group_obj = new usergroup();
	
	if ($group_obj->generate_from_id($group_ids) == OP_SUCCESSFUL)
	{
		$group['id'] = $group_obj->id;
		$group['name'] = $group_obj->get_name();
		$default_usergroups[] = $group;
	}
	else
	{
		// delete unproper entries
		unset($HTTP_SESSION_VARS['default_usergroup_ids'][$key]);
	}
}

foreach($HTTP_SESSION_VARS['registered_users_usergroup_ids'] as $key => $group_ids)
{
	$group_obj = new usergroup();
	if ($group_obj->generate_from_id($group_ids) == OP_SUCCESSFUL)
	{
		$group['id'] = $group_obj->id;
		$group['name'] = $group_obj->get_name();
		$registered_users_usergroups[] = $group;
	}
	else
	{
		// delete unproper entries
		unset($HTTP_SESSION_VARS['registered_users_usergroup_ids'][$key]);
	}
}

 
$smarty->assign('default_usergroup_ids',$default_usergroups);
$smarty->assign('registered_users_usergroup_ids',$registered_users_usergroups);

@$smarty->assign('not_default_usergroup_ids',array_minus_array($usergroups,$default_usergroups));
@$smarty->assign('not_registered_users_usergroup_ids',array_minus_array($usergroups,$registered_users_usergroups));




$smarty->assign('installed_templates',get_installed_templates());
$smarty->assign('installed_language',get_installed_languages());

$smarty->assign('config_vars',$config_vars);

if ($HTTP_SESSION_VARS['default_basket_enable'])
{
	$smarty->assign('basket_enable','checked');
}

$smarty->display($userdata['photo_user_template'].'/admin/config.tpl');
?>
