<?php
require_once(ROOT_PATH . 'modules/authorisation/interface.inc.php');
require_once(ROOT_PATH . 'modules/pic_managment/interface.inc.php');

class categorie
{
	var $id;
	var $name;
	var $catgroup_id;
	var $parent_id;
	var $current_rating=0;
	var $is_serie=false;
	var $content_amount=0;
	var $child_content_amount=0;
	var $child_comments_amount=0;
	var $description;
	
	// vars for objects to be commited on commit()
	var $commit_parent_cat;
	
	function categorie()
	{
	}
	
	function fill_up()
	{
		// looks in the database for an entry that matches the already setted data and fills in the rest
		
		global $db,$config_vars;
		
		$vars = get_object_vars($this);
		
		foreach ($vars as $key => $value)
		{
			if (isset($value))
			{
				$where=$where." ($key = '$value') and";
			}
			
		}
		$where=  $where." 1";
				
		
		
		$sql = 'select * from ' . $config_vars['table_prefix'] . "cats WHERE $where";
		
		
		if (!$result = $db->sql_query($sql))
		{
			error_report(SQL_ERROR, 'generate' , __LINE__, __FILE__,$sql);
		}
		
		$row = $db->sql_fetchrow($result);
		return $this->generate_from_row($row);

		
	}
	
	function check_perm($perm)
	{
		global $userdata;
		if (!isset($$perm))
		{
			$this->$perm = check_cat_action_allowed($this->catgroup_id, $userdata['user_id'], $perm);
		}
		return $this->$perm;
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
		while (($cat_obj->get_parent_id() != $config_vars['root_categorie']) and ($cat_obj->id != $cat_obj->get_parent_id()))
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
		// Füllt das Objekt mit den daten der Categorie mit id == $id aus der Datenbank
		$sql = 'select * from ' . $config_vars['table_prefix'] . "cats where id like $id";
		if (!$result = $db->sql_query($sql))
		{
			error_report(SQL_ERROR, 'generate' , __LINE__, __FILE__,$sql);
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
	
	function delete($mode,$mode_params=0,$content_removed = 0, $cats_removed = 0)
	{
		// deletes the categorie assigned with this object from the database
		global $db,$config_vars,$userdata;
				
		
		if (isset($this->id))
		{
			$keep=false;
			// check if user has permission to do that
			$parent_cat = new categorie();
			$parent_cat->generate_from_id($this->parent_id);
			
			
			if (check_cat_action_allowed($parent_cat->get_catgroup_id(),$userdata['user_id'],'cat_remove'))
			{			
				if ($mode == CDM_MOVE_CONTENT)
				{
					
					// check if user has right to edit all content in this categorie
					
				
					// move content in this categorie to the cat with id $mode_params

					// wie genau soll man hier mit den perms umgehen ?? 
				}
				else
				{
					// check if there is content to be removed
					$content = get_content_of_cat($this->id);
					
					if (is_array($content))
					{
					
						// check is user is allowed to do that
						if (check_cat_action_allowed($this->catgroup_id,$userdata['user_id'],'content_remove'))
						{	
							// there is content to be removed
							for ($i=0; $i<sizeof($content); $i++)
							{
								if (($content[$i]->remove_from_cat($this->id) == OP_SUCCESSFUL) and ($content[$i]->commit()))
								{
									$content_removed++;
								}
								else 
								{
									// not all content was removed do not delete this cat
									$keep=true;
								}
								
							}
						}
						else
						{
							$keep=true;
						}
					}
					
					// check if user is allowed th remove cats from this cat
					$cats = get_cats_of_cat($this->id);
					
					
					if (is_array($cats))
					{
						// there are cats to be removed
						if (check_cat_action_allowed($this->catgroup_id,$userdata['user_id'],'cat_remove'))
						{
							
							for ($i=0; $i<sizeof($cats); $i++)
							{
								if ($cats[$i]->delete($mode,$mode_params,$content_removed,$cats_removed) == OP_SUCCESSFUL)
								{
									$cats_removed++;
								}
								else 
								{
									// not all cats were removed do not delete this cat
									
								}
								
							}
						}
						else
						{
							$keep=true;
						}
					}
					
					
					// delete content of the cat
				}
				
				if (!$keep)
				{
					$sql = 'DELETE FROM '. $config_vars['table_prefix'] . "cats where id like $this->id";
					if (!$result = $db->sql_query($sql))
					{
						error_report(SQL_ERROR, 'delete' , __LINE__, __FILE__,$sql);
					}
					unset($this->id);
					return OP_SUCCESSFUL;
				}
				return OP_PARTLY_SUCCESSFUL;
				
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
			if ($this->is_serie===false)
			{
				$is_serie=0;
			}
			else
			{
				$is_serie=1;
			}
			
			// this is object is not yet in the datebase, make a new entry
			$sql = 'INSERT INTO ' . $config_vars['table_prefix'] . "cats (name, current_rating, parent_id, catgroup_id,is_serie,content_amount,description,child_content_amount,child_comments_amount)
				VALUES ('$this->name', '$this->current_rating', '$this->parent_id', '$this->catgroup_id', '$is_serie', '$this->content_amount', '$this->description', '$this->child_content_amount', '$this->child_comments_amount')";
			if (!$result = $db->sql_query($sql))
			{
				error_report(SQL_ERROR, 'commit' , __LINE__, __FILE__,$sql);
			}
			
			$this->id = $db->sql_nextid();
			return OP_SUCCESSFUL;
			

			
		}
		else
		{
			// object is already in the database just du an update
			$sql = 'UPDATE ' . $config_vars['table_prefix'] . "cats 
				SET 	name = '$this->name', 
					current_rating = '$this->current_rating', 
					parent_id = '$this->parent_id', 
					catgroup_id = '$this->catgroup_id',
					is_serie = '$this->is_serie',
					content_amount = '$this->content_amount',
					description = '$this->description',
					child_content_amount = '$this->child_content_amount',
					child_comments_amount = '$this->child_comments_amount'
				WHERE id like $this->id";
			if (!$result = $db->sql_query($sql))
			{
				error_report(SQL_ERROR, 'commmit' , __LINE__, __FILE__,$sql);
			}
			
			
			// recalc child content_amount
			
			if ($this->get_id() != $config_vars['root_categorie'])
			{	
				$cat = new categorie();
				$cat->generate_from_id($this->get_parent_id());
				$cat->set_child_content_amount($cat->calc_child_content_amount());
				$cat->commit();
			}
			
			if (isset($this->commit_parent_cat))
			{
				$this->commit_parent_cat->commit();
			}
			
			return OP_SUCCESSFUL;

			
			
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
	
	function set_catgroup_id($new_catgroup_id,$recursive=false)
	{
		global $userdata;
		if (($this->id == 0) or check_cat_action_allowed($this->catgroup_id,$userdata['user_id'],'edit'))
		{
			$this->catgroup_id=$new_catgroup_id;
			if ($recursive)
			{
				$child_content = get_content_of_cat($this->id);
				
				foreach ($child_content as $content)
				{
					$content->set_contentgroup_id($new_catgroup_id);
					$content->commit();
				}
				$child_cats = get_cats_of_cat($this->id);
				foreach ($child_cats as $cat)
				{
					$cat->set_catgroup_id($new_catgroup_id,$recursive);
					$cat->commit();
				}
			}
			
			
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
	
	
	function calc_content_amount()
	{
		global $db,$config_vars;
		// calculates the content amount from content_in_cat
		$sql = "SELECT count(cat_id) FROM {$config_vars['table_prefix']}content_in_cat where cat_id={$this->id}";
		if (!$result = $db->sql_query($sql))
		{
			error_report(SQL_ERROR, 'calc_content_amount' , __LINE__, __FILE__,$sql);
		}
		$row = $db->sql_fetchrow($result);
		return $row[0];
	}

	function get_content_amount()
	{
		return $this->content_amount;
	}
	
	function set_content_amount($new_content_amount)
	{
		$this->content_amount = $new_content_amount;
		$this->set_child_content_amount($this->calc_child_content_amount());
		return OP_SUCCESSFUL;
	}
	
	function get_child_content_amount()
	{
		return $this->child_content_amount;
	}
	
	function set_child_content_amount($val)
	{
		global $config_vars;
		$this->child_content_amount = $val;
		
		return OP_SUCCSESSFUL;
	}
	
	
	
	function calc_child_content_amount()
	{
		global $config_vars;
		$amount=$this->content_amount;
		$cats = get_cats_of_cat($this->id);
		if (is_array($cats))
		{
			foreach ($cats as $value)
			{
				if ($value->get_id() != $config_vars['root_categorie'])
				{
					
					$amount+=$value->calc_child_content_amount();
				}
			}
		}
		
		return $amount;
		
		
	}
	
	function inc_child_comments_amount()
	{
		global $config_vars;
		$this->child_comments_amount++;
		if ($this->get_id() != $config_vars['root_categorie'])
		{
			$this->commit_parent_cat = new categorie();
			$this->commit_parent_cat->generate_from_id($this->get_parent_id());
			$this->commit_parent_cat->inc_child_comments_amount();
		}
	}
	
	function dec_child_comments_amount()
	{
		global $config_vars;
		$this->child_comments_amount--;
		if ($this->get_id() != $config_vars['root_categorie'])
		{
			$this->commit_parent_cat = new categorie();
			$this->commit_parent_cat->generate_from_id($this->get_parent_id());
			$this->commit_parent_cat->dec_child_comments_amount();
		}
	}
	
	
	
	function get_child_comments_amount()
	{
		return $this->child_comments_amount;
	}
	
	function set_child_comments_amount($val)
	{
		$this->child_comments_amount = $val;
		return OP_SUCCESSFUL;
	}
	
	function calc_child_comments_amount()
	{
		global $db, $config_vars;	
		
		
		
		//get the comments from  cat
		$sql = 'SELECT count(cat_comments.id) FROM ' . $config_vars['table_prefix'] . 'cat_comments AS cat_comments WHERE cat_comments.owner_id = ' . $this->id;
		if (!$result = $db->sql_query($sql))
		{
			error_report(SQL_ERROR, 'commit' , __LINE__, __FILE__,$sql);
		}
		$row = $db->sql_fetchrow($result);
		$amount = $row[0];

		//get the comments from content
		$sql = 'SELECT count(content_comments.id) 
				FROM ' . $config_vars['table_prefix'] . 'content_comments AS content_comments, ' . $config_vars['table_prefix'] . 'content_in_cat AS content_in_cat 
				WHERE (content_in_cat.cat_id = ' . $this->id . ') AND (content_in_cat.content_id = content_comments.owner_id)';
		if (!$result = $db->sql_query($sql))
		{
			error_report(SQL_ERROR, 'commit' , __LINE__, __FILE__,$sql);
		}
		$row = $db->sql_fetchrow($result);
		$amount += $row[0];
		
		
			$child_cats = get_cats_of_cat($this->id);	
			for($i=0; $i < sizeof($child_cats); $i++)
			{
				if ($child_cats[$i]->id != $config_vars['root_categorie'])
				{
					$amount += $child_cats[$i]->calc_child_comments_amount();
				}
			}
		return $amount;
	}

	
}

?>
