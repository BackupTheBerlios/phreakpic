<?php
require_once('./includes/common.inc.php');
require_once('./classes/album_content.inc.php');
require_once('./includes/template.inc.php');
include_once('./modules/pic_managment/interface.inc.php');

$content = get_content_object_from_id($content_id);
if (!is_object($content))
{
	message_die(GENERAL_ERROR, "Could not generate content from id", '', __LINE__, __FILE__, $sql);
}

$surrounding_content = $content->get_surrounding_content($cat_id);


if (is_object($surrounding_content['prev']))
{
	$smarty->assign('is_prev_content', true);
	$smarty->assign('prev_thumb',$surrounding_content['prev']->get_thumb());
}

if (is_object($surrounding_content['next']))
{
	$smarty->assign('is_next_content', true);
	$smarty->assign('next_thumb',$surrounding_content['next']->get_thumb());
}

$smarty->assign('html', $content->get_html());
$smarty->assign('name', $content->get_name());
$smarty->assign('cat_id', $cat_id);
//$smarty->assign('content_size',$content->get_content_size()); //thats the height and width of the object...
//$smarty->assign('id',$id);
$smarty->display($userdata['photo_user_template'].'/view_content.tpl');
?>
