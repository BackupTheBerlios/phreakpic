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
		error_report(SQL_ERROR, 'get_cats_of_cat' , __LINE__, __FILE__,$sql);
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

function get_content_of_cat($cat_id,$start=-1,$anzahl=-1,$viewable_amount=0)
{
	// Returns an Array of album_content objects of all content which is in the categorie with id $cat_id
	global $db,$config_vars,$userdata,$filetypes;

	// all content in cat $cat_id	
	if (!isset($cat_id))
	{
		return OP_FAILED;
	}
		

	$auth_where = get_allowed_contentgroups_where($userdata['user_id'], "view",'content.contentgroup_id');
	

	if ($anzahl!=0)	
	{
		$limit="LIMIT $start, $anzahl";
	}
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
		error_report(SQL_ERROR, 'get_content_of_cat' , __LINE__, __FILE__,$sql);
	}
	
	$viewable_amount = $db->sql_affectedrows($result);
		
	
	// we only want the rows from $start
	if ($start != -1)
	{
		$db->sql_rowseek($start,$result);
	}
	if ($anzahl == -1)
	{	
		$anzahl = $viewable_amount;
	}
	
	// and only $anzahl ones
	for ($i=0;$i<$anzahl;$i++)
	{
		$row = $db->sql_fetchrow($result);
		
		// delete place_in_cat from $row
		// why that ?
		unset($row['place_in_cat']);
		$content=get_content_from_row($row);
		if (is_object($content))
		{
			$objarray[]=$content;
		}
		
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
		error_report(SQL_ERROR, 'get_content_from_sql' , __LINE__, __FILE__,$sql);
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
	if ($uncontent->generate_from_id($id) == OP_SUCCESSFUL )
	{

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
			else
			{
				// unsupported filetype
			}
			return $incontent;
		}
		else
		{
			return OP_MISSING_VIEW;
		}
	}
	else
	{
		return OP_FAILED;
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
		error_report(SQL_ERROR, 'get_cats_data_where_perm' , __LINE__, __FILE__,$sql);
	}

	// generate categorie objects for each categorie that is returned by the query
	return generate_array_from_row($row);	
}

function get_catgroups_data_where_perm($data,$perm)
{
	// returns an indexed array containing all fields speicfied in $data in an assoc array where user has permission $perm
	
	global $db,$config_vars,$userdata;	

	$where = get_allowed_catgroups_where($userdata['user_id'],$perm,'id');
	
	$sql = "select $data from {$config_vars['table_prefix']}groups where $where";
	
	if (!$result = $db->sql_query($sql))
	{
		error_report(SQL_ERROR, 'get_catgroups_data_where_perm' , __LINE__, __FILE__,$sql);
	}
	
	// generate catgroup array for each catgroup that is returned by the query
	return generate_array_from_row($row);
	
}

function get_contentgroups_data_where_perm($data,$perm)
{
	// returns an indexed array containing all fields speicfied in $data in an assoc array where user has permission $perm
	
	global $db,$config_vars,$userdata;

	$where = get_allowed_contentgroups_where($userdata['user_id'],$perm,'id');
	$sql = "select $data from {$config_vars['table_prefix']}groups where $where";
	
	if (!$result = $db->sql_query($sql))
	{
		error_report(SQL_ERROR, 'get_contentgroups_data_where_perm' , __LINE__, __FILE__,$sql);
	}
	
	// generate catgroup array for each catgroup that is returned by the query
	return generate_array_from_row($row);
	
}

function get_users_data($data)
{
	// returns an indexed array containing all fields speicfied in $data in an assoc array 

	global $db,$config_vars,$userdata;	

	
	$sql = "select $data from ".USERS_TABLE;
	
	if (!$result = $db->sql_query($sql))
	{
		error_report(SQL_ERROR, 'get_contentgroups_data_where_perm' , __LINE__, __FILE__,$sql);
	}
	
	while ($row = $db->sql_fetchrow($result))
	{
		$users[] = $row;
	}
	
	
	return $users;

}




// Comment Functions

function get_comments_of_content($content_id)
{
	global $config_vars,$db;
	// makes this to the first comment of content $content_id
	$sql = 'SELECT * FROM ' . $config_vars['table_prefix'] . 'content_comments
		WHERE (owner_id = ' .$content_id . ') and (parent_id = 0) ORDER BY creation_date' ;
		
	if (!$result = $db->sql_query($sql))
	{
		error_report(SQL_ERROR, 'get_comments' , __LINE__, __FILE__,$sql);
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
		WHERE (owner_id = ' .$cat_id . ') and (parent_id = 0) ORDER BY creation_date';
		
	if (!$result = $db->sql_query($sql))
	{
		error_report(SQL_ERROR, 'get_comments' , __LINE__, __FILE__,$sql);
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

function add_dir_parsed($dir,$group_id,$parent_id=-1)
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


				add_content($file,$dir_and_file,getfile($file),$parent_id,0,$group_id);

			}
			elseif (is_dir($dir_and_file))
			{

				//file is a sub dir
				$cat = new categorie();
				$cat->set_name($file);
				$cat->set_parent_id($parent_id);
				$cat->fill_up();
				$cat->set_catgroup_id($group_id);
				if (!isset($cat->id))
				{
					$cat->commit();
				}
				add_dir_parsed($dir.'/'.$file,$group_id,$cat->get_id());

			}


		}
	}

	closedir($dir_handle);

}



?>
