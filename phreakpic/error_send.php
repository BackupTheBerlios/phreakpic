<?php
define ("ROOT_PATH",'');
include_once('./includes/common.inc.php');
include_once('./includes/functions.inc.php');
include_once('./languages/' . $userdata['user_lang'] . '/lang_main.php');
include_once('./includes/template.inc.php');

$sql = "UPDATE " . $config_vars['table_prefix'] . "error_reports
				SET	
					comment = '{$HTTP_POST_VARS['comment']}'
				WHERE id = $error_id";

if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Error adding error report comment", '', __LINE__, __FILE__, $sql);
}

$smarty->display($userdata['photo_user_template']."/error_send.tpl");
?>
