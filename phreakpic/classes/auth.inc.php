<?php


class auth
{
	var $usergroup_id;
	var $group;
	var $view;
	var $delete;
	var $edit;

	function delete()
	{
		global $db,$config_vars;
		// remove from content table
		$sql = "DELETE FROM '" . $config_vars['table_prefix'] . get_class($this) . " WHERE 'id' = " . $this->id;
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Konnte Objekt nicht löschen", '', __LINE__, __FILE__, $sql);
		}
		unset($this->id);
	}
	
	function commit()
	{
	
	}
	
	function generate($usergroup_id,$group_id)
	{
	
	}
		
	function set_view()
	{
		$this->view = 1;
	}

	function unset_view()
	{
		$this->view = 0;
	}

	function set_delete()
	{
		$this->delete = 1;
	}

	function unset_delete()
	{
		$this->delete = 0;
	}
	
	function set_edit()
	{
		$this->edit = 1;
	}

	function unset_delete()
	{
		$this->edit = 0;
	}

	
	
		
}

class cat_auth extends auth
{

	function commit()
	{
		global $db,$config_vars;
		if (!isset($this->id))
		{
			// this is object is not yet in the datebase, make a new entry
			$sql = 'INSERT INTO ' . $config_vars['table_prefix'] . get_class($this) . " (usergroup_id,catgroup_id,view,delete,edit)
				VALUES ('$this->usergroup_id', '$this->group_id','$this->view','$this->delete','$this->edit')";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error while submitting a new group object to the db", '', __LINE__, __FILE__, $sql);
			}
			return OP_SUCCESSFULL;
			
		}
		else
		{
			// object is already in the database just du an update
			$sql = 'UPDATE ' . $config_vars['table_prefix'] . get_class($this) . " 
				SET 	usergroup_id = '$this->usergroup_id', 
					catgroup_id = '$this->group',
					view = '$this->view',
					delete = '$this->delete',
					edit = '$this->delete'		
				WHERE id like $this->id";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error while updating an existing cat object to the db", '', __LINE__, __FILE__, $sql);
			}
			return OP_SUCCESSFULL;
		}

	}
}

class content_auth extends auth
{
}
?>
