<?php
define ("ROOT_PATH",'');
include_once('./includes/common.inc.php');
include_once('./classes/album_content.inc.php');
include_once('./modules/pic_managment/interface.inc.php');
include_once('./languages/' . $userdata['user_lang'] . '/lang_main.php');
include_once('./includes/template.inc.php');


function parse_sql($query)
{
	// replaces the placeholders in $query with the values form $HTTP_POST_VARS['returns']
	
	global $HTTP_POST_VARS;
	// get param fields
	
	
	for ($i=0;$i<sizeof($HTTP_POST_VARS['returns']);$i++)
	{
		$query=preg_replace('/\[\$'.$i.'\]/',$HTTP_POST_VARS['returns'][$i],$query);
	}
	
	return $query;	
}







session_start();




// get list of custom queries
$sql="SELECT * from {$config_vars['table_prefix']}custom_searches";
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Error in sql", '', __LINE__, __FILE__, $sql);
}
while ($row = $db->sql_fetchrow($result))
{
	if (strlen($lang[$row['name']])>0)
	{
		$row['name']=$lang[$row['name']];
	}
	$searches[]=$row;
}

// assign them to smarty
$smarty->assign('searches',$searches);


// if a query has been selected
if (isset($query))
{
	//smarty needs to now this for adding it to the link again
	$smarty->assign('query',$query);


	// get params string for thar query
	$sql="SELECT params from {$config_vars['table_prefix']}custom_searches WHERE id=$query";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Error in sql", '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	
	// generate param array
	$param=generate_params($row['params']);
	
	// if there are paarams assign it to smarty
	if (is_array($param))
	{
		$smarty->assign('param',$param);
	}
	// else now params needed already submit the query
	else
	{
		$submit=true;
	}
	//$sql=parse_sql($row['query'],);
	
	
}

if (isset($submit))
{
	// if you come back from a view_content param data already has been set so use the one out of the session vars
	if ($submit=='old')
	{
	
		$HTTP_POST_VARS['returns']=$HTTP_SESSION_VARS['rets'];
	}
	
	// get query string for the query
	$sql="SELECT query from {$config_vars['table_prefix']}custom_searches WHERE id=$query";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Error in sql", '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	
	// replace placeholder with data from params
	$sql=parse_sql($row['query']);
	
	//clear content array
	unset ($contents);
	
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Error in sql", '', __LINE__, __FILE__, $sql);
	}
		
	//fill content array with result from query
	while ($row = $db->sql_fetchrow($result))
	{
		if (is_object(get_content_object_from_id($row[0])))
		{
			$contents[]=get_content_object_from_id($row[0]);
		}
	}
	
	// save contents array for view_content next and back buttons
	$HTTP_SESSION_VARS['contents']=$contents;
	// save link back to the thumbs for view_content
	$HTTP_SESSION_VARS['thumb_link']="view_custom_searches.php?query=$query&submit=old";
	$smarty->assign('thumb_link',$HTTP_SESSION_VARS['thumb_link']);
	// also the setted params
	$HTTP_SESSION_VARS['rets']=$HTTP_POST_VARS['returns'];
	// the already sleected params should also be displayed on the page
	$smarty->assign('rets',$HTTP_POST_VARS['returns']);
	
	

	include "includes/view_thumbs.php";
}
$smarty->display($userdata['photo_user_template']."/view_custom_searches.tpl");

?>
