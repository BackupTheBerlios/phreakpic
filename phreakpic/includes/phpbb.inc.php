<?php
define('IN_PHPBB', true);
$phpbb_root_path = ROOT_PATH . '../phpBB2/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);


// if ( !$userdata['session_logged_in'] )
// {
// 	$redirect =  "../".$PHP_SELF;
// 	$header_location = ( @preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE")) ) ? "Refresh: 0; URL=" : "Location: ";
// 	header($header_location . append_sid($phpbb_root_path."login.$phpEx?redirect=$redirect", true));
// }

?>
