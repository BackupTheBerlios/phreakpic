<?php
define(ROOT_PATH,'../');
require_once(ROOT_PATH . 'includes/common.inc.php');
include_once(ROOT_PATH . './languages/'.$userdata['user_lang'].'/lang_main.php');
require_once(ROOT_PATH . 'includes/template.inc.php');
require_once(ROOT_PATH . 'classes/group.inc.php');
require_once(ROOT_PATH . 'modules/authorisation/interface.inc.php');



session_start();

$HTTP_GET_VARS['type'];

if (($HTTP_GET_VARS['type']=='content') or $HTTP_GET_VARS['type']=='cat')
{
	$HTTP_SESSION_VARS['type']=$HTTP_GET_VARS['type'];
}
else
{
	$type=$HTTP_SESSION_VARS['type'];
}



// get all usergroups
$sql = 'SELECT id,name FROM ' . $config_vars['table_prefix'] . 'usergroups';



if (!isset($group))
{
	$group=1;
}
if (!isset($usergroup))
{
	$usergroup=1;
}

// check submits
$class=$type."_auth";
$groupclass = $type . 'group';

if (isset($del_group))
{
	$delgroup = new $groupclass();
	$delgroup->generate_from_id($del_group);
	$delgroup->delete();
}

if (isset($HTTP_POST_VARS['new_group']))
{
	$groupclass = $type . 'group';
	$newgroup = new $groupclass;
	echo $HTTP_POST_VARS['name'];
	$newgroup->set_name($HTTP_POST_VARS['name']);
	$newgroup->set_description($HTTP_POST_VARS['description']);
	$newgroup->commit();
}


if (isset($HTTP_POST_VARS['new_auth']))
{
	$auth = new $class();
	$auth->set_usergroup_id($usergroup);	
	$auth->set_group_id($group);
	$auth->commit();
}

if (isset($HTTP_POST_VARS['delete_auth']))
{
	$auth = new $class;
	$auth->set_usergroup_id($usergroup);	
	$auth->set_group_id($group);
	$auth->delete();
}


if (isset($HTTP_POST_VARS['change_auth']))
{
	$auth = new $class();
	$auth->generate($usergroup,$group);

	if ($HTTP_POST_VARS['view'] == 'on')
	{
		$auth->set_view(1);
	}
	else
	{
		$auth->set_view(0);
	}
	
	if ($HTTP_POST_VARS['edit'] == 'on')
	{
		$auth->set_edit(1);
	}
	else
	{
		$auth->set_edit(0);
	}
	
	if ($HTTP_POST_VARS['delete'] == 'on')
	{
		$auth->set_delete(1);
	}
	else
	{
		$auth->set_delete(0);
	}
	
	if ($HTTP_POST_VARS['comment_edit'] == 'on')
	{
		$auth->set_comment_edit(1);
	}
	else
	{
		$auth->set_comment_edit(0);
	}
	
	if ($type=='cat')
	{
	// additional cat fields
		if ($HTTP_POST_VARS['cat_add'] == 'on')
		{
			$auth->set_cat_add(1);
		}
		else
		{
			$auth->set_cat_add(0);
		}
		
		if ($HTTP_POST_VARS['cat_remove'] == 'on')
		{
			$auth->set_cat_remove(1);
		}
		else
		{
			$auth->set_cat_remove(0);
		}
		
		if ($HTTP_POST_VARS['content_add'] == 'on')
		{
			$auth->set_content_add(1);
		}
		else
		{
			$auth->set_content_add(0);
		}
		
		if ($HTTP_POST_VARS['content_remove'] == 'on')
		{
			$auth->set_content_remove(1);
		}
		else
		{
			$auth->set_content_remove(0);
		}
	}
	$auth->commit();
	
}




if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not get groups of user", '', __LINE__, __FILE__, $sql);
}

while ($row = $db->sql_fetchrow($result))
{
	$usergroups[]=$row;
}



// get all groups
$sql = 'SELECT id,name FROM ' . $config_vars['table_prefix'] . $type . 'groups';

if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not get groups of user", '', __LINE__, __FILE__, $sql);
}

while ($row = $db->sql_fetchrow($result))
{
	$groups[]=$row;
}

// get values
$sql = 'SELECT usergroup_id,' . $type . 'group_id FROM ' . $config_vars['table_prefix'] . $type . "_auth where (usergroup_id = $usergroup) and (" . $type . "group_id = $group)";

if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not get groups of user", '', __LINE__, __FILE__, $sql);
}



if ($row = $db->sql_fetchrow($result))
{
	$smarty->assign('auth_exists',true);	
	$auth = new $class();
	$auth->generate($row['usergroup_id'],$row[$type . 'group_id']);
	if ($auth->get_view())
	{
		$smarty->assign('view_checked','checked');	
	}
	if ($auth->get_delete())
	{
		$smarty->assign('delete_checked','checked');	
	}
	if ($auth->get_edit())
	{
		$smarty->assign('edit_checked','checked');	
	}
	if ($auth->get_comment_edit())
	{
		$smarty->assign('comment_edit_checked','checked');	
	}
	
	if ($type=='cat')
	{
		if ($auth->get_cat_add())
		{
			$smarty->assign('cat_add_checked','checked');	
		}
		if ($auth->get_cat_remove())
		{
			$smarty->assign('cat_remove_checked','checked');	
		}
		if ($auth->get_content_add())
		{
			$smarty->assign('content_add_checked','checked');	
		}
		if ($auth->get_content_remove())
		{
			$smarty->assign('content_remove_checked','checked');	
		}	
	}
}





$smarty->assign('sel_usergroup',$usergroup);
$smarty->assign('usergroups',$usergroups);

$smarty->assign('sel_group',$group);
$smarty->assign('groups',$groups);

$smarty->assign('group_name',$lang[$type.'groups']);
$smarty->assign('new_group',$lang['new_'.$type.'group']);
$smarty->assign('type',$type);

$smarty->display($userdata['photo_user_template'].'/admin/auths.tpl');


?>
