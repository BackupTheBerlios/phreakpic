<?php
include_once('./classes/album_content.inc.php');
function generate_array_from_row($row)
{
	global $db;
	while ($row = $db->sql_fetchrow($result))
	{
		foreach ($row as $key => $value)
		{
			// filter out all keys which are not strings, because the array containt both assoziativ and numbers
			if (is_string($key))
			{
				$item[$key] = $value;
			}
		}
		$array[]=$item;
	}
	return $array;

}

function generate_where($field,$array)
{
	// generates an string that can be used in an sql where, which limits the query to all entry where $field is in $array
	for ($i=0;$i<sizeof($array);$i++)
	{
		$where=$where." ".$field." like ".$array[$i];
		if ($i<sizeof($array)-1)
			$where=$where." or";
	}
	if (!isset($where))
	{
		$where = 0;
	}
	return $where;
}

function getext($in_file)
{
	// return the file extension of $in_file (without a leading dot)
	if (end(explode('.',$in_file)) != $in_file)
	{
		return(strtolower(end(explode('.',$in_file))));
	}
	return "";
	
}

function getfile($in_file)
{
	// returns the filename of $in_file without extension (wihtoud trailing dot)
	return substr($in_file,0,strrpos($in_file,"."));	
}

/*function linkencode ($p_url) {
	// needs php 4.1.0
 $ta = parse_url($p_url);
 if (!empty($ta[scheme])) { $ta[scheme].='://'; }
 if (!empty($ta[pass]) and !empty($ta[user])) {
 $ta[user].=':';
 $ta[pass]=rawurlencode($ta[pass]).'@';
 } elseif (!empty($ta[user])) {
 $ta[user].='@';
 }
 if (!empty($ta[port]) and !empty($ta[host])) {
 $ta[host]=''.$ta[host].':';
 } elseif (!empty($ta[host])) {
 $ta[host]=$ta[host];
 }
 if (!empty($ta[path])) {
 $tu='';
 $tok=strtok($ta[path], "\\/");
 while (strlen($tok)) {
 $tu.=rawurlencode($tok).'/';
 $tok=strtok("\\/");
 }
 $ta[path]='/'.trim($tu, '/');
 }
 if (!empty($ta[query])) { $ta[query]='?'.$ta[query]; }
 if (!empty($ta[fragment])) { $ta[fragment]='#'.$ta[fragment]; }
 return implode('', array($ta[scheme], $ta[user], $ta[pass], $ta[host], 
$ta[port], $ta[path], $ta[query], $ta[fragment]));
}*/

function  linkencode($str)
{
	// encodes a string so it can be used as an html link e.g. in urls
	// for php < 4.1.0
	$str=ereg_replace(" ","%20",$str);
	$str=ereg_replace("&","%26",$str);
	$str=ereg_replace("\+","%2b",$str);
	$str=ereg_replace("ü","%fc",$str);
	$str=ereg_replace("Ü","%dc",$str);

	return $str;
}

function makedir($dir)
{
	// makes directory $dir with dir_mask out of the config and creates an index.html in it so nobdy can se the directory listing
	global $config_vars;
	mkdir($dir,$config_vars['dir_mask']);
	touch ($dir . '/index.html');
}
//makes the string of cats and content where the user is at the moment
function build_nav_string($cat_id)
{
	global $config_vars, $lang;
	//get the navigation string
	$cat = new categorie();
	$result = $cat->generate_from_id($cat_id);
	if ($result)
	{

	}
	$parent_cats = $cat->get_parent_cat_array();
	
	//build the navigation string
	for ($i = 0; $i < sizeof($parent_cats); $i++)
	{
		$nav_string[$i]['id'] = $parent_cats[$i]['id'];
		$nav_string[$i]['name'] = $parent_cats[$i]['name'];
	}
	return $nav_string;
}

//get all comments and save it as an useable array
function make_comments($comment, $level,$editable)
{
	global $comments,$userdata;
	$comment_infos['level'] = $level;
	$comment_infos['id'] = $comment->id;    //get_id();
	$comment_infos['text'] = nl2br(htmlspecialchars($comment->get_feedback()));
	$comment_userdata = get_userdata(intval($comment->get_user_id()));
	$comment_infos['username'] = $comment_userdata['username'];
	$comment_infos['topic'] = htmlspecialchars($comment->get_topic());
	$comment_infos['creation_date'] = $comment->get_creation_date();
	$comment_infos['changed_count'] = $comment->get_changed_count();
	$comment_infos['last_changed_date'] = $comment->get_last_changed_date();
	if (($comment_userdata['user_id'] == $userdata['user_id']) or ($editable))
	{
		$comment_infos['editable'] = true;
	}
	
	// check if comment is new
	
	$newest = strtotime($comment->get_creation_date());
	if (strtotime($comment->get_last_changed_date()) > $newest)
 	{
 		$newest = strtotime($comment->get_last_changed_date());
 	}
	
	//echo date("Y-m-d H:i:s",$userdata['user_lastvisit'])."<br>";
	
	
	if ($userdata['user_lastvisit'] < $newest)
	{
		$comment_infos['new'] = true;
	}
	
	$comments[] = $comment_infos;
	
	//echo ($comment->get_feedback()."<br>");
	$comment_childs = $comment->get_childs();
	if (is_array($comment_childs))
	{
		for ($i = 0; $i < sizeof($comment_childs); $i++)
		{
			make_comments($comment_childs[$i],$level+1,$editable);
		}
	}
}

function stop_view($start_view,$content_id)
{
	if (($start_view==0) or (!isset($start_view)) or ((!isset($content_id))))
	{
		return OP_FAILED;
	}
	global $db,$config_vars,$userdata;
	$now = date("Y-m-d H:i:s");
	$sql = 'UPDATE  '. $config_vars['table_prefix'] ."views SET" .
		KEY_QUOTE.'end' . KEY_QUOTE . " = '$now'
		WHERE (user_id = " . $userdata['user_id'] . ") and (start = '$start_view') and (content_id = $content_id)";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't start view", '', __LINE__, __FILE__, $sql);
	}
}

function get_installed_languages()
{
	$dir= ROOT_PATH . './languages/';
	
	$dir_handle=opendir($dir);
	while ($file = readdir ($dir_handle))
	{
			
		if (($file != "." && $file != "..") and (is_dir($dir.$file)))
		{	
			if (is_file($dir.$file.'/lang_main.php'))
			{
				$langs[]=$file;
			}
		
		}
	}
	
	return $langs;
	
	
}

function add_content($POST_FILES,$name,$cat_id,$place_in_cat,$content_group)
{
	global $filetypes;
	$objtyp = $filetypes[getext($POST_FILES['new_content_file']['name'])];
	$new_content = new $objtyp;

	// endgültigen dateinamen generieren und das tmp file verschieben. Weil das object nicht des dateiendung bekommen würde, wenn nur file=tmp_file und name=irgenwas gesätzt wäare
	$new_content->file = $POST_FILES['new_content_file']['name'];
	$new_content->add_to_cat($cat_id);
	if ($name != "")
	{
		$new_content->set_name($name);
	}
	else
	{
		$new_content->set_name(getfile($POST_FILES['new_content_file']['name']));
	}

	$new_file_name = $new_content->generate_filename();
	rename ($POST_FILES['new_content_file']['tmp_name'], $new_file_name); 
	$new_content->file = $new_file_name;

	$new_content->set_place_in_cat($cat_id,$place_in_cat);
	$new_content->set_contentgroup_id($content_group);


	$new_content->commit();
	return $new_content;

}





?>
