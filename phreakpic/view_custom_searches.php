<?php
define ("ROOT_PATH",'');
include_once('./includes/common.inc.php');
include_once('./classes/album_content.inc.php');
include_once('./modules/pic_managment/interface.inc.php');
include_once('./languages/' . $userdata['user_lang'] . '/lang_main.php');
include_once('./includes/template.inc.php');
include_once('./includes/custom_searches.inc.php');





session_start();


if (isset($add))
{		
		$HTTP_SESSION_VARS['lines'][$HTTP_POST_VARS['row']]++;
}

if (isset($remove))
{		
		if ($HTTP_SESSION_VARS['lines'][$HTTP_POST_VARS['row']]>0)
		{
			$HTTP_SESSION_VARS['lines'][$HTTP_POST_VARS['row']]--;
		}
}

// if you come back from a view_content param data already has been set so use the one out of the session vars
if ($submit=='old')
{
	$HTTP_POST_VARS['returns']=$HTTP_SESSION_VARS['rets'];
}




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
$no_instand_submit=true;
if (isset($_GET['query']))
{
	$no_instand_submit=false;
	
	//smarty needs to now this for adding it to the link again
	$smarty->assign('query',$_GET['query']);
	

	// get xml string for that query
	$sql="SELECT xml from {$config_vars['table_prefix']}custom_searches WHERE id={$_GET['query']}";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Error in sql", '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	
	// generate param array
	$query_sql=parse_xml($row[0]);

			
	//print_r($query_sql->entities);
	
	// generate info for template
	$x=0;
	$y=-1;
	foreach ($query_sql->entities as $query_part)
	{
		$loops=0;
		if (get_class($query_part)=='custom_sql')
		{
			$no_instand_submit=true;
			$loops++;
			foreach ($query_part->entities as $value)
			{
				if (get_class($value)=='sql_field')
				{
					$y++;
					field_param($value,$query_sql);
					
				}
			}
			$y--;
			
		}
		if (get_class($query_part)=='sql_field')
		{
			$no_instand_submit=true;
			$y++;
			field_param($query_part,$query_sql);
// 			$field['name']=$query_part->name;
// 			$field['type']=$query_part->type;
// 			$field['descr']=$query_part->descr;
// 			$field['value']=generate_values($query_part->value);
// 			$field['loop']=0;
// 			$fields[0][$y]=$field;
			
			
		}
		
	}
	
	
	

}

$smarty->assign('fields',$fields);




if (($submit) or (!$no_instand_submit))
{




	// replace placeholder with data from params
	$sql=make_sql($HTTP_POST_VARS['returns']);
	//clear content array
#	echo $sql;
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
			$new_content=get_content_object_from_id($row[0]);
			$arr_keys = array_keys($row);
			for ($i=3;$i<sizeof($arr_keys);$i+=2)
			{
				$new_content->add_params[$arr_keys[$i]] = $row[$arr_keys[$i]];
			}

			$contents[]=$new_content;
		}
	}
	

	// save contents array for view_content next and back buttons
	$HTTP_SESSION_VARS['contents']=$contents;
	// save link back to the thumbs for view_content
	$HTTP_SESSION_VARS['thumb_link']="view_custom_searches.php?query=$query&submit=old";
	$smarty->assign('thumb_link',$HTTP_SESSION_VARS['thumb_link']);
	// also the setted params
	$HTTP_SESSION_VARS['rets']=$HTTP_POST_VARS['returns'];



	include "includes/view_thumbs.php";
}

$nav_content['name']=$lang['search'];
$nav_string[]=$nav_content;
$smarty->assign('nav_string',$nav_string);

$end_time = getmicrotime();
$execution_time = $end_time - $start_time;
$smarty->display($userdata['photo_user_template']."/view_custom_searches.tpl");
$template_end_time = getmicrotime();
$template_execution_time = $template_end_time - $end_time;
echo("execution_time: $execution_time seconds<br>");
echo("template_execution_time: $template_execution_time seconds<br>");
$execution_time = $end_time - $start_time + $template_execution_time;
echo("gesamt execution_time: $execution_time seconds<br>");

?>
