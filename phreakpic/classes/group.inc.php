<?php
require_once(ROOT_PATH . 'classes/auth.inc.php');

// Group, User, Auth editing functions
class group
{
	var $id;
	var $name;
	var $description;
	
	function delete()
	{
		global $db,$config_vars;
		if (check_auth_action_allowed())
		{
			// remove from content table
			$sql = "DELETE FROM " . $config_vars['table_prefix'] . get_class($this) . "s WHERE id = " . $this->id;
			if (!$result = $db->sql_query($sql))
			{
				error_report(SQL_ERROR, 'delete' , __LINE__, __FILE__,$sql);
			}
			unset($this->id);
		}
		return OP_NP_MISSING_DELETE;
	}
	
	function commit()
	{
		global $db,$config_vars;
		if (check_group_action_allowed())
		{
			if (!isset($this->id))
			{
				// this is object is not yet in the datebase, make a new entry
				$sql = 'INSERT INTO ' . $config_vars['table_prefix'] . get_class($this) . "s (name, description)
					VALUES ('$this->name', '$this->description')";
				if (!$result = $db->sql_query($sql))
				{
					error_report(SQL_ERROR, 'commit' , __LINE__, __FILE__,$sql);
				}
				

				// set id;
				$this->id = $db->sql_nextid();

				return OP_SUCCESSFULL;
			}
			else
			{
				// object is already in the database just du an update
				$sql = 'UPDATE ' . $config_vars['table_prefix'] . get_class($this) . "s
					SET 	name = '$this->name', 
						description = '$this->description'
					WHERE id = $this->id";
				if (!$result = $db->sql_query($sql))
				{
					error_report(SQL_ERROR, 'commit' , __LINE__, __FILE__,$sql);
				}
				return OP_SUCCESSFULL;
			}
		}
		return OP_NP_MISSING_EDIT;
	}
	
	function generate_from_id($id)
	{
		// Füllt das Objekt mit den daten der gruppe mit id == $id aus der Datenbank. Benutzt den table der wie die klasse heist
		global $db,$config_vars;
		
		// generating the table from the class name plus a traling s 
		$sql = 'select * from ' . $config_vars['table_prefix'] . get_class($this) . "s where id like $id";
		if (!$result = $db->sql_query($sql))
		{
			error_report(SQL_ERROR, 'generate' , __LINE__, __FILE__,$sql);
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
	
	function set_description($new_desc)
	{
		$this->description = $new_desc;
	}
	
	function get_description()
	{	
		return $this->description;
	}

}



class usergroup extends group
{
	function add_user($user_id)
	{
		global $db,$config_vars;
		
		if (check_group_action_allowed())
		{
			if (!$this->user_in_group($user_id))
			{
				$sql = 'INSERT INTO ' . $config_vars['table_prefix'] . "user_in_group (user_id,group_id) 
					VALUES ('$user_id','$this->id')";
				if (!$result = $db->sql_query($sql))
				{
					error_report(SQL_ERROR, 'add_user' , __LINE__, __FILE__,$sql);
				}
				return OP_SUCCESSFUL;
			}
			else
			{
				return OP_USER_ALREADY_IN_GROUP;
			}
		}
		else
		{
			return OP_NP_MISSING_EDIT;
		}
	
	}

	function remove_user($user_id)
	{
		global $db,$config_vars;
		
		if (check_group_action_allowed())
		{
		
			if ($this->user_in_group($user_id))
			{
				$sql = 'DELETE FROM ' . $config_vars['table_prefix'] . "user_in_group 
					WHERE (user_id = $user_id) and group_id = $this->id";
				if (!$result = $db->sql_query($sql))
				{
					error_report(SQL_ERROR, 'remove_user' , __LINE__, __FILE__,$sql);
				}
				return OP_SUCCESSFUL;
			}
			else
			{
				return OP_USER_NOT_IN_GROUP;
			}
		}
		else
		{
			return OP_NP_MISSING_EDIT;
		}

	}
	
	function user_in_group($user_id)
	{
		// return true if $user_id is in this group
		global $db,$config_vars;
		$sql = 'SELECT user_id FROM ' . $config_vars['table_prefix'] . "user_in_group where (user_id = $user_id) and (group_id = $this->id)";
		if (!$result = $db->sql_query($sql))
		{
			error_report(SQL_ERROR, 'user_in_group' , __LINE__, __FILE__,$sql);
		}
		
		// if there is a row returned the user is in that group
		$row = $db->sql_fetchrow($result);
		
		if ($row['user_id'] == $user_id)
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
		return $auth;
	}

}

class contentgroup extends group
{
}
?>
