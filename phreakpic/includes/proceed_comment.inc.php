<?php

$class=$comment_type."_comment";
$id=$comment_type."_id";


if ($HTTP_GET_VARS['mode'] == 'edit_comments')
{
	$comment_edit=true;
	$smarty->assign('mode','edit_comments');
}
else
{
	$comment_edit=false;
}



// Comments
if ($HTTP_POST_VARS['mode'] == "add")
{
	// add a new comment
	$comment = new $class;
	$comment->set_feedback($comment_text);
	$comment->set_topic($topic);
	$comment->set_user_id($userdata['user_id']);
	$comment->set_owner_id($$id);
	$comment->set_parent_id($parent_id);
	$comment->set_poster_name($poster_name);
	$comment->commit();
}

if ($HTTP_POST_VARS['mode'] == 'edit_comment')
{
	$comment = new $class;
	$error = $comment->generate_from_id($HTTP_POST_VARS['parent_id']);
	if ($error == OP_SUCCESSFUL)
	{
		$comment->set_feedback($HTTP_POST_VARS['comment_text']);
		$comment->set_topic($HTTP_POST_VARS['topic']);
		$comment->set_poster_name($HTTP_POST_VARS['poster_name']);

		// dont change user id if it has not set (occures when normal users edit their postings)
		if (isset($HTTP_POST_VARS['user_id']))
		{
			$comment->set_user_id($HTTP_POST_VARS['user_id']);
		}
		$comment->set_changed_count($comment->get_changed_count()+1);
		$comment->set_last_changed_date(date("Y-m-d H:i:s"));
		$comment->commit();
	}
}


if ($HTTP_GET_VARS['mode'] == 'del_comment')
{

	$comment = new $class;
	$error = $comment->generate_from_id($comment_id);
	if ($error == OP_SUCCESSFUL)
	{
		$comment->delete();
	}

}

if (isset($HTTP_POST_VARS['edit_comments']))
{
	foreach ($HTTP_POST_VARS['comment_move'] as $comment_id)
	{
		if ($comment_id != $HTTP_POST_VARS['comment_to'])
		{
			$comment = new $class;
			$comment->generate_from_id($comment_id);
			$comment->set_parent_id($HTTP_POST_VARS['comment_to']);
			$comment->commit();
		}
	}
	
}


	
?>
