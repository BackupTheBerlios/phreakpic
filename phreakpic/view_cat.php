<?php
include_once('./includes/common.inc.php');
include_once('./classes/album_content.inc.php');
include_once('./includes/template.inc.php');
include_once('./modules/pic_managment/interface.inc.php');

if (!isset($cat_id))
{
	die ("cat_id nicht gesetzt");
}

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
	$smarty->assign('number_of_child_cats',0);
}





$contents = get_content_of_cat($cat_id);

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
$smarty->assign('title', 'Testtitel');
$smarty->display($userdata['photo_user_template'].'/view_cat.tpl');
?>