<?php
define(ROOT_PATH,'../');
include_once(ROOT_PATH . 'includes/common.inc.php');
include_once(ROOT_PATH . 'languages/'.$userdata['user_lang'].'/lang_main.php');
include_once(ROOT_PATH . 'includes/template.inc.php');
include_once(ROOT_PATH . 'classes/group.inc.php');
include_once(ROOT_PATH . 'modules/authorisation/interface.inc.php');



session_start();

//check if User is allowed to view this file
if ($userdata['user_level'] != 1)
{
	error_report(AUTH_ERROR, 'no_admin' , __LINE__, __FILE__,$sql);
}


if (($HTTP_GET_VARS['type']=='content') or $HTTP_GET_VARS['type']=='cat')
{
	$HTTP_SESSION_VARS['type']=$HTTP_GET_VARS['type'];
}
else
{
	if (isset($HTTP_SESSION_VARS['type']))
	{
		$type=$HTTP_SESSION_VARS['type'];
	}
	else
	{
		$type='cat';
	}
}






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

// if (isset($del_group))
// {
// 	$delgroup = new $groupclass();
// 	$delgroup->generate_from_id($del_group);
// 	$delgroup->delete();
// }
// 
// if (isset($HTTP_POST_VARS['new_group']))
// {
// 	$groupclass = $type . 'group';
// 	$newgroup = new $groupclass;
// 	$newgroup->set_name($HTTP_POST_VARS['name']);
// 	$newgroup->set_description($HTTP_POST_VARS['description']);
// 	$newgroup->commit();
// }


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
	
	// automaticly set all vars of the class set
	
	foreach ($auth->processing_vars as $value)
	{		
		$set_func = "set_$value";
		if ($HTTP_POST_VARS[$value] == 'on')
		{
			$auth->$set_func(1);
		}
		else
		{
			$auth->$set_func(0);
		}
	}


	$auth->commit();
	
}


// get all usergroups
$sql = 'SELECT id,name FROM ' . $config_vars['table_prefix'] . 'usergroups';


if (!$result = $db->sql_query($sql))
{
	error_report(SQL_ERROR, 'get_usergroups_of_user' , __LINE__, __FILE__,$sql);
}

while ($row = $db->sql_fetchrow($result))
{
	$usergroups[]=$row;
}



// get all groups
$sql = 'SELECT id,name FROM ' . $config_vars['table_prefix'] . $type . 'groups';

if (!$result = $db->sql_query($sql))
{
	error_report(SQL_ERROR, 'get_groups_of_user' , __LINE__, __FILE__,$sql);
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
	// automaticly get all vars of the class and fill the the checkboxes if set
	
	
	foreach ($auth->processing_vars as $value)
	{		
		$get_func = "get_$value";
		
		if ($auth->$get_func())
		{
			$smarty->assign("{$value}_checked",'checked');	
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
