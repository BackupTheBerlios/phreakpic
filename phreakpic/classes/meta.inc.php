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

?>