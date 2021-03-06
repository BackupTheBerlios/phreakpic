<?php

require_once (ROOT_PATH . 'includes/functions.inc.php');

require_once (ROOT_PATH . 'classes/auth.inc.php');

require_once (ROOT_PATH . 'classes/error.inc.php');



function generate_perm_array()
{
	global $userdata,$db,$config_vars;

	$auth = new content_auth;

	$usergroup_ids=get_groups_of_user($userdata['user_id']);
	// add the default usergroups
	$usergroup_ids=array_merge($usergroup_ids,$config_vars['auto_usergroup_ids']);

	$where = generate_where('usergroup_id',$usergroup_ids);

	$sql = 'select contentgroup_id, ' .KEY_QUOTE . implode(KEY_QUOTE.', '.KEY_QUOTE,$auth->processing_vars) . KEY_QUOTE.' from '.$config_vars['table_prefix']."content_auth where ($where)";


	if (!$result = $db->sql_query($sql))
	{
		error_report(SQL_ERROR, 'action_allowed' , __LINE__, __FILE__,$sql);
	}




	while ($row = $db->sql_fetchrow($result))
	{
		foreach($auth->processing_vars as $var)
		{
			$perm_array[$row['contentgroup_id']][$var] = $row[$var];
		}

	}
	return $perm_array;
}


$perm_array = generate_perm_array();



function check_auth_action_allowed()
{
	global $userdata;

	if ($userdata['user_level'] == ADMIN)
	{
		return '1';
	}

	return false;
}

function check_group_action_allowed()
{
	return check_auth_action_allowed();
}

function check_usergroup_action_allowed($usergroupgroup_id,$user_id,$action)
{
	// Returns TRUE if the user with id $user_id is allowed to do $action with the categories in in the catgroup with id $catgroup_id
	global $db,$config_vars,$userdata;

	// if the current user is admin allow everything
	if ($userdata['user_level'] == ADMIN)
	{
		return true;
	}

	// check in which groups the user is
	$usergroup_ids=get_groups_of_user($user_id);
	
	// add the default usergroups
	$usergroup_ids=array_merge($usergroup_ids,$config_vars['auto_usergroup_ids']);

	// if the user is in no usergroup then disallow the action
	if (!isset($usergroup_ids))
	{
		return false;
	}
	
	$where = generate_where('usergroup_id',$usergroup_ids);
	$sql = 'select usergroup_id from '.$config_vars['table_prefix']."usergroup_auth where (`$action` like 1) and (usergroupgroup_id like $usergroupgroup_id) and ($where) limit 1";
	if (!$result = $db->sql_query($sql))
	{
		error_report(SQL_ERROR, 'action_allowed' , __LINE__, __FILE__,$sql);
	}


	if ($db->sql_affectedrows()>=1)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function check_cat_action_allowed($catgroup_id,$user_id,$action)
{
	// Returns TRUE if the user with id $user_id is allowed to do $action with the categories in in the catgroup with id $catgroup_id
	global $db,$config_vars,$userdata;

	// if the current user is admin allow everything
	if ($userdata['user_level'] == ADMIN)
	{
		return true;
	}

	// check in which groups the user is
	$usergroup_ids=get_groups_of_user($user_id);

	// add the default usergroups
	$usergroup_ids=array_merge($usergroup_ids,$config_vars['auto_usergroup_ids']);

	// if the user is in no usergroup then disallow the action
	if (!isset($usergroup_ids))
	{
		return false;
	}

	$where = generate_where('usergroup_id',$usergroup_ids);
	$sql = 'select usergroup_id from '.$config_vars['table_prefix']."cat_auth where (`$action` like 1) and (catgroup_id like $catgroup_id) and ($where) limit 1";
	if (!$result = $db->sql_query($sql))
	{
		error_report(SQL_ERROR, 'action_allowed' , __LINE__, __FILE__,$sql);
	}


	if ($db->sql_affectedrows()>=1)
	{
		return true;
	}
	else
	{
		return false;
	}
}


function check_content_action_allowed($contentgroup_id,$user_id,$action)
{
	// Returns TRUE if the user with id $user_id is allowed to do $action with content in $contentgroup_id

	global $perm_array,$userdata;
	// if the current user is admin allow everything
	if ($userdata['user_level'] == ADMIN)
	{

		return true;
	}
	return $perm_array[$contentgroup_id][$action];
}



function get_allowed_contentgroups_where($user_id,$action,$field='contentgroup_id')
{
	// Returns an SQL where that limits a query to the content where $action if allowed by $user_id.
	// $action must be the name of a field out of the content_auth table, which says if this action is allowed or not

	global $db,$config_vars,$userdata;



	// if the current user is admin allow everything
	if ($userdata['user_level'] == ADMIN)
	{
		return "'1'";
	}

	// first of all get the groups in which the user is.
//	$user_groups = get_groups_of_user($user_id);

//	if (!isset($user_groups))
//	{
		// user is in now usergroups
//		return '0';
//	}
	
	// get contentgroup_ids from the contents groups where at least one usergroup out of �users_groups is allowed to do $action
//	$where = generate_where('usergroup_id',$user_groups);

	
//	$sql = 'select contentgroup_id from '.$config_vars['table_prefix']."content_auth where ($action like 1) and $where";

// EXPERIMENTAL SQL which doesnt needs get_groups_of_user
	$default_usergroups_where = generate_where('auth.usergroup_id',$config_vars['auto_usergroup_ids']);

                                           
	$sql ='SELECT auth.contentgroup_id, uig.group_id FROM '.$config_vars['table_prefix'].'content_auth as auth, '.$config_vars['table_prefix']."user_in_group AS uig 
	WHERE (auth.$action like 1) and (((uig.user_id = $user_id) and (auth.usergroup_id=uig.group_id)) or ($default_usergroups_where) ) group by auth.contentgroup_id";

	
	if (!$result = $db->sql_query($sql))
	{
		error_report(SQL_ERROR, 'action_allowed' , __LINE__, __FILE__,$sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$allowed_contentgroups[]=$row['contentgroup_id'];
		
	}

	$r = generate_where($field,$allowed_contentgroups);
//TODO: check this
	if (!isset($r))
	{
		$r="'0'";
	}
	return $r;
}

function get_allowed_catgroups_where($user_id,$action,$field='catgroup_id')
{
	// Returns an SQL where that limits a query to the categories where $action if allowed by $user_id.
	// $action must be the name of a field out of the cat_auth table, which says if this action is allowed or no
	
	global $db,$config_vars,$userdata;	
	
	// if the current user is admin allow everything
	if ($userdata['user_level'] == ADMIN)
	{
	
		return "'1'";
	}
	
	// first of all get the groups in which the user is.
	$usergroup_ids = get_groups_of_user($user_id);
	
		// add the default usergroups
	$usergroup_ids=array_merge($usergroup_ids,$config_vars['auto_usergroup_ids']);

	
		
	if (!isset($usergroup_ids))
	{
		// user is in now usergroups return 
		return "'0'";
	}


	// get contentgroup_ids from the contents groups where at least one usergroup out of �users_groups is allowed to do $action
	$where = generate_where('usergroup_id',$usergroup_ids);
	$sql = 'select catgroup_id from '.$config_vars['table_prefix']."cat_auth where ($action like 1) and ($where)";
	if (!$result = $db->sql_query($sql))
	{
		error_report(SQL_ERROR, 'action_allowed' , __LINE__, __FILE__,$sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$allowed_catgroups[]=$row['catgroup_id'];

	}

	return generate_where($field,$allowed_catgroups);
}





// !!!!maybe not interface anymore!!!!!
function get_groups_of_user($user_id)
{
	global $db,$config_vars;
	// Returns array of group_ids in which the user with id $user_id is
	$sql = "
		SELECT group_id from " . $config_vars['table_prefix'] . "user_in_group
		WHERE user_id = $user_id";


	if (!$result = $db->sql_query($sql))
	{
		error_report(SQL_ERROR, 'get_groups_of_user' , __LINE__, __FILE__,$sql);
	}


	while ($row = $db->sql_fetchrow($result))
	{
		$group_ids[] = $row['group_id'];

	}

	return $group_ids;

}

// function get_user_group_objects()
// {
// 	// Returns array of group_ids in which the user with id $user_id is
// 	global $db,$config_vars;
// 	
// 	$sql = "SELECT id from " . $config_vars['table_prefix'] . "usergroups";
// 	
// 	if (!$result = $db->sql_query($sql))
// 	{
// 		message_die(GENERAL_ERROR, "Could not get groups of user", '', __LINE__, __FILE__, $sql);
// 	}
// 
// 	while ($row = $db->sql_fetchrow($result))
// 	{
// 		$obj = new usergroup;
// 		$obj->genereate_from_id($row['group_id']);
// 		$groups[]=$obj;	
// 		
// 	}
// 	return $groups;
// 
// 
// 		
// }

?>
