<?php
include_once('./includes/common.inc.php');
include_once('./classes/album_content.inc.php');
include_once('./includes/template.inc.php');
include_once('./modules/pic_managment/interface.inc.php');
include_once('./classes/categorie.inc.php');
include_once('./languages/'.$userdata['user_lang'].'/lang_main.php');
include_once('./includes/functions.inc.php');

$content = get_content_object_from_id($content_id);
if (!is_object($content))
{
	message_die(GENERAL_ERROR, "Could not generate content from id", '', __LINE__, __FILE__, $sql);
}

//get previous and next content and display the thumbnail if aviable
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


//Show comments
$root_comments = get_comments_of_content($content_id);
for ($i = 0; $i < sizeof($root_comments); $i++)
{
	make_comments($root_comments[$i],0);
}
$smarty->assign('comments',$comments);


$smarty->assign('lang', $lang);
$smarty->assign('nav_string', build_nav_string($cat_id));
$smarty->assign('html', $content->get_html());
$smarty->assign('name', $content->get_name());
$smarty->assign('content_id', $content->get_id());
$smarty->assign('views', $content->get_views());
$smarty->assign('current_rating', $content->get_current_rating());
$smarty->assign('cat_id', $cat_id);
//$smarty->assign('content_size',$content->get_content_size()); //thats the height and width of the object...
//$smarty->assign('id',$id);
$smarty->display($userdata['photo_user_template'].'/view_content.tpl');
?>
