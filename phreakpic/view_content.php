<?php
define ("ROOT_PATH",'');
include_once('./includes/common.inc.php');
include_once('./includes/template.inc.php');
include_once('./classes/album_content.inc.php');
include_once('./modules/pic_managment/interface.inc.php');
include_once('./classes/categorie.inc.php');
include_once('./includes/functions.inc.php');
include_once('classes/user_feedback.inc.php');

session_start();


stop_view($HTTP_SESSION_VARS['view_start'],$HTTP_SESSION_VARS['view_content_id']);
$HTTP_SESSION_VARS['view_start'] = 0;
$HTTP_SESSION_VARS['view_content_id'] = 0;



// proceed comments
$comment_type='content';
include ('includes/proceed_comment.inc.php');




$content = get_content_object_from_id($content_id);
if (!is_object($content))
{
	message_die(GENERAL_ERROR, "Could not generate content from id", '', __LINE__, __FILE__, $sql);
}



// if there is no cat_id assigned take the first cat of the content
if (!is_integer($cat_id))
{
	$ids=$content->get_cat_ids();
	$cat_id=$ids[0];
}



//get previous and next content and display the thumbnail if aviable 

// $surrounding_content = $content->get_surrounding_content($cat_id);
for ($i=0;$i<sizeof($HTTP_SESSION_VARS['contents']);$i++)
{
	if ($HTTP_SESSION_VARS['contents'][$i]->get_id() == $content_id)
	{
		$surrounding_content['next']=$HTTP_SESSION_VARS['contents'][$i+1];
		$surrounding_content['prev']=$HTTP_SESSION_VARS['contents'][$i-1];
	}
}


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


// Check if user has rights to add content to a cat

$add_to_cats = get_cats_data_where_perm('id,name','content_add');
if (is_array($add_to_cats))
{
	$smarty->assign('allow_link',1);	
	if ($mode == "edit")
	{
		
		$smarty->assign('add_to_cats',$add_to_cats);
		$smarty->assign('mode','edit');
	}
	if ($mode == "commit")
	{
		// check link
		if ($HTTP_POST_VARS['link'] == "on")
		{
			$content->add_to_cat($HTTP_POST_VARS['to_cat']);
		}
		
				
	}


}

// Check if user has content_remove rights on this categorie
$cat_obj = new categorie();
$cat_obj->generate_from_id($cat_id);
if (check_cat_action_allowed($cat_obj->get_catgroup_id(),$userdata['user_id'],'content_remove'))
{
	$smarty->assign('allow_content_remove',1);
	if ($mode == "edit")
	{
		$smarty->assign('mode','edit');
	}
	
	if ($mode == "commit")
	{
		
		//rotate
		if ($HTTP_POST_VARS['rotate_mode'] == 'free')
		{
			if (intval($HTTP_POST_VARS['rotate'])!=0) 
			{
				$content->rotate($HTTP_POST_VARS['rotate']);
			}
		}
		else
		{
			$content->rotate($HTTP_POST_VARS['rotate_mode']);
		}
		
		// check unlink
		if ($HTTP_POST_VARS['unlink'] == "on")
		{
			$content->remove_from_cat($cat_id);
			$redirect_to_cat=true;
		}
		
		// check move
		// check if user has clicked the button
		if ($HTTP_POST_VARS['move'] == "on")
		{
			// check if user has cats to move in
			if (is_array($add_to_cats))
			{
				$content->remove_from_cat($cat_id);
				$content->add_to_cat($HTTP_POST_VARS['to_cat']);
				$redirect_to_cat=true;
			}
		}
	}
}

// Check if the user has remove_from_group right for this content
if ($content->check_perm('remove_from_group'))
{
	if ($mode == 'edit')
	{
		// get the groups where the user has add_to_group rights
		$add_to_contentgroups = get_contentgroups_data_where_perm('id,name','add_to_group');
		if (is_array($add_to_contentgroups))
		{
			$smarty->assign('allow_change_group','1');
			$smarty->assign('add_to_contentgroups',$add_to_contentgroups);
			$smarty->assign('contentgroup',$content->get_contentgroup_id());
		}
	}
	if ($mode == 'commit')
	{
		if ($HTTP_POST_VARS['change_group'] == "on")
		{
			$content->set_contentgroup_id($HTTP_POST_VARS['to_group']);
		}
	}
}


// Check if user has edit rights to this content
if ($content->check_perm('edit'))
{
	$smarty->assign('allow_edit',1);
	if ($mode == "edit")
	{
		// edit this picture
		$smarty->assign('mode','edit');
		$place_in_cat_array = $content->get_place_in_cat();
		$smarty->assign('place_in_cat',$place_in_cat_array[$cat_id]);
		if($content->get_locked())
		{
			$smarty->assign('locked','checked');
		}
		
		// check if user has unlink rights
		
	}
	
	if ($mode == "commit")
	{
		// change values of the picture
		if ($HTTP_POST_VARS['lock'] == "on")
		{
			$content->lock();
		}
		else
		{
			$content->unlock();
		}
		
		$content->set_place_in_cat($cat_id,$HTTP_POST_VARS['place_in_cat']);
		
		$content->set_name($HTTP_POST_VARS['name']);
	}
}

if ($mode == "commit")
{
	// commit all changes
	$content->commit();	
}
// check delete
if ($content->check_perm('delete'))
{

	$smarty->assign('allow_delete',1);	
	if ($mode == "commit")
	{
		// check unlink
		if ($HTTP_POST_VARS['delete'] == "on")
		{
			
			$content->delete();
			$redirect_to_cat=true;
		}
	}


}

if ($redirect_to_cat)
{
	// redirect to cat view

	$header_location = ( @preg_match("/Microsoft|WebSTAR|Xitami/", 
	getenv("SERVER_SOFTWARE")) ) ? "Refresh: 0; URL=" : "Location: ";
	header($header_location . append_sid("view_cat.php?cat_id=$cat_id", true));
}



	


//Show comments
$root_comments = get_comments_of_content($content_id);
for ($i = 0; $i < sizeof($root_comments); $i++)
{
	make_comments($root_comments[$i],0,$content->check_perm('comment_edit'));
}
$smarty->assign('comments',$comments);


$smarty->assign('nav_string', build_nav_string($cat_id));
$smarty->assign('html', $content->get_html());
$smarty->assign('name', $content->get_name());
$smarty->assign('content_id', $content->get_id());
$smarty->assign('views', $content->get_views());
$smarty->assign('current_rating', $content->get_current_rating());
$smarty->assign('cat_id', $cat_id);
$smarty->assign('redirect', PHREAKPIC_PATH . 'view_content.php');

//assign link back to thumbs;
$smarty->assign('thumb_link',$HTTP_SESSION_VARS['thumb_link']);


//$smarty->assign('content_size',$content->get_content_size()); //thats the height and width of the object...
//$smarty->assign('id',$id);
$end_time = getmicrotime();
$execution_time = $end_time - $start_time;
$smarty->display($userdata['photo_user_template'].'/view_content.tpl');
$template_end_time = getmicrotime();
$template_execution_time = $template_end_time - $end_time;
echo("execution_time: $execution_time seconds<br>");
echo("template_execution_time: $template_execution_time seconds<br>");
$execution_time = $end_time - $start_time + $template_execution_time;
echo("gesamt execution_time: $execution_time seconds<br>");

$HTTP_SESSION_VARS['view_start'] = $content->start_view();
$HTTP_SESSION_VARS['view_content_id'] = $content_id;

?>
