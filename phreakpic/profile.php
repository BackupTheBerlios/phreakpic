<?php
define ("ROOT_PATH",'');
include_once(ROOT_PATH . 'includes/common.inc.php');
include_once(ROOT_PATH . 'includes/template.inc.php');
include_once(ROOT_PATH . 'includes/functions.inc.php');

if ($HTTP_POST_VARS['user_basket_enable'] == 'on')
{
	$HTTP_SESSION_VARS['basket_enable'] = true;
}
else
{
	$HTTP_SESSION_VARS['basket_enable'] = false;
}

if (isset($HTTP_POST_VARS['submit']))
{
	$userdata['phreakpic_basket_enable'] = $HTTP_SESSION_VARS['basket_enable'];

	$sql="UPDATE {$table_prefix}users
		SET
			phreakpic_basket_enable = '{$userdata['phreakpic_basket_enable']}'
		WHERE user_id = {$userdata['user_id']}";
		
	if (!$result = $db->sql_query($sql))
	{
		error_report(SQL_ERROR, 'commit' , __LINE__, __FILE__,$sql);
	}
	
	
}


$HTTP_SESSION_VARS['basket_enable'] = $userdata['phreakpic_basket_enable'];


if ($HTTP_SESSION_VARS['basket_enable'])
{
	$smarty->assign('basket_enable','checked');
}

$nav_content['name']=$lang['profile'];
$nav_string[]=$nav_content;
$smarty->assign('nav_string',$nav_string);


$smarty->display($userdata['photo_user_template'].'/profile.tpl');
?>

