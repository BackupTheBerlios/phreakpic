<?php

function get_views($content_id,$user_id=-2)
{
	global $db,$config_vars;	
	
	if ($user_id != -2)
	{
		$userwhere="and (user_id = $user_id)";
	}

	
	$sql = 'SELECT COUNT(*) FROM ' . $config_vars['table_prefix'] . "views WHERE content_id = $content_id $userwhere"; 
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldnt get place_in_cat", '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	return $row[0];
	


}

function get_viewed_time($content_id,$user_id=-2)
{
	global $db,$config_vars;	
	
	if ($user_id != -2)
	{
		$userwhere="and (user_id = $user_id)";
	}
	
	$sql = 'SELECT UNIX_TIMESTAMP(start),UNIX_TIMESTAMP(end) FROM ' . $config_vars['table_prefix'] . "views WHERE (content_id = $content_id) and (end !=0) $userwhere"; 
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldnt get place_in_cat", '', __LINE__, __FILE__, $sql);
	}
	$viewed_time = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$viewed_time = $viewed_time + $row[1]-$row[0];
			
	}
	return $viewed_time;


}

?> 
