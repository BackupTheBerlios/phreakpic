<?php
define(ROOT_PATH,'../');
include_once(ROOT_PATH . 'includes/common.inc.php');
include_once(ROOT_PATH . 'includes/template.inc.php');
include_once(ROOT_PATH . 'includes/functions.inc.php');

function get_words_from_searches()
{	
	global $db,$config_vars;
	$sql="SELECT name,params from {$config_vars['table_prefix']}custom_searches";
	
	//echo $sql;
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Error in SQL", '', __LINE__, __FILE__, $sql);
	}
	
	while ($row = $db->sql_fetchrow($result))
	{	
		$words[]=$row['name'];
		$param=generate_params($row['params']);
		if (is_array($param))
		{
			foreach ($param as $value)
			{
				if ($value['name']!='')
				$words[]=$value['name'];

			}
		}
		//echo "A: {$row['params']} <br>";
		
		
			
	}
	
	
	return $words;
	
}


function get_words_from_polls()
{
}

function get_custom_words()
{
	$words=get_words_from_searches();
	$words=array_merge($words,get_words_from_polls());
	return $words;
}




$installed_langs=get_installed_languages();


echo $HTTP_POST_VARS['trans']['Sort by'];

if (isset($submit))
{
	
	foreach ($installed_langs as $languages)
	{
		$fp=fopen(ROOT_PATH . './languages/'.$languages.'/lang_custom.php','w');
		fwrite($fp,"<?php\n");
		foreach ($HTTP_POST_VARS['trans'] as $code => $trans_array)
		{
			$str='$lang[\''.urldecode($code).'\']="'.$trans_array[$languages].'";';
			fwrite($fp,$str."\n");
			
			
			//echo urldecode($code).": $languages => $trans_array[$languages]<br>";
		}
		fwrite($fp,'?>');
		fclose($fp);
	}
	
}


$custom_words=get_custom_words();


foreach ($custom_words as $key => $value)
{	
	unset ($lang);
	include(ROOT_PATH . './languages/'.$userdata['user_lang'].'/lang_main.php');
	if (!isset($lang[$value]))
	{
	//	echo $value;
		foreach ($installed_langs as $ilangs)
		{
			unset($lang);
			@include(ROOT_PATH . './languages/'.$ilangs.'/lang_custom.php');
			$trans[$ilangs]=$lang[$value];
			$trans['code']=$value;
		}
		$words[urlencode($value)]=$trans;
	}
}




$smarty->assign('words',$words);
$smarty->assign('installed_langs',$installed_langs);

$smarty->display($userdata['photo_user_template'].'/admin/lang.tpl');

?>
