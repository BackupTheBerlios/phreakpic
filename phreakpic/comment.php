<?php
define ("ROOT_PATH",'');
include_once ("includes/common.inc.php");
include_once('./includes/template.inc.php');
include_once('./classes/user_feedback.inc.php');
include_once('./modules/pic_managment/interface.inc.php');
	

session_start();
stop_view($HTTP_SESSION_VARS['view_start'],$HTTP_SESSION_VARS['view_content_id']);
$HTTP_SESSION_VARS['view_start'] = 0;
$HTTP_SESSION_VARS['view_content_id'] = 0;

	

$smarty->assign('mode', $mode);
$smarty->assign('type', $HTTP_GET_VARS['type']);
if (isset($content_id))
{
	$content_obj = get_content_object_from_id($content_id);
	$smarty->assign('oontent_html', $content_obj->get_html());
	$smarty->assign('oontent_id', $content_id);
	$smarty->assign('oontent_id_string', "&content_id=$content_id");
}

// get parent comments 
$class=$HTTP_GET_VARS['type']."_comment";
// get root comment
$root_parent_id=$parent_id;
while ($root_parent_id != 0)
{
 $parent_comment= new $class;
 $parent_comment->generate_from_id($root_parent_id);
 $root_parent_id = $parent_comment->get_parent_id();
 $root_id = $parent_comment->id;
}
if ($root_id != 0)
{
	$root_comment = new $class;
	$root_comment->generate_from_id($root_id);
	make_comments($root_comment,0,false);
	$smarty->assign('comments',$comments);
}




$smarty->assign('cat_id', $cat_id);
	
	if ($mode == "add")
	{
		$smarty->assign('parent_id', $parent_id);
	}
	
	if ($mode == 'edit_comment')
	{
		if ($HTTP_GET_VARS['type'] == 'content')
		{
			$comment = new content_comment();
		}
		else
		{
			$comment = new cat_comment();
		}
		$comment->generate_from_id($id);
		$smarty->assign('text',$comment->get_feedback());
		$smarty->assign('topic',$comment->get_topic());
		// parent id is just as id here
		$smarty->assign('parent_id',$id);
	}
$smarty->display($userdata['photo_user_template'].'/comment.tpl');
?>
