<?php
define ("ROOT_PATH",'');
include_once('./includes/common.inc.php');
include_once('./classes/album_content.inc.php');
include_once('./classes/group.inc.php');
include_once('./modules/pic_managment/interface.inc.php');
include_once('./includes/functions.inc.php');
include_once('./languages/' . $userdata['user_lang'] . '/lang_main.php');
include_once('./includes/template.inc.php');

// move this to index.php
validate_config();



// bigbrother stop the view of the last viewed content
session_start();

stop_view($HTTP_SESSION_VARS['view_start'],$HTTP_SESSION_VARS['view_content_id']);
$HTTP_SESSION_VARS['view_start'] = 0;
$HTTP_SESSION_VARS['view_content_id'] = 0;

if (isset($HTTP_GET_VARS['first_content']))
{
	$HTTP_SESSION_VARS['first_content'] = $HTTP_GET_VARS['first_content'];
}


unset($HTTP_SESSION_VARS['contents']);




if (!isset($cat_id))
{
	$cat_id = $config_vars['root_categorie'];
	$template_file = 'index';
}


// create new categorie
if (isset($HTTP_POST_VARS['newcat']))
{
	$new_cat = new categorie();
	$new_cat->set_parent_id($cat_id);
	$new_cat->set_name($HTTP_POST_VARS['cat_name']);
	$new_cat->set_description($HTTP_POST_VARS['cat_describtion']);
	if ($HTTP_POST_VARS['cat_is_serie'] == 'on')
	{
		$new_cat->set_is_serie(1);
	}
	$new_cat->set_catgroup_id($HTTP_POST_VARS['add_to_catgroup']);
	$new_cat->commit();

}





//get the cats in the actual cat and information about them
$child_cats = get_cats_of_cat($cat_id);

if (isset($HTTP_POST_VARS['edit_cat']))
{
	for ($i = 0; $i < sizeof($child_cats); $i++)
	{
		$child_cats[$i]->set_name($HTTP_POST_VARS['cat_name'][$i]);
		$child_cats[$i]->set_description($HTTP_POST_VARS['cat_description'][$i]);
		$child_cats[$i]->set_catgroup_id($HTTP_POST_VARS['cat_catgroup'][$i],$HTTP_POST_VARS['cat_apply_recursive'][$i]);
		$child_cats[$i]->set_parent_id($HTTP_POST_VARS['cat_parent_cat'][$i]);

		if ($HTTP_POST_VARS['cat_delete'][$i] == 'on')
		{
			$error=$child_cats[$i]->delete('CDM_REMOVE_CONTENT');
			if ($error != OP_SUCCESSFUL)
			{
				error_report(GENERAL_ERROR, 'del_cat' , __LINE__, __FILE__,$error);
			}

		}
		else
		{
			$error=$child_cats[$i]->commit();
			if ($error != OP_SUCCESSFUL)
			{
				error_report(GENERAL_ERROR, 'cat_commit' , __LINE__, __FILE__,$error);
			}
		}

	}
	$child_cats = get_cats_of_cat($cat_id);
}



//Get the contents of the actual cat and their thumbnails plus information like
$category = new categorie;
$category->generate_from_id($cat_id);



if (isset($child_cats))
{
	for ($i = 0; $i < sizeof($child_cats); $i++)
	{
		$child_cat_infos[$i]['id'] = $child_cats[$i]->get_id();
		$child_cat_infos[$i]['parent_id'] = $child_cats[$i]->get_parent_id();
		$child_cat_infos[$i]['name'] = $child_cats[$i]->get_name();
		$child_cat_infos[$i]['description'] = $child_cats[$i]->get_description();
		$child_cat_infos[$i]['content_amount'] = $child_cats[$i]->get_content_amount();
		$child_cat_infos[$i]['content_child_amount'] = $child_cats[$i]->get_child_content_amount() - $child_cat_infos[$i]['content_amount'];
		$child_cat_infos[$i]['current_rating'] = $child_cats[$i]->get_current_rating();
		$child_cat_infos[$i]['remove_from_group'] = $child_cats[$i]->check_perm('remove_from_group');
		$child_cat_infos[$i]['delete'] = $child_cats[$i]->check_perm('delete');
		$child_cat_infos[$i]['edit'] = $child_cats[$i]->check_perm('edit');
		$child_cat_infos[$i]['catgroup_id'] = $child_cats[$i]->get_catgroup_id();
		$child_cat_infos[$i]['comments_amount'] = $child_cats[$i]->get_child_comments_amount();

	}
	// in edit mode check on which cats user has rights to remove cat
	if ($mode == 'edit')
	{
		$smarty->assign('allow_cat_remove',check_cat_action_allowed($category->get_catgroup_id(),$userdata['user_id'],'cat_remove'));
		$add_to_cats_unparsed = get_cats_data_where_perm('id,name','cat_add');
		$add_to_cats = get_cats_string($add_to_cats_unparsed);
		$smarty->assign('add_to_cats',$add_to_cats);
		
	}

	$smarty->assign('child_cat_infos',$child_cat_infos);
	$smarty->assign('number_of_child_cats',$i);

}
else
{
	//no child cats
	$smarty->assign('number_of_child_cats',0);
}


// check is user is allowed to add a child cat
$smarty->assign('allow_cat_add',check_cat_action_allowed($category->get_catgroup_id(),$userdata['user_id'],'cat_add'));
if ($mode == 'edit')
{
	$smarty->assign('mode','edit');
	$add_to_catgroups = get_catgroups_data_where_perm('id,name','add_to_group');
	$smarty->assign('add_to_catgroups',$add_to_catgroups);

	// get contentgroups where user has add rights
	$add_to_contentgroups = get_contentgroups_data_where_perm('id,name','add_to_group');
	if (is_array($add_to_contentgroups))
	{
		$smarty->assign('add_to_contentgroups',$add_to_contentgroups);
	}


}

// check if user is allowed to add content
if (check_cat_action_allowed($category->get_catgroup_id(),$userdata['user_id'],'content_add'))
{
	$smarty->assign('allow_content_add',true);

	// get catgroups where add_to_group is allowed
	$add_to_contentgroups = get_contentgroups_data_where_perm('id,name','add_to_group');
	$smarty->assign('add_to_contentgroups',$add_to_contentgroups);

	if (isset($HTTP_POST_VARS['newcontent']))
	{
		$objtyp = $filetypes[getext($HTTP_POST_FILES['new_content_file']['name'])];
		if (isset($objtyp))
		{
			add_content($HTTP_POST_FILES,$HTTP_POST_VARS['new_content_name'],$cat_id,$HTTP_POST_VARS['new_content_place_in_cat'],$HTTP_POST_VARS['new_content_group']);
		}
	}
}



if (intval($HTTP_SESSION_VARS['first_content']) == '')
{
	$HTTP_SESSION_VARS['first_content'] = 0;
}

if (!isset($HTTP_GET_VARS['content_per_page']))
{
	if (!isset($HTTP_SESSION_VARS['content_per_page']))
	{
		$content_per_page = $userdata['content_per_page'];
		$HTTP_SESSION_VARS['content_per_page'] = $content_per_page;
	}
	else
	{
		$content_per_page = $HTTP_SESSION_VARS['content_per_page'];
	}
}
else
{
	$content_per_page = $HTTP_GET_VARS['content_per_page'];
	$HTTP_SESSION_VARS['content_per_page'] = $content_per_page;
	
	if ($HTTP_SESSION_VARS['content_per_page'] == -1)
	{
		$HTTP_SESSION_VARS['first_content'] = 0;
	}
	elseif ($HTTP_SESSION_VARS['first_content'] + $HTTP_SESSION_VARS['content_per_page'] > $category->get_content_amount())
	{
		$HTTP_SESSION_VARS['first_content'] = intval($category->get_content_amount() / $HTTP_SESSION_VARS['content_per_page']) * $HTTP_SESSION_VARS['content_per_page'];
	}
	
}


$smarty->assign('content_per_page',$content_per_page);
$contents = get_content_of_cat($cat_id,$HTTP_SESSION_VARS['first_content'],$content_per_page);
include "includes/view_thumbs.php";


// build navigtion

if ($content_per_page > 0)
{
	$i=0;
	while (($i*$content_per_page)<$category->get_content_amount())
	{
		$cat_nav_links[] = $i * $content_per_page;
		$i++;
	}

	// only assign if there is more than 1 page
	if (sizeof($cat_nav_links)>1)
	{
		$smarty->assign('cat_nav_links',$cat_nav_links);
	}
	$smarty->assign('first_content',$HTTP_SESSION_VARS['first_content']);

	if ($HTTP_SESSION_VARS['first_content']+$content_per_page>=$category->get_content_amount())
	{
		$smarty->assign('first_content_next',0);
	}
	else
	{
		$smarty->assign('first_content_next',$HTTP_SESSION_VARS['first_content']+$content_per_page);
	}

	if ($HTTP_SESSION_VARS['first_content']-$userdata['content_per_page'] < 0)
	{
		$smarty->assign('first_content_prev', (int)($category->get_content_amount()/$content_per_page)*$content_per_page);
	}
	else
	{
		$smarty->assign('first_content_prev', $HTTP_SESSION_VARS['first_content']-$content_per_page);
	}
}

// content_per_page selector
if (is_array($config_vars['selectable_content_per_page']))
{
	foreach ($config_vars['selectable_content_per_page'] as $key => $value)
	{
		$op['amount'] = $value;
		if ($value == -1)
		{
			$op['text'] = 'all';
		}
		else
		{
			$op['text'] = $value;
		}
		$selector_options[]=$op;
	}
	$smarty->assign('selectable_content_per_page',$selector_options);
}


$smarty->assign('cat_id',$cat_id);


// proceed comments
$comment_type='cat';
include ('includes/proceed_comment.inc.php');
//Show comments



$root_comments = get_comments_of_cat($cat_id);


if (sizeof($root_comments) > 0)
{

	for ($i = 0; $i < sizeof($root_comments); $i++)
	{
	
		make_comments($root_comments[$i],0,check_cat_action_allowed($cat_id,$userdata['user_id'],'comment_edit'));
	}
	$smarty->assign('comments',$comments);
}
else
{
	$smarty->assign('comments','false');
}



//link where to go when back to thumbs
$HTTP_SESSION_VARS['thumb_link']="view_cat.php?cat_id=$cat_id";
$smarty->assign('thumb_link',$HTTP_SESSION_VARS['thumb_link']);
$smarty->assign('current_page',$HTTP_SESSION_VARS['thumb_link']);


//thats for the index.php who needs another template file. index.php just set the $template_file to another value and includes this file
if (!isset($template_file))
{
	$template_file = 'view_cat';
}


$smarty->assign('nav_string', build_nav_string($cat_id));
$smarty->assign('redirect', PHREAKPIC_PATH . "$template_file.php");
$smarty->assign('thumb_size', $config_vars['thumb_size']['maxsize']);
$smarty->assign('title_page',$lang['view_cat']);
$smarty->assign('title_name',$category->get_name());


$end_time = getmicrotime();
$execution_time = $end_time - $start_time;

$smarty->display($userdata['photo_user_template']."/$template_file.tpl");
$template_end_time = getmicrotime();
$template_execution_time = $template_end_time - $end_time;
echo("execution_time: $execution_time seconds<br>");
echo("template_execution_time: $template_execution_time seconds<br>");
$execution_time = $end_time - $start_time + $template_execution_time;
echo("gesamt execution_time: $execution_time seconds<br>");
?>
