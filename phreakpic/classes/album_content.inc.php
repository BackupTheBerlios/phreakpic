<?php
require_once('modules/authorisation/interface.inc.php');
require_once('classes/categorie.inc.php');


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

	function album_content() //Constructor
	{

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

	function delete()
	{
	//delete the actual object from Database and filesystem. Checks if the actual object ist yet in database. Also checks authorisation.
	global $db, $config_vars;

		//check if the object is in the database
		if (isset($this->id))
		{  
			if (check_content_action_allowed($this->content_group_id, $userdata['user_id'], "delete")) //Authorisation is okay
			{
				// remove from content table
				$sql = "DELETE FROM '" . $config_vars['table_prefix'] . "content' WHERE 'id' = " . $this->id;
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Konnte Objekt nicht löschen", '', __LINE__, __FILE__, $sql);
				}
				unset($this->id);
				
				// remove from content_in_cat table
				$this->clear_content_in_cat();
				


				if (!unlink($this->file))
				{
					message_die(GENERAL_ERROR, "Konnte Datei nicht löschen", '', __LINE__, __FILE__, '');
				}
				unset($this->file);

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

		// check if already in db)
		if (isset($this->id))
		{
			// already in db

			// update entry in content table
			$sql = "UPDATE '" . $config_vars['table_prefix'] . "content'
				SET	file = '$this->file ',
					name = '$this->name',
					views = '$this->views',
					current_rating = '$this->current_rating', 
					creation_date = '$this->creation_date', 
					contentgroup_id = '$this->contentgroup_id',
					views = '$this->views',
					locked = '$this->locked'
				WHERE id like $this->id";
					
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Konnte Objekt nicht commiten", '', __LINE__, __FILE__, $sql);
			}
			
			// update content_in_cat table
			// i will do this by first deleting all entry with this content and then generate them all new

			$this->clear_content_in_cat();			

			// if content is in no cat anymore
			if (sizeof($cat_ids) == 0)
			{
				// move content in the deleted pics cat.
				$cat_ids[] = $config_vars['deleted_content_cat'];
			}
			// now add them all again
			$this->fill_content_in_cat();
			
			
		}
		else
		{
			//not in db
			// add content to the content table
			$sql = "INSERT INTO " . $config_vars['table_prefix'] . "content
				(file,name,views,current_rating,creation_date,contentgroup_id,locked)
				VALUES ('$this->file', '$this->name', '$this->views', '$this->current_rating', '$this->creation_date', '$this->contentgroup_id', '$this->locked')";
					
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Konnte Objekt nicht commiten", '', __LINE__, __FILE__, $sql);
			}

				// add content to the cats	
				$this->fill_content_in_cat();	

		}
	}
	
	
	function generate_from_id($id)
	{
		// Füllt das Objekt mit den daten des Contents mit id == $id aus der Datenbank
		global $db,$config_vars;
		
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
			if (($this->id == 0) or (check_content_action_allowed($this->content_group_id, $userdata['user_id'], "edit")))
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
		
		$old_cat = new categorie();
		$old_cat->generate_from_id($old_cat_id);

		// check perms (needs content_remove)
		if (check_cat_action_allowed($old_cat->catgroup_id, $userdata['user_id'], "content_remove"))
		
		// check if content is in cat
		if (in_array($old_cat_id,$this->cat_ids))
		{
			// unset the key that contains the cat to be removed
			unset($this->cat_ids[array_search($old_cat_id,$this->cat_ids)]);
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_CONTENT_NOT_IN_CAT;
		}
	}
	
	
	function get_cat_id()
	{
		//get the cat_id of the actual object. Checks if actual user is allowed to.
		return $this->cat_id;
	}

	function set_name($name)
	{
		global $userdata;
		//set the name of the actual object. Checks if actual user is allowed to.
		if (($this->id == 0) or (check_content_action_allowed($this->content_group_id, $userdata['user_id'], "edit")))
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
		if (($this->id == 0) or (check_content_action_allowed($this->content_group_id, $userdata['user_id'], "edit")))
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
		if (($this->id == 0) or (check_content_action_allowed($this->content_group_id, $userdata['user_id'], "edit")))
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
				VALUES ('" . $this->cat_ids[$key]. "', '$this->id', '" . $this->place_in_cat[$key]. "')";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error during inserting into content_in_cat", '', __LINE__, __FILE__, $sql);
			}
		}

		
	}
	
	function clear_content_in_cat()
	{
		global $db;
		$sql = "DELETE FROM '" . $config_vars['table_prefix'] . "content_in_cat'
		WHERE content_id = $this->id";

		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't DELETE content_in_cat entrys", '', __LINE__, __FILE__, $sql);
		}
	}

}


class photo extends album_content
{
   function generate_thumb($thumb_size = '0')
   {

   }
}


?>
