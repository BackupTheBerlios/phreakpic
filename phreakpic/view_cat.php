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
	$new_cat->set_catgroup_id($HTTP_POST_VARS['cat_group']);
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
}

// check if user is allowed to add content
if (check_cat_action_allowed($category->get_catgroup_id(),$userdata['user_id'],'content_add'))
{
	$smarty->assign('allow_content_add',true);
	if (isset($HTTP_POST_VARS['newcontent']))
	{
		$objtyp = $filetypes[getext($HTTP_POST_FILES['new_content_file']['name'])];
		if (isset($objtyp))
		{
			
			
			$new_content = new $objtyp;
						
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
			
			
			$new_content->commit();
		}
	}
}





$contents = get_content_of_cat($cat_id);

if (is_array($contents))
{
	//editing the contents
	if ((isset($submit)) and ($HTTP_POST_VARS['mode'] == 'edited'))
	{
		$add_to_cats = get_cats_data_where_perm('id,name','content_add');
		// loop through all content
		for ($i = 0; $i < sizeof($contents); $i++)	
		{
			
			
			// set everything for edit
			if ($contents[$i]->check_perm('edit'))
			{	
				// name
				if ($contents[$i]->set_name($HTTP_POST_VARS['name'][$i]) != OP_SUCCESSFUL)
				{
					die('Konnte Name '.$HTTP_POST_VARS['name'][$i].' von '.$HTTP_POST_VARS['content_id'][$i].' nicht setzen ('.$i);
				}
				// place_in_cat
				
				if ($contents[$i]->set_place_in_cat($cat_id,$HTTP_POST_VARS['place_in_cat'][$i]) != OP_SUCCESSFUL)
				{
					die('Konnte Place in cat '.$HTTP_POST_VARS['place_in_cat'][$i].' von '.$HTTP_POST_VARS['content_id'][$i].' nicht setzen ('.$i);
				}
				
				// lock
				
				if ($HTTP_POST_VARS['lock'][$i] == 'on')
				{
					
					if ($contents[$i]->lock() != OP_SUCCESSFUL)
					{
						die('Konnte '.$HTTP_POST_VARS['name'][$i].' nicht locken');
					}	
				}
				else
				{
					$contents[$i]->unlock();
				}
			}
			
			
			// check unlink
			if (check_cat_action_allowed($category->get_catgroup_id(),$userdata['user_id'],'content_remove'))
			{
				if ($HTTP_POST_VARS['unlink'][$i] == 'on')
				{
				
					if ($contents[$i]->remove_from_cat($cat_id) != OP_SUCCESSFUL)
					{
						die ('Konnte '.$HTTP_POST_VARS['name'][$i].' nicht von der cat entfernen');
					}
				}				
			}
			
			// check link
			
			if (is_array($add_to_cats))
			{
				// echo "I: ".$i." id ".$contents[$i]->id." link: ".$HTTP_POST_VARS['link'][$i]."<br>";
				if ($HTTP_POST_VARS['link'][$i] == 'on')
				{
					if ($contents[$i]->add_to_cat($HTTP_POST_VARS['to_cat']) != OP_SUCCESSFUL)
					{
						die ('Konnte '.$HTTP_POST_VARS['name'][$i].' nicht linken');
					}
				}
				// check if you have content remove rights
				if (check_cat_action_allowed($category->get_catgroup_id(),$userdata['user_id'],'content_remove'))
				{
					if ($HTTP_POST_VARS['move'][$i] == 'on')
					{
						if ($contents[$i]->add_to_cat($HTTP_POST_VARS['to_cat']) != OP_SUCCESSFUL)
						{
							die ('Konnte '.$HTTP_POST_VARS['name'][$i].' nicht moven (add)');
						}
						if ($contents[$i]->remove_from_cat($cat_id) != OP_SUCCESSFUL)
						{
							die ('Konnte '.$HTTP_POST_VARS['name'][$i].' nicht moven (remove)');
						}
					}
				}
					
			}
			
			$contents[$i]->commit();
			// check delete
			if ($contents[$HTTP_POST_VARS['place_in_array'][$i]]->check_perm('delete'))
			{
				if ($HTTP_POST_VARS['delete'][$i] == 'on')
				{
				
					if ($contents[$i]->delete() != OP_SUCCESSFUL)
					{
						die('Konnte '.$HTTP_POST_VARS['name'][$i].' nicht löschen');
					}	
				}
			}
			
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
		

			
			// check if user is allowed to unlink from thie cat
			$smarty->assign('allow_content_remove',check_cat_action_allowed($category->get_catgroup_id(),$userdata['user_id'],'content_remove'));

			

			// Check if user has rights to add content to a cat
			$add_to_cats = get_cats_data_where_perm('id,name','content_add');
			if (is_array($add_to_cats))
			{
					$smarty->assign('allow_link',true);	
					$smarty->assign('add_to_cats',$add_to_cats);
			}

		
			
			// check if user has edit perm to that content
			$thumb_infos['allow_edit'] = $contents[$i-1]->check_perm('edit');
			// check if user has delete perm to that content
			$thumb_infos['allow_delete'] = $contents[$i-1]->check_perm('delete');
			$thumb_infos['place_in_array'] = $i-1;
			$place_in_cat_array = $contents[$i-1]->get_place_in_cat();
			$thumb_infos['place_in_cat'] = $place_in_cat_array[$cat_id];
			if ($contents[$i-1]->get_locked())
			{
				$thumb_infos['locked'] = 'checked';
			}
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
	$smarty->assign('is_content', true);
}

$smarty->assign('cat_id',$cat_id);

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

if ($mode == 'edit_comment')
{
	$comment = new cat_comment();
	$comment->generate_from_id($HTTP_POST_VARS['parent_id']);
	$comment->set_feedback($HTTP_POST_VARS['comment_text']);
	$comment->set_topic($HTTP_POST_VARS['topic']);
	$comment->set_changed_count($comment->get_changed_count()+1);
	$comment->set_last_changed_date(date("Y-m-d H:i:s"));
	$comment->commit();	
}

if ($mode == 'del_comment')
{
// TODO: hier fehlt noch was passiert wenn unterkommentare enthalten sind.
	$comment = new cat_comment();
	$comment->generate_from_id($comment_id);
	$comment->delete();
}


//Show comments
$root_comments = get_comments_of_cat($cat_id);
if (sizeof($root_comments) > 0)
{
	for ($i = 0; $i < sizeof($root_comments); $i++)
	{
		make_comments($root_comments[$i],0,check_cat_action_allowed($cat_id,$userdata['user_id'],'content_edit'));
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
$smarty->assign('sid','&sid='.$userdata['session_id']);

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
