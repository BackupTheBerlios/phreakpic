<?php
include_once(ROOT_PATH . 'includes/functions.inc.php');

DEFINE('NO_ERROR','0');
DEFINE('INFORMATION','1');
DEFINE('SQL_ERROR','2');
DEFINE('AUTH_ERROR','3');
DEFINE('FILE_ERROR','4');
DEFINE('GENERAL_ERROR','5');



class phreak_error
{
	var $ident; // possibility why this happened
	var $line;
	var $file;
	var $sql;
	var $operation; // operation in which the error occured
	var $is_value; // current value of the parameter
	var $set_value; // value that should be setted
	var $level;
	var $type; //warning, error,...
	var $sql_error;

	var $object_id; //id of the object the error occured

	function phreak_error($level,$type,$line,$file,$operation=FALSE,$object_id=FALSE,$is_value=FALSE,$should_value=FALSE,$sql='')
	{
		foreach(get_class_vars('phreak_error') as $var=>$value)
		{
			$this->$var=$$var;
		}
	}


	function is_error()
	{
		if ($this->type != ERROR_NO_ERROR)
		{
			return false;
		}
		return true;
	}

	function set_ident($iden)
	{
		$this->ident=$ident;
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

	function set_set_value($value)
	{
		$this->set_value=$value;
	}

	function set_operation($op)
	{
		$this->operation=$op;
	}

	function commit()
	{
		global $smarty,$db;
		global $HTTP_SESSION_VARS;



		// if its an sql error gets sql infos
		if ($this->type == SQL_ERROR)
		{
			$this->sql_error=$db->sql_error();

		}
		//aktivate error report
// TODO: hier muss noch was hin all ls if error_level > level to display
		$smarty->assign('is_error',true);

		// if level is error than halt immediatlley

		$HTTP_SESSION_VARS['error_container'][]=$this;
		if ($this->level == E_ERROR)
		{
			$this->report();
		}

	}

	function get_error_info()
	{
		global $userdata,$db,$config_vars;
		switch ($this->type)
		{
			case SQL_ERROR:

				$error_info['type'] = 'SQL_ERROR';
				$error_info['sql_error'] = $this->sql_error;
				break;
			case AUTH_ERROR: $error_info['type'] = 'AUTH_ERROR'; break;
			case FILE_ERROR: $error_info['type'] = 'FILE_ERROR'; break;
			case GENERAL_ERROR: $error_info['type'] = 'GENERAL_ERROR'; break;
			case INFORMATION: $error_info['type'] = 'INFORMATION'; break;
		}

		$error_info['level'] = $this->level;
		$error_info['ident'] = $this->ident;
		$error_info['text'] = $error[$this->ident];
		$error_info['line'] = $this->line;
		$error_info['file'] = $this->file;
		$error_info['sql'] = $this->sql;

		return $error_info;


	}

	function report()
	{
	global $userdata,$smarty,$db,$config_vars,$QUERY_STRING,$error;

	switch ($this->type)
	{
		case SQL_ERROR:
			$error_info['type'] = 'SQL_ERROR';
			$error_info['sql_error'] = $db->sql_error();
			break;
		case AUTH_ERROR: $error_info['type'] = 'AUTH_ERROR'; break;
		case FILE_ERROR: $error_info['type'] = 'FILE_ERROR'; break;
		case GENERAL_ERROR: $error_info['type'] = 'GENERAL_ERROR'; break;
		case INFORMATION: $error_info['type'] = 'INFORMATION'; break;
	}


	$error_info['ident'] = $this->ident;
	$error_info['text'] = $error[$this->ident];
	$error_info['line'] = $this->line;
	$error_info['file'] = $this->file;
	$error_info['sql'] = $this->sql;
	$error_info['debug'] = DEBUG;



	if ($type != INFORMATION)
	{
		// submit error to db
		$sql = "INSERT INTO " . $config_vars['table_prefix'] . "error_reports
					(type,file,line,sql,ident,user_id,query_string,error_time)
					VALUES ('{$type}','{$error_info['file']}','{$error_info['line']}','" . addslashes($error_info['sql']) . "','{$error_info['ident']}','{$userdata['user_id']}','$QUERY_STRING','" . date("Y-m-d H:i:s") . "')";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Error report failed", '', __LINE__, __FILE__, $sql);
		}
		$error_info['id'] = $db->sql_nextid();
	}

	$smarty->assign('error_info',$error_info);
	$smarty->assign('ROOT_PATH',ROOT_PATH);
	$smarty->display($userdata['photo_user_template']."/error_msg.tpl");
	die();
}


}



?>