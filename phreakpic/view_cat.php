<?php
include_once('./includes/common.inc.php');
include_once('./classes/album_content.inc.php');
include_once('./includes/template.inc.php');
include_once('./modules/pic_managment/interface.inc.php');
include_once('./languages/'.$userdata['user_lang'].'/lang_main.php');
include_once('./includes/functions.inc.php');

if (!isset($cat_id))
{
	$cat_id = $config_vars['root_categorie'];
	$template_file = 'index';
}

//get the cats in the actual cat and information about them
$child_cats = get_cats_of_cat($cat_id);
if (isset($child_cats))
{
	for ($i = 0; $i < sizeof($child_cats); $i++)
	{
		$child_cat_infos[$i]['id'] = $child_cats[$i]->get_id();
		$child_cat_infos[$i]['name'] = $child_cats[$i]->get_name();
		$child_cat_infos[$i]['description'] = $child_cats[$i]->get_description();
		$child_cat_infos[$i]['content_amount'] = $child_cats[$i]->get_content_amount();
		$child_cat_infos[$i]['current_rating'] = $child_cats[$i]->get_current_rating();
	}
	$smarty->assign('child_cat_infos',$child_cat_infos);
	$smarty->assign('number_of_child_cats',$i);
}
else
{
	//no child cats
	$smarty->assign('number_of_child_cats',0);
}



//Get the contents of the actual cat and their thumbnails plus information like
$contents = get_content_of_cat($cat_id);
if (is_array($contents))
{
	for ($i = 1; $i <= sizeof($contents); $i++)
	{
		$thumb_infos = $contents[$i-1]->get_thumb();
		$array_row[] = $thumb_infos;
		if ($i % $config_vars['thumb_table_cols'] == 0)
		{
			$thumbs[]=$array_row;
			unset($array_row);
		}
	}
	$thumbs[]=$array_row;
	$smarty->assign('thumbs',$thumbs);
	$smarty->assign('cat_id',$cat_id);
	$smarty->assign('is_content', true);
}

$smarty->assign('nav_string', build_nav_string($cat_id));
$smarty->assign('lang',$lang);
$smarty->assign('title', 'Testtitel');

//thats for the index.php who needs another template file. index.php just set the $template_file to another value and includes this file
if (!isset($template_file))
{
	$template_file = 'view_cat';
}
$smarty->display($userdata['photo_user_template'].'/'.$template_file.'.tpl');
?>