<?php

require_once(ROOT_PATH . 'includes/common.inc.php');
require_once(ROOT_PATH . 'classes/album_content.inc.php');
require_once(ROOT_PATH . 'classes/categorie.inc.php');
require_once(ROOT_PATH . 'classes/user_feedback.inc.php');


// Get Functions

function get_cats_of_cat($parent_id)
{
	// Returns an array of categorie Objects of all categories which are under the categorie with ihe id $parent_id
	global $db,$config_vars,$userdata;

	// get the sql where to limit the query to categories which the user is allowed to view
	$auth_where=get_allowed_catgroups_where($userdata['user_id'],'view');
	if (!isset($auth_where))
	{
		return;
	}

	$sql = "SELECT * FROM " . $config_vars['table_prefix'] . "cats WHERE (parent_id = $parent_id) and ($auth_where)";

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Konnte Kategorie nicht auswählen", '', __LINE__, __FILE__, $sql);
	}
	// generate categorie objects for each categorie that is returned by the query
	while ($row = $db->sql_fetchrow($result))
	{
		$catobj= new categorie();
		if ($catobj->generate_from_row($row) != OP_SUCCESSFUL)
		{
			return OP_FAILED;
		}

		$cat_objects[]=$catobj;
	}
	return $cat_objects;

}

function get_content_of_cat($cat_id)
{
	// Returns an Array of album_content objects of all content which is in the categorie with id $cat_id
	global $db,$config_vars,$userdata,$filetypes;

	// all content in cat $cat_id	
	if (!isset($cat_id))
	{
		return OP_FAILED;
	}
		

	$auth_where = get_allowed_contentgroups_where($userdata['user_id'], "view",'content.contentgroup_id');
	

	
	// get all content
	
	$sql = 	'SELECT content.*,content_in_cat.place_in_cat 
		FROM ' .	 $config_vars['table_prefix'] . "content as content,
			"  . $config_vars['table_prefix'] . "content_in_cat as content_in_cat
		WHERE	($auth_where) and 
			(content.id = content_in_cat.content_id) and 
			(content_in_cat.cat_id = $cat_id)
		ORDER BY content_in_cat.place_in_cat";
		
		
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldnt get data of the of the content in the cat", '', __LINE__, __FILE__, $sql);
	}
	
	
	
	while ($row = $db->sql_fetchrow($result))
	{
		
		// creating objects for every content
/*		$objtyp = $filetypes[getext($row['file'])];
		if (isset($objtyp))
		{
			$contentobj = new $objtyp;
			if ($contentobj->generate_from_row($row) != OP_SUCCESSFUL)
			{
				return OP_FAILED;
			}
		}
		
		$objarray[]=$contentobj;*/
		
		// delete place_in_cat from $row
		unset($row['place_in_cat']);
		$objarray[]=get_content_from_row($row);
		
	}
	
	return $objarray;

}

function get_content_from_sql($sql_where_clause)
{
	// Returns an Array of the requested fields of the pictures that are returned by the sql where clause $sql_where_clause
	global $db;
		global $config_vars;

	$sql = "SELECT * FROM " . $config_vars['table_prefix'] . "pics
		WHERE $sql_where_clause";

	if (!$result = $db->query($sql))
	{
		message_die(GENERAL_ERROR, "Konnte Bilder nicht auswählen bei eigener WHERE clause", '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$objarray[]=get_content_from_row($row);
	}


	return $objarray;

}


function get_content_object_from_id($id)
{
	// returns an object for the content with id == $id
	global $db,$config_vars,$userdata,$filetypes;
	
	// get  content
	
	$uncontent = new album_content();
	$uncontent->generate_from_id($id);
	
	// check if user has view perms to that content
	
	if (check_content_action_allowed($uncontent->get_contentgroup_id(),$userdata['user_id'],'view'))
	{
		$objtyp = $filetypes[getext($uncontent->file)];
		if (isset($objtyp))
		{
			$incontent = new $objtyp;
			
			
			//this sucks (additional sql query) but its ok for now
			$incontent->generate_from_id($id);
		}
		
		return $incontent;
	}
	else
	{
		return OP_MISSING_VIEW;
	}
	
}

function get_content_from_row($row)
{
	global $filetypes;
	$objtyp = $filetypes[getext($row['file'])];
	if (isset($objtyp))
	{
		$contentobj = new $objtyp;
		if ($contentobj->generate_from_row($row) != OP_SUCCESSFUL)
		{
			return OP_FAILED;
		}
	
		return $contentobj;
	}
	return OP_FAILED;
}

function get_cats_data_where_perm($data,$perm)
{
	// returns an indexed array containing all fields speicfied in $data in an assoc array where user has permission $perm
	global $db,$config_vars,$userdata;

	// get the sql where to limit the query to categories which the user is allowed to view
	$auth_where=get_allowed_catgroups_where($userdata['user_id'],$perm);
	if (!isset($auth_where))
	{
		return;
	}

	$sql = "SELECT $data FROM " . $config_vars['table_prefix'] . "cats WHERE ($auth_where)";

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Konnte Kategorie nicht auswählen", '', __LINE__, __FILE__, $sql);
	}

	// generate categorie objects for each categorie that is returned by the query
	return generate_array_from_row($row);	
}

function get_catgroups_data_where_perm($data,$perm)
{
	// returns an indexed array containing all fields speicfied in $data in an assoc array where user has permission $perm
	
	global $db,$config_vars,$userdata;	

	$where = get_allowed_catgroups_where($userdata['user_id'],$perm,'id');
	
	$sql = "select $data from {$config_vars['table_prefix']}catgroups where $where";
	
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not check whether the contentgroups where this user is allowed to this action", '', __LINE__, __FILE__, $sql);
	}
	
	// generate catgroup array for each catgroup that is returned by the query
	return generate_array_from_row($row);
	
}

function get_contentgroups_data_where_perm($data,$perm)
{
	// returns an indexed array containing all fields speicfied in $data in an assoc array where user has permission $perm
	
	global $db,$config_vars,$userdata;	

	$where = get_allowed_contentgroups_where($userdata['user_id'],$perm,'id');
	$sql = "select $data from {$config_vars['table_prefix']}contentgroups where $where";
	
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not check whether the contentgroups where this user is allowed to this action", '', __LINE__, __FILE__, $sql);
	}
	
	// generate catgroup array for each catgroup that is returned by the query
	return generate_array_from_row($row);
	
}






// Comment Functions

function get_comments_of_content($content_id)
{
	global $config_vars,$db;
	// makes this to the first comment of content $content_id
	$sql = 'SELECT * FROM ' . $config_vars['table_prefix'] . 'content_comments
		WHERE (owner_id = ' .$content_id . ') and (parent_id = 0)';
		
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Error generating initial comment", '', __LINE__, __FILE__, $sql);
	}
	
	
	while ($row = $db->sql_fetchrow($result))
	{
		$com = new content_comment();
		$com->generate_from_row($row);	
		$com_array[] = $com;
	}
	
	return $com_array;
}

function get_comments_of_cat($cat_id)
{
	global $config_vars,$db;
	// makes this to the first comment of content $content_id
	$sql = 'SELECT * FROM ' . $config_vars['table_prefix'] . 'cat_comments
		WHERE (owner_id = ' .$cat_id . ') and (parent_id = 0)';
		
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Error generating initial comments for cat", '', __LINE__, __FILE__, $sql);
	}
	
	
	while ($row = $db->sql_fetchrow($result))
	{
		$com = new cat_comment();
		$com->generate_from_row($row);	
		$com_array[] = $com;
	}
	
	return $com_array;
}




// Add Functions

function add_dir_to_cat($dir,$cat_id, $contentgroup_id, $name_mode = GENERATE_NAMES)
{
	// Adds all pictures in the Directory $dir into the Categorie with the id $cat_id. If wanted it makes the names of the pics from the filenames
	global $db,$config_vars,$filetypes;

	
	$dir_handle=opendir($dir);
	

	//HIER NOCH CHECKEN WAS PASSIERT wenn $dir kein gültiges Verzeiczhnis ist


	while ($file = readdir ($dir_handle))
	{
		
		if (($file != "." && $file != "..") and (isset($filetypes[getext($file)]) )) // WELCHE DATEIENDUNGEN werden benutzt? Soll es einstellbar sein? Wenn ja, wo?
		{
			$unsorted_files[] = $file;
			
		}
	}
	
	
	for ($i = 0; $i < sizeof($unsorted_files); $i++)
	{
		$dir_and_file = $dir . '/' . $unsorted_files[$i];
		
		
		
		// generate a new album_content obj
		
		$content = new $filetypes[getext($unsorted_files[$i])];
		//if the name of the picture should be the filename, get it and cutoff the dateiendung	
		if ($name_mode == GENERATE_NAMES)
		{
			$content->set_name(getfile($unsorted_files[$i]));
			
		}
		else
		{
			$name = '';
		}
		
		$content->add_to_cat($cat_id);
		$content->set_file($dir_and_file);
		$content->set_contentgroup_id($contentgroup_id);
		
		$content->commit();
		
	}
	closedir($dir_handle);
}

function add_dir_parsed($dir,$contentgroup_id,$catgroup_id,$parent_id=-1)
{
	// Add all pictures under the Directory $dir to categories and series depending on the relativ path to $dir
	global $db,$config_vars,$filetypes;
	
	if ($parent_id == -1)
	{
		$parent_id = $config_vars['root_categorie'];
	}

	$dir_handle = opendir($dir);
	while ($file = readdir ($dir_handle))
	{	
		
		if (($file != ".") && ($file != ".."))
		{
			$dir_and_file = $dir . '/' . $file;
			if (isset($filetypes[getext($file)]))
			{	
			
				// $file is content
				// generate a new album_content obj
				$content = new $filetypes[getext($file)];
				//if the name of the picture should be the filename, get it and cutoff the dateiendung	

				$content->set_name(getfile($file));
				$content->add_to_cat($parent_id);
				$content->set_file($dir_and_file);
				$content->set_contentgroup_id($contentgroup_id);
				$content->commit();
				

			}
			elseif (is_dir($dir_and_file))
			{
				
				//file is a sub dir
				if (strpos($file,"cat_") === 0) // 3 = for zusätzliche typen gleicheit
				{
				// subdir cat
					$cat = new categorie();
					$cat->set_name(substr($file,4));
					$cat->set_parent_id($parent_id);
					$cat->fill_up();
					$cat->set_catgroup_id($catgroup_id);
					if (!isset($cat->id))
					{
					
						$cat->commit();
					}
					add_dir_parsed($dir.'/'.$file,$contentgroup_id,$catgroup_id,$cat->get_id());
				}
				elseif (strpos($file,"serie_") === 0)
				{
					$cat = new categorie();
					$cat->set_name(substr($file,6));
					$cat->set_parent_id($parent_id);
					$cat->set_is_serie(1);
					$cat->fill_up();
					
					if (!isset($cat->id))
					{
					
						$cat->commit();
					}
					add_dir_parsed($dir.'/'.$file,$contentgroup_id,$catgroup_id,$cat->get_id());
				// subdir serie
				}
			}
		
			
		}
	}
	
	closedir($dir_handle);

}

function get_unread_content_comments()
{
	$sql = "select comments.id from photo_content_comments as comments, photo_views as views WHERE (views.start < comments.creation_date) and (comments.id=views.content_id)";
}


?>
