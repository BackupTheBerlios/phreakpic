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

// delete cat
if (isset($HTTP_POST_VARS['cat_delete']))
{
	$del_cat = new categorie();
	$del_cat->generate_from_id($HTTP_POST_VARS['cat_delete']);
	$del_cat->delete('CDM_REMOVE_CONTENT');
	
}



//get the cats in the actual cat and information about them
$child_cats = get_cats_of_cat($cat_id);


//Get the contents of the actual cat and their thumbnails plus information like
$category = new categorie;
$category->generate_from_id($cat_id);



if (isset($child_cats))
{
	for ($i = 0; $i < sizeof($child_cats); $i++)
	{
		$child_cat_infos[$i]['id'] = $child_cats[$i]->get_id();
		$child_cat_infos[$i]['name'] = $child_cats[$i]->get_name();
		$child_cat_infos[$i]['description'] = $child_cats[$i]->get_description();
		$child_cat_infos[$i]['content_amount'] = $child_cats[$i]->get_content_amount();
		$child_cat_infos[$i]['content_child_amount'] = $child_cats[$i]->get_child_content_amount();
		$child_cat_infos[$i]['current_rating'] = $child_cats[$i]->get_current_rating();
		
	}
	// in edit mode check on which cats user has rights to remove cat
	if ($mode == 'edit')
	{
		$smarty->assign('allow_cat_remove',check_cat_action_allowed($category->get_catgroup_id(),$userdata['user_id'],'cat_remove'));
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
			
			
/*			$new_content = new $objtyp;
						
			// endgültigen dateinamen generieren und das tmp file verschieben. Weil das object nicht des dateiendung bekommen würde, wenn nur file=tmp_file und name=irgenwas gesätzt wäare
			$new_content->file = $HTTP_POST_FILES['new_content_file']['name'];
			$new_content->add_to_cat($cat_id);
			if ($HTTP_POST_VARS['new_content_name'] != "")
			{
				$new_content->set_name($HTTP_POST_VARS['new_content_name']);
			}
			else
			{
				$new_content->set_name(getfile($HTTP_POST_FILES['new_content_file']['name']));
			}
			
			$new_file_name = $new_content->generate_filename();
			//echo "source: ".$HTTP_POST_FILES['new_content_file']['tmp_name'];
			rename ($HTTP_POST_FILES['new_content_file']['tmp_name'], $new_file_name); 
			$new_content->file = $new_file_name;
						
			$new_content->set_place_in_cat($cat_id,$HTTP_POST_VARS['new_content_place_in_cat']);
			$new_content->set_contentgroup_id($HTTP_POST_VARS['new_content_group']);
			
			
			$new_content->commit();*/
			add_content($HTTP_POST_FILES,$HTTP_POST_VARS['new_content_name'],$cat_id,$HTTP_POST_VARS['new_content_place_in_cat'],$HTTP_POST_VARS['new_content_group']);
		}
	}
}



if (!isset($first_content))
{
	$first_content = 0;
}

$contents = get_content_of_cat($cat_id,$first_content,$userdata['content_per_page']);

include "includes/view_thumbs.php";


// build navigtion 
$i=0;
while (($i*$userdata['content_per_page'])<$category->get_content_amount())
{
	$cat_nav_links[] = $i * $userdata['content_per_page'];
	$i++;
}

$smarty->assign('cat_nav_links',$cat_nav_links);
$smarty->assign('first_content',$first_content);

if ($first_content+$userdata['content_per_page']>$category->get_content_amount())
{
	$smarty->assign('first_content_next',0);
}
else
{
	$smarty->assign('first_content_next',$first_content+$userdata['content_per_page']);
}

if ($first_content-$userdata['content_per_page']<0)
{
	$smarty->assign('first_content_prev',(int)($category->get_content_amount()/$userdata['content_per_page'])*$userdata['content_per_page']);
}
else
{
	$smarty->assign('first_content_prev',$first_content-$userdata['content_per_page']);
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


//thats for the index.php who needs another template file. index.php just set the $template_file to another value and includes this file
if (!isset($template_file))
{
	$template_file = 'view_cat';
}


$smarty->assign('nav_string', build_nav_string($cat_id));
$smarty->assign('redirect', PHREAKPIC_PATH . "$template_file.php");
$smarty->assign('thumb_size', $config_vars['thumb_size']['maxsize']);


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
