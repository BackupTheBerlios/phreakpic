<?php
define ("ROOT_PATH",'');
include_once('./includes/common.inc.php');
include_once('./classes/album_content.inc.php');
include_once('./classes/group.inc.php');
include_once('./modules/pic_managment/interface.inc.php');
include_once('./includes/functions.inc.php');
include_once('./languages/' . $userdata['user_lang'] . '/lang_main.php');
include_once('./includes/template.inc.php');


session_start();


$add_to_contentgroups = get_contentgroups_data_where_perm('id,name','add_to_group');
if (!is_array($add_to_contentgroups))
{
	die ('You dont have perms to add content to any contentgroup');
}
$smarty->assign('add_to_contentgroups',$add_to_contentgroups);





$cats = get_cats_data_where_perm('id,name','content_add');
$add_to_cats = get_cats_string($cats);
if (!is_array($add_to_cats))
{
	die ('You dont have perms to add content to any categorie');
}
$smarty->assign('add_to_cats',$add_to_cats);





if (isset($content_to_add))
{
	$place=0;
	foreach ($content_to_add as $value)
	{
		
		
		add_content(basename($value),$value,"",$new_content_cat,$place,$new_content_group);
		$place++;
	}

}

$dir=$config_vars['default_upload_dir'];


if ($HTTP_POST_VARS['moveup']=="up")
{
	$temp=$HTTP_SESSION_VARS['files'][$HTTP_POST_VARS['id']-1];
	$HTTP_SESSION_VARS['files'][$HTTP_POST_VARS['id']-1] = $HTTP_SESSION_VARS['files'][$HTTP_POST_VARS['id']];
	$HTTP_SESSION_VARS['files'][$HTTP_POST_VARS['id']] = $temp;

}
else if ($HTTP_POST_VARS['movedown']=="down")
{
	$temp=$HTTP_SESSION_VARS['files'][$id+1];
	$HTTP_SESSION_VARS['files'][$id+1] = $HTTP_SESSION_VARS['files'][$id];
	$HTTP_SESSION_VARS['files'][$id] = $temp;

}
elseif (isset($dir))
{
	unset($HTTP_SESSION_VARS['files']);
	$dir_handle=opendir($dir);
	while ($file = readdir ($dir_handle))
	{
		
		if (($file != "." && $file != "..") and (isset($filetypes[getext($file)]) )) // WELCHE DATEIENDUNGEN werden benutzt? Soll es einstellbar sein? Wenn ja, wo?
		{
			$f['filesize'] = round(filesize($dir.'/'.$file)/1024,1);
			$f['size'] = getimagesize($dir.'/'.$file);
			$f['url'] = $dir.'/'.$file;
			$f['name'] = basename($file);
			$HTTP_SESSION_VARS['files'][] = $f;
		}
	}
}



$smarty->assign('files',$HTTP_SESSION_VARS['files']);
$smarty->assign('files_size',sizeof($HTTP_SESSION_VARS['files']));
$smarty->assign('dir',$dir);
if (!isset($HTTP_POST_VARS['thumbsize']))
{
	$HTTP_POST_VARS['thumbsize']=50;
}
$smarty->assign('thumbsize',$HTTP_POST_VARS['thumbsize']);
$smarty->assign('thumbs',$HTTP_POST_VARS['thumbs']);

$end_time = getmicrotime();
$execution_time = $end_time - $start_time;

$smarty->display($userdata['photo_user_template']."/add_content.tpl");
$template_end_time = getmicrotime();
$template_execution_time = $template_end_time - $end_time;
echo("execution_time: $execution_time seconds<br>");
echo("template_execution_time: $template_execution_time seconds<br>");
$execution_time = $end_time - $start_time + $template_execution_time;
echo("gesamt execution_time: $execution_time seconds<br>");	
?>
