<?php


class phreak_auth
{
	var $in_db=false;
	var $usergroup_id;
	var $view=false;
	var $delete=false;
	var $edit=false;
	var $comment_edit=false;
	var $add_to_group=false;
	var $remove_from_group=false;
	
	// need to save those becuase object is identified by them
	var $old_usergroup_id;
	
	// this array indicated which vars should be processed by external automatic processing
	var $processing_vars = Array('view','delete','edit','comment_edit','add_to_group','remove_from_group');
	

	// this vars will be stored in the db
	var $db_vars = Array('view','delete','edit','comment_edit','add_to_group','usergroup_id','remove_from_group');

	

	function delete()
	{
	}
	
	function commit($where)
	{
	
		global $db,$config_vars;
		if (check_auth_action_allowed())
		{
			if (!$this->in_db)
			{
				// this is object is not yet in the datebase, make a new entry
				$sql = 'INSERT INTO ' . $config_vars['table_prefix'] . get_class($this). ' (';

				// get the set vars from db_vars
				foreach ($this->db_vars as $value)
				{			
					$sql = $sql . KEY_QUOTE. $value.KEY_QUOTE.", ";
				}
				// unset the last ','
				$sql{strlen($sql)-2}=' ';

				$sql = $sql . ') VALUES ( ';

				foreach ($this->db_vars as $value)
				{			
					// if false enter '0' postresql needs this.
					if ($this->$value===false)
					{
						$sql = $sql . "'0', ";	
					}
					else
					{
						$sql = $sql . "'{$this->$value}', ";	
					}
				}
				$sql{strlen($sql)-2}=' ';

				$sql = $sql . ')';
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
				$sql = 'UPDATE ' . $config_vars['table_prefix'] . get_class($this) . ' SET ';
				// get the set vars from db_vars
				foreach ($this->db_vars as $value)
				{			
					$sql = $sql . KEY_QUOTE .$value . KEY_QUOTE . " = '{$this->$value}', ";
				}
				// unset the last ','
				$sql{strlen($sql)-2}=' ';
				$sql = $sql . "WHERE $where";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Error while updating an existing cat_auth object to the db", '', __LINE__, __FILE__, $sql);
				}
				return OP_SUCCESSFUL;
			
			
			}
		}
			
		return OP_NP_MISSING_EDIT;

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
	
	function set_comment_edit($comment_edit=1)
	{
		$this->comment_edit = $comment_edit;
		return OP_SUCCESFULL;
	}
	
				
	function get_comment_edit()
	{
		return $this->comment_edit;
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
	
	
	function get_add_to_group()
	{
		return $this->add_to_group;
	}

	
	function set_add_to_group($add_to_group=1)
	{
		$this->add_to_group = $add_to_group;
		return OP_SUCCESFULL;
	}


	function unset_add_to_group()
	{
		$this->add_to_group = 0;
		return OP_SUCCESFULL;
	}
	
	
	function get_remove_from_group()
	{
		return $this->remove_from_group;
	}

	
	function set_remove_from_group($d=1)
	{
		$this->remove_from_group = $d;
		return OP_SUCCESFULL;
	}


	function unset_remove_from_group()
	{
		$this->remove_from_group = 0;
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

class cat_auth extends phreak_auth
{
	var $catgroup_id;
	var $old_catgroup_id;
	var $cat_add=false;
	var $cat_remove=false;
	var $content_add=false;
	var $content_remove=false;
	
	function cat_auth()
	{
		$this->processing_vars[] = 'cat_add';
		$this->processing_vars[] = 'cat_remove';
		$this->processing_vars[] = 'content_add';
		$this->processing_vars[] = 'content_remove';
	
		$this->db_vars[] = 'cat_add';
		$this->db_vars[] = 'content_add';
		$this->db_vars[] = 'cat_remove';
		$this->db_vars[] = 'content_remove';
		$this->db_vars[] = 'catgroup_id';

		
	}

		
	
	function delete()
	{
		if (check_auth_action_allowed())
		{
			global $db,$config_vars;
			// remove from content table
			$sql = "DELETE FROM " . $config_vars['table_prefix'] . get_class($this) . " WHERE (usergroup_id = $this->usergroup_id) and (catgroup_id = $this->catgroup_id)";

			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Konnte Objekt nicht löschen", '', __LINE__, __FILE__, $sql);
			}
			unset($this->id);
		}
		else
		{
			return OP_NP_MISSING_DELETE;
		}
	}

	
		
	function get_cat_add()
	{
		return $this->cat_add;
	}


	function set_cat_add($d = 1)
	{
		$this->cat_add = $d;
		return OP_SUCCESFULL;
	}

	function get_cat_remove()
	{
		return $this->cat_remove;
	}

	function set_cat_remove($d = 1)
	{
		$this->cat_remove = $d;
		return OP_SUCCESFULL;
	}	
	
	function get_content_add()
	{
		return $this->content_add;
	}


	function set_content_add($d = 1)
	{
		$this->content_add = $d;
		return OP_SUCCESFULL;
	}
	
		
	function get_content_remove()
	{
		return $this->content_remove;
	}


	function set_content_remove($d = 1)
	{
		$this->content_remove = $d;
		return OP_SUCCESFULL;
	}
	
	
	
		
	function generate($usergroup_id,$group_id)
	{
		phreak_auth::generate($usergroup_id,$group_id);
		$this->old_catgroup_id = $this->catgroup_id;
		
	}
	
	function commit()
	{
		$where = "(usergroup_id = $this->old_usergroup_id) and (catgroup_id = $this->old_catgroup_id)";
		phreak_auth::commit($where);

	}
	
	function set_group_id($id)
	{
		$this->catgroup_id = $id;
		return OP_SUCCSESSFUL;
	}
	
	function get_group_id($id)
	{
		return $this->catgroup_id;
	}
	
	function set_catgroup_id($id)
	{
		$this->catgroup_id = $id;
		return OP_SUCCSESSFUL;
	}
	
	function get_catgroup_id()
	{
		return $this->catgroup_id;
	}
}

class content_auth extends phreak_auth
{
	var $contentgroup_id;
	
	var $old_contentgroup_id;
	
	function content_auth()
	{
		$this->db_vars[] = 'contentgroup_id';
	}
	
	function generate($usergroup_id,$group_id)
	{
		phreak_auth::generate($usergroup_id,$group_id);
		$this->old_contentgroup_id = $this->contentgroup_id;
	}
	
	function delete()
	{
		global $db,$config_vars;
		if (check_auth_action_allowed())
		{
			// remove from content table
			$sql = "DELETE FROM " . $config_vars['table_prefix'] . get_class($this) . " WHERE (usergroup_id = $this->usergroup_id) and (contentgroup_id = $this->contentgroup_id)";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Konnte Objekt nicht löschen", '', __LINE__, __FILE__, $sql);
			}
			unset($this->id);
		}
		else
		{
			return OP_NP_MISSING_DELETE;
		}
			
	}
	
	function set_group_id($id)
	{
		$this->contentgroup_id = $id;
		return OP_SUCCSESSFUL;
	}
	
	function get_group_id()
	{
		return $this->contentgroup_id;
	}
	
	function set_contentgroup_id($id)
	{
		$this->contentgroup_id = $id;
		return OP_SUCCSESSFUL;
	}
	
	function get_contentgroup_id()
	{
		return $this->contentgroup_id;
	}

	
	function commit()
	{
		$where = "(usergroup_id = $this->old_usergroup_id) and (contentgroup_id = $this->old_contentgroup_id)";
		phreak_auth::commit($where);
	}
}


?>
