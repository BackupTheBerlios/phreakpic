<?php
require_once(ROOT_PATH . 'classes/base.inc.php');



class content_meta_field extends phreakpic_base
{
	var $id;
	var $fieldname;
	var $processing_vars = Array('fieldname');
	
	function generate_from_id($id)
	{
		return $this->base_generate_from_id($id,'content_meta_fields');
	}
	
	function commit()
	{
		global $db,$config_vars;
		
		// check if allowed
		if (true)
		{
			if (!isset($this->id))
			{
				// this is object is not yet in the datebase, make a new entry
				$sql = 'INSERT INTO ' . $config_vars['table_prefix'] . "content_meta_fields (fieldname)
					VALUES ('" . database_encode($this->fieldname) . "')";
					
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
				$sql = 'UPDATE ' . $config_vars['table_prefix'] . "content_meta_fields
					SET 	fieldname = '" . database_encode($this->fieldname) . "'
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
	

	
	function set_fieldname($name)
	{
		$this->fieldname=$name;
	}
	
	function get_fieldname()
	{
		return $this->get_fieldname;
	}
}

class content_meta_data extends phreakpic_base
{
	var $content_id;
	var $meta_data;
	
	function commit()
	{
		global $db,$config_vars;
		$sql = "DELETE FROM " . $config_vars['table_prefix'] . "content_meta_data WHERE content_id = {$this->content_id}";
		if (!$result = $db->sql_query($sql))
		{
			error_report(SQL_ERROR, 'commit' , __LINE__, __FILE__,$sql);
		}
		// delete all meta data of this content
		foreach ($this->meta_data as $id=>$meta)
		{
			if ($meta['data']!='')
			{
				$sql = "INSERT INTO " . $config_vars['table_prefix'] . "content_meta_data
				(meta_field_id,content_id,data) VALUES ('{$meta['meta_field_id']}','{$meta['content_id']}','{$meta['data']}')";
				echo $sql;
				if (!$result = $db->sql_query($sql))
				{
					error_report(SQL_ERROR, 'commit' , __LINE__, __FILE__,$sql);
				}
			}

		}
		
		$this->generate_from_content_id($this->content_id);
	}
	
	
	function generate_from_content_id($content_id)
	{
		global $db,$config_vars;
		unset($this->meta_data);
		$sql = 'select * from ' . $config_vars['table_prefix'] . "content_meta_data where content_id = $content_id";
		
		if (!$result = $db->sql_query($sql))
		{
			error_report(SQL_ERROR, 'generate' , __LINE__, __FILE__,$sql);
		}
		
		while ($row = $db->sql_fetchrow($result))
		{
			$this->meta_data[$row['id']] = $row;
		}
		$this->content_id=$content_id;
		return OP_SUCCESSFUL;
	}
	
	
	
	
	
	function set_meta_value($index,$value)
	{
		$this->meta_data[$index]['data'] = $value;
	}
	
	function add_meta_value($field_id,$value)
	{
		$meta['meta_field_id']=$field_id;
		$meta['data']=$value;
		$meta['content_id']=$this->content_id;
		$this->meta_data[]=$meta;
	}
	
	function get_meta_data()
	{
		foreach($this->meta_data as $row)
		{
			$meta_data[$row['meta_field_id']][$row['id']]=$row['data'];
		}
		return $meta_data;
	}
	
}

?>