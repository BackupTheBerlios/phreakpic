<?php
	include_once ("includes/common.inc.php");
	include_once('./includes/template.inc.php');
	
	$smarty->assign('mode', $mode);
	$smarty->assign('type', $type);
	$smarty->assign('parent_id', $parent_id);
	$smarty->assign('cat_id', $cat_id);
	
	if (isset($content_id))
	{
		$smarty->assign('oontent_id', $content_id);
		$smarty->assign('oontent_id_string', "&content_id=$content_id");
	}
	$smarty->display($userdata['photo_user_template'].'/comment.tpl');
?>
