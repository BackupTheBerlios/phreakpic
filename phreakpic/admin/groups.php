<?php
define(ROOT_PATH,'../');
include_once(ROOT_PATH . 'includes/common.inc.php');
include_once(ROOT_PATH . 'includes/template.inc.php');
include_once(ROOT_PATH . 'classes/group.inc.php');
include_once(ROOT_PATH . 'modules/authorisation/interface.inc.php');

session_start();

//check if User is allowed to view this file
if ($userdata['user_level'] != 1)
{
	error_report(AUTH_ERROR, 'no_admin' , __LINE__, __FILE__);
}

if (($HTTP_GET_VARS['type']=='') or ($HTTP_GET_VARS['type']=='user'))
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
		$type='user';
	}
}


$groupclass = $type . 'group';



if (isset($HTTP_POST_VARS['delete']))
{
	$del_group = new $groupclass();
	$del_group->generate_from_id($HTTP_GET_VARS['sel_group_id']);
	$del_group->delete();
}

if (isset($HTTP_POST_VARS['create']))
{
	if ($HTTP_POST_VARS['name']=='')
	{
		error_report(INFORMATION,'enter_name',__LINE__,__FILE__);
	}
	$group = new $groupclass();
	
	$group->set_name($HTTP_POST_VARS['name']);
	$group->set_description($HTTP_POST_VARS['description']);
	$group->commit();
	$HTTP_GET_VARS['sel_group_id'] = $group->id;
}


if (isset($HTTP_POST_VARS['change']))
{
	$group = new $groupclass();
	$group->generate_from_id($HTTP_GET_VARS['sel_group_id']);
	$group->set_name($HTTP_POST_VARS['name']);
	$group->set_description($HTTP_POST_VARS['description']);
	$group->commit();
}



// get all usergroups
$sql = "SELECT * from " . $config_vars['table_prefix'] . "{$groupclass}s";
if (!$result = $db->sql_query($sql))
{
	error_report(AUTH_ERROR, 'get_groups' , __LINE__, __FILE__,$sql);
}
while ($row = $db->sql_fetchrow($result))
{
	$groups[] = $row;
}

// get data of selected group
foreach ($groups as $value)
{
	
	if ($value['id'] == $HTTP_GET_VARS['sel_group_id'])
	{
		$sel_group=$value;
		break;
	}
}

$smarty->assign('groups',$groups);
$smarty->assign('sel_group_id',$HTTP_GET_VARS['sel_group_id']);
$smarty->assign('sel_group',$sel_group);
$smarty->assign('group_name',$lang[$type.'groups']);
$smarty->assign('new_group',$lang["new_".$type.'group']);
$smarty->display($userdata['photo_user_template'].'/admin/groups.tpl');

?>
