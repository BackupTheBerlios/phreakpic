<?php
define ("ROOT_PATH",'../');
include_once(ROOT_PATH . 'includes/common.inc.php');
include_once(ROOT_PATH . 'classes/album_content.inc.php');
include_once(ROOT_PATH . 'includes/template.inc.php');

//check if User is allowed to view this file
if ($userdata['user_level'] != 1)
{
	message_die(GENERAL_ERROR, "You are not Administrator", '', __LINE__, __FILE__, $sql);
}

/*	HIer kommt die Übersicht der Funktionen rein und vielleicht etwas Statistiken
* 
* 
* Funktionesweise:
* $mode= left ist der linke Framerahmen
* 
*/

$smarty->display($userdata['photo_user_template'].'/admin/index.tpl');
?>