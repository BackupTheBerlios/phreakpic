<?php
define ("ROOT_PATH",'');
include_once('./includes/common.inc.php');
include_once('./classes/album_content.inc.php');
include_once('./classes/group.inc.php');
include_once('./modules/pic_managment/interface.inc.php');
include_once('./includes/functions.inc.php');
include_once('./languages/' . $userdata['user_lang'] . '/lang_main.php');
include_once('./includes/template.inc.php');

if (!is_dir($dir))
{
	$dir=$config_vars['default_upload_dir'];
}

$add_to_contentgroups = get_contentgroups_data_where_perm('id,name','add_to_group');
if (!is_array($add_to_contentgroups))
{
	die ('You dont have perms to add content to any contentgroup');
}
$smarty->assign('add_to_contentgroups',$add_to_contentgroups);

$add_to_catgroups = get_catgroups_data_where_perm('id,name','add_to_group');
if (!is_array($add_to_catgroups))
{
	die ('You dont have perms to add content to any categorie');
}
$smarty->assign('add_to_catgroups',$add_to_catgroups);


$add_to_cats = get_cats_data_where_perm('id,name','content_add');
if (!is_array($add_to_cats))
{
	die ('You dont have perms to add content to any categorie');
}
$smarty->assign('add_to_cats',$add_to_cats);


if (isset($add_content))
{
	echo add_dir_parsed($dir,$new_content_group,$new_cat_group,$parent_cat_id);
	echo "added";
}



if (isset($dir))
{
	$dir_handle=opendir($dir);
	while ($file = readdir ($dir_handle))
	{
		if ($file != "." && $file != "..")   // WELCHE DATEIENDUNGEN werden benutzt? Soll es einstellbar sein? Wenn ja, wo?
		{
			
			if (is_dir("$dir/$file"))
			{	
				$amount['dirs']++;
			}
			if (is_file("$dir/$file"))
			{
				$amount['files']++;
			}
		}
	}
}

$smarty->assign('amount',$amount);


$end_time = getmicrotime();
$execution_time = $end_time - $start_time;

$smarty->display($userdata['photo_user_template']."/add_parsed_dir.tpl");
$template_end_time = getmicrotime();
$template_execution_time = $template_end_time - $end_time;
echo("execution_time: $execution_time seconds<br>");
echo("template_execution_time: $template_execution_time seconds<br>");
$execution_time = $end_time - $start_time + $template_execution_time;
echo("gesamt execution_time: $execution_time seconds<br>");	
?>
