<?php
require_once('classed/auth.inc.php');

// Group, User, Auth editing functions
class group
{
	var $id;
	var $name;
	var $describtion;
	
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
		global $db,$config_vars;
		if (!isset($this->id))
		{
			// this is object is not yet in the datebase, make a new entry
			$sql = 'INSERT INTO ' . $config_vars['table_prefix'] . get_class($this) . " (name, describtion)
				VALUES ('$this->name', '$this->describtion')";
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
				SET 	name = '$this->name', 
					describtion = '$this->describtion'
				WHERE id like $this->id";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error while updating an existing cat object to the db", '', __LINE__, __FILE__, $sql);
			}
			return OP_SUCCESSFULL;
		}
	}
	
	function generate_from_id($id)
	{
		// Füllt das Objekt mit den daten der gruppe mit id == $id aus der Datenbank. Benutzt den table der wie die klasse heist
		global $db,$config_vars;
		$sql = 'select * from ' . $config_vars['table_prefix'] . get_class($this) . " where id like $id";
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
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_FAILED;
		}
	}
	
	function get_id()
	{
		return $this->id;
	}
	
	function set_name($new_name)
	{
		$this->name = $new_name;
	}
	
	function get_name()
	{
		return $this->name;
	}
	
	function set_describtion($new_desc)
	{
		$this->describtion = $new_desc;
	}
	
	function get_describtion()
	{	
		return $this->describtion;
	}

}



class usergroup extends group
{
	function add_user($user_id)
	{
		global $db,$config_vars;
		if (!user_in_group($user_id))
		{
			$sql = 'INSERT INTO' . $config_vars['table_prefix'] . "user_in_group (user_id,group_id) 
				VALUES ('$user_id','$this->id')";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error while adding user to group", '', __LINE__, __FILE__, $sql);
			}
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_USER_ALREADY_IN_GROUP;
		}
	
	}

	function remove_user($user_id)
	{
		global $db,$config_vars;
		if (user_in_group($user_id))
		{
			$sql = 'DELETE FROM' . $config_vars['table_prefix'] . "user_in_group 
				WHERE (user_id = $user_id) and group_id = $this->id";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error while removing user from group", '', __LINE__, __FILE__, $sql);
			}
			return OP_SUCCESSFUL;
		}
		else
		{
			return OP_USER_NOT_IN_GROUP;
		}

	}
	
	function user_in_group($user_id)
	{
		// return true if $user_id is in this group
		global $db,$config_vars;
		$sql = 'select user_id from' . $config_vars['table_prefix'] . "user_in_group where (user_id = $user_id) and (group_id = $group_id)";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Error while checking wether user is in group", '', __LINE__, __FILE__, $sql);
		}
		
		// if there is a row returned the user is in that group
		if ($db->sql_affectedrows()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

class catgroup extends group
{
	function get_auth($usergroup_id)	
	{
		// returns an auth object with the auth of $usergroup_id to this catgroup
		$auth = new cat_auth();
		$auth->generate($usergroup_id,$this->id);
	}

}

class contentgroup extends group
{
}
?>
