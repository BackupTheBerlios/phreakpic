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

function get_content_by_views($order='DESC',$limit_start=0,$limit_end=-1)
{
	// returns a assoc array containing 'object' = content objects and 'views' = number of views ordered by the number of times they are viewed.
	

	global $db,$config_vars;	
	$sql = 'SELECT views.content_id,count(*) as amount,content.* FROM ' . $config_vars['table_prefix'] . 'views as views, ' . $config_vars['table_prefix'] . 'content as content 
		WHERE views.content_id=content.id 
		GROUP BY views.content_id 
		ORDER BY amount '.$order." 
		LIMIT $limit_start,$limit_end";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldnt get content by views", '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{	
		$a['object']=get_content_from_row($row);
		$a['views']=$row['amount'];
		$objarray[]=$a;
		
	}
	return $objarray;

}
function get_content_by_viewed_length($order='DESC',$limit_start=0,$limit_end=-1)
{
	// returns a assoc array containing 'object' = content objects and 'length' = how long they have been viewed ordered by the length they have been viewed.	
	global $db,$config_vars;	
	$sql = 'SELECT views.content_id, SUM(UNIX_TIMESTAMP(views.end) - UNIX_TIMESTAMP(views.start)) as length,content.* FROM ' . $config_vars['table_prefix'] . 'views AS views, ' . $config_vars['table_prefix'] . 'content AS content 
		WHERE (views.content_id=content.id) and (views.end!=0)
		GROUP BY views.content_id 
		ORDER BY length '.$order." 
		LIMIT $limit_start,$limit_end";

		
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldnt get content by views", '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{	
		$a['object']=get_content_from_row($row);
		$a['time']=$row['length'];
		
		$objarray[]=$a;
		
	}
	return $objarray;
}

function get_content_ordered_by($by,$filter=true,$order='DESC',$limit_start=0,$limit_end=-1)
{
	// returns a assoc array containing 'object' = content objects and 'length' = how long they have been viewed ordered by the length they have been viewed.	
	global $db,$config_vars;	
	if ($filter)
	{
		$filter='and (view_table.end!=0)';
	}
	$sql = 'SELECT SUM(UNIX_TIMESTAMP(view_table.end) - UNIX_TIMESTAMP(view_table.start)) as length,COUNT(*) as amount,content.* FROM ' . $config_vars['table_prefix'] . 'views AS view_table, ' . $config_vars['table_prefix'] . 'content AS content 
		WHERE (view_table.content_id=content.id) '.$filter.'
		GROUP BY view_table.content_id 
		ORDER BY '.$by . " $order 
		LIMIT $limit_start,$limit_end";
	echo $sql;
		
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldnt get content by views", '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{	
		
		
		$a['object']=get_content_from_row($row);
		$a['length']=$row['length'];
		$a['amount']=$row['amount'];		
		$objarray[]=$a;
		
	}
	return $objarray;
}
?> 