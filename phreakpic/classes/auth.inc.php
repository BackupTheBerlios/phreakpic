<?php


class auth
{
	var $in_db=false;
	var $usergroup_id;
	var $view;
	var $delete;
	var $edit;
	
	// need to save those becuase object is identified by them
	var $old_usergroup_id;
	

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
		// Füllt das Objekt mit den daten der auth mit id == $id aus der Datenbank. Benutzt den table der wie die klasse heist
		global $db,$config_vars;
		
		// generating the table from the class name plus a traling s
		$sql = 'SELECT * FROM ' . $config_vars['table_prefix'] . get_class($this) . " 
			WHERE (usergroup_id = $usergroup_id) and (" . ereg_replace("_auth$","",get_class($this)) . "group_id = $group_id)";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not get content from id", '', __LINE__, __FILE__, $sql);
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
			$this->in_db=true;
			$this->old_usergroup_id = $this->usergroup_id;
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_FAILED;
		}
	
	}
	
	function get_usergroup_id()
	{
		return $this->usergroup_id;
	}
	
	function set_usergroup_id($id)
	{
		$this->usergroup_id=$id;
		return OP_SUCCESFULL;
	}

			
	function get_view()
	{
		return $this->view;
	}
	
	function set_view($view=1)
	{
		$this->view = $view;
		return OP_SUCCESFULL;
	}

	function unset_view()
	{
		$this->view = 0;
		return OP_SUCCESFULL;
	}
	
	function get_delete()
	{
		return $this->delete;
	}


	function set_delete($d = 1)
	{
		$this->delete = $d;
		return OP_SUCCESFULL;
	}

	function unset_delete()
	{
		$this->delete = 0;
		return OP_SUCCESFULL;
	}
	
	function get_edit()
	{
		return $this->edit;
	}
	
	function set_edit($d = 1)
	{
		$this->edit = $d;
		return OP_SUCCESFULL;
	}

	function unset_edit()
	{
		$this->edit = 0;
		return OP_SUCCESFULL;
	}

	
	
		
}

class cat_auth extends auth
{
	var $catgroup_id;
	var $old_catgroup_id;
	
	function generate($usergroup_id,$group_id)
	{
		auth::generate($usergroup_id,$group_id);
		$this->old_catgroup_id = $this->catgroup_id;
		
	}
	
	function commit()
	{
		global $db,$config_vars;
		if (!$this->in_db)
		{
			// this is object is not yet in the datebase, make a new entry
			$sql = 'INSERT INTO ' . $config_vars['table_prefix'] . get_class($this) . " (`usergroup_id`,`catgroup_id`,`view`,`delete`,`edit`)
				VALUES ('$this->usergroup_id', '$this->catgroup_id','$this->view','$this->delete','$this->edit')";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error while submitting a new auth object to the db", '', __LINE__, __FILE__, $sql);
			}
			return OP_SUCCESSFULL;
			
			$this->in_db = true;
			
		}
		else
		{
			// object is already in the database just du an update
			$sql = 'UPDATE ' . $config_vars['table_prefix'] . get_class($this) . " 
				SET 	`usergroup_id` = '$this->usergroup_id', 
					`catgroup_id` = '$this->catgroup_id',
					`view` = '$this->view',
					`delete` = '$this->delete',
					`edit` = '$this->delete'		
				WHERE (usergroup_id = $this->old_usergroup_id) and (catgroup_id = $this->old_catgroup_id)";
			echo "<br>$sql<br>";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error while updating an existing cat_auth object to the db", '', __LINE__, __FILE__, $sql);
			}
			return OP_SUCCESSFUL;
		}

	}
	
	function set_catgroup_id($id)
	{
		$this->catgroup_id = $id;
		return OP_SUCCSESSFUL;
	}
	
	function get_catgroup_id($id)
	{
		return $this->catgroup_id;
	}
}

class content_auth extends auth
{
}
?>
