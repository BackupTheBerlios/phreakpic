<?php
function check_cat_allowed($cat_id,$user_id)
{
// Returns TRUE if the user with id $user_id is allowed to see the categorie with id $cat_id
	global $db,$config_vars;

	$sql = "SELECT $requested_fields FROM " . $config_vars['table_prefix'] . "cats WHERE parent_id = '$parent_id'";

	if (!$result = $db->query($sql))
	{
		message_die(GENERAL_ERROR, "Could not obtain forum watch information", '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$cat_data[] = $row;
	}


	return $cat_data;




}

function check_pic_allowed($pic_id,$user_id)
{
// Returns TRUE if the user with id $user_id is allowed to see the picture with id $pic_id

?> 
