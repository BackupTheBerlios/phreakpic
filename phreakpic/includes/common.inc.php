<?php

require_once('DB.php');
require_once('../config.inc.php');
require_once('errorhandling.inc.php');

//Check if connected to database, if not connect to it

if (!isset($db))
{
	$dsn = $config_vars['db_type'].'://'.$config_vars['db_user'].':'.$config_vars['db_passwd'].'@'.$config_vars['db_host'].'/'.$config_vars['db_name'];
	
	// connect to database presisent
	$db = DB::connect($dsn, true);
	
	if (DB::isError($db)) 
	{
	echo ("ERROR");
		message_die("", $db->getMessage(), '', __LINE__, __FILE__);
	}
	
	// set default fetchmode to assoc
	$db->setFetchMode(DB_FETCHMODE_ASSOC);
}

?> 
