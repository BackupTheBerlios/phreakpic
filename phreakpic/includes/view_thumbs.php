<?php
include_once(ROOT_PATH . 'libs/pclzip/pclzip.lib.php');

if ($mode == 'download')
{
	$cookie = $_COOKIE[$config_vars['cookie_name'].'basket'];
	$download_array=explode(':',$cookie);
	
	for ($i=0;$i<sizeof($download_array)-1;$i++)
	{
		$content_obj=new album_content();
		$content_obj->generate_from_id($download_array[$i]);
		$files[]=$content_obj->get_file();
		
	}
	
	$filename = $config_vars['content_path_prefix']."/content_{$userdata['username']}.zip";
	// create zip
	$zip= new PclZip($filename);
	$zip->create($files);
	
	//delete zip
	$HTTP_SESSION_VARS['delete_files'][] = $filename;

	
	// send zip to browser
 	$header_location = ( @preg_match("/Microsoft|WebSTAR|Xitami/", 
 	getenv("SERVER_SOFTWARE")) ) ? "Refresh: 0; URL=" : "Location: ";
 	header($header_location . append_sid($filename, true));
	
	
	
	
}

if (is_array($contents))
{
	//editing the contents
	if ((isset($submit)) and ($HTTP_POST_VARS['mode'] == 'edited'))
	{
		$add_to_cats_unparsed = get_cats_data_where_perm('id,name','content_add');
		$add_to_cats = get_cats_string($add_to_cats_unparsed);
		// loop through all content
		for ($i = 0; $i < sizeof($contents); $i++)	
		{
			
			// set everything for edit
			if ($contents[$i]->check_perm('edit'))
			{	
				
				$vals['name']=$HTTP_POST_VARS['name'][$i];
				$vals['place_in_cat']=$HTTP_POST_VARS['place_in_cat'][$i];
				$vals['lock']=$HTTP_POST_VARS['lock'][$i];
				$vals['rotate']=$HTTP_POST_VARS['rotate'][$i];
				$vals['rotate_mode']=$HTTP_POST_VARS['rotate_mode'][$i];
				$vals['unlink']=$HTTP_POST_VARS['unlink'][$i];
				$vals['link']=$HTTP_POST_VARS['link'][$i];
				$vals['to_cat']=$HTTP_POST_VARS['to_cat'];
				$vals['move']=$HTTP_POST_VARS['move'][$i];
				$vals['change_group']=$HTTP_POST_VARS['change_group'][$i];
				$vals['to_contentgroup']=$HTTP_POST_VARS['to_contentgroup'];
				$vals['delete']=$HTTP_POST_VARS['delete'][$i];
				$contents[$i]->edit_content($vals,$cat_id);
				
			}
		
			
			
		}
		
		$smarty->assign('mode','view');
		$smarty->assign('edited',true);
		$contents = get_content_of_cat($cat_id);
	}
	
	
	
	// check if user is allowed to unlink from this cat
	// das muss raus aus der loop
	if ($mode == 'edit')
	{
		$smarty->assign('allow_content_remove',check_cat_action_allowed($category->get_catgroup_id(),$userdata['user_id'],'content_remove'));

		// Check if user has rights to add content to a cat (zu irgendeiner)
		//dass auch
		$add_to_cats_unparsed = get_cats_data_where_perm('id,name','content_add');
		$add_to_cats = get_cats_string($add_to_cats_unparsed);
		
		if (is_array($add_to_cats))
		{
				$smarty->assign('allow_link',true);	
				$smarty->assign('add_to_cats',$add_to_cats);
		}
	}

	
	//show thumbnails and get some infos about the content
	
	for ($i = 1; $i <= sizeof($contents); $i++)
	{	
		$thumb_infos = $contents[$i-1]->get_thumb();
		if ($mode == 'edit')
		{
			// check what the user is allowed to edit
			$edit_values=$contents[$i-1]->get_editable_values($cat_id);
			$thumb_infos=array_merge($thumb_infos,$edit_values);
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
	
	$smarty->assign('cookie_name',$config_vars['cookie_name']);
	
	$smarty->assign('table_cols',$config_vars['thumb_table_cols']);
	$smarty->assign('thumbs',$thumbs);
	$smarty->assign('is_content', true);
}
	$smarty->assign('basket_enable',$userdata['phreakpic_basket_enable']);


?>
