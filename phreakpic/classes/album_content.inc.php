<?php
require_once('modules/authorisation/interface.inc.php');
require_once('classes/categorie.inc.php');
require_once('includes/functions.inc.php');


// Holds information about which object to user with which file ending:
$filetypes = Array (
	'jpeg' => 'picture',
	'jpg' => 'picture',
	'png' => 'picture',
	'gif' => 'picture',
	'jpe' => 'picture',
	'bmp' => 'picture',
	'tiff' => 'picture',
	'tif' => 'picture');


class album_content
{
	var $id;
	var $file;
	var $cat_ids;
	var $place_in_cat;
	var $name;
	var $views;
	var $current_rating;
	var $creation_date;
	var $contentgroup_id;
	var $locked;
	var $width;
	var $height;
	var $edit;
	var $view;
	var $delete;
	var $thumbfile;
	
	
	function check_perm($perm)
	{
		global $userdata;
		if (!isset($$perm))
		{
			$this->$perm = check_content_action_allowed($this->contentgroup_id, $userdata['user_id'], $perm);
		}
		return $this->$perm;
	}

	

	function album_content() //Constructor
	{

	}
	
	function generate_content_in_cat_data()
	{
		if (isset($this->id))
		{
			global $db,$config_vars;	
			$sql = 'SELECT cat_id,place_in_cat FROM '. $config_vars['table_prefix'] . 'content_in_cat where content_id = ' . $this->id;

			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldnt get place_in_cat", '', __LINE__, __FILE__, $sql);
			}

			while ($row = $db->sql_fetchrow($result))
			{
				$this->place_in_cat[$row['cat_id']] = $row['place_in_cat'];
				$this->cat_ids[] = $row['cat_id'];
			}
		}

	}
	
	function get_place_in_cat()
	{
		if (!isset($this->place_in_cat))
		{
			$this->generate_content_in_cat_data();
		}
		return $this->place_in_cat;
	}
	
	function set_place_in_cat($cat,$place)
	{
		if (!isset($this->place_in_cat))
		{
			$this->generate_content_in_cat_data();
		}
		$this->place_in_cat[$cat] = $place;
		return OP_SUCCESSFUL;
	}
	
	function get_surrounding_content($cat_id)
	{
		// Returns an Array of album_content objects of all content which is in the categorie with id $cat_id
		global $db,$config_vars,$userdata,$filetypes;

		// get auth where
		$auth_where = get_allowed_contentgroups_where('content.contentgroup_id',$userdata['user_id'], "view");

		// get all content

		$sql = 	'SELECT content.id,content.file,content_in_cat.place_in_cat FROM ' .  $config_vars['table_prefix'] . "content as content,"  . $config_vars['table_prefix'] . "content_in_cat as content_in_cat
			WHERE ($auth_where) and 
				(content.id = content_in_cat.content_id) and (content_in_cat.cat_id = $cat_id) 
			ORDER BY content_in_cat.place_in_cat";
		
		
  
  
  
 		if (!$result = $db->sql_query($sql))
 		{
 			message_die(GENERAL_ERROR, "Couldnt get data of the of the content in the cat", '', __LINE__, __FILE__, $sql);
 		}

		while ($row = $db->sql_fetchrow($result))
		{
			if ($row['id'] == $this->id)
			{
				$objarray['prev'] = get_content_from_row($lastrow);
				$objarray['next'] = get_content_from_row($db->sql_fetchrow($result));
				return $objarray;
			}
			$lastrow = $row;
		}
		
	}

	function generate_thumb($thumb_size = '0')
	{
		// is for extended classes
		//Generates a thumbnail picture from the actual content in the size $thumb_size. check for making the size of the thumb right (higher pictures other than widther pictures).
		return NOT_SUPPORTED;
	}
	
	

	function change_compression($compression)
	{
	// is for extended classes
	//Change the compression of the actual content object.
	return NOT_SUPPORTED;
	}

	function change_size($size, $save_mode)
	{
	// is for extended classes
	//change the size of the actual object.
	return NOT_SUPPORTED;
	}

	function get_html()
	{
	// is for extended classes
	//returns the needed HTML Code to show the actual object.
	return NOT_SUPPORTED;
	}
	
	function lock()
	{
		global $userdata;
		//set the name of the actual object. Checks if actual user is allowed to.
		if (($this->id == 0) or (check_content_action_allowed($this->contentgroup_id, $userdata['user_id'], "edit")))
		{
			$this->locked=1;
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_NP_MISSING_EDIT;
		}
	}
	
	function get_locked()
	{
		return $this->locked;
	}
	
	function unlock()
	{
		global $userdata;
		//set the name of the actual object. Checks if actual user is allowed to.
		if (($this->id == 0) or (check_content_action_allowed($this->contentgroup_id, $userdata['user_id'], "edit")))
		{
			$this->locked=0;
			return OP_SUCCESSFULL;
		}
		else
		{
			return OP_NP_MISSING_EDIT;
		}
	}
	

	function delete()
	{
	//delete the actual object from Database and filesystem. Checks if the actual object ist yet in database. Also checks authorisation.
	global $db, $config_vars;

		//check if the object is in the database
		if (isset($this->id))
		{  
		
			if ($this->check_perm('delete')) //Authorisation is okay
			{
				if (!unlink($this->file))
				{
					message_die(GENERAL_ERROR, "Konnte Datei nicht löschen", '', __LINE__, __FILE__, '');
				}
				
				if (!unlink($this->get_thumbfile()))
				{
					message_die(GENERAL_ERROR, "Konnte Thumb nicht löschen", '', __LINE__, __FILE__, '');
				}
				
				
				
					
				// remove from content table
				$sql = "DELETE FROM " . $config_vars['table_prefix'] . "content WHERE id = " . $this->id;
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Konnte Objekt nicht löschen", '', __LINE__, __FILE__, $sql);
				}
				$this->clear_content_in_cat();
				unset($this->id);
				
				// remove from content_in_cat table
				
				
				unset($this->file);
				unset($this->cat_ids);
				unset($this->place_in_cat);
				
				
				return OP_SUCCESSFUL;

			}
			else
			{
				return OP_NP_MISSING_DELETE;
			}
		}
		else
		{
			return OP_NOT_IN_DB;
		}

	}

	function commit()
	{
		//commits all changes of the actual object to the database and/or filesystem 
		//or create a new db entry if object is not yet in db
		global $db,$config_vars;
				
		
		
		// fill palce_in_cat and cat_ids array if they are not yet filled;
		
		if (isset($this->id))
		{
			if ((!isset($this->cat_ids)) or (!isset($this->place_in_cat)))
			{
				$this->generate_content_in_cat_data();
			}
		}
		
		
		$this->calc_size();
		
		
		
		// if content is in no cat anymore
		if (sizeof($this->cat_ids) == 0)
		{
			// move content in the deleted pics cat.
			$this->cat_ids[0] = $config_vars['deleted_content_cat'];
		}
		
		
		
		// move to the new calculated localtaion (may be the same)		
		$new_file=$this->generate_filename();
		
		if (!is_dir(dirname($new_file)))
		{
			makedir(dirname($new_file));
		}
		
		if (rename($this->file,$new_file));
		{
			$this->set_file($new_file); 
		}
		//echo "rename pic" .$this->file." -> ".$new_file."<br>";
		
		// move thumb
		if (!is_dir(dirname($this->get_thumbfile())))
		{
			makedir(dirname($this->get_thumbfile()));
		}
		
		
		//echo "rename thumb" .$this->thumbfile." -> ".$this->get_thumbfile()."<br>";
		if (rename($this->thumbfile,$this->get_thumbfile()));
		{
			$this->thumbfile = $this->get_thumbfile();
		}
		
		
		
		
		
		
		// check if already in db)
		if (isset($this->id))
		{
			// already in db

			// update entry in content table
			$sql = "UPDATE " . $config_vars['table_prefix'] . "content
				SET	file = '$this->file',
					name = '$this->name',
					views = '$this->views',
					current_rating = '$this->current_rating', 
					creation_date = '$this->creation_date', 
					contentgroup_id = '$this->contentgroup_id',
					views = '$this->views',
					locked = '$this->locked',
					width = '$this->width',
					height = '$this->height'
				WHERE id = $this->id";
			
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Konnte Objekt nicht commiten", '', __LINE__, __FILE__, $sql);
			}
			
			// update content_in_cat table
			// i will do this by first deleting all entry with this content and then generate them all new
			
			$this->clear_content_in_cat();			

		}
		else
		{
			//not in db
			$this->creation_date=date("Y-m-d H:i:s");
			// add content to the content table
			$sql = "INSERT INTO " . $config_vars['table_prefix'] . "content
				(file,name,views,current_rating,creation_date,contentgroup_id,locked,width,height)
				VALUES ('$this->file', '$this->name', '$this->views', '$this->current_rating', '$this->creation_date', '$this->contentgroup_id', '$this->locked','$this->width','$this->height')";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Konnte Objekt nicht commiten", '', __LINE__, __FILE__, $sql);
			}
			// set id of object to the id of the insert
			$this->id = $db->sql_nextid();
		}
		
		
		
		
		// add content to the cats	
		
		$this->fill_content_in_cat();	
		

		
		return OP_SUCESSFUL;
	}
	
	
	function generate_from_row($row)
	{
		if (is_array($row))
		{
			// fill the var of the object with the data from the database (the field names of the database are the same than the var names)
			foreach ($row as $key => $value)
			{
				// filter out all keys which are not strings, because the array containt both assoziativ and numbers
				if (is_string($key))
				{
					$this->$key = $value;
				}
				
				
				$this->thumbfile=$this->get_thumbfile();
			}
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_FAILED;
		}
	}
	
	
	function generate_from_id($id)
	{
		// Füllt das Objekt mit den daten des Contents mit id == $id aus der Datenbank
		global $db,$config_vars;
		$sql = 'select * from ' . $config_vars['table_prefix'] . "content where id = $id";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not get content from id", '', __LINE__, __FILE__, $sql);
		}
		
		$row = $db->sql_fetchrow($result);
		return $this->generate_from_row($row);
		
	}
	 
	//set and get functions for every variable
	function set_id($id)
	{
		//set the id of the actual object. Nobody should do this.
		return NOT_ALLOWED;
	}
  
	function get_id()
	{
		//get the id of the actual object. Checks if actual user is allowed to.
		return $this->id;
	}
  
	function set_file($file)
	{
		global $userdata;
		//set the file of the actual object. Checks if actual user is allowed to.
		// check if file exists
		if (is_file($file))
		{
			if (($this->id == 0) or (check_content_action_allowed($this->contentgroup_id, $userdata['user_id'], "edit")))
			{
				$this->file = $file;
				return OP_SUCCESSFUL;
			}
			else
			{
				return OP_NP_MISSING_EDIT;
			}
		}
		else
		{
			return OP_NOT_A_FILE;
		}
		
	}

	function get_file()
	{
		//get the id of the actual object. Checks if actual user is allowed to.
		return $this->file;
	}

	function add_to_cat($new_cat_id)
	{
		global $userdata;
		
		if (!is_array($this->cat_ids))
		{
			$this->generate_content_in_cat_data();
		}

		
		
		//adds the actual object to the cat with id == $new_cat_id. Checks if actual user is allowed to.

		// get objekt for the new_cat

		$new_cat = new categorie();
		$new_cat->generate_from_id($new_cat_id);
		
		// user needs content_add rights in the cat where he wants to add that content
		if (check_cat_action_allowed($new_cat->catgroup_id, $userdata['user_id'], "content_add"))
		{
			$this->cat_ids[] = $new_cat_id;
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_NP_MISSING_CONTENT_ADD;
		}
	}
	
	function remove_from_cat($old_cat_id)
	{
		global $userdata;
		
				
		if (!is_array($this->cat_ids))
		{
			$this->generate_content_in_cat_data();
		}
				
		$old_cat = new categorie();
		$old_cat->generate_from_id($old_cat_id);

		

		// check perms (needs content_remove)
		if (check_cat_action_allowed($old_cat->catgroup_id, $userdata['user_id'], "content_remove"))
		{
			// check if content is in cat
			if (in_array($old_cat_id,$this->cat_ids))
			{
				// unset the key that contains the cat to be removed
				array_splice($this->cat_ids,array_search($old_cat_id,$this->cat_ids),1);
				return OP_SUCCESSFUL;
			}
			else
			{
				return OP_CONTENT_NOT_IN_CAT;
			}
		}
	}
	
	
	function get_cat_ids()
	{
		if (!is_array($this->cat_ids))
		{
			$this->generate_content_in_cat_data();
		}

		//get the cat_id of the actual object. Checks if actual user is allowed to.
		return $this->cat_ids;
	}

	function set_name($name)
	{
		global $userdata;
		//set the name of the actual object. Checks if actual user is allowed to.
		if (($this->id == 0) or (check_content_action_allowed($this->contentgroup_id, $userdata['user_id'], "edit")))
		{
			$this->name = $name;
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_NP_MISSING_EDIT;
		}
	}

	function get_name()
	{
		//get the name of the actual object. Checks if actual user is allowed to.
		return $this->name;
	}

	function set_views($views)
	{
		//set the views of the actual object. Allowed check is not usefull.
		$this->views = $views;
		return OP_SUCCESSFUL;
	}

	function get_views()
	{
		//get the views of the actual object. Checks if actual user is allowed to.
		return $this->views;
	}

	function set_current_rating($current_rating)
	{
		//set the current_rating of the actual object. Allowed check is not usefull.
		$this->current_rating = $current_rating;
		return OP_SUCCESSFUL;
	}

	function get_current_rating()
	{
		//get the current_rating of the actual object. Checks if actual user is allowed to.
		return $this->current_rating;
	}

	function set_creation_date($creation_date)
	{
		global $userdata;
		//set the creation_date of the actual object. Checks if actual user is allowed to.
		if (($this->id == 0) or (check_content_action_allowed($this->contentgroup_id, $userdata['user_id'], "edit")))
		{
			$this->creation_date = $creation_date;
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_NP_MISSIN_EDIT;
		}
	}

	function get_creation_date()
	{
		//get the creation_date of the actual object. Checks if actual user is allowed to.
		return $this->creation_date;
	}

	function set_contentgroup_id($contentgroup_id)
	{
		global $userdata;
		//set the contentgroup_id of the actual object. checks if actual user is allwoed to.
		if (($this->id == 0) or (check_content_action_allowed($this->contentgroup_id, $userdata['user_id'], "edit")))
		{
			$this->contentgroup_id = $contentgroup_id;
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_NP_MISSIN_EDIT;
		}
	}

	function get_contentgroup_id()
	{
		//get the creation_date of the actual object. Checks if actual user is allowed to.
		return $this->contentgroup_id;
	}
	
	
	// helpers 
	// private:
	function fill_content_in_cat()
	{
		// fills content_in_cat table
		global $db,$config_vars;
		foreach($this->cat_ids as $key => $value)
		{
			$sql = 'INSERT INTO ' . $config_vars['table_prefix'] . "content_in_cat (cat_id,content_id,place_in_cat)
				VALUES ('" . $this->cat_ids[$key]. "', '$this->id', '" . $this->place_in_cat[$this->cat_ids[$key]]. "')";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error during inserting into content_in_cat", '', __LINE__, __FILE__, $sql);
			}
		}

		
	}
	
	function clear_content_in_cat()
	{
		global $db,$config_vars;
		$sql = "DELETE FROM " . $config_vars['table_prefix'] . "content_in_cat
		WHERE content_id = $this->id";

		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't DELETE content_in_cat entrys", '', __LINE__, __FILE__, $sql);
		}
	}
	
	
	function generate_filename()
	{
		global $config_vars;
		//check if content is already in a cat 
		
		if (!isset($this->cat_ids))
		{
		
			$this->generate_content_in_cat_data();
		}
		if (sizeof($this->cat_ids)>0)
		{
			$cat_obj = new categorie();
			$cat_obj->generate_from_id($this->cat_ids[0]);
			$path = $cat_obj->get_name();
			
			
			
			while ($cat_obj->get_parent_id() != $config_vars['root_categorie'])
			{
				
				$old_cat_id=$cat_obj->get_parent_id();
				$cat_obj = new categorie();
				$cat_obj->generate_from_id($old_cat_id);
				$path = $cat_obj->get_name() . '/' . $path;
			}
			
			// make $path is it doesnt exists
			if (!is_dir($config_vars['content_path_prefix'] . '/' . $path))
			{
				makedir($config_vars['content_path_prefix'] . '/' . $path);
			}
	
			$path = $path . '/' . basename($this->name) . '.' . getext($this->file)	;
			return $config_vars['content_path_prefix'] .'/' . $path;
		}
		else
		{
			return OP_CONTENT_NOT_IN_CAT;	
		}
	}
	
	function get_thumbfile()
	{
		return dirname($this->file) . '/thumbs/' . basename($this->file);
	}
	
	function start_view()
	{
		global $config_vars,$userdata;
		$now = date("Y-m-d H:i:s");
		$sql = 'INSERT INTO '. $config_vars['table_prefix'] .'views (user_id,content_id,start) VALUES ('.$userdata['user_id']. ',' . $this->id . ",$now)";
		
	}

}


class picture extends album_content
{


	function calc_size()
	{
			// get width and height of pic
			$size = getimagesize($this->file);
			$this->width = $size[0];
			$this->height = $size[1];
	}
	


	function generate_thumb($thumb_size = '0')
	{
		global $config_vars;
		// if $thumb_size is not set == 0 then set it from the config vars
		if ($thumb_size == '0')
		{
			$thumb_size = $config_vars['thumb_size'];
		}
		
		
		$thumbfile=$this->get_thumbfile();
		
		$size= getimagesize($this->file);

		
		if ($size[2]==1) $src_img = imagecreatefromgif($this->file);
		if ($size[2]==2) $src_img = imagecreatefromjpeg($this->file);
		if ($size[2]==3) $src_img = imagecreatefrompng($this->file);

		if (isset($thumb_size['percent']))
		{
			// resize everthing per percent
			$new_w = $size[0] * $thumb_size['percent'] / 100;
			$new_h = $size[1] * $thumb_size['percent'] / 100;
		}
		elseif (isset($thumb_size['maxsize']))
		{
			// resize the larger value to maxsize
			if ($size[0] > $size[1])
			{
				// set width to maxsize
				$thumb_size['width'] = $thumb_size['maxsize'];
			}
			else
			{
				// set height to maxsize;
				$thumb_size['height'] = $thumb_size['maxsize'];
			}
		}
		if (isset($thumb_size['width'])) 
		{
			if (isset($thumb_size['height']))
			{
				// to a fixed resize 
				$new_w = $thumb_size['width'];
				$new_h = $thumb_size['height'];
				
			}
			else
			{
				// to a relative resize to width
				$new_w = $thumb_size['width'];
				$new_h = $size[1]*($new_w/$size[0]);
				
			}
		}
		else 
		{
			// do a relative resize to height	
			$new_h = $thumb_size['height'];
			$new_w = $size[0]*($new_h/$size[1]);
			
		}
		
		
		
		$dst_img = imagecreate($new_w,$new_h);
		imagecopyresized($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img));
		if (!is_dir(dirname($thumbfile))) {makedir(dirname($thumbfile));}

		imagejpeg($dst_img,$thumbfile);
		ImageDestroy($src_img);
		ImageDestroy($dst_img);
	}

	function get_html()
	{
		return "<img src=".linkencode($this->get_file()).">";
	}

	function get_thumb()
	{
		if (!is_file($this->get_thumbfile()))
		{
			$this->generate_thumb();
		}
		
		$array['content_id'] = $this->id;
		$size=getimagesize($this->get_thumbfile());
		$array['html'] = "<img src=".linkencode($this->get_thumbfile())." $size[3]>";
		$array['width'] = $this->width;
		$array['height'] = $this->height;
		$array['name'] = $this->get_name();
		$array['current_rating'] = $this->get_current_rating();
		$array['views'] = $this->get_views();
		return $array;
	}
}


?>
