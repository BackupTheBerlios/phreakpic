<?php
include_once(ROOT_PATH . 'includes/functions.inc.php');


class phreak_error
{
	var $why; // possibility why this happened
	var $operation; // operation in which the error occured
	var $is_value; // current value of the parameter
	var $should_value; // value that should be setted
	var $type; //warning, error,...
	var $object_id; //id of the object the error occured

	function is_error()
	{
		if ($this->type != ERROR_NO_ERROR)
		{
			return false;
		}
		return true;
	}

	function set_why($why)
	{
		$this->why=$why;
	}

	function set_type($type)
	{
		$this->type=$type;
	}

	function set_object_id($id)
	{
		$this->object_id=$id;
	}

	function set_is_value($value)
	{
		$this->is_value=$value;
	}

	function set_should_value($value)
	{
		$this->should_value=$value;
	}

	function set_operation($op)
	{
		$this->operation=$op;
	}
}



?>
