<?php

if (is_array($contents))
{

	$HTTP_SESSION_VARS['contents']=$contents;
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
				
				//rotate
				if ($HTTP_POST_VARS['rotate_mode'][$i] == 'free')
				{
					if (intval($HTTP_POST_VARS['rotate'][$i])!=0) 
					{
						$contents[$i]->rotate($HTTP_POST_VARS['rotate'][$i]);
					}
				}
				else
				{
					$contents[$i]->rotate($HTTP_POST_VARS['rotate_mode'][$i]);
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
			
			// check change group
			if ($HTTP_POST_VARS['change_group'][$i] == 'on')
			{
				
				$contents[$i]->set_contentgroup_id($HTTP_POST_VARS['to_contengroup']);
				
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
			
			// Check if user has the right to remove the content from the contentgroup
			$thumb_infos['allow_remove_from_group'] = $contents[$i-1]->check_perm('remove_from_group');
			
			// get current contentgroup
			$c_group = new contentgroup();
			$c_group->generate_from_id($contents[$i-1]->get_contentgroup_id());
			$thumb_infos['contentgroup_name'] = $c_group->get_name();
			
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


?>
