<?php
define(ROOT_PATH,'../');
include_once(ROOT_PATH . 'includes/common.inc.php');
include_once(ROOT_PATH . 'includes/template.inc.php');
include_once(ROOT_PATH . 'classes/group.inc.php');
include_once(ROOT_PATH . 'classes/meta.inc.php');
include_once(ROOT_PATH . 'modules/authorisation/interface.inc.php');

session_start();
//check if User is allowed to view this file
if ($userdata['user_level'] != 1)
{
	error_report(AUTH_ERROR, 'no_admin' , __LINE__, __FILE__);
}


$allowed_types= Array ('content_meta_field','usergroup','group');

if (in_array($HTTP_GET_VARS['type'],$allowed_types))
{	
	$HTTP_SESSION_VARS['type']=$HTTP_GET_VARS['type'];
}
else
{
	if (!isset($HTTP_SESSION_VARS['type']))
	{
		$HTTP_SESSION_VARS['type'] = 'usergroup';
	}
}




$entry = new $_SESSION['type']();


if (isset($HTTP_POST_VARS['delete']))
{
	$entry->generate_from_id($HTTP_GET_VARS['sel_group_id']);
	$entry->delete();
}

if (isset($HTTP_POST_VARS['create']))
{
	
	if ($HTTP_POST_VARS[$entry->processing_vars[0]]=='')
	{
		error_report(INFORMATION,'enter_name',__LINE__,__FILE__);
	}

	$entry->set_vars($HTTP_POST_VARS);
	$entry->commit();
	$HTTP_GET_VARS['sel_group_id'] = $entry->id;
}


if (isset($HTTP_POST_VARS['change']))
{
	// make sure at first var is set
	if ($HTTP_POST_VARS[$entry->processing_vars[0]]=='')
	{
		error_report(INFORMATION,'enter_name',__LINE__,__FILE__);
	}
	
	$entry->generate_from_id($HTTP_GET_VARS['sel_group_id']);
	$entry->set_vars($HTTP_POST_VARS);
	$entry->commit();
}



// get all usergroups
$sql = "SELECT * from " . $config_vars['table_prefix'] . "{$_SESSION[type]}s";
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

$smarty->assign('processing_vars',$entry->processing_vars);
$smarty->assign('groups',$groups);
$smarty->assign('sel_group_id',$HTTP_GET_VARS['sel_group_id']);
$smarty->assign('sel_group',$sel_group);
$smarty->assign('group_name',$lang[$_SESSION['type'].'s']);
$smarty->assign('new_group',$lang["new_".$type]);
$smarty->display($userdata['photo_user_template'].'/admin/groups.tpl');

?>
