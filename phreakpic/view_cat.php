<?php
include_once('./includes/common.inc.php');
include_once('./classes/album_content.inc.php');
include_once('./includes/template.inc.php');
include_once('./modules/pic_managment/interface.inc.php');
include_once('./languages/'.$userdata['user_lang'].'/lang_main.php');
include_once('./includes/functions.inc.php');

if (!isset($cat_id))
{
	$cat_id = $config_vars['root_categorie'];
	$template_file = 'index';
}

//get the cats in the actual cat and information about them
$child_cats = get_cats_of_cat($cat_id);
if (isset($child_cats))
{
	for ($i = 0; $i < sizeof($child_cats); $i++)
	{
		$child_cat_infos[$i]['id'] = $child_cats[$i]->get_id();
		$child_cat_infos[$i]['name'] = $child_cats[$i]->get_name();
		$child_cat_infos[$i]['description'] = $child_cats[$i]->get_description();
		$child_cat_infos[$i]['content_amount'] = $child_cats[$i]->get_content_amount();
		$child_cat_infos[$i]['current_rating'] = $child_cats[$i]->get_current_rating();
	}
	$smarty->assign('child_cat_infos',$child_cat_infos);
	$smarty->assign('number_of_child_cats',$i);
}
else
{
	//no child cats
	$smarty->assign('number_of_child_cats',0);
}


//Get the contents of the actual cat and their thumbnails plus information like
$category = new categorie;
$category->generate_from_id($cat_id);
$contents = get_content_of_cat($cat_id);
if (is_array($contents))
{
	//editing the contents
	if ((isset($submit)) and ($HTTP_POST_VARS['mode'] == 'edited'))
	{
		for ($i = 0; $i < sizeof($HTTP_POST_VARS['name']); $i++)
		{
			if ($contents[$HTTP_POST_VARS['place_in_array'][$i]]->set_name($HTTP_POST_VARS['name'][$i]) != OP_SUCCESSFUL)
			{
				die('Konnte Name '.$HTTP_POST_VARS['name'][$i].' von '.$HTTP_POST_VARS['content_id'][$i].' nicht setzen ('.$i);
			}
			if ($HTTP_POST_VARS['lock'][$HTTP_POST_VARS['content_id'][$i]] == 'true')
			{
				if ($contents[$HTTP_POST_VARS['place_in_array'][$i]]->lock() != OP_SUCCESSFUL)
				{
					die('Konnte '.$HTTP_POST_VARS['name'][$i].' nicht locken');
				}	
			}
			if ($HTTP_POST_VARS['delete'][$HTTP_POST_VARS['content_id'][$i]] == 'true')
			{
				if ($contents[$HTTP_POST_VARS['place_in_array'][$i]]->remove_from_cat($cat_id) != OP_SUCCESSFUL)
				{
					die ('Konnte '.$HTTP_POST_VARS['name'][$i].' nicht löschen');
				}
			}
			$contents[$HTTP_POST_VARS['place_in_array'][$i]]->commit();
		}
		$smarty->assign('mode','view');
		$smarty->assign('edited',true);
		$contents = get_content_of_cat($cat_id);
	}
	
	
	//show thumbnails and get some infos about the content
	for ($i = 1; $i <= sizeof($contents); $i++)
	{
		$thumb_infos = $contents[$i-1]->get_thumb();
		if ($mode == 'edit')
		{
			$smarty->assign('mode','edit');
			$thumb_infos['allow_edit'] = $contents[$i-1]->check_perm('edit');
			$thumb_infos['allow_unlink'] = check_cat_action_allowed($category->get_catgroup_id(),$userdata['user_id'],'content_remove');
			$thumb_infos['place_in_array'] = $i-1;
		}
		$array_row[] = $thumb_infos;
		if ($i % $config_vars['thumb_table_cols'] == 0)
		{
			$thumbs[]=$array_row;
			unset($array_row);
		}
		
	}
	$thumbs[]=$array_row;
	$smarty->assign('thumbs',$thumbs);
	$smarty->assign('cat_id',$cat_id);
	$smarty->assign('is_content', true);
}

// Comments
if ($mode == "add")
{
	// add a new comment
	$comment = new cat_comment();
	$comment->set_feedback($comment_text);
	$comment->set_topic($topic);
	$comment->set_user_id($userdata['user_id']);
	$comment->set_owner_id($cat_id);
	$comment->set_parent_id($parent_id);
	$comment->commit();
}

//Show comments
$root_comments = get_comments_of_cat($cat_id);
if (sizeof($root_comments) > 0)
{
	for ($i = 0; $i < sizeof($root_comments); $i++)
	{
		make_comments($root_comments[$i],0);
	}
	$smarty->assign('comments',$comments);
}
else
{
	$smarty->assign('comments','false');
}




//thats for the index.php who needs another template file. index.php just set the $template_file to another value and includes this file
if (!isset($template_file))
{
	$template_file = 'view_cat';
}


$smarty->assign('nav_string', build_nav_string($cat_id));
$smarty->assign('lang',$lang);
$smarty->assign('template_name', $userdata['photo_user_template']);


$end_time = getmicrotime();
$execution_time = $end_time - $start_time;

$smarty->display($userdata['photo_user_template'].'/'.$template_file.'.tpl');
$template_end_time = getmicrotime();
$template_execution_time = $template_end_time - $end_time;
echo("execution_time: $execution_time seconds<br>");
echo("template_execution_time: $template_execution_time seconds<br>");
$execution_time = $end_time - $start_time + $template_execution_time;
echo("gesamt execution_time: $execution_time seconds<br>");	
?>
