<?php
require_once('modules/authorisation/interface.inc.php');
require_once('includes/functions.inc.php');

class user_feedback
{
	var $id;
	var $feedback;
	var $user_id;
	var $owner_id;
	
	function commit()
	{
		
	}
	
	function delete()
	{
		global $db,$config_vars;
		// remove from content table
		$sql = "DELETE FROM '" . $config_vars['table_prefix'] . get_class($this) . "s WHERE 'id' = " . $this->id;
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Konnte Objekt nicht löschen", '', __LINE__, __FILE__, $sql);
		}
		unset($this->id);

	}
	
	function set_feedback($new_feedback)
	{
		$this->feedback = $new_feedback;
	}
	
	function get_feedback()
	{
		return $this->feedback;
	}
	
	function set_user_id($new_user_id)
	{
		$this->user_id = $new_user_id;
	}
	
	function get_user_id()
	{
		return $this->user_id;
	}


	function set_owner_id($new_owner_id)
	{
		$this->owner_id = $new_owner_id;
	}
	
	function get_owner_id()
	{
		return $this->owner_id;
	}

	
}

class comment extends user_feedback
{
	var $creation_date;
	var $change_count;
	var $parent_id;
	var $topic;
	
	function set_parent_id($new_parent_id)
	{
		$this->parent_id = $new_parent_id;
	}
	
	function get_parent_id()
	{
		return $this->parent_id;
	}
	
	function set_topic($new_topic)
	{
		$this->topic = $new_topic;
	}
	
	function get_topic()
	{
		return $this->topic;
	}


	
	function get_childs()
	{
		global $db,$config_vars;	
		// returns all comments that have $this->id as parent_id
		$sql = 'SELECT * FROM ' . $config_vars['table_prefix'] . get_class($this) . "s 
			WHERE parent_id like $this->id";
		
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not get content from id", '', __LINE__, __FILE__, $sql);
		}
		
		while ($row = $db->sql_fetchrow($result))
		{	
			$new_class = get_class($this);
			$child = new $new_class;
			if ($child->generate_from_row($row) != OP_SUCCESSFUL)
			{
				return OP_FAILED;
			}
			$child_array[]=$child;

		}
		return $child_array;
	}
	
	function commit()
	{
		global $db,$config_vars;
		if (!isset($this->id))
		{
			// this is object is not yet in the datebase, make a new entry
			$sql = 'INSERT INTO ' . $config_vars['table_prefix'] . get_class($this) . "s 
				(owner_id, feedback, user_id, creation_date, changed_count, parent_id, comment_topic)
				VALUES ('$this->owner_id', '$this->feedback', '$this->user_id', '$this->creation_date', '$this->changed_count', '$this->parent_id', '$this->topic')";
				
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error while submitting a new group object to the db", '', __LINE__, __FILE__, $sql);
			}
			return OP_SUCCESSFULL;
			
			// set id;
			$this->id = $db->sql_nextid();
			
		}
		else
		{
			// object is already in the database just du an update
			$sql = 'UPDATE ' . $config_vars['table_prefix'] . get_class($this) . "s  
				SET	owner_id = '$this->owner_id',
					comment_text = '$this->feedback',
					user_id = '$this->user_id',
					creation_date = '$this->creation_date',
					changed_count = '$this->changed_count', 
					parent_id = '$this->parent_id', 
					comment_topic = '$this->topic'
				WHERE id like $this->id";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error while updating an existing cat object to the db", '', __LINE__, __FILE__, $sql);
			}
			return OP_SUCCESSFULL;
		}

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

	
	function generate_initial_for_content($content_id)
	{
		global $config_vars,$db;
		// makes this to the first comment of content $content_id
		$sql = 'SELECT * FROM ' . $config_vars['table_prefix'] . get_class($this) .'s
			WHERE (owner_id = ' .$content_id . ') and (parent_id = 0)';
			
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Error generating initial comment", '', __LINE__, __FILE__, $sql);
		}

		return $this->generate_from_row($db->sql_fetchrow($result));
	}
	
	function generate_from_id($id)
	{
		global $config_vars,$db;
		// Füllt das Objekt mit den daten des Contents mit id == $id aus der Datenbank
		global $db,$config_vars;
		$sql = 'SELECT * FROM ' . $config_vars['table_prefix'] . get_class($this) . "s 
			WHERE id like $id";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not get content from id", '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		return $this->generate_from_row($row);
	}
	
}

class content_comment extends comment
{
	
	
}



?> 
