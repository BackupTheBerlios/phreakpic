<?php
define ("ROOT_PATH",'');
include_once('./includes/common.inc.php');
include_once('./classes/album_content.inc.php');
include_once('./includes/functions.inc.php');
include_once('./modules/pic_managment/interface.inc.php');
include_once('./includes/template.inc.php');

$cookie = $_COOKIE[$config_vars['cookie_name'].'basket'];
$content_id_array=explode(':',$cookie);
	
for ($i=0;$i<sizeof($content_id_array)-1;$i++)
{
	$content_obj = get_content_object_from_id($content_id_array[$i]);
	$contents[]=$content_obj;
	
}

include "includes/view_thumbs.php";



$smarty->display($userdata['photo_user_template']."/basket.tpl");
$template_end_time = getmicrotime();
$template_execution_time = $template_end_time - $end_time;
echo("execution_time: $execution_time seconds<br>");
echo("template_execution_time: $template_execution_time seconds<br>");
$execution_time = $end_time - $start_time + $template_execution_time;
echo("gesamt execution_time: $execution_time seconds<br>");
?>