<?php 
include_once(ROOT_PATH . './classes/album_content.inc.php');

DEFINE('INFORMATION','0');
DEFINE('SQL_ERROR','1');
DEFINE('AUTH_ERROR','2');
DEFINE('FILE_ERROR','3');
DEFINE('GENERAL_ERROR','3');

function database_encode($string)
{
	// encodes a string for storing in the db
	return str_replace("\'", "''", (addslashes($string)));
}


function error_report($type, $ident , $line, $file,$sql='')
{
	global $userdata,$smarty,$db,$config_vars,$QUERY_STRING,$error;
	
	switch ($type)
	{
		case SQL_ERROR: 
			$error_info['type'] = 'SQL_ERROR'; 
			$error_info['sql_error'] = $db->sql_error();
			break;
		case AUTH_ERROR: $error_info['type'] = 'AUTH_ERROR'; break;
		case FILE_ERROR: $error_info['type'] = 'FILE_ERROR'; break;
		case GENERAL_ERROR: $error_info['type'] = 'GENERAL_ERROR'; break;
		case INFORMATION: $error_info['type'] = 'INFORMATION'; break;
	}
	
	$error_info['ident'] = $ident;
	$error_info['text'] = $error[$ident];
	$error_info['line'] = $line;
	$error_info['file'] = $file;
	$error_info['sql'] = $sql;
	$error_info['debug'] = DEBUG;
	
	
	if ($type != INFORMATION)
	{
		// submit error to db
		$sql = "INSERT INTO " . $config_vars['table_prefix'] . "error_reports
					(type,file,line,sql,ident,user_id,query_string,error_time)
					VALUES ('{$type}','{$error_info['file']}','{$error_info['line']}','" . addslashes($error_info['sql']) . "','{$error_info['ident']}','{$userdata['user_id']}','$QUERY_STRING','" . date("Y-m-d H:i:s") . "')";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Error report failed", '', __LINE__, __FILE__, $sql);
		}
		$error_info['id'] = $db->sql_nextid();
	}

	$smarty->assign('error_info',$error_info);
	$smarty->assign('ROOT_PATH',ROOT_PATH);
	$smarty->display($userdata['photo_user_template']."/error_msg.tpl");
	die();
}

function write_config($Smarty_dir,$phpBB_Path,$phreakpic_path,$Server_name)
{
	global $config_vars;
	$config_content = "<?php

//Template System
//absolute path to smarty
define(\"SMARTY_DIR\",\"" . $Smarty_dir . "\");

//relative path from phreakpic to phpBB2 (if the URL is \"http://www.blabla.com/com/phpBB2/\" and phreakpic is at \"http://www.blabla.com/com/phreakpic/\" then PHPBB_DIR will be \"../phpBB2/\")
//Don't forget the / at end!
define(\"PHPBB_PATH\",\"" . $phpBB_Path . "\");

//relative path from phpBB2 to phreakpic see above
define(\"PHREAKPIC_PATH\",\"" . $phreakpic_path . "\");



define(\"SERVER_NAME\",\"" . $Server_name . "\");



\$config_vars = array
(
	//Database
	'table_prefix' => '" . $config_vars['table_prefix'] . "',

	// path to where the content should be stored
	'content_path_prefix' => '" . $config_vars['content_path_prefix'] . "',

	//Picture stuff
	// size of thumbs (for generating)
	'thumb_size' =>
	array
	(
		// if set thumb is percent as big as the original picture
	//	'percent' => '30',
		// if set height will be exactly this value (if the width not set the apsectio ratio will be keept)
	//	'height' => '130',
		// if set width will be exactly this value
	//	'width' => '100'
		// if set the longer size will become this value
		'maxsize' => '130'
	),

	// ID of the cat where to put pictures that are no longer linked in any cat
	'deleted_content_cat' => {$config_vars['deleted_content_cat']},

	// ID of the root categorie
	'root_categorie' => {$config_vars['root_categorie']},

	// Umask of new created directories
	'dir_mask' => 0775,
	
	//view_cat.php the Colums of the table, where we can see the thumbnails
	'thumb_table_cols' => {$config_vars['thumb_table_cols']},

	// template used if not setted by user
	'default_template' => '" . $config_vars['default_template'] . "',

	// language used if not setted by user
	'default_lang' => '" . $config_vars['default_lang'] . "',
	
	'default_upload_dir' => '{$config_vars['default_upload_dir']}',
	
	// the ids of the usergroups in which every user is automaicly
	'default_usergroup_ids' => Array(" . @implode(',',$config_vars['default_usergroup_ids']) . "),
	
	// the ids of the usergroups in which every registered user is automaicly
	'registered_users_usergroup_ids' => Array(" . @implode(',',$config_vars['registered_users_usergroup_ids']) . "),
	
	'default_content_per_page' => {$config_vars['default_content_per_page']},
	
	'selectable_content_per_page' => Array(" . @implode(',',$config_vars['selectable_content_per_page']) . "), 
	
	'cookie_name' => 'phreakpic',
	
	'default_basket_enable' => " . ($config_vars['default_basket_enable'] ? 'true' : 'false') . "
);
?>";
	$file = fopen(ROOT_PATH . "config.inc.php", "w+b");
	if ($file == false)
	{
		die ('<br>Couldn\'t open the config File for writing. Maybe the permissions are not right. Please write the following Text to the file "config.inc.php".<br><p><textarea name="textfield" cols="100" rows="40">' . $config_content . '</textarea></p>');
	}
	
	$write = fwrite($file,$config_content);
	if ($write == false)
	{
		die ('<br>Couldn\'t write the config file. Please write the following Text to the file "config.inc.php".<br><p><textarea name="textfield" cols="100" rows="40">' . $config_content . '</textarea></p>');
	}

}

function get_cats_string($cats)
{
	global $config_vars;
	
	if (is_array($cats))
	{
		foreach ($cats as $key => $value)
		{
			$cat_obj = new categorie();
			$cat_obj->generate_from_id($value['id']);
			$name = $cat_obj->get_name();

			while ($cat_obj->get_parent_id() != $config_vars['root_categorie'])
			{
				$old_cat_id=$cat_obj->get_parent_id();
				$cat_obj = new categorie();
				$cat_obj->generate_from_id($old_cat_id);
				$name = $cat_obj->get_name() . '/' . $name;
			}
			$cats[$key]['name']=$name;
		}
	}
	return $cats;
}

function validate_config()
{
	global $config_vars;
	//check table prefix

	//check content path
	if (!is_dir($config_vars['content_path_prefix']))
	{
		$error['conntent_path_prefix']=true;
	}
	// check thumb size
	if (!isset($config_vars['thumb_size']))
	{
		$error['thumb_size']=1;
	}
	elseif (isset($config_vars['thumb_size']['percent']) and isset($config_vars['thumb_size']['maxsize']))
	{
		$error['thumb_size']=2;
	}
	elseif ((isset($config_vars['thumb_size']['percent']) or isset($config_vars['thumb_size']['maxsize'])) 
		and (isset($config_vars['thumb_size']['width']) or isset($config_vars['thumb_size']['height'])))
	{
		$error['thumb_size']=3;
	}
	
	// deleted content cat
	if (!isset($config_vars['deleted_content_cat']))
	{
		$error['deleted_content_cat']=1;
	}
	else
	{
		$cat=new categorie;
		if ($cat->generate_from_id($config_vars['deleted_content_cat'])==OP_FAILED)
		{
			$error['deleted_content_cat']=2;
		}
	}
	
	// root cat
	if (!isset($config_vars['root_categorie']))
	{
		$error['root_categorie']=1;
	}
	else
	{
		$cat=new categorie;
		if ($cat->generate_from_id($config_vars['root_categorie'])==OP_FAILED)
		{
			$error['root_categorie']=2;
		}
		if ($cat->id!=$cat->parent_id)
		{
			$error['root_categorie']=3;
		}
	}
	
	
	// show errors
	
	if (isset($error))
	{
		echo "The following Vars in the config file make no sense <br><br>";
		if ($error['conntent_path_prefix'])
		{
			echo "content_path_prefix: Is not a directory";
		}
		
		if ($error['thumb_size'] == 1)
		{
			echo "thumb_size: Not set<br>";
		}
		elseif ($error['thumb_size'] == 2)
		{
			echo "thumb_size: Percent and Maxsize set<br>";
		}
		elseif ($error['thumb_size'] == 3)
		{
			echo "thumb_size: Percent or Maxsize and width or height set<br>";
		}
		
		if ($error['deleted_content_cat'] == 1)
		{
			echo "deleted_content_cat: Not set";
		}
		elseif ($error['deleted_content_cat'] == 2)
		{
			echo "deleted_content_cat: Cateogire does not exists";
		}
		
		if ($error['root_categorie'] == 1)
		{
			echo "root_categorie: Not set";
		}
		elseif ($error['root_categorie'] == 2)
		{
			echo "root_categorie: Cateogire does not exists";
		}
		elseif ($error['root_categorie'] == 3)
		{
			echo "root_categorie: id and parent_id of root cat are not the same";
		}
	
	
		die;
	}

}
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
	if (!isset($where))
	{
		$where="'0'";
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

function linkencode($str)
{
	$array = explode('/',$str);
	foreach ($array as $key => $value)
	{
		#$ret.=urlencode($value).'/';
		$array[$key]=rawurlencode($value);
	} 

	$ret = implode('/',$array);
return $ret;
}

function linkencode2 ($p_url) {

	// needs php 4.1.0
 $ta = parse_url($p_url);
print_r($ta); 
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
 $ta[path]=trim($tu, '/');
 }
 if (!empty($ta[query])) { $ta[query]='?'.$ta[query]; }
 if (!empty($ta[fragment])) { $ta[fragment]='#'.$ta[fragment]; }
 return implode('', array($ta[scheme], $ta[user], $ta[pass], $ta[host], 
$ta[port], $ta[path], $ta[query], $ta[fragment]));
}

function  linkencode1($str)
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

function check_writeable($dir)
{
	
	$check = @mkdir("$dir/test_install_dir", 0755);
	if ($check)
	{
		rmdir("$dir/test_install_dir");
	}
	return $check;
	
}

function ensure_writable_dir($dir)
{
	/*returns:
	 	0 if $dir exists and is writeable
		1 if $dir could be created writeable
		2 if $dir does exists but is not writeable
		3 if $dir could be created but is not writeable
		4 if $dir does not exists and could not be created
		
	 
	*/
	if (is_dir("./" . $dir))
	{
		if (check_writeable($dir))
		{
			return 0;
		}
		else
		{
			return 2;
		}
	}
	else
	{
		if (@makedir($dir))
		{
			// dir created
			if (check_writeable($dir))
			{
				return 1;
			}
			else
			{
				return 3;
			}
		}
		else
		{
			// dir not creatable
			return 4;
		}
	}
}

function makedir($dir)
{
	// makes directory $dir with dir_mask out of the config and creates an index.html in it so nobdy can se the directory listing
	global $config_vars;
	$ret = ForceDirectories($dir,$config_vars['dir_mask']);
	touch ($dir . '/index.html');
	return $ret;
}

function ForceDirectories( $path,$umask) 
{ 
	if ( strlen( $path) == 0) 
	{ 
		return 0; 
	} 
	// 
	if ( strlen( $path) < 3) 
	{ 
		return 1; // avoid 'xyz:\' problem. 
	} 
	elseif ( is_dir( $path)) 
	{ 
		return 1; // avoid 'xyz:\' problem. 
	} 
	elseif ( dirname( $path) == $path) 
	{ 
		return 1; // avoid 'xyz:\' problem. 
	} 
	return ( ForceDirectories( dirname( $path),$umask) and mkdir( $path, $umask)); 
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
	global $comments,$userdata,$board_config,$lang;
	$comment_infos['level'] = $level;
	$comment_infos['id'] = $comment->id;    //get_id();
	$comment_infos['text'] = nl2br(htmlspecialchars($comment->get_feedback()));
	$comment_userdata = get_userdata(intval($comment->get_user_id()));
	$comment_infos['username'] = $comment_userdata['username'];
	switch( $comment_userdata['user_avatar_type'] )
	{
		case USER_AVATAR_UPLOAD:
			$poster_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="'.PHPBB_PATH . $board_config['avatar_path'] . '/' . $comment_userdata['user_avatar'] . '" alt="" border="0" />' : '';
			break;
		case USER_AVATAR_REMOTE:
			$poster_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="'. $comment_userdata['user_avatar'] . '" alt="" border="0" />' : '';
			break;
		case USER_AVATAR_GALLERY:
		
			$poster_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="'.PHPBB_PATH . $board_config['avatar_gallery_path'] . '/' . $comment_userdata['user_avatar'] . '" alt="" border="0" />' : '';
			break;
	}
	
	$comment_infos['avatar'] = $poster_avatar;
	$comment_infos['topic'] = htmlspecialchars($comment->get_topic());
	$comment_infos['creation_date'] = date($userdata['user_dateformat'],strtotime($comment->get_creation_date()));
	$comment_infos['changed_count'] = $comment->get_changed_count();
	$comment_infos['last_changed_date'] = date($userdata['user_dateformat'],strtotime($comment->get_last_changed_date()));
	$comment_infos['poster_name'] = htmlspecialchars($comment->get_poster_name());
	$comment_infos['comment_has_been_changed'] = sprintf($lang['comment_has_been_changed'],$comment_infos['changed_count'],$comment_infos['last_changed_date']);
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
	global $db,$config_vars,$userdata,$HTTP_SESSION_VARS;
	
	// delete files in $HTTP_SESSION_VARS['delete_files']. actually it doesnt really belong here, but this funtion is called at start of every page so its a good place
	if (is_array($HTTP_SESSION_VARS['delete_files']))
	{
		foreach ($HTTP_SESSION_VARS['delete_files'] as $index => $file)
		{
			unlink($file);
			unset($HTTP_SESSION_VARS['delete_files'][$index]);
		}
	}

	if (($start_view==0) or (!isset($start_view)) or ((!isset($content_id))))
	{
		return OP_FAILED;
	}
	
	$now = date("Y-m-d H:i:s");
	$sql = 'UPDATE  '. $config_vars['table_prefix'] ."views SET" .
		KEY_QUOTE.'end' . KEY_QUOTE . " = '$now'
		WHERE (user_id = " . $userdata['user_id'] . ") and (start = '$start_view') and (content_id = $content_id)";
	if (!$result = $db->sql_query($sql))
	{
		error_report(SQL_ERROR, 'stop_view' , __LINE__, __FILE__,$sql);
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

function get_installed_templates()
{
	$dir= ROOT_PATH . './templates/';
	
	$dir_handle=opendir($dir);
	while ($file = readdir ($dir_handle))
	{
			
		if (($file != "." && $file != "..") and (is_dir($dir.$file)))
		{	
			if (is_file($dir.$file.'/index.tpl'))
			{
				$langs[]=$file;
			}
		
		}
	}
	return $langs;
}

function add_content($filename,$tmp_file,$name,$cat_id,$place_in_cat,$content_group)
{
	global $filetypes;
	
	$objtyp = $filetypes[getext($filename)];
	$new_content = new $objtyp;

	// endgültigen dateinamen generieren und das tmp file verschieben. Weil das object nicht des dateiendung bekommen würde, wenn nur file=tmp_file und name=irgenwas gesätzt wäare
	$new_content->file = $filename;
	

	
	$new_content->add_to_cat($cat_id);
	
	unset($new_content->new_filename);
	if ($name != "")
	{
		$new_content->set_name($name);
	}
	else
	{
		$new_content->set_name(getfile($filename));
	}

	$new_file_name = $new_content->generate_filename();
	
	rename ($tmp_file, $new_file_name); 
	$new_content->file = $new_file_name;
	
	$new_content->set_place_in_cat($cat_id,$place_in_cat);
	$new_content->set_contentgroup_id($content_group);

	
	$new_content->commit();
	return $new_content;

}

function array_minus_array($a,$b) 
{ 
	$c = Array();
	foreach ($a as $key => $val) 
	{ 
		$posb = array_search($val,$b);
		if (is_integer($posb)) 
		{
			unset($b[$posb]); 
		} else 
		{
			$c[] = $val;
		}
	}
	return $c;
}



?>
