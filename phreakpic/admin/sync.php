<?php
define ("ROOT_PATH",'../');
include_once(ROOT_PATH . 'includes/common.inc.php');
include_once(ROOT_PATH . 'languages/'.$userdata['user_lang'].'/lang_main.php');
include_once(ROOT_PATH . 'includes/template.inc.php');
include_once(ROOT_PATH . 'classes/group.inc.php');
include_once(ROOT_PATH . 'classes/album_content.inc.php');
include_once(ROOT_PATH . 'classes/categorie.inc.php');
include_once(ROOT_PATH . 'modules/authorisation/interface.inc.php');

define('CONTENT_IN_CAT_AMOUNT',1);
define('CHILD_CONTENT_IN_CAT_AMOUNT',2);
define('CHILD_COMMENTS_IN_CAT_AMOUNT',3);





if (isset($HTTP_POST_VARS['do_correct']))
{
	if (is_array($HTTP_POST_VARS['correct']))
	{
		foreach($HTTP_POST_VARS['correct'] as $key => $value)
		{
			if ($HTTP_POST_VARS['type'][$key] == CONTENT_IN_CAT_AMOUNT)
			{
				$cat=new categorie();
				$cat->generate_from_id($HTTP_POST_VARS['id'][$key]);
				$cat->set_content_amount($cat->calc_content_amount());
				$cat->commit();
			}
			elseif ($HTTP_POST_VARS['type'][$key] == CHILD_CONTENT_IN_CAT_AMOUNT)
			{
				$cat=new categorie();
				$cat->generate_from_id($HTTP_POST_VARS['id'][$key]);
				$cat->set_child_content_amount($cat->calc_child_content_amount());
				$cat->commit();
			}
			elseif ($HTTP_POST_VARS['type'][$key] == CHILD_COMMENTS_IN_CAT_AMOUNT)
			{
				$cat=new categorie();
				$cat->generate_from_id($HTTP_POST_VARS['id'][$key]);
				$cat->set_child_comments_amount($cat->calc_child_comments_amount());
				$cat->commit();
			}
		
		}
	}
	
}


// check content amount for each cat

// get all categories
$sql = "SELECT id FROM " . $config_vars['table_prefix'] . "cats";
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Coudnt get cats", '', __LINE__, __FILE__, $sql);
}

while ($row = $db->sql_fetchrow($result))
{
	$cat=new categorie();
	$cat->generate_from_id($row['id']);
	$catarray[]=$cat;
}

foreach ($catarray as $cat)
{
	// check how many content is in this cat
	$should_be = $cat->calc_content_amount();
	if ($should_be != $cat->get_content_amount())
	{
		$missmatch['type']=CONTENT_IN_CAT_AMOUNT;
		$missmatch['id'] = $cat->id;
		$missmatch['name'] = $cat->get_name();
		$missmatch['value'] = $cat->get_content_amount();
		$missmatch['should_be'] = $should_be;
		$missmatch_array[]=$missmatch;
	}
	
	// check child_content_amount
	$calc_child_content_amount = $cat->calc_child_content_amount();
	
	if ($calc_child_content_amount != $cat->get_child_content_amount())
	{
		$missmatch['type']=CHILD_CONTENT_IN_CAT_AMOUNT;
		$missmatch['id'] = $cat->id;
		$missmatch['name'] = $cat->get_name();
		$missmatch['value'] = $cat->get_child_content_amount();
		$missmatch['should_be'] = $calc_child_content_amount;
		$missmatch_array[]=$missmatch;
	}
	
	// check child comment amount
	$calc_child_comments_amount = $cat->calc_child_comments_amount();
	
	if ($calc_child_comments_amount != $cat->get_child_comments_amount())
	{
		$missmatch['type']=CHILD_COMMENTS_IN_CAT_AMOUNT;
		$missmatch['id'] = $cat->id;
		$missmatch['name'] = $cat->get_name();
		$missmatch['value'] = $cat->get_child_comments_amount();
		$missmatch['should_be'] = $calc_child_comments_amount;
		$missmatch_array[]=$missmatch;
	}
	
}




// auswertung:
$smarty->assign('missmatch_array',$missmatch_array);







$smarty->display($userdata['photo_user_template'].'/admin/sync.tpl');




?>
