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
	error_report(INFORMATION, 'content_not_existing' , __LINE__, __FILE__);
	
}

// if there is no cat_id assigned take the first cat of the content

if (!isset($cat_id))
{
	$ids=$content->get_cat_ids();
	$cat_id=$ids[0];
}

//get previous and next content and display the thumbnail if aviable 
// $surrounding_content = $content->get_surrounding_content($cat_id);
if (isset($HTTP_SESSION_VARS['contents']))
{
	for ($i=0;$i<sizeof($HTTP_SESSION_VARS['contents']);$i++)
	{
		if ($HTTP_SESSION_VARS['contents'][$i]->get_id() == $content_id)
		{
			$surrounding_content['next']=$HTTP_SESSION_VARS['contents'][$i+1];
			$surrounding_content['prev']=$HTTP_SESSION_VARS['contents'][$i-1];
			break;
		}
	}
}
else
{
	$surrounding_content = $content->get_surrounding_content($cat_id);
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
	if (isset($HTTP_GET_VARS['slideshow']))
	{
		$smarty->assign('meta',"<meta http-equiv=\"refresh\" content=\"{$HTTP_GET_VARS['slideshow']}; URL=view_content.php?cat_id=$cat_id&content_id={$surrounding_content['next']->id}&slideshow={$HTTP_GET_VARS['slideshow']}&$sid#pic\">");
	}

}


// do commit
if ($mode=='commit')
{
	$vals['name']=$HTTP_POST_VARS['name'];
	$vals['place_in_cat']=$HTTP_POST_VARS['place_in_cat'];
	$vals['lock']=$HTTP_POST_VARS['lock'];
	$vals['rotate']=$HTTP_POST_VARS['rotate'];
	$vals['rotate_mode']=$HTTP_POST_VARS['rotate_mode'];
	$vals['unlink']=$HTTP_POST_VARS['unlink'];
	$vals['link']=$HTTP_POST_VARS['link'];
	$vals['to_cat']=$HTTP_POST_VARS['to_cat'];
	$vals['move']=$HTTP_POST_VARS['move'];
	$vals['change_group']=$HTTP_POST_VARS['change_group'];
	$vals['to_contentgroup']=$HTTP_POST_VARS['to_contentgroup'];
	$vals['delete']=$HTTP_POST_VARS['delete'];
	$redirect_to_cat=$content->edit_content($vals,$cat_id);
}

//check if in edit mode
$edit_info['allow_edit'] = $content->check_perm('edit');
if ($mode=="edit")
{
	$smarty->assign('mode','edit');
	// get edit values from content obj
	$edit_info = $content->get_editable_values($cat_id);
	
	// add to cats
	$add_to_cats_unparsed = get_cats_data_where_perm('id,name','content_add');
	$add_to_cats = get_cats_string($add_to_cats_unparsed);
	if (is_array($add_to_cats))
	{
		$smarty->assign('allow_link',true);	
		$smarty->assign('add_to_cats',$add_to_cats);
	}

	// Check if the user has remove_from_group right for this content	
	if ($content->check_perm('remove_from_group'))
	{
		// get the groups where the user has add_to_group rights
		$add_to_contentgroups = get_contentgroups_data_where_perm('id,name','add_to_group');
		if (is_array($add_to_contentgroups))
		{
			
			$smarty->assign('add_to_contentgroups',$add_to_contentgroups);
			$smarty->assign('contentgroup',$content->get_contentgroup_id());
		}
	}
}
$smarty->assign('edit_info',$edit_info);

// Check if user has content_remove rights on this categorie
$cat_obj = new categorie();
$cat_obj->generate_from_id($cat_id);
if (check_cat_action_allowed($cat_obj->get_catgroup_id(),$userdata['user_id'],'content_remove'))
{
	$smarty->assign('allow_content_remove',1);	
}

if ($redirect_to_cat)
{
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


// show content
$smarty->assign('nav_string', build_nav_string($cat_id));
$content->inc_views();
$smarty->assign('html', $content->get_html());
$smarty->assign('name', $content->get_name());
$smarty->assign('content_id', $content->get_id());
$smarty->assign('views', $content->get_views());
$smarty->assign('current_rating', $content->get_current_rating());
$smarty->assign('cat_id', $cat_id);
$smarty->assign('redirect', PHREAKPIC_PATH . 'view_content.php');

//calculate first_content
if ($userdata['content_per_page']>0)
{
	$first_content = (int)($surrounding_content['place']/$userdata['content_per_page'])*$userdata['content_per_page'];
}
//assign link back to thumbs;
$smarty->assign('thumb_link',$HTTP_SESSION_VARS['thumb_link']."&first_content=".$first_content);

$smarty->assign('content_height',$content->height);
$smarty->assign('content_width',$content->width);

//titel
$smarty->assign('title_site',$board_config['sitename']);
$smarty->assign('title_page',$lang['view_content']);
$smarty->assign('title_name',$content->get_name());

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
