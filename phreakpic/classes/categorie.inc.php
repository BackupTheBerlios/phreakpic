<?php
require_once('modules/authorisation/interface.inc.php');

class categorie
{
	var $id;
	var $name;
	var $catgroup_id;
	var $parent_id;
	var $current_rating;
	var $is_serie;
	var $content_amount;
	var $description;
	
	function categorie()
	{
	}
	
	
	function set_is_serie($new_val)
	{
		$this->is_serie=$new_val;
		return OP_SUCCESSFUL;
	}
	
	function get_is_serie()
	{
		return $this->is_serie;
	}
	
	function get_parent_cat_array()
	{
		global $config_vars;
		$cat_obj = $this;
		$parent_cat['name'] = $cat_obj->get_name();
		$parent_cat['id'] = $cat_obj->get_id();
		$parent_cats[] = $parent_cat;
		while ($cat_obj->get_parent_id() != $config_vars['root_categorie'])
		{
			
			$old_cat_id=$cat_obj->get_parent_id();

			$cat_obj = new categorie();	
			$cat_obj->generate_from_id($old_cat_id);
			
			$parent_cat['name'] = $cat_obj->get_name();
			$parent_cat['id'] = $cat_obj->get_id();	
			$parent_cats[] = $parent_cat;
			
		}
		
		return array_reverse($parent_cats);
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
		global $db,$config_vars;
		
		if (!isset($id))
		{
			return OP_FAILED;
		}
		// F�llt das Objekt mit den daten der Categorie mit id == $id aus der Datenbank
		$sql = 'select * from ' . $config_vars['table_prefix'] . "cats where id like $id";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not get categorie from id", '', __LINE__, __FILE__, $sql);
		}
		
		$row = $db->sql_fetchrow($result);
		
/*		if (is_array($row))
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
		}*/
		
		return $this->generate_from_row($row);
		
	}
	
	function delete($mode,$mode_params=0)
	{
		// deletes the categorie assigned with this object from the database
		global $db,$config_vars;
		
		if (isset($this->id))
		{
			// check if user has permission to do that
			if (check_cat_action_allowed($this->catgroup_id,$userdata['user_id'],'delete'))
			{			
				if ($mode == CDM_MOVE_CONTENT)
				{
					
					// check if user has right to edit all content in this categorie
					
				
					// move content in this categorie to the cat with id $mode_params

					// wie genau soll man hier mit den perms umgehen ?? 
				}
				else
				{
					// check if user has delete right for all pictures in this categorie
					
					// delete content of the cat
				}
				$sql = 'DELETE FROM '. $config_vars['table_prefix'] . "where id like $this->id";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Error while submitting a new cat object to the db", '', __LINE__, __FILE__, $sql);
				}
				unset($this->id);
				return OP_SUCCESSFULL;
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
		//if the object is already in the db it is updated by the changes made to this object, otherwise a new db entry is made
		global $db,$config_vars;
		if (!isset($this->id))
		{
			// this is object is not yet in the datebase, make a new entry
			$sql = 'INSERT INTO ' . $config_vars['table_prefix'] . "cats (name, current_rating, parent_id, catgroup_id,is_serie,content_amount,description)
				VALUES ('$this->name', '$this->current_rating', '$this->parent_id', '$this->catgroup_id', '$this->is_serie', '$this->content_amount', '$this->description')";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error while submitting a new cat object to the db", '', __LINE__, __FILE__, $sql);
			}
			
			$this->id = $db->sql_nextid();
			return OP_SUCCESSFULL;
			

			
		}
		else
		{
			// object is already in the database just du an update
			$sql = 'UPDATE ' . $config_vars['table_prefix'] . "cats 
				SET 	name = '$this->name', 
					current_rating = '$this->current_rating', 
					parent_id = '$this->parent_id', 
					catgroup_id = '$this->catgroup_id'
					is_serie = '$this->is_serie'
					content_amount = '$this->content_amount'
					description = '$this->description'					
				WHERE id like $this->id";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error while updating an existing cat object to the db", '', __LINE__, __FILE__, $sql);
			}
			return OP_SUCCESSFULL;

			
			
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
		if (($this->id == 0) or check_cat_action_allowed($this->catgroup_id,$userdata['user_id'],'edit'))
		{
			$this->name=$new_name;
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_NP_MISSING_EDIT;	
		}
	}
	
	function get_description()
	{
		return $this->description;
	}
	
	function set_description($new_description)
	{	
		global $userdata;
		if (($this->id == 0) or check_cat_action_allowed($this->catgroup_id,$userdata['user_id'],'edit'))
		{
			$this->description=$new_description;
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_NP_MISSING_EDIT;	
		}
	}

	
	function get_catgroup_id()
	{
		return $this->catgroup_id;
	}
	
	function set_catgroup_id($new_catgroup_id)
	{
		global $userdata;
		if (($this->id == 0) or check_cat_action_allowed($this->catgroup_id,$userdata['user_id'],'edit'))
		{
			$this->catgroup_id=$new_catgroup_id;
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_NP_MISSING_EDIT;	
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
			// check if user has cat_add rights in the parent group
			if (check_cat_action_allowed($parent->catgroup_id,$userdata['user_id'],'cat_add'))
			{
				// if this categoris is already in the db you also need move rights
				if (isset($this->id) and (!check_car_action_allowed($this->catgroup_id,$userdata['user_id'],'move')))
				{
					return OP_NP_MISSING_CAT_MOVE;
				}
				$this->parent_id=$new_parent_id;
				return OP_SUCCESSFUL;
			}
			else
			{
				return OP_NP_MISSING_CAT_ADD;	
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

	function get_content_amount()
	{
		return $this->content_amount;
	}
	
	function set_content_amount($new_content_amount)
	{
		$this->content_amount = $new_content_amount;
		return OP_SUCCESSFUL;
	}


	
}

?>
