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
	var $last_changed_date;
	var $creation_date;
	var $change_count;
	var $parent_id;
	var $topic;
	
	function comment()
	{
		
	}
	
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
	
	function set_last_changed_date($new_last_changed_date)
	{
		$this->last_changed_date = $new_last_changed_date;
	}
	
	function get_last_changed_date()
	{
		return $this->last_changed_date;
	}
	
	function set_creation_date($new_creation_date)
	{
		$this->creation_date = $new_creation_date;
	}
	
	function get_creation_date()
	{
		return $this->creation_date;
	}

	function set_changed_count($new_changed_count)
	{
		$this->changed_count = $new_changed_count;
	}
	
	function get_changed_count()
	{
		return $this->changed_count;
	}



	
	function get_childs()
	{
		global $db,$config_vars;	
		// returns all comments that have $this->id as parent_id
		$sql = 'SELECT * FROM ' . $config_vars['table_prefix'] . get_class($this) . 's 
			WHERE parent_id like '.$this->id;
		
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
			$this->creation_date=date("Y-m-d H:i:s");
			// this is object is not yet in the datebase, make a new entry
			$sql = 'INSERT INTO ' . $config_vars['table_prefix'] . get_class($this) . "s 
				(owner_id, feedback, user_id, creation_date, changed_count, parent_id, topic, last_changed_date)
				VALUES ('$this->owner_id', '$this->feedback', '$this->user_id', '$this->creation_date', '$this->changed_count', '$this->parent_id', '$this->topic', '$this->last_changed_date')";
				
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
					feedback = '$this->feedback',
					user_id = '$this->user_id',
					creation_date = '$this->creation_date',
					changed_count = '$this->changed_count', 
					parent_id = '$this->parent_id', 
					topic = '$this->topic',
					last_changed_date = '$this->last_changed_date'
				WHERE id like $this->id";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error while updating an existing cat object to the db", '', __LINE__, __FILE__, $sql);
			}
			return OP_SUCCESSFULL;
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
	function delete()
	{
		global $db,$config_vars,$userdata;
		// remove from content table
		// check is user is allowed
		$content = new album_content();
		$content->generate_from_id($this->owner_id);
	echo "HH";		
		
		if (($userdata['user_id'] == $this->user_id) or (check_content_action_allowed($content->get_contentgroup_id(),$userdata['user_id'],'content_edit')))
		{
			$sql = "DELETE FROM " . $config_vars['table_prefix'] . "content_comments WHERE 'id' = " . $this->id;
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Konnte Objekt nicht löschen", '', __LINE__, __FILE__, $sql);
			}
			unset($this->id);
		}

	}
	
	
}

class cat_comment extends comment
{
	function delete()
	{
		global $db,$config_vars,$userdata;
		// remove from content table
		// check is user is allowed
		$cat = new categorie();
		$cat->generate_from_id($this->owner_id);
		
		
		if (($userdata['user_id'] == $this->user_id) or (check_cat_action_allowed($cat->get_catgroup_id(),$userdata['user_id'],'content_edit')))
		{
			$sql = "DELETE FROM " . $config_vars['table_prefix'] . "cat_comments WHERE id = " . $this->id;
			echo $sql;
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Konnte Objekt nicht löschen", '', __LINE__, __FILE__, $sql);
			}
			unset($this->id);
		}

	}
	
	
}

class rating extends user_feedback
{
	var $type_id;
	
	function set_type_id($new_type_id)
	{
		$this->type_id = $new_type_id;
	}
	
	function get_type_id()
	{
		return $this->type_id;
	}
	
	function commit()
	{
		global $db,$config_vars;
		if (!isset($this->id))
		{
			// this is object is not yet in the datebase, make a new entry
			$sql = 'INSERT INTO ' . $config_vars['table_prefix'] . get_class($this) . "s 
				(owner_id, feedback, user_id, type_id)
				VALUES ('$this->owner_id', '$this->feedback', '$this->user_id', '$this->type_id')";
				
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
					type_id = '$this->type_id',
					
				WHERE id like $this->id";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Error while updating an existing cat object to the db", '', __LINE__, __FILE__, $sql);
			}
			return OP_SUCCESSFULL;
		}

	}
	
}

class content_rating extends rating
{
	
}


class cat_rating extends rating
{
}

?>
