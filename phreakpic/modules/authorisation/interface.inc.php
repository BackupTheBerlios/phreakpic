<?php
function check_cat_allowed($cat_id,$user_id)
{
// Returns TRUE if the user with id $user_id is allowed to see the categorie with id $cat_id
	global $db,$config_vars;
	
	
	// check in which groups the user is
	
	$group_ids=get_groups_of_user($user_id);
	

}

function check_pic_allowed($pic_id,$user_id)
{
// Returns TRUE if the user with id $user_id is allowed to see the picture with id $pic_id
	$group_ids=get_groups_of_user($user_id);
}

// !!!!maybe not interface anymore!!!!!
function get_groups_of_user($user_id)
{
	// Returns array of group_ids in which the user with id $user_id is
	$sql = "
		SELECT group_id from " . $config_vars['table_prefix'] . "user_in_group 
		WHERE user_id like $user_id";
	
	if (!$result = $db->query($sql))
	{
		message_die(GENERAL_ERROR, "Could not get groups of user", '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$group_ids[] = $row['group_id'];
	}
		return $group_ids;

}
?> 
