<?php

require_once('DB.php');
require_once('config.inc.php');
require_once('errorhandling.inc.php');

//Check if connected to database, if not connect to it

if (!isset($db))
{
	$dsn = "$db_type://$db_user:$db_passwd@$db_host/$db_name";
	
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
