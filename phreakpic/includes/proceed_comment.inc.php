<?php

$class=$comment_type."_comment";
$id=$comment_type."_id";


// Comments
if ($mode == "add")
{
	// add a new comment
	$comment = new $class;
	$comment->set_feedback($comment_text);
	$comment->set_topic($topic);
	$comment->set_user_id($userdata['user_id']);
	$comment->set_owner_id($$id);
	$comment->set_parent_id($parent_id);
	$comment->commit();
}

if ($mode == 'edit_comment')
{
	$comment = new $class;
	$comment->generate_from_id($HTTP_POST_VARS['parent_id']);
	$comment->set_feedback($HTTP_POST_VARS['comment_text']);
	$comment->set_topic($HTTP_POST_VARS['topic']);
	$comment->set_changed_count($comment->get_changed_count()+1);
	$comment->set_last_changed_date(date("Y-m-d H:i:s"));
	$comment->commit();	
}

if ($mode == 'del_comment')
{
	$comment = new $class;
	$comment->generate_from_id($comment_id);
	$comment->delete();
}
	
	
?>
