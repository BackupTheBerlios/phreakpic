<?php
require_once ('includes/functions.inc.php');

function check_cat_action_allowed($catgroup_id,$user_id,$action)
{
	// Returns TRUE if the user with id $user_id is allowed to do $action with the categories in in the catgroup with id $catgroup_id
	global $db,$config_vars;
	
	// check in which groups the user is
	$usergroup_ids=get_groups_of_user($user_id);

	// if the user is in no usergroup then disallow the action
	if (!isset($usergroup_ids))
	{
		return false;
	}
	
	$where = generate_where('usergroup_id',$usergroup_ids);
	$sql = 'select usergroup_id from '.$config_vars['table_prefix']."cat_auth where ($action like 1) and (catgroup_id like $catgroup_id) and ($where) limit 1";
	
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not check whether the user is allowed to this action", '', __LINE__, __FILE__, $sql);
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
	
	global $db,$config_vars;	
	
	// check in which groups the user is
	$user_groups=get_groups_of_user($user_id);	
	// check if there is at least one entry where in one of the $user_groups is $action allowed in $contentgroup_id
	$where = generate_where('usergroup_id',$user_groups);
	$sql = 'select usergroup_id from '.$config_vars['table_prefix']."content_auth where (`$action` like 1) and (contentgroup_id like $contentgroup_id) and ($where) limit 1";
	
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not check whether the user is allowed to this action", '', __LINE__, __FILE__, $sql);
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

function get_allowed_contentgroups_where($field,$user_id,$action)
{
	// Returns an SQL where that limits a query to the content where $action if allowed by $user_id.
	// $action must be the name of a field out of the content_auth table, which says if this action is allowed or not

	global $db,$config_vars;	
	// first of all get the groups in which the user is.
//	$user_groups = get_groups_of_user($user_id);
	
//	if (!isset($user_groups))
//	{
		// user is in now usergroups
//		return '0';
//	}
	
	// get contentgroup_ids from the contents groups where at least one usergroup out of §users_groups is allowed to do $action
//	$where = generate_where('usergroup_id',$user_groups);
	
	
//	$sql = 'select contentgroup_id from '.$config_vars['table_prefix']."content_auth where ($action like 1) and $where";

// EXPERIMENTAL SQL which doesnt needs get_groups_of_user
	$sql ='SELECT auth.contentgroup_id, uig.group_id FROM '.$config_vars['table_prefix'].'content_auth as auth, '.$config_vars['table_prefix']."user_in_group AS uig 
	WHERE ($action like 1) and (uig.user_id = $user_id) and (auth.usergroup_id=uig.group_id) group by auth.contentgroup_id";

	
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not check whether the contentgroups where this user is allowed to this action", '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$allowed_contentgroups[]=$row['contentgroup_id'];
		
	}

	return generate_where($field,$allowed_contentgroups);	
}

function get_allowed_catgroups_where($user_id,$action)
{
	// Returns an SQL where that limits a query to the categories where $action if allowed by $user_id.
	// $action must be the name of a field out of the cat_auth table, which says if this action is allowed or not

	global $db,$config_vars;	
	// first of all get the groups in which the user is.
	$user_groups = get_groups_of_user($user_id);
	if (!isset($user_groups))
	{
		// user is in now usergroups return 
		return '0';
	}
	
	
	// get contentgroup_ids from the contents groups where at least one usergroup out of §users_groups is allowed to do $action
	$where = generate_where('usergroup_id',$user_groups);
	$sql = 'select catgroup_id from '.$config_vars['table_prefix']."cat_auth where ($action like 1) and $where";
	
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not check whether the contentgroups where this user is allowed to this action", '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$allowed_catgroups[]=$row['catgroup_id'];
		
	}

	return generate_where('catgroup_id',$allowed_catgroups);	
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
		message_die(GENERAL_ERROR, "Could not get groups of user", '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$group_ids[] = $row['group_id'];
		
	}
	return $group_ids;

}
?>
