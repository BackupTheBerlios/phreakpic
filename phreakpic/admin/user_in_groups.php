<?php
define(ROOT_PATH,'../');
include_once(ROOT_PATH . 'includes/common.inc.php');
include_once(ROOT_PATH . 'includes/template.inc.php');
include_once(ROOT_PATH . 'classes/group.inc.php');
include_once(ROOT_PATH . 'modules/authorisation/interface.inc.php');


//check if User is allowed to view this file
if ($userdata['user_level'] != 1)
{
	message_die(GENERAL_ERROR, "You are not Administrator", '', __LINE__, __FILE__, $sql);
}

if (!isset($usergroup))
{
	$usergroup=1;
}

$sel_group = new usergroup();
$sel_group->generate_from_id($usergroup);


if (isset($del_usergroup))
{

	$del_group = new usergroup();
	$del_group->generate_from_id($del_usergroup);
	$del_group->delete();

}




if (isset($HTTP_POST_VARS['new_usergroup']))
{

	$new_group = new usergroup();
	$new_group->set_name($HTTP_POST_VARS['name']);
	$new_group->set_description($HTTP_POST_VARS['describtion']);
	$new_group->commit();

}


if (isset($HTTP_POST_VARS['add']))
{
	foreach ($HTTP_POST_VARS['add_users'] as $value)
	{
		$sel_group->add_user($value);
	}
}

if (isset($HTTP_POST_VARS['remove']))
{
	foreach ($HTTP_POST_VARS['remove_users'] as $value)
	{
		$sel_group->remove_user($value);
	}

}




// get all usergroups
$sql = "SELECT * from " . $config_vars['table_prefix'] . "usergroups";
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not get groups of user", '', __LINE__, __FILE__, $sql);
}

 while ($row = $db->sql_fetchrow($result))
 {
	$usergroups[] = $row;
 	
 }
$smarty->assign('usergroups',$usergroups);

// get all users

$sql = 	$sql="SELECT user_id,username from " . $table_prefix . "users order by username";
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not get groups of user", '', __LINE__, __FILE__, $sql);
}

 
 while ($row = $db->sql_fetchrow($result))
 {
	$users[$row['user_id']] = $row['username'];
 	
 }

// get all users in this group
$sql = "SELECT user_id from " . $config_vars['table_prefix'] . "user_in_group WHERE group_id=$usergroup";
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not get groups of user", '', __LINE__, __FILE__, $sql);
}

 while ($row = $db->sql_fetchrow($result))
 {
	$user_in_group[$row['user_id']] = true ;
 }


$smarty->assign('sel_usergroup',$usergroup);
$smarty->assign('usergroups',$usergroups);
$smarty->assign('users',$users);
$smarty->assign('user_in_group',$user_in_group);






$smarty->display($userdata['photo_user_template'].'/admin/user_in_groups.tpl');

?>
