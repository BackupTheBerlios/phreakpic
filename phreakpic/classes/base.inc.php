<?php

class phreakpic_base
{
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
	
	function base_generate_from_id($id,$table)
	{
		//global $PHP_SELF,$QUERY_STRING;
		// Füllt das Objekt mit den daten des Contents mit id == $id aus der Datenbank
		global $db,$config_vars;
		$sql = 'select * from ' . $config_vars['table_prefix'] . $table . " where id = $id";
		
		if (!$result = $db->sql_query($sql))
		{
			$error = new phreak_error(E_WARNING,SQL_ERROR,__LINE__,__FILE__,'generate',$this->id,0,0,$sql);
			$error->commit();
// 			error_report(SQL_ERROR, 'generate' , __LINE__, __FILE__,$sql);
		}
		
		$row = $db->sql_fetchrow($result);
		return $this->generate_from_row($row);
		
	}
	
	function delete()
	{
	
		global $config_vars,$db;
		//delete all cat_auths related to this usergroup
		$sql = "DELETE FROM " . $config_vars['table_prefix'] . get_class($this) . "s WHERE (id = $this->id)";
		if (!$result = $db->sql_query($sql))
		{
			$error = new phreak_error(E_WARNING,SQL_ERROR,__LINE__,__FILE__,'delete',$this->id,0,0,$sql);
			$error->commit();
// 			error_report(SQL_ERROR, 'delete' , __LINE__, __FILE__,$sql);
		}
		
	}
	
	function set_vars($vars)
	{
		
		foreach ($this->processing_vars as $value)
		{
			
			$set_func = "set_$value";
			$this->$set_func($vars[$value]);
			
		}
	}
	
	
}


?>
