<?php
include_once('includes/common.inc.php');
include_once('./classes/album_content.inc.php');
include_once('./includes/template.inc.php');

//check if User is allowed to view this file
if ($userdata['user_level'] != 1)
{
	message_die(GENERAL_ERROR, "You are not Administrator", '', __LINE__, __FILE__, $sql);
}

/*	admin_cats:
* 	$mode can have following content:
* 		view
* 		add
* 		edit
* 		delete
* 		move
*/

if($mode == "view")
{
	
}	//end of $mode = view
elseif($mode == "add")
{
	
}	//end of $mode = add
elseif($mode == "edit")
{
	
}	//end of $mode = edit
elseif($mode == "delete")
{
	
}	//end of $mode = delete
elseif($mode == "move")
{
	
}	//end of $mode = mode
else	//if $mode is nothing, do nothing.
{
	
}
?>