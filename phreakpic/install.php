<html>
<head>
<title>Phreakpic Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
	h1, h2, h3, h4, h5, h6, p, font, td {  font-family: "Trebuchet MS", Verdana}
-->
</style>
</head>

<body bgcolor="#FFFFFF">

<?php
if ($mode == "check_user_info")
{
	/* 
	* 
	* 
	* 
	* Server_name
	* table_prefix
	* root_categorie
	* deleted_content_cat
	* 
	* 
	*/
	
	echo ('<p>Checking the Data of the Form.<br>');
	
	//phpBB Path
	$check = @include($phpBB_Path . "config.php");
	if (empty($check))
	{
		die('phpBB2 Path is wrong. Check if you have a trailing slash (like foo/bar/phpBB2/ ).');
	}
	echo ('phpBB Path: <font color=#00ff00>OK</font><br>');
	
	//phreakpic Path
	$check = file_exists ($phpBB_Path . $phreakpic_path . "view_cat.php");
	if (empty($check))
	{
		die('Phreakpic Path is wrong. Check if you have a trailing slash (like foo/bar/Phreakpic/ ).');
	}
	echo ('Phreakpic Path: <font color=#00ff00>OK</font><br>');
	
	//Smarty Dir
	$check = file_exists($Smarty_dir . "Smarty.class.php");
	if (empty($check))
	{
		die('Smarty Dir is wrong. Check if you have a trailing slash (like /usr/local/httpd/htdocs/foo/bar/Smarty/ ).');
	}
	echo ('Smarty Dir: <font color=#00ff00>OK</font><br>');
	
	//content path prefix
	$check = @chdir("./" . $content_path_prefix);
	if ($check == false)
	{
		die('Couldn\'t go into the content dir. Is the folder ' . $content_path_prefix . ' existant and have proper rights??');
	}
	chdir("../");
	echo ('Content Dir: <font color=#00ff00>OK</font><br>');
	
	$check = @mkdir("test_install_dir", 0755);
	if ($check == false)
	{
		die ('Content dir have not the right permissions (must be writable for PhreakPic) or the Folder is existing.');
	}
	rmdir("test_install_dir");
	echo ('Content Dir permissions: <font color=#00ff00>OK</font><br>');
	
	//default_lang
	$check = @chdir("./languages/" . $default_lang);
	if ($check == false)
	{
		die('Couldn\'t go into the Language dir. Is the Language ' . $default_lang . ' installed in phreakpic/languages??');
	}
	chdir("../../");
	echo ('Default Language: <font color=#00ff00>OK</font><br>');
	
	//default Template
	$check = @chdir("./templates/" . $default_template);
	if ($check == false)
	{
		die('Couldn\'t go into the Template dir. Is the Template ' . $default_template . ' installed in phreakpic/templates??');
	}
	chdir("../../");
	echo ('Default Template: <font color=#00ff00>OK</font><br>');
	
	
	echo ('</p>');
	
	
	//Now do the db stuff
	
	echo ('<p>Now we can start with the DB Stuff.<br>');
	if ($dbms != 'mysql')
	{
		die ('Sorry, ' . $dbms . ' is not supported. Please install your phpBB2 with MySQL Database.');
	}
	echo ('DB Type: <font color=#00ff00>OK</font><br>');
	
	
	echo ("Connecting to DB $dbname on host $dbhost with user $dbuser<br>");
	$connect = mysql_connect($dbhost,$dbuser,$dbpasswd);
	if (!$connect)
	{
		die ('Database connect: <font color=#ff0000>FAILED</font><br>');
	}
	
	include ("mysql_db_alpha.sql.php");
	
	
	//every Table need it own sql query...
	for ($x = 0; $x < sizeof($query); $x++)
	{
		echo ("Table $x: ");
		$check = mysql_db_query($dbname, $query[$x], $connect);
		if ($check == false)
		{
			die (mysql_errno().": ".mysql_error()."<BR> Query: " . $query[$x]);
		}
		echo ('<font color=#0000ff>done</font><br>');
	}
	echo ('DB make Tables: <font color=#00ff00>OK</font><br>');	
	echo ('</p>');
	
	
	//write the config file
	echo ('<p> Writing the Config ');
	
	
	//don't change the following line!
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
	'table_prefix' => '" . $phreakpic_table_prefix . "',

	// path to where the content should be stored
	'content_path_prefix' => '" . $content_path_prefix . "',

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
	'deleted_content_cat' => 2,

	// ID of the root categorie
	'root_categorie' => 1,

	// Umask of new created directories
	'dir_mask' => 0775,
  
	//view_cat.php the Colums of the table, where we can see the thumbnails
	'thumb_table_cols' => 4,

	// template used if not setted by user
	'default_template' => '" . $default_template . "',

	// language used if not setted by user
	'default_lang' => '" . $default_lang . "'
);
?>";
	
	$file = fopen("config.inc.php", "w+b");
	if ($file == false)
	{
		die ('<br>Couldn\'t open the config File for writing. Maybe the permissions are not right. Please write the following Text to the file "config.inc.php".<br><p><textarea name="textfield" cols="100" rows="40">' . $config_content . '</textarea></p>');
	}
	
	$write = fwrite($file,$config_content);
	if ($write == false)
	{
		die ('<br>Couldn\'t write the config file. Please write the following Text to the file "config.inc.php".<br><p><textarea name="textfield" cols="100" rows="40">' . $config_content . '</textarea></p>');
	}
	echo ('<font color=#00ff00>OK</font></p>');
}

else
{
?>
<div align="center">
	<h1>PhreakPic Installation</h1>
	<p>
		Welcome and thank you for using PhreakPic!<br>
		Please fill out the empty fields. Only change the filled fields if you know 
		what you're doing.<br>
		You must have installed a working phpBB2 and smarty.
	</p>
	<form method="post" action="<?php $PHP_SELF ?>">
		<input type="hidden" name="mode" value="check_user_info">
		
    <table width="95%" border="0" cellspacing="0" cellpadding="5">
      <tr bgcolor="#3399CC"> 
        <td colspan="4" height="10"></td>
      </tr>
      <tr> 
        <td rowspan="19" bgcolor="#3399CC" width="10"></td>
        <td colspan="3"> 
          <h3>Files and Pathes</h3>
        </td>
      </tr>
      <tr bgcolor="#FFCC99"> 
        <td width="21%">phpBB2 Path</td>
        <td width="25%"> 
          <input type="text" name="phpBB_Path" size="30">
        </td>
        <td width="54%">Relative path from phreakpic to phpBB2 (if the URL is 
          "http://www.blabla.com/com/phpBB2/" and phreakpic is at "http://www.blabla.com/com/phreakpic/" 
          then it will be "../phpBB2/").</td>
      </tr>
      <tr bgcolor="#FFCC99"> 
        <td width="21%">PhreakPic Path</td>
        <td width="25%"> 
          <input type="text" name="phreakpic_path" size="30">
        </td>
        <td width="54%">Relative Path from phpBB2 to PhreakPic. See above.</td>
      </tr>
      <tr bgcolor="#FFCC99"> 
        <td width="21%">Smarty Dir</td>
        <td width="25%"> 
          <input type="text" name="Smarty_dir" size="30">
        </td>
        <td width="54%">Absolute Path to Smarty. E.g. &quot;/usr/local/httpd/htdocs/smarty/&quot;</td>
      </tr>
      <tr bgcolor="#FFCC99"> 
        <td width="21%">Content Path</td>
        <td width="25%"> 
          <input type="text" name="content_path_prefix" size="30" value="content">
        </td>
        <td width="54%">In this Folder the Content (mostly Pictures) will be stored.</td>
      </tr>
      <tr bgcolor="#FFCC99"> 
        <td width="21%">Servername</td>
        <td width="25%"> 
          <input type="text" name="Server_name" size="30">
        </td>
        <td width="54%">Thats the Domain as you type it in your Webbrowser. E.g. 
          &quot;http://www.localhorst.com&quot; </td>
      </tr>
      <tr> 
        <td colspan="3" height="26"> 
          <h3>Database</h3>
        </td>
      </tr>
      <tr bgcolor="#CCCC99"> 
        <td width="21%">Table Prefix</td>
        <td width="25%"> 
          <input type="text" name="phreakpic_table_prefix" size="30" value="photo_">
        </td>
        <td width="54%">The prefix for the tables in the database.</td>
      </tr>
      
      <tr> 
        <td colspan="3"> 
          <h3>User Interface</h3>
        </td>
      </tr>
      <tr bgcolor="#99CCCC"> 
        <td width="21%">Default Language</td>
        <td width="25%"> 
          <input type="text" name="default_lang" size="30" value="english">
        </td>
        <td width="54%">&nbsp;</td>
      </tr>
      <tr bgcolor="#99CCCC"> 
        <td width="21%" height="38">Default Template</td>
        <td width="25%" height="38"> 
          <input type="text" name="default_template" size="30" value="standard">
        </td>
        <td width="54%" height="38">&nbsp;</td>
      </tr>
      <tr bgcolor="#99CCCC">
        <td width="21%" height="38">&nbsp;</td>
        <td width="25%" height="38">
          <div align="center">
            <input type="submit" name="install" value="Install">
          </div>
        </td>
        <td width="54%" height="38"> 
          <div align="right">
            <input type="reset" name="cancel" value="Delete Form">
          </div>
        </td>
      </tr>
    </table>
	</form>
</div>
<?php

}

?>
</body>
</html>
