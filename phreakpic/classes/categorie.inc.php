<?php
require_once('modules/authorisation/interface.inc.php');

class categorie
{
	var $id;
	var $name;
	var $catgroup_id;
	var $parent_id;
	var $current_rating;
	
	function categorie()
	{
	}
	
	function generate_from_id($id)
	{
		global $db,$config_vars;
		// Füllt das Objekt mit den daten der Categorie mit id == $id aus der Datenbank
		$sql = 'select * from ' . $config_vars['table_prefix'] . "cats where id like $id";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not get categorie from id", '', __LINE__, __FILE__, $sql);
		}
		
		$row = $db->sql_fetchrow($result);
		
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

			}
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_FAILED;
		}
		
	}
	
	function delete($mode,$mode_params)
	{
	}
	
	function commit()
	{
		global $db,$config_vars;
		if (!isset($this->id))
		{
			// this is object is not yet in the datebase, make a new entry
			$sql = "INSERT INTO " . $config_vars['table_prefix'] . "cats (name, current_rating, parent_id, catgroup_id)
				VALUES ('$this->name', '$this->current_rating', '$this->parent_id', '$this->catgroup_id')";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error while submitting a new cat object to the db", '', __LINE__, __FILE__, $sql);
			}
			return OP_SUCCESSFULL;

			
		}
		else
		{
			// object is already in the database just du an update
			$sql = "INSERT INTO " . $config_vars['table_prefix'] . "cats (name, current_rating, parent_id, catgroup_id)
				VALUES ('$this->name', '$this->current_rating', '$this->parent_id', '$this->catgroup_id')";
			
		}
		
	}
	
	function get_id()
	{
		return $this->id;
	}
	
	function get_name()
	{
		return $this->name;
	}
	
	function set_name($new_name)
	{	
		global $userdata;
		if (($this->id == 0) or check_cat_action_allowed($this->catgroup_id,$userdate['user_id'],'edit'))
		{
			$this->name=$new_name;
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_NOT_PERMITTED;	
		}
	}
	
	function get_catgroup_id()
	{
		return $this->catgroup_id;
	}
	
	function set_catgroup_id($new_catgroup_id)
	{
		global $userdata;
		if (($this->id == 0) or check_cat_action_allowed($this->catgroup_id,$userdate['user_id'],'edit'))
		{
			$this->catgroup_id=$new_catgroup_id;
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_NOT_PERMITTED;	
		}
		
	}

	function get_parent_id()
	{
		return $this->parent_id;
	}
	
	function set_parent_id($new_parent_id)
	{
		global $userdata;
		
		
		// get objekt for the parent cat
		$parent = new categorie();
		if ($parent->generate_from_id($new_parent_id) == OP_SUCCESSFUL)
		{
			// check if user has edit rights in the parent id
			if (check_cat_action_allowed($parent->catgroup_id,$userdata['user_id'],'edit'))
			{
				$this->parent_id=$new_parent_id;
				return OP_SUCCESSFUL;
			}
			else
			{
				return OP_NOT_PERMITTED;	
			}
		}
		else
		{
			return OP_PARENT_ID_INVALID;
		}
	}
	
	function get_current_rating()
	{
		return $this->current_rating;
	}
	
	function set_current_rating($new_current_rating)
	{
		$this->current_rating = $new_current_rating;
		return OP_SUCCESSFUL;
	}


	
}

?>
